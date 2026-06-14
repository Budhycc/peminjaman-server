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
        Schema::create('aset', function (Blueprint $table) {
            $table->id('id_aset');
            $table->string('kode_aset', 50);
            $table->string('nama_aset', 100);
            $table->string('kategori', 50);
            $table->string('merk', 100)->nullable();
            $table->string('lokasi', 100);
            $table->enum('kondisi', ['baik', 'rusak ringan', 'rusak berat']);
            $table->enum('status', ['tersedia', 'dipinjam']);
            $table->text('qr_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aset');
    }
};
