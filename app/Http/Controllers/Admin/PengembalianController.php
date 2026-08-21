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

    public function create(Request $request)
    {
        $peminjamans = Peminjaman::with(['user', 'aset'])->get()->filter(function ($p) {
            return $p->sisa_pinjaman > 0;
        });
        
        $selectedPeminjamanId = $request->query('peminjaman_id');
        return view('admin.pengembalian.create', compact('peminjamans', 'selectedPeminjamanId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Id_peminjaman' => 'required|exists:peminjaman,Id_peminjaman',
            'jumlah' => 'required|integer|min:1',
            'tanggal_kembali' => 'required|date',
            'kondisi_Aset' => 'required|in:baik,rusak',
            'status_pengembalian' => 'nullable|in:pending,disetujui,ditolak',
            'catatan' => 'nullable|string'
        ]);

        $peminjaman = Peminjaman::find($validated['Id_peminjaman']);
        $sisaPinjaman = $peminjaman ? $peminjaman->sisa_pinjaman : 0;
        
        if ($validated['jumlah'] > $sisaPinjaman) {
            return back()->withErrors(['jumlah' => 'Jumlah pengembalian melebihi sisa pinjaman. Sisa: ' . $sisaPinjaman])->withInput();
        }

        $validated['id_peminjaman'] = $validated['Id_peminjaman'];
        
        // All new returns should start as pending by default
        if (!isset($validated['status_pengembalian'])) {
            $validated['status_pengembalian'] = 'pending';
        }
        // Map 'rusak' to 'rusak berat' to satisfy DB enum constraints
        if ($validated['kondisi_Aset'] === 'rusak') {
            $validated['kondisi_Aset'] = 'rusak berat';
        }
        
        $pengembalian = Pengembalian::create($validated);

        // Update Peminjaman status is not needed because it's derived dynamically
        // Aset availability is calculated dynamically, no need to update status_aset here

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
            'Id_peminjaman' => 'required|exists:peminjaman,Id_peminjaman',
            'jumlah' => 'required|integer|min:1',
            'tanggal_kembali' => 'required|date',
            'kondisi_Aset' => 'required|in:baik,rusak',
            'status_pengembalian' => 'required|in:pending,disetujui,ditolak',
            'catatan' => 'nullable|string'
        ]);

        $validated['id_peminjaman'] = $validated['Id_peminjaman'];
        
        // Map 'rusak' to 'rusak berat' to satisfy DB enum constraints
        if ($validated['kondisi_Aset'] === 'rusak') {
            $validated['kondisi_Aset'] = 'rusak berat';
        }

        $pengembalian->update($validated);

        return redirect()->route('admin.returns.index')->with('success', 'Pengembalian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        $pengembalian->delete();

        return redirect()->route('admin.returns.index')->with('success', 'Pengembalian berhasil dihapus.');
    }
}
