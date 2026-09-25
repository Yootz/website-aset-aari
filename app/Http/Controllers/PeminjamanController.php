<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Peminjaman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PeminjamanController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Peminjaman::with(['employee', 'details.asset'])->latest()->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'p_code' => ['nullable', 'string', 'max:20', 'unique:peminjaman,p_code'],
            'e_code' => ['required', 'string', 'exists:master_employee,e_code'],
            'tgl_pinjam' => ['required', 'date'],
            'tgl_balik' => ['required', 'date', 'after_or_equal:tgl_pinjam'],
            'p_desc' => ['nullable', 'string'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.a_code' => ['required', 'string', 'distinct', 'exists:master_aset,a_code'],
            'details.*.dt_qty' => ['required', 'integer', 'in:1'],
        ]);

        DB::beginTransaction();

        try {
            $assetCodes = collect($validated['details'])->pluck('a_code')->all();
            $assets = Asset::query()
                ->whereIn('a_code', $assetCodes)
                ->lockForUpdate()
                ->get()
                ->keyBy('a_code');

            $unavailableAssets = collect($assetCodes)
                ->filter(fn (string $assetCode): bool => ! isset($assets[$assetCode]) || $assets[$assetCode]->a_status !== 'available')
                ->values();

            if ($unavailableAssets->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'details' => 'One or more assets are not available: '.$unavailableAssets->implode(', ').'.',
                ]);
            }

            $peminjamanCode = $validated['p_code'] ?? $this->generatePeminjamanCode();
            $peminjaman = Peminjaman::create([
                'p_code' => $peminjamanCode,
                'e_code' => $validated['e_code'],
                'tgl_pinjam' => $validated['tgl_pinjam'],
                'tgl_balik' => $validated['tgl_balik'],
                'p_status' => 'pending',
                'p_desc' => $validated['p_desc'] ?? null,
            ]);

            foreach ($validated['details'] as $detail) {
                $peminjaman->details()->create([
                    'dt_code' => $this->generateDetailCode(),
                    'a_code' => $detail['a_code'],
                    'dt_qty' => $detail['dt_qty'],
                    'dt_status' => 'borrowed',
                ]);

                $assets[$detail['a_code']]->update(['a_status' => 'unavailable']);
            }

            DB::commit();

            return response()->json([
                'message' => 'Peminjaman created successfully.',
                'data' => $peminjaman->load(['employee', 'details.asset']),
            ], 201);
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function updateStatus(Request $request, Peminjaman $peminjaman): JsonResponse
    {
        $validated = $request->validate([
            'p_status' => ['required', Rule::in(['pending', 'approved', 'returned', 'rejected'])],
        ]);

        if ($validated['p_status'] === 'returned') {
            return $this->returnAsset($peminjaman);
        }

        $peminjaman->update(['p_status' => $validated['p_status']]);

        return response()->json([
            'message' => 'Peminjaman status updated successfully.',
            'data' => $peminjaman->fresh(['employee', 'details.asset']),
        ]);
    }

    public function returnAsset(Peminjaman $peminjaman): JsonResponse
    {
        DB::beginTransaction();

        try {
            $peminjaman->load('details');
            $assetCodes = $peminjaman->details->pluck('a_code')->unique()->all();
            $assets = Asset::query()
                ->whereIn('a_code', $assetCodes)
                ->lockForUpdate()
                ->get()
                ->keyBy('a_code');

            foreach ($peminjaman->details as $detail) {
                $detail->update(['dt_status' => 'returned']);
                $assets[$detail->a_code]->update(['a_status' => 'available']);
            }

            $peminjaman->update(['p_status' => 'returned']);
            DB::commit();

            return response()->json([
                'message' => 'Peminjaman assets returned successfully.',
                'data' => $peminjaman->fresh(['employee', 'details.asset']),
            ]);
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    private function generatePeminjamanCode(): string
    {
        $prefix = 'PJM-'.now()->format('Ym');
        $sequence = Peminjaman::query()
            ->where('p_code', 'like', $prefix.'-%')
            ->lockForUpdate()
            ->count() + 1;

        return $prefix.'-'.str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }

    private function generateDetailCode(): string
    {
        return 'DT-'.substr(str()->uuid()->toString(), 0, 17);
    }
}
