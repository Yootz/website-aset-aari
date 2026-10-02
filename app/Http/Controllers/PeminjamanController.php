<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Employee;
use App\Models\Peminjaman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function create(): View
    {
        $employees = Employee::query()->orderBy('e_name')->get();
        $assets = Asset::query()
            ->whereRaw('LOWER(a_status) = ?', ['available'])
            ->orderBy('a_code')
            ->get();

        $selectedAssetCode = request()->query('a_code');

        if (! $assets->contains('a_code', $selectedAssetCode)) {
            $selectedAssetCode = null;
        }

        return view('peminjaman.create', compact('employees', 'assets', 'selectedAssetCode'));
    }

    public function storeWeb(Request $request): RedirectResponse
    {
        $this->store($request);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dibuat.');
    }

    public function webIndex(): View
    {
        $peminjaman = Peminjaman::with(['employee', 'details.asset'])
            ->latest('created_at')
            ->paginate(5);

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function monitoring(): View
    {
        $peminjaman = Peminjaman::with(['employee', 'details.asset'])
            ->latest('created_at')
            ->paginate(5);

        return view('peminjaman.monitoring', compact('peminjaman'));
    }

    public function edit(Peminjaman $peminjaman): View
    {
        $peminjaman->load(['employee', 'details.asset']);
        $employees = Employee::query()->orderBy('e_name')->get();
        $linkedAssetCodes = $peminjaman->details->pluck('a_code');
        $assets = Asset::query()
            ->where(function ($query) use ($linkedAssetCodes): void {
                $query->whereRaw('LOWER(a_status) = ?', ['available'])
                    ->orWhereIn('a_code', $linkedAssetCodes);
            })
            ->orderBy('a_code')
            ->get();
        $detailRows = old('details', $peminjaman->details
            ->map(fn ($detail): array => ['a_code' => $detail->a_code, 'dt_qty' => $detail->dt_qty])
            ->values()
            ->all());

        if ($detailRows === []) {
            $detailRows = [['a_code' => '', 'dt_qty' => 1]];
        }

        return view('peminjaman.edit', compact('peminjaman', 'employees', 'assets', 'detailRows'));
    }

    public function update(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $validated = $request->validate([
            'e_code' => ['required', 'exists:master_employee,e_code'],
            'tgl_pinjam' => ['required', 'date'],
            'tgl_balik' => ['nullable', 'date', 'after_or_equal:tgl_pinjam'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.a_code' => ['required', 'string', 'distinct', 'exists:master_aset,a_code'],
            'details.*.dt_qty' => ['required', 'integer', 'in:1'],
        ]);

        DB::transaction(function () use ($peminjaman, $validated): void {
            $currentDetails = $peminjaman->details()->get();
            $currentAssetCodes = $currentDetails->pluck('a_code')->all();
            $requestedDetails = collect($validated['details']);
            $requestedAssetCodes = $requestedDetails->pluck('a_code')->all();
            $newAssetCodes = array_values(array_diff($requestedAssetCodes, $currentAssetCodes));
            $assetCodesToLock = collect($currentAssetCodes)->merge($requestedAssetCodes)->unique();
            $assets = Asset::query()
                ->whereIn('a_code', $assetCodesToLock)
                ->lockForUpdate()
                ->get()
                ->keyBy('a_code');

            $unavailableAssets = collect($newAssetCodes)
                ->filter(fn (string $assetCode): bool => ! isset($assets[$assetCode]) || strtolower(trim((string) $assets[$assetCode]->a_status)) !== 'available')
                ->values();

            if ($unavailableAssets->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'details' => 'One or more added assets are no longer available: '.$unavailableAssets->implode(', ').'.',
                ]);
            }

            $peminjaman->update([
                'e_code' => $validated['e_code'],
                'tgl_pinjam' => $validated['tgl_pinjam'],
                'tgl_balik' => $validated['tgl_balik'] ?? null,
            ]);

            foreach ($currentDetails as $currentDetail) {
                if (! in_array($currentDetail->a_code, $requestedAssetCodes, true)) {
                    $currentDetail->delete();
                }
            }

            foreach ($requestedDetails as $requestedDetail) {
                $existingDetail = $currentDetails->firstWhere('a_code', $requestedDetail['a_code']);

                if ($existingDetail !== null) {
                    $existingDetail->update(['dt_qty' => $requestedDetail['dt_qty']]);

                    continue;
                }

                $peminjaman->details()->create([
                    'dt_code' => $this->generateDetailCode(),
                    'a_code' => $requestedDetail['a_code'],
                    'dt_qty' => $requestedDetail['dt_qty'],
                    'dt_status' => 'borrowed',
                ]);
            }

            $removedAssetCodes = array_values(array_diff($currentAssetCodes, $requestedAssetCodes));

            if ($removedAssetCodes !== []) {
                Asset::query()->whereIn('a_code', $removedAssetCodes)->update(['a_status' => 'available']);
            }

            if ($newAssetCodes !== []) {
                Asset::query()->whereIn('a_code', $newAssetCodes)->update(['a_status' => 'pending']);
            }
        });

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function loadMore(Request $request): JsonResponse
    {
        $page = $request->get('page', 2);
        $peminjaman = Peminjaman::with(['employee', 'details.asset'])
            ->latest('created_at')
            ->paginate(5, ['*'], 'page', $page);

        return response()->json([
            'data' => $peminjaman->items(),
            'current_page' => $peminjaman->currentPage(),
            'last_page' => $peminjaman->lastPage(),
            'has_more' => $peminjaman->hasMorePages(),
        ]);
    }

    public function updateStatusWeb(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $this->updateStatus($request, $peminjaman);

        return redirect()->route('peminjaman.monitoring')->with('success', 'Status peminjaman berhasil diperbarui.');
    }

    public function show(Peminjaman $peminjaman): View
    {
        $peminjaman->load(['employee.division', 'details.asset']);

        return view('peminjaman.show', compact('peminjaman'));
    }

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
                ->filter(fn (string $assetCode): bool => ! isset($assets[$assetCode]) || strtolower(trim((string) $assets[$assetCode]->a_status)) !== 'available')
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

                $assets[$detail['a_code']]->update(['a_status' => 'pending']);
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

        if (in_array($validated['p_status'], ['returned', 'rejected'], true)) {
            return $this->returnAsset($peminjaman, $validated['p_status']);
        }

        DB::transaction(function () use ($peminjaman, $validated): void {
            if ($validated['p_status'] === 'approved') {
                $assetCodes = $peminjaman->details()->pluck('a_code')->unique();

                Asset::query()
                    ->whereIn('a_code', $assetCodes)
                    ->update(['a_status' => 'unavailable']);
            }

            $peminjaman->update(['p_status' => $validated['p_status']]);
        });

        return response()->json([
            'message' => 'Peminjaman status updated successfully.',
            'data' => $peminjaman->fresh(['employee', 'details.asset']),
        ]);
    }

    public function returnAsset(Peminjaman $peminjaman, string $status = 'returned'): JsonResponse
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

            $peminjaman->update(['p_status' => $status]);
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
