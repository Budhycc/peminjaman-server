<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, \App\Models\Traits\HasUniqueNumberId;

    protected $primaryKey = "id_user";

    protected $fillable = [
        "nama",
        "username",
        "password",
        "email",
        "role",
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