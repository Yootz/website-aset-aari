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
        return view('dashboard', [
            'divisionCount' => Division::count(),
            'employeeCount' => Employee::count(),
            'assetCount' => Asset::count(),
            'peminjamanCount' => Peminjaman::count(),
            'divisions' => Division::withCount('employees')->orderBy('d_name')->get(),
        ]);
    }
}
