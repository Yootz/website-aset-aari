<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    // Menampilkan daftar divisi
    public function index()
    {
        $divisions = Division::all();
        return view('division.index', compact('divisions'));
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
}