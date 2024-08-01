<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesinJahit extends Model
{
    use HasFactory;
    protected $table = 'mesin_jahit';
    protected $fillable = ['nama', 'status'];
}
