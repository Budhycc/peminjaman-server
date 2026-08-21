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
            $table->renameColumn('Row', 'jenis_barang');
            $table->string('tempat_barang', 150)->nullable()->after('status_aset');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aset', function (Blueprint $table) {
            $table->dropColumn('tempat_barang');
            $table->renameColumn('jenis_barang', 'Row');
        });
    }
};
