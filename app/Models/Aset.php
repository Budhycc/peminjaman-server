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
        "jumlah",
        "jenis_barang",
        "tempat_barang",
        "foto_aset",
        "jumlah_diperbaiki"
    ];

    public function peminjaman() {
        return $this->hasMany(Peminjaman::class, "id_Aset");
    }

    public function qrCode() {
        return $this->hasOne(TableQrCode::class, 'id_Aset', 'Id_Aset');
    }

    public function getJumlahTersediaAttribute() {
        $totalDipinjam = $this->peminjaman()->sum('jumlah');
        $totalDikembalikanBaik = \App\Models\Pengembalian::whereHas('peminjaman', function($q) {
            $q->where('id_Aset', $this->Id_Aset);
        })->where('kondisi_Aset', 'baik')
          ->where('status_pengembalian', 'disetujui')
          ->sum('jumlah');

        return max(0, $this->jumlah - ($totalDipinjam - $totalDikembalikanBaik) + $this->jumlah_diperbaiki);
    }

    public function getJumlahRusakAttribute() {
        $totalRusak = \App\Models\Pengembalian::whereHas('peminjaman', function($q) {
            $q->where('id_Aset', $this->Id_Aset);
        })->whereIn('kondisi_Aset', ['rusak', 'rusak ringan', 'rusak berat'])
          ->where('status_pengembalian', 'disetujui')
          ->sum('jumlah');
        return max(0, $totalRusak - $this->jumlah_diperbaiki);
    }
}