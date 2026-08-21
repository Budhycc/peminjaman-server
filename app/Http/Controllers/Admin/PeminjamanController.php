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
        $asets = Aset::get()->filter(function($aset) {
            return $aset->jumlah_tersedia > 0;
        });
        return view('admin.peminjaman.create', compact('users', 'asets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pengguna' => 'required|exists:users,id_pengguna',
            'Id_Aset' => 'required|exists:aset,Id_Aset',
            'jumlah' => 'required|integer|min:1',
            'Tanggal_pinjam' => 'required|date',
            'catatan' => 'nullable|string'
        ]);

        $aset = Aset::find($validated['Id_Aset']);
        if ($validated['jumlah'] > $aset->jumlah_tersedia) {
            return back()->withErrors(['jumlah' => 'Jumlah aset yang tersedia tidak mencukupi. Tersedia: ' . $aset->jumlah_tersedia])->withInput();
        }

        $validated['id_Aset'] = $validated['Id_Aset'];
        
        $peminjaman = Peminjaman::create($validated);

        // Update status aset
        if ($aset) {
            if ($aset->jumlah_tersedia == 0) {
                $aset->update(['status_aset' => 'dipinjam']);
            }
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
            'id_pengguna' => 'required|exists:users,id_pengguna',
            'Id_Aset' => 'required|exists:aset,Id_Aset',
            'jumlah' => 'required|integer|min:1',
            'Tanggal_pinjam' => 'required|date',
            'catatan' => 'nullable|string'
        ]);

        $validated['id_Aset'] = $validated['Id_Aset'];

        // Check availability if increasing jumlah or changing aset
        $newAset = Aset::find($validated['Id_Aset']);
        if ($newAset) {
            $available = $newAset->jumlah_tersedia;
            // If it's the same aset and status is still dipinjam, we must add back the old amount before checking
            if ($peminjaman->Id_Aset == $validated['Id_Aset'] && !$peminjaman->pengembalian) {
                $available += $peminjaman->jumlah;
            }
            
            if (!$peminjaman->pengembalian && $validated['jumlah'] > $available) {
                return back()->withErrors(['jumlah' => 'Jumlah aset yang tersedia tidak mencukupi. Tersedia: ' . $available])->withInput();
            }
        }

        // Handle aset status if aset changed
        if ($peminjaman->Id_Aset != $validated['Id_Aset']) {
            $oldAset = Aset::find($peminjaman->Id_Aset);
            if ($oldAset && $oldAset->jumlah_tersedia > 0) $oldAset->update(['status_aset' => 'tersedia']);
            
            $newAset = Aset::find($validated['Id_Aset']);
            if ($newAset && $newAset->jumlah_tersedia == 0) {
                $newAset->update(['status_aset' => 'dipinjam']);
            }
        } else {
            // Same aset, check if it needs status update
            $aset = Aset::find($validated['Id_Aset']);
            if ($aset) {
                if ($aset->jumlah_tersedia == 0) {
                    $aset->update(['status_aset' => 'dipinjam']);
                } elseif ($aset->jumlah_tersedia > 0) {
                    $aset->update(['status_aset' => 'tersedia']);
                }
            }
        }

        $peminjaman->update($validated);

        return redirect()->route('admin.loans.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if (!$peminjaman->pengembalian) {
            $aset = Aset::find($peminjaman->Id_Aset);
            // Since it's deleted, effectively returned, recheck availability
            if ($aset && ($aset->jumlah_tersedia + $peminjaman->jumlah) > 0) {
                $aset->update(['status_aset' => 'tersedia']);
            }
        }

        $peminjaman->delete();

        return redirect()->route('admin.loans.index')->with('success', 'Peminjaman berhasil dihapus.');
    }
}
