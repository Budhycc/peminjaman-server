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
        Schema::table('aset', function (Blueprint $table) {
            $table->integer('jumlah')->default(1)->after('status_aset');
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->integer('jumlah')->default(1)->after('id_Aset');
        });

        Schema::table('pengembalian', function (Blueprint $table) {
            $table->integer('jumlah')->default(1)->after('id_peminjaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aset', function (Blueprint $table) {
            $table->dropColumn('jumlah');
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('jumlah');
        });

        Schema::table('pengembalian', function (Blueprint $table) {
            $table->dropColumn('jumlah');
        });
    }
};
