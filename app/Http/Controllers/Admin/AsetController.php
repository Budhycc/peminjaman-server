<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use Illuminate\Http\Request;

class AsetController extends Controller
{
    public function index()
    {
        $asets = Aset::orderBy('id_aset', 'desc')->get();
        return view('admin.aset.index', compact('asets'));
    }

    public function create()
    {
        return view('admin.aset.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'merk' => 'required|string|max:100',
            'lokasi' => 'required|string|max:255',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'status' => 'required|in:tersedia,dipinjam,rusak',
            'qr_code' => 'nullable|string'
        ]);

        $validated['kode_aset'] = 'AST-' . strtoupper(\Illuminate\Support\Str::random(8));

        Aset::create($validated);

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
            'kode_aset' => 'required|string|max:50|unique:aset,kode_aset,'.$id.',id_aset',
            'nama_aset' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'merk' => 'required|string|max:100',
            'lokasi' => 'required|string|max:255',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'status' => 'required|in:tersedia,dipinjam,rusak',
            'qr_code' => 'nullable|string'
        ]);

        $aset->update($validated);

        return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $aset = Aset::findOrFail($id);
        $aset->delete();

        return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil dihapus.');
    }
}
