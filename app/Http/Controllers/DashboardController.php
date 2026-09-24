<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Employee;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'divisionCount' => Division::count(),
            'employeeCount' => Employee::count(),
            'divisions' => Division::withCount('employees')->orderBy('d_name')->get(),
        ]);
    }
}
