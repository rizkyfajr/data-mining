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


        Schema::create('produksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id');
            $table->unsignedBigInteger('produk_id');
            $table->integer('jumlah');
            $table->date('tanggal');
            $table->timestamp('waktu_mulai');
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();

            $table->foreign('karyawan_id')->references('id')->on('karyawan');
            $table->foreign('produk_id')->references('id')->on('produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi');
    }
};
