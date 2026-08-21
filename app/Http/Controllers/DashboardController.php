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
        
        $totalAset = Aset::sum('jumlah');
        $totalDipinjam = Peminjaman::sum('jumlah');
        $totalDikembalikan = Pengembalian::sum('jumlah');
        
        $peminjamanAktif = $totalDipinjam - $totalDikembalikan;
        $asetTersedia = $totalAset - $peminjamanAktif;
        
        $dikembalikanBulanIni = Pengembalian::whereMonth('tanggal_kembali', Carbon::now()->month)
                                            ->whereYear('tanggal_kembali', Carbon::now()->year)
                                            ->sum('jumlah');

        $recentPeminjaman = Peminjaman::with(['user', 'aset', 'pengembalian'])
                                      ->orderBy('created_at', 'desc')
                                      ->take(5)
                                      ->get();

        // Data for Chart: Peminjaman per month for the current year
        $peminjamans = Peminjaman::whereYear('Tanggal_pinjam', Carbon::now()->year)->get();
        $chartData = $peminjamans->groupBy(function($item) {
            return Carbon::parse($item->Tanggal_pinjam)->format('n'); // 1 to 12
        })->map(function($group) {
            return $group->sum('jumlah');
        })->toArray();

        // Prepare array for all 12 months
        $monthlyPeminjaman = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyPeminjaman[] = $chartData[$i] ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalAset',
            'asetTersedia', 
            'peminjamanAktif', 
            'dikembalikanBulanIni', 
            'recentPeminjaman',
            'monthlyPeminjaman'
        ));
    }
}