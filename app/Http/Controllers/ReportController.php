<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function inventory()
    {
        $asets = Aset::all();
        $summary = [
            'total' => $asets->count(),
            'tersedia' => $asets->where('status', 'tersedia')->count(),
            'dipinjam' => $asets->where('status', 'dipinjam')->count(),
            'kondisi_baik' => $asets->where('kondisi', 'baik')->count(),
            'kondisi_rusak_ringan' => $asets->where('kondisi', 'rusak ringan')->count(),
            'kondisi_rusak_berat' => $asets->where('kondisi', 'rusak berat')->count(),
        ];

        return response()->json([
            'summary' => $summary,
            'data' => $asets
        ]);
    }

    public function loans()
    {
        $peminjaman = Peminjaman::with(['user', 'aset'])->get();
        return response()->json($peminjaman);
    }

    public function returns()
    {
        $pengembalian = Pengembalian::with(['peminjaman.user', 'peminjaman.aset'])->get();
        return response()->json($pengembalian);
    }
}
