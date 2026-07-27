<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use Illuminate\Http\Request;

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
            'nama_Aset' => 'required|string|max:255',
            'status_aset' => 'required|in:tersedia,dipinjam',
            'Row' => 'nullable|string|max:50'
        ]);

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
            'nama_Aset' => 'required|string|max:255',
            'status_aset' => 'required|in:tersedia,dipinjam',
            'Row' => 'nullable|string|max:50'
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
