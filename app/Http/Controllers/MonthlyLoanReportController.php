<?php

namespace App\Http\Controllers;

use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class MonthlyLoanReportController extends Controller
{
    public function page(): View
    {
        return view('laporan.peminjaman-bulanan');
    }

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'month' => ['sometimes', 'integer', 'between:1,12'],
            'year' => ['sometimes', 'integer', 'between:2000,2100'],
        ]);

        $month = (int) ($validated['month'] ?? now()->month);
        $year = (int) ($validated['year'] ?? now()->year);
        $period = Carbon::create($year, $month, 1);

        $loans = Peminjaman::query()
            ->whereBetween('tgl_pinjam', [
                $period->copy()->startOfMonth()->toDateString(),
                $period->copy()->endOfMonth()->toDateString(),
            ])
            ->with([
                'employee:e_code,e_name',
                'details.asset:a_code,a_name',
            ])
            ->orderBy('tgl_pinjam')
            ->orderBy('p_code')
            ->get();

        $data = $loans->flatMap(function (Peminjaman $loan): array {
            $loanDate = Carbon::parse($loan->getRawOriginal('tgl_pinjam'));
            $returnDateValue = $loan->getRawOriginal('tgl_balik');
            $returnDate = $returnDateValue === null ? null : Carbon::parse($returnDateValue);

            return $loan->details->map(function (DetailPeminjaman $detail) use ($loan, $loanDate, $returnDate): array {
                return [
                    'p_code' => $loan->p_code,
                    'tgl_pinjam' => $loanDate->toDateString(),
                    'tgl_balik' => $returnDate?->toDateString(),
                    'durasi_hari' => $returnDate === null
                        ? null
                        : (int) $loanDate->diffInDays($returnDate),
                    'peminjam' => [
                        'e_code' => $loan->employee?->e_code,
                        'nama' => $loan->employee?->e_name,
                    ],
                    'aset' => [
                        'a_code' => $detail->asset?->a_code,
                        'nama' => $detail->asset?->a_name,
                    ],
                    'jumlah' => $detail->dt_qty,
                    'status_peminjaman' => $loan->p_status,
                    'status_aset' => $detail->dt_status,
                ];
            })->all();
        })->values();

        return response()->json([
            'period' => [
                'month' => $month,
                'year' => $year,
            ],
            'summary' => [
                'total_transaksi' => $loans->count(),
                'total_aset' => $data->count(),
            ],
            'data' => $data,
        ]);
    }
}
