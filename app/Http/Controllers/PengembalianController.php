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
            'jumlah' => 'required|integer|min:1',
            'kondisi_Aset' => 'required|in:baik,rusak',
            'catatan' => 'nullable|string'
        ]);

        $peminjaman = Peminjaman::with('aset')->findOrFail($validated['Id_peminjaman']);

        $sisaPinjaman = $peminjaman->sisa_pinjaman;

        if ($validated['jumlah'] > $sisaPinjaman) {
            return response()->json(['message' => 'Jumlah pengembalian melebihi sisa pinjaman. Sisa yang harus dikembalikan: ' . $sisaPinjaman], 400);
        }

        DB::beginTransaction();
        try {
            $pengembalian = Pengembalian::create([
                'id_peminjaman' => $peminjaman->Id_peminjaman,
                'jumlah' => $validated['jumlah'],
                'tanggal_kembali' => now(),
                'kondisi_Aset' => $validated['kondisi_Aset'] === 'rusak' ? 'rusak berat' : 'baik'
            ]);

            // Aset availability is calculated dynamically, no need to update status_aset here.
            // Furthermore, the return is now pending verification.

            LogAktivitas::create([
                'id_pengguna' => $request->user()->id_pengguna,
                'Aktivitas' => 'Pengembalian aset ' . $peminjaman->aset->nama_Aset . ' sejumlah ' . $validated['jumlah'],
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
