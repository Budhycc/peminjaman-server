<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'aset', 'pengembalian'])->orderBy('Tanggal_pinjam', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('Tanggal_pinjam', [$request->start_date, $request->end_date]);
        }
        
        $peminjaman = $query->get();
        
        return view('admin.reports.index', compact('peminjaman'));
    }
}
