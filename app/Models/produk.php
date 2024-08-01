<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $fillable = ['nama', 'smv', 'target_produksi_harian'];

    public function produksi()
    {
        return $this->hasMany(Produksi::class);
    }
}
