<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Aset;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalAset = Aset::count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $dikembalikanBulanIni = Pengembalian::whereMonth('tanggal_kembali', Carbon::now()->month)
                                            ->whereYear('tanggal_kembali', Carbon::now()->year)
                                            ->count();

        $recentPeminjaman = Peminjaman::with(['user', 'aset'])
                                      ->orderBy('created_at', 'desc')
                                      ->take(5)
                                      ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalAset', 
            'peminjamanAktif', 
            'dikembalikanBulanIni', 
            'recentPeminjaman'
        ));
    }
}
