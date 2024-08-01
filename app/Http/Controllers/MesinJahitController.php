<?php

namespace App\Http\Controllers;

use App\Models\MesinJahit;
use Illuminate\Http\Request;

class MesinJahitController extends Controller
{
    public function index()
    {
        $mesinJahit = MesinJahit::all();
        return view('mesin_jahit.index', compact('mesinJahit'));
    }

    public function create()
    {
        return view('mesin_jahit.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|unique:mesin_jahit|max:255',
            'status' => 'required|in:Tersedia,Perbaikan,Tidak Aktif',
        ]);

        MesinJahit::create($validatedData);

        return redirect()->route('mesinjahit.index')->with('success', 'Mesin jahit berhasil ditambahkan.');
    }

    public function show(MesinJahit $mesinJahit)
    {
        return view('mesin_jahit.show', compact('mesinJahit'));
    }

    public function edit(MesinJahit $mesinJahit)
    {
        return view('mesin_jahit.edit', compact('mesinJahit'));
    }

    public function update(Request $request, MesinJahit $mesinJahit)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'status' => 'required|in:Tersedia,Perbaikan,Tidak Aktif',
        ]);

        $mesinJahit->update($validatedData);

        return redirect()->route('mesinjahit.index')->with('success', 'Data mesin jahit berhasil diperbarui.');
    }

    public function destroy(MesinJahit $mesinJahit)
    {
        $mesinJahit->delete();

        return redirect()->route('mesinjahit.index')->with('success', 'Mesin jahit berhasil dihapus.');
    }
}
