<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;

class AsetController extends Controller
{
    public function index()
    {
        return response()->json(Aset::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_aset' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'merk' => 'nullable|string|max:100',
            'lokasi' => 'required|string|max:100',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'status' => 'required|in:tersedia,dipinjam',
        ]);

        $validated['kode_aset'] = 'AST-' . strtoupper(\Illuminate\Support\Str::random(8));

        $aset = Aset::create($validated);
        return response()->json($aset, 201);
    }

    public function show($id)
    {
        $aset = Aset::findOrFail($id);
        return response()->json($aset);
    }

    public function update(Request $request, $id)
    {
        $aset = Aset::findOrFail($id);

        $validated = $request->validate([
            'kode_aset' => 'sometimes|string|max:50|unique:aset,kode_aset,' . $aset->id_aset . ',id_aset',
            'nama_aset' => 'sometimes|string|max:100',
            'kategori' => 'sometimes|string|max:50',
            'merk' => 'nullable|string|max:100',
            'lokasi' => 'sometimes|string|max:100',
            'kondisi' => 'sometimes|in:baik,rusak ringan,rusak berat',
            'status' => 'sometimes|in:tersedia,dipinjam',
        ]);

        $aset->update($validated);
        return response()->json($aset);
    }

    public function destroy($id)
    {
        $aset = Aset::findOrFail($id);
        $aset->delete();
        return response()->json(['message' => 'Aset deleted successfully']);
    }

    public function generateQr($id)
    {
        $aset = Aset::findOrFail($id);
        // Simplification: We'll just generate a unique string or identifier for the QR code for now
        $qrString = 'ASET-QR-' . $aset->kode_aset . '-' . uniqid();
        $aset->update(['qr_code' => $qrString]);
        return response()->json(['qr_code' => $qrString, 'message' => 'QR Code generated successfully']);
    }

    public function scanQr(Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);
        $aset = Aset::where('qr_code', $request->qr_code)->first();

        if (!$aset) {
            return response()->json(['message' => 'Aset not found'], 404);
        }

        return response()->json($aset);
    }

    public function status()
    {
        $tersedia = Aset::where('status', 'tersedia')->count();
        $dipinjam = Aset::where('status', 'dipinjam')->count();

        return response()->json([
            'tersedia' => $tersedia,
            'dipinjam' => $dipinjam,
            'total' => $tersedia + $dipinjam
        ]);
    }

    public function available()
    {
        return response()->json(Aset::where('status', 'tersedia')->get());
    }

    public function borrowed()
    {
        return response()->json(Aset::where('status', 'dipinjam')->get());
    }
}
