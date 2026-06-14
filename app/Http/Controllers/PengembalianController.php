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
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'kondisi_kembali' => 'required|in:baik,rusak ringan,rusak berat',
            'catatan' => 'nullable|string'
        ]);

        $peminjaman = Peminjaman::with('aset')->findOrFail($validated['id_peminjaman']);

        if ($peminjaman->status === 'dikembalikan') {
            return response()->json(['message' => 'Aset has already been returned'], 400);
        }

        DB::beginTransaction();
        try {
            $pengembalian = Pengembalian::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'tanggal_kembali' => now(),
                'kondisi_kembali' => $validated['kondisi_kembali'],
                'catatan' => $validated['catatan'] ?? null
            ]);

            $peminjaman->update(['status' => 'dikembalikan']);
            $peminjaman->aset->update([
                'status' => 'tersedia',
                'kondisi' => $validated['kondisi_kembali']
            ]);

            LogAktivitas::create([
                'id_user' => $request->user()->id_user,
                'aktivitas' => 'Pengembalian aset ' . $peminjaman->aset->nama_aset,
                'waktu' => now(),
                'ip_address' => $request->ip()
            ]);

            DB::commit();
            return response()->json($pengembalian, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to process return', 'error' => $e->getMessage()], 500);
        }
    }
}
