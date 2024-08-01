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
        Schema::create('detail_produksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produksi_id');
            $table->unsignedBigInteger('karyawan_id');
            $table->timestamp('waktu_mulai');
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();

            $table->foreign('produksi_id')->references('id')->on('produksi');
            $table->foreign('karyawan_id')->references('id')->on('karyawan');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_produksi');
    }
};
