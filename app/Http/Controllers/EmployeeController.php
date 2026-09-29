<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Division;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // Menampilkan daftar karyawan beserta divisinya
    public function index()
    {
        $employees = Employee::with('division')->orderBy('e_code')->paginate(5);
        return view('employee.index', compact('employees'));
    }

    public function loadMore(Request $request): JsonResponse
    {
        $page = $request->get('page', 2);
        $employees = Employee::with('division')->orderBy('e_code')->paginate(5, ['*'], 'page', $page);

        return response()->json([
            'data' => $employees->items(),
            'current_page' => $employees->currentPage(),
            'last_page' => $employees->lastPage(),
            'has_more' => $employees->hasMorePages(),
        ]);
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
    // Form edit karyawan
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $divisions = Division::all();
        return view('employee.edit', compact('employee', 'divisions'));
    }

    // Simpan perubahan karyawan
    public function update(Request $request, $id)
    {
        $request->validate([
            'e_name' => 'required',
            'e_d_code' => 'required',
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update($request->all());

        return redirect()->route('employee.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    // Hapus karyawan
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('employee.index')->with('success', 'Data karyawan berhasil dihapus!');
    }
}