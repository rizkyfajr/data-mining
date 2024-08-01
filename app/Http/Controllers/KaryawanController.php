<?php

namespace App\Http\Controllers;

use App\Models\karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawan = Karyawan::all();
        return view('karyawan.index', compact('karyawan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'spesialisasi' => 'required',
            'tingkat_keahlian' => 'required',
            'gaji' => 'required'
        ]);

        Karyawan::create($request->all());

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');

    }

    /**
     * Display the specified resource.
     */
    public function show(karyawan $karyawan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(karyawan $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, karyawan $karyawan)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'spesialisasi' => 'required',
            'tingkat_keahlian' => 'required',
            'gaji' => 'required'
        ]);

        $karyawan->update($validatedData);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(karyawan $karyawan)
    {
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
