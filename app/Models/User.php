<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, \App\Models\Traits\HasUniqueNumberId;

    protected $primaryKey = "id_pengguna";

    protected $fillable = [
        "nama_pengguna",
        "Username",
        "password",
        "email",
        "role",
        "Unit_Kerja",
        "Status_Akun",
    ];

    protected $hidden = [
        "password",
    ];

    protected function casts(): array
    {
        return [
            "password" => "hashed",
        ];
    }
}