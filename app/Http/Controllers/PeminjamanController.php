<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index()
    {
        return response()->json(Peminjaman::with(['user', 'aset'])->get());
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'aset'])->findOrFail($id);
        return response()->json($peminjaman);
    }

    public function myHistory(Request $request)
    {
        $userId = $request->user()->id_pengguna;
        $peminjaman = Peminjaman::with('aset')->where('id_pengguna', $userId)->get();
        return response()->json($peminjaman);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Id_Aset' => 'required|exists:aset,id_aset',
            'Tanggal_kembali' => 'required|date|after:now',
            'catatan' => 'nullable|string'
        ]);

        $aset = Aset::findOrFail($validated['Id_Aset']);

        if ($aset->status !== 'tersedia') {
            return response()->json(['message' => 'Aset is not available for borrowing'], 400);
        }

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::create([
                'id_pengguna' => $request->user()->id_pengguna,
                'Id_Aset' => $aset->Id_Aset,
                'Tanggal_pinjam' => now(),
                'Tanggal_kembali' => $validated['Tanggal_kembali'],
                'status' => 'dipinjam',
                'catatan' => $validated['catatan'] ?? null
            ]);

            $aset->update(['status' => 'dipinjam']);

            LogAktivitas::create([
                'id_pengguna' => $request->user()->id_pengguna,
                'aktivitas' => 'Peminjaman aset ' . $aset->nama_aset,
                'waktu' => now(),
                'ip_address' => $request->ip()
            ]);

            DB::commit();
            return response()->json($peminjaman, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to process loan', 'error' => $e->getMessage()], 500);
        }
    }
}
