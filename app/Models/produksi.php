<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produksi extends Model
{
    use HasFactory;
    protected $table = 'produksi';
    protected $fillable = ['produk_id', 'jumlah_pekerja', 'mesin_jahit_id', 'kode_produksi', 'smv', 'tanggal', 'waktu_mulai', 'waktu_selesai', 'karyawan_id'];
    protected $casts = [
        'karyawan_id' => 'array', // Cast kolom karyawan_id menjadi json saat diambil dari database
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function produksi()
    {
        return $this->hasMany(Produksi::class);
    }

    public function cocokUntukProduk(Produk $produk)
    {
        return $this->spesialisasi === $produk->nama || $this->spesialisasi === 'Lainnya';
    }

    // Di dalam model Produksi.php
    public function mesinJahit()
    {
        return $this->belongsTo(MesinJahit::class, 'mesin_jahit_id'); // 'mesin_jahit_id' adalah foreign key di tabel produksi
    }

}
