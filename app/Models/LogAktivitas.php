<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use \App\Models\Traits\HasUniqueNumberId;

    protected $table = "log_aktivitas";
    protected $primaryKey = "id_log";

    protected $fillable = [
        "id_user",
        "aktivitas",
        "waktu",
        "ip_address"
    ];

    public function user() {
        return $this->belongsTo(User::class, "id_user");
    }
}