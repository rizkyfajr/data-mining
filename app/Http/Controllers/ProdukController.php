<?php
namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        return view('produk.index', compact('produk'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|unique:produk|max:255',
            'smv' => 'required|numeric',
            'target_produksi_harian' => 'required|integer',
        ]);

        Produk::create($validatedData);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Produk $produk)
    {
        return view('produk.show', compact('produk'));
    }

    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $validatedData = $request->validate([
            'nama' => 'required|unique:produk,nama,' . $produk->id . '|max:255', // Unique kecuali ID saat ini
            'smv' => 'required|numeric',
            'target_produksi_harian' => 'required|integer',
        ]);

        $produk->update($validatedData);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
