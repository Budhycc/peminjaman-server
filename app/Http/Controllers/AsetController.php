<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\TableQrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AsetController extends Controller
{
    public function index()
    {
        return response()->json(Aset::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_Aset' => 'required|string|max:100',
            'status_aset' => 'required|in:tersedia,dipinjam',
            'Row' => 'nullable|string|max:50',
        ]);

        $aset = Aset::create($validated);

        $qrCode = TableQrCode::create([
            'id_Aset' => $aset->Id_Aset,
            'tanggal_generate' => now(),
            'kode_unik' => 'AST-' . $aset->Id_Aset . '-' . strtoupper(Str::random(6))
        ]);

        $aset->load('qrCode');

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
            'kode_aset' => 'sometimes|string|max:50|unique:aset,kode_aset,' . $aset->Id_Aset . ',id_aset',
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
