<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Division;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // Menampilkan daftar karyawan beserta divisinya
    public function index()
    {
        $employees = Employee::with('division')->get();
        return view('employee.index', compact('employees'));
    }

    // Form tambah karyawan
    public function create()
    {
        $divisions = Division::all();
        return view('employee.create', compact('divisions'));
    }

    // Simpan karyawan baru
    public function store(Request $request)
    {
        $request->validate([
            'e_code' => 'required|unique:master_employee,e_code',
            'e_name' => 'required',
            'e_d_code' => 'required',
        ]);

        Employee::create($request->all());

        return redirect()->route('employee.index')->with('success', 'Data karyawan berhasil ditambahkan!');
    }
}