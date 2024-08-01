<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');

            $table->enum('spesialisasi', ['Penjahit', 'Pemotong', 'Quality Control', 'Lainnya']);
            $table->enum('tingkat_keahlian', ['Junior', 'Menengah', 'Senior']);
            $table->decimal('gaji', 8, 2); // Gaji per jam, 8 digit dengan 2 desimal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
