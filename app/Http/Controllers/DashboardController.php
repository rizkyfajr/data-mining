<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data produksi terbaru (misalnya 5 data terakhir)
        $produksiTerbaru = Produksi::with('produk')->latest()->limit(5)->get();
        // $dataGrafik = Produksi::with('produk')->latest()->limit(5)->get();

        // Data untuk grafik (contoh)
        $dataGrafik = Produksi::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('SUM(smv) as total_produksi'),
            DB::raw('MAX(created_at) as created_at')
        )
        ->groupBy('created_at')
        ->get();



        return view('dashboard', compact('produksiTerbaru', 'dataGrafik'));
    }
}
