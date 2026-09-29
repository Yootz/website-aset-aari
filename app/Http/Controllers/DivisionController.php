<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    // Menampilkan daftar divisi
    public function index()
    {
        $divisions = Division::orderBy('d_code')->paginate(5);
        return view('division.index', compact('divisions'));
    }

    public function loadMore(Request $request): JsonResponse
    {
        $page = $request->get('page', 2);
        $divisions = Division::orderBy('d_code')->paginate(5, ['*'], 'page', $page);

        return response()->json([
            'data' => $divisions->items(),
            'current_page' => $divisions->currentPage(),
            'last_page' => $divisions->lastPage(),
            'has_more' => $divisions->hasMorePages(),
        ]);
    }

    // Menampilkan form tambah divisi
    public function create()
    {
        return view('division.create');
    }

    // Menyimpan data divisi baru
    public function store(Request $request)
    {
        $request->validate([
            'd_code' => 'required|unique:master_division,d_code',
            'd_name' => 'required',
        ]);

        Division::create($request->all());

        return redirect()->route('division.index')->with('success', 'Divisi berhasil ditambahkan!');
    }
    // Form edit divisi
    public function edit($id)
    {
        $division = Division::findOrFail($id);
        return view('division.edit', compact('division'));
    }

    // Simpan perubahan divisi
    public function update(Request $request, $id)
    {
        $request->validate([
            'd_name' => 'required',
        ]);

        $division = Division::findOrFail($id);
        $division->update($request->all());

        return redirect()->route('division.index')->with('success', 'Divisi berhasil diperbarui!');
    }

    // Hapus divisi
    public function destroy($id)
    {
        $division = Division::findOrFail($id);
        $division->delete();

        return redirect()->route('division.index')->with('success', 'Divisi berhasil dihapus!');
    }
}