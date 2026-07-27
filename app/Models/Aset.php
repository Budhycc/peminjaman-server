<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use \App\Models\Traits\HasUniqueNumberId;

    protected $table = "aset";
    protected $primaryKey = "Id_Aset";

    protected $fillable = [
        "nama_Aset",
        "status_aset",
        "Row"
    ];

    public function peminjaman() {
        return $this->hasMany(Peminjaman::class, "id_Aset");
    }
}