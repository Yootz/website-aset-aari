<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Peminjaman;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $divisions = Division::withCount('employees')->orderBy('d_name')->get();
        $divisionCount = $divisions->count();
        $employeeCount = Employee::count();
        $assetStatusCounts = Asset::query()
            ->selectRaw('LOWER(TRIM(a_status)) as status, COUNT(*) as aggregate')
            ->groupByRaw('LOWER(TRIM(a_status))')
            ->pluck('aggregate', 'status')
            ->map(fn ($count): int => (int) $count);
        $loanStatusCounts = Peminjaman::query()
            ->selectRaw('LOWER(TRIM(p_status)) as status, COUNT(*) as aggregate')
            ->groupByRaw('LOWER(TRIM(p_status))')
            ->pluck('aggregate', 'status')
            ->map(fn ($count): int => (int) $count);

        return view('dashboard', [
            'divisionCount' => $divisionCount,
            'employeeCount' => $employeeCount,
            'assetCount' => $assetStatusCounts->sum(),
            'peminjamanCount' => $loanStatusCounts->sum(),
            'assetStatusCounts' => $assetStatusCounts,
            'loanStatusCounts' => $loanStatusCounts,
            'divisions' => $divisions,
        ]);
    }
}
