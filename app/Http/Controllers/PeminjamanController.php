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
        $peminjaman = Peminjaman::with(['aset', 'pengembalian'])->where('id_pengguna', $userId)->get();
        return response()->json($peminjaman);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Id_Aset' => 'required|exists:aset,Id_Aset',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string'
        ]);

        $aset = Aset::findOrFail($validated['Id_Aset']);

        $jumlahTersedia = $aset->jumlah_tersedia;

        if ($validated['jumlah'] > $jumlahTersedia) {
            return response()->json(['message' => 'Jumlah aset yang tersedia tidak mencukupi. Tersedia: ' . $jumlahTersedia], 400);
        }

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::create([
                'id_pengguna' => $request->user()->id_pengguna,
                'id_Aset' => $aset->Id_Aset,
                'jumlah' => $validated['jumlah'],
                'Tanggal_pinjam' => now()
            ]);

            if ($aset->jumlah_tersedia == 0) {
                $aset->update(['status_aset' => 'dipinjam']);
            }

            LogAktivitas::create([
                'id_pengguna' => $request->user()->id_pengguna,
                'Aktivitas' => 'Peminjaman aset ' . $aset->nama_Aset . ' sejumlah ' . $validated['jumlah'],
                'waktu' => now()
            ]);

            DB::commit();
            return response()->json($peminjaman, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to process loan', 'error' => $e->getMessage()], 500);
        }
    }
}
