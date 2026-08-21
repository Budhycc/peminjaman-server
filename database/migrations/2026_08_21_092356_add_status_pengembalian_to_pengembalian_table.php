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
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->enum('status_pengembalian', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('kondisi_Aset');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->dropColumn('status_pengembalian');
        });
    }
};
