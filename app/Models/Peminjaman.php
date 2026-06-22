<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use \App\Models\Traits\HasUniqueNumberId;

    protected $table = "peminjaman";
    protected $primaryKey = "id_peminjaman";

    protected $fillable = [
        "id_user",
        "id_aset",
        "tanggal_pinjam",
        "rencana_kembali",
        "status",
        "catatan"
    ];

    public function user() {
        return $this->belongsTo(User::class, "id_user");
    }

    public function aset() {
        return $this->belongsTo(Aset::class, "id_aset");
    }

    public function pengembalian() {
        return $this->hasOne(Pengembalian::class, "id_peminjaman");
    }
}