<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableQrCode extends Model
{
    protected $table = 'table_qr_code';
    protected $primaryKey = 'id_qr';
    
    protected $fillable = [
        'id_Aset',
        'tanggal_generate',
        'kode_unik'
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'id_Aset', 'Id_Aset');
    }
}
