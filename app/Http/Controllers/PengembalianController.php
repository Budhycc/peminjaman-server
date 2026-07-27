<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index()
    {
        return response()->json(Pengembalian::with('peminjaman.aset', 'peminjaman.user')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'kondisi_Aset' => 'required|in:baik,rusak ringan,rusak berat',
            'catatan' => 'nullable|string'
        ]);

        $peminjaman = Peminjaman::with('aset')->findOrFail($validated['Id_peminjaman']);

        if (Pengembalian::where('id_peminjaman', $validated['Id_peminjaman'])->exists()) {
            return response()->json(['message' => 'Aset has already been returned'], 400);
        }

        DB::beginTransaction();
        try {
            $pengembalian = Pengembalian::create([
                'id_peminjaman' => $peminjaman->Id_peminjaman,
                'tanggal_kembali' => now(),
                'kondisi_Aset' => $validated['kondisi_Aset']
            ]);

            $peminjaman->aset->update([
                'status_aset' => 'tersedia'
            ]);

            LogAktivitas::create([
                'id_pengguna' => $request->user()->id_pengguna,
                'Aktivitas' => 'Pengembalian aset ' . $peminjaman->aset->nama_Aset,
                'waktu' => now()
            ]);

            DB::commit();
            return response()->json($pengembalian, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to process return', 'error' => $e->getMessage()], 500);
        }
    }
}
