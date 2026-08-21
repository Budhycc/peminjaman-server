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
        "jumlah",
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
        return $this->hasMany(Pengembalian::class, "id_peminjaman", "Id_peminjaman");
    }

    public function getSisaPinjamanAttribute() {
        $dikembalikan = $this->pengembalian()
            ->where('status_pengembalian', '!=', 'ditolak')
            ->sum('jumlah');
        return max(0, $this->jumlah - $dikembalikan);
    }

    public function getLamaPinjamAttribute() {
        $pinjam = \Carbon\Carbon::parse($this->Tanggal_pinjam);
        
        if ($this->pengembalian->count() > 0) {
            // Use the date of the most recent return
            $lastReturn = $this->pengembalian->sortByDesc('tanggal_kembali')->first();
            $kembali = \Carbon\Carbon::parse($lastReturn->tanggal_kembali);
        } else {
            $kembali = \Carbon\Carbon::now();
        }
        
        $diff = $pinjam->diff($kembali);
        $parts = [];
        if ($diff->d > 0 || $diff->m > 0 || $diff->y > 0) {
            $days = $diff->days; // total days
            $parts[] = $days . ' Hari';
        }
        if ($diff->h > 0) {
            $parts[] = $diff->h . ' Jam';
        }
        if ($diff->i > 0 && empty($parts)) {
            $parts[] = $diff->i . ' Menit';
        }
        
        if (empty($parts)) {
            return 'Baru saja';
        }
        
        return implode(' ', $parts);
    }
}