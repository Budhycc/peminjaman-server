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
        Schema::create('table_qr_code', function (Blueprint $table) {
            $table->id('id_qr');
            $table->foreignId('id_Aset')->constrained('aset', 'Id_Aset')->onDelete('cascade');
            $table->dateTime('tanggal_generate');
            $table->string('kode_unik', 100)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_qr_code');
    }
};
