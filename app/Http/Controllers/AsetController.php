<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\TableQrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            'jumlah' => 'required|integer|min:1',
            'jenis_barang' => 'required|string|max:100',
            'tempat_barang' => 'nullable|string|max:150',
            'foto_aset' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($request->hasFile('foto_aset')) {
            $validated['foto_aset'] = $request->file('foto_aset')->store('fotos', 'public');
        }

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
            'nama_Aset' => 'sometimes|string|max:100',
            'status_aset' => 'sometimes|in:tersedia,dipinjam',
            'jumlah' => 'sometimes|integer|min:1',
            'jenis_barang' => 'sometimes|string|max:100',
            'tempat_barang' => 'nullable|string|max:150',
            'foto_aset' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($request->hasFile('foto_aset')) {
            if ($aset->foto_aset) {
                Storage::disk('public')->delete($aset->foto_aset);
            }
            $validated['foto_aset'] = $request->file('foto_aset')->store('fotos', 'public');
        }

        $aset->update($validated);
        return response()->json($aset);
    }

    public function destroy($id)
    {
        $aset = Aset::findOrFail($id);

        if ($aset->foto_aset) {
            Storage::disk('public')->delete($aset->foto_aset);
        }

        $aset->delete();
        return response()->json(['message' => 'Aset deleted successfully']);
    }

    public function generateQr($id)
    {
        $aset = Aset::findOrFail($id);
        
        // Delete existing QR if any
        TableQrCode::where('id_Aset', $aset->Id_Aset)->delete();

        $qrCode = TableQrCode::create([
            'id_Aset' => $aset->Id_Aset,
            'tanggal_generate' => now(),
            'kode_unik' => 'AST-' . $aset->Id_Aset . '-' . strtoupper(Str::random(6))
        ]);

        return response()->json(['qr_code' => $qrCode->kode_unik, 'message' => 'QR Code generated successfully']);
    }

    public function scanQr(Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);
        
        $qrCode = TableQrCode::where('kode_unik', $request->qr_code)->with('aset')->first();

        if (!$qrCode || !$qrCode->aset) {
            return response()->json(['message' => 'Aset not found'], 404);
        }

        return response()->json($qrCode->aset);
    }

    public function status()
    {
        $tersedia = Aset::where('status_aset', 'tersedia')->count();
        $dipinjam = Aset::where('status_aset', 'dipinjam')->count();

        return response()->json([
            'tersedia' => $tersedia,
            'dipinjam' => $dipinjam,
            'total' => $tersedia + $dipinjam
        ]);
    }

    public function available()
    {
        return response()->json(Aset::where('status_aset', 'tersedia')->get());
    }

    public function borrowed()
    {
        return response()->json(Aset::where('status_aset', 'dipinjam')->get());
    }
}
