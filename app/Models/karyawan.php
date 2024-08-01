<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class karyawan extends Model
{
    use HasFactory;
    protected $table = 'karyawan';
    protected $fillable = ['nama', 'spesialisasi', 'tingkat_keahlian', 'produksi_count', 'smv','gaji'];

       // Relasi dengan model Produksi (one-to-many)
       public function produksi()
       {
           return $this->hasMany(Produksi::class);
       }

       // Fungsi untuk mengecek apakah karyawan cocok untuk suatu produk
       public function cocokUntukProduk(Produk $produk)
       {
           $spesialisasiProduk = $produk->nama; // Atau gunakan kolom lain yang relevan
           $spesialisasiKaryawan = $this->spesialisasi;

           if ($spesialisasiKaryawan === $spesialisasiProduk || $spesialisasiKaryawan === 'Lainnya') {
               return true;
           } else {
               return false;
           }
       }

       // Fungsi untuk menghitung total produksi karyawan
       public function totalProduksi()
       {
           return $this->produksi->sum('jumlah');
       }

       // Fungsi untuk menghitung total produksi karyawan per produk tertentu
       public function totalProduksiPerProduk(Produk $produk)
       {
           return $this->produksi()->where('produk_id', $produk->id)->sum('jumlah');
       }

       // Fungsi untuk menghitung total produksi karyawan per periode waktu tertentu
       public function totalProduksiPerPeriode($startDate, $endDate)
       {
           return $this->produksi()
                       ->whereBetween('tanggal', [$startDate, $endDate])
                       ->sum('jumlah');
       }
}
