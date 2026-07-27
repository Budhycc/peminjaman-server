<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Aset;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['user', 'aset'])->orderBy('Id_peminjaman', 'desc')->get();
        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $users = User::all();
        $asets = Aset::where('status', 'tersedia')->get();
        return view('admin.peminjaman.create', compact('users', 'asets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pengguna' => 'required|exists:users,id_user',
            'Id_Aset' => 'required|exists:aset,id_aset',
            'Tanggal_pinjam' => 'required|date',
            'Tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'catatan' => 'nullable|string'
        ]);

        $validated['status'] = 'dipinjam';
        
        $peminjaman = Peminjaman::create($validated);

        // Update status aset
        $aset = Aset::find($validated['Id_Aset']);
        if ($aset) {
            $aset->update(['status' => 'dipinjam']);
        }

        return redirect()->route('admin.loans.index')->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'aset'])->findOrFail($id);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function edit($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $users = User::all();
        $asets = Aset::all();
        return view('admin.peminjaman.edit', compact('peminjaman', 'users', 'asets'));
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $validated = $request->validate([
            'id_pengguna' => 'required|exists:users,id_user',
            'Id_Aset' => 'required|exists:aset,id_aset',
            'Tanggal_pinjam' => 'required|date',
            'Tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:dipinjam,dikembalikan',
            'catatan' => 'nullable|string'
        ]);

        // Handle aset status if aset changed
        if ($peminjaman->Id_Aset != $validated['Id_Aset']) {
            $oldAset = Aset::find($peminjaman->Id_Aset);
            if ($oldAset) $oldAset->update(['status' => 'tersedia']);
            
            $newAset = Aset::find($validated['Id_Aset']);
            if ($newAset && $validated['status'] == 'dipinjam') {
                $newAset->update(['status' => 'dipinjam']);
            }
        } else {
            // Same aset, just check status
            $aset = Aset::find($validated['Id_Aset']);
            if ($aset) {
                if ($validated['status'] == 'dipinjam') {
                    $aset->update(['status' => 'dipinjam']);
                } else {
                    $aset->update(['status' => 'tersedia']);
                }
            }
        }

        $peminjaman->update($validated);

        return redirect()->route('admin.loans.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->status == 'dipinjam') {
            $aset = Aset::find($peminjaman->Id_Aset);
            if ($aset) {
                $aset->update(['status' => 'tersedia']);
            }
        }

        $peminjaman->delete();

        return redirect()->route('admin.loans.index')->with('success', 'Peminjaman berhasil dihapus.');
    }
}
