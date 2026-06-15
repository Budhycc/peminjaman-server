<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\Aset;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalian = Pengembalian::with(['peminjaman.user', 'peminjaman.aset'])->orderBy('id_pengembalian', 'desc')->get();
        return view('admin.pengembalian.index', compact('pengembalian'));
    }

    public function create()
    {
        $peminjamans = Peminjaman::with(['user', 'aset'])->where('status', 'dipinjam')->get();
        return view('admin.pengembalian.create', compact('peminjamans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'tanggal_kembali' => 'required|date',
            'kondisi_kembali' => 'required|in:baik,rusak ringan,rusak berat',
            'catatan' => 'nullable|string'
        ]);

        $pengembalian = Pengembalian::create($validated);

        // Update Peminjaman status
        $peminjaman = Peminjaman::find($validated['id_peminjaman']);
        if ($peminjaman) {
            $peminjaman->update(['status' => 'dikembalikan']);
            
            // Update Aset status and kondisi
            $aset = Aset::find($peminjaman->id_aset);
            if ($aset) {
                $aset->update([
                    'status' => 'tersedia',
                    'kondisi' => $validated['kondisi_kembali']
                ]);
            }
        }

        return redirect()->route('admin.returns.index')->with('success', 'Pengembalian berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pengembalian = Pengembalian::with(['peminjaman.user', 'peminjaman.aset'])->findOrFail($id);
        return view('admin.pengembalian.show', compact('pengembalian'));
    }

    public function edit($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        $peminjamans = Peminjaman::with(['user', 'aset'])->get();
        return view('admin.pengembalian.edit', compact('pengembalian', 'peminjamans'));
    }

    public function update(Request $request, $id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        $validated = $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'tanggal_kembali' => 'required|date',
            'kondisi_kembali' => 'required|in:baik,rusak ringan,rusak berat',
            'catatan' => 'nullable|string'
        ]);

        $pengembalian->update($validated);

        // Update Aset condition again based on this new update
        $peminjaman = Peminjaman::find($validated['id_peminjaman']);
        if ($peminjaman) {
            $aset = Aset::find($peminjaman->id_aset);
            if ($aset) {
                $aset->update([
                    'kondisi' => $validated['kondisi_kembali']
                ]);
            }
        }

        return redirect()->route('admin.returns.index')->with('success', 'Pengembalian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        $pengembalian->delete();

        return redirect()->route('admin.returns.index')->with('success', 'Pengembalian berhasil dihapus.');
    }
}
