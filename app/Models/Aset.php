<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use \App\Models\Traits\HasUniqueNumberId;

    protected $table = "aset";
    protected $primaryKey = "id_aset";

    protected $fillable = [
        "kode_aset",
        "nama_aset",
        "kategori",
        "merk",
        "lokasi",
        "kondisi",
        "status",
        "qr_code"
    ];

    public function peminjaman() {
        return $this->hasMany(Peminjaman::class, "id_aset");
    }
}