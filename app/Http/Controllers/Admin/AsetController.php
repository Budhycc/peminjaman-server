<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\TableQrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{
    public function index()
    {
        $asets = Aset::orderBy('Id_Aset', 'desc')->get();
        return view('admin.aset.index', compact('asets'));
    }

    public function create()
    {
        return view('admin.aset.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_Aset' => 'required|string|max:100',
            'status_aset' => 'required|in:tersedia,dipinjam',
            'jumlah' => 'required|integer|min:1',
            'jenis_barang' => 'required|string|max:100',
            'tempat_barang' => 'nullable|string|max:150',
            'foto_aset' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($request->hasFile('foto_aset')) {
            $validated['foto_aset'] = $request->file('foto_aset')->store('fotos', 'public');
        }

        $aset = Aset::create($validated);

        TableQrCode::create([
            'id_Aset' => $aset->Id_Aset,
            'tanggal_generate' => now(),
            'kode_unik' => 'AST-' . $aset->Id_Aset . '-' . strtoupper(Str::random(6))
        ]);

        return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $aset = Aset::findOrFail($id);
        return view('admin.aset.edit', compact('aset'));
    }

    public function update(Request $request, $id)
    {
        $aset = Aset::findOrFail($id);

        $validated = $request->validate([
            'nama_Aset' => 'required|string|max:100',
            'status_aset' => 'required|in:tersedia,dipinjam',
            'jumlah' => 'required|integer|min:1',
            'jumlah_diperbaiki' => 'nullable|integer|min:0',
            'jenis_barang' => 'required|string|max:100',
            'tempat_barang' => 'nullable|string|max:150',
            'foto_aset' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if (isset($validated['jumlah_diperbaiki'])) {
            // Can't repair more than what is broken
            if ($validated['jumlah_diperbaiki'] > ($aset->jumlah_diperbaiki + $aset->jumlah_rusak)) {
                return back()->withErrors(['jumlah_diperbaiki' => 'Jumlah barang yang diperbaiki melebihi stok yang rusak.'])->withInput();
            }
        } else {
            $validated['jumlah_diperbaiki'] = $aset->jumlah_diperbaiki;
        }

        if ($request->hasFile('foto_aset')) {
            if ($aset->foto_aset) {
                Storage::disk('public')->delete($aset->foto_aset);
            }
            $validated['foto_aset'] = $request->file('foto_aset')->store('fotos', 'public');
        }

        $aset->update($validated);

        return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $aset = Aset::findOrFail($id);

        if ($aset->foto_aset) {
            Storage::disk('public')->delete($aset->foto_aset);
        }

        $aset->delete();

        return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil dihapus.');
    }
}
