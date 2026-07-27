<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use \App\Models\Traits\HasUniqueNumberId;

    protected $table = "peminjaman";
    protected $primaryKey = "Id_peminjaman";

    protected $fillable = [
        "id_pengguna",
        "id_Aset",
        "Tanggal_pinjam",
        "Tanggal_kembali"
    ];

    public function user() {
        return $this->belongsTo(User::class, "id_pengguna", "id_pengguna");
    }

    public function aset() {
        return $this->belongsTo(Aset::class, "id_Aset", "Id_Aset");
    }

    public function pengembalian() {
        return $this->hasOne(Pengembalian::class, "id_peminjaman", "Id_peminjaman");
    }
}