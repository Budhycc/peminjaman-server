@extends('admin.layouts.app')

@section('title', 'Edit Aset')

@section('content')
<div class="page-header">
    <h1>Edit Aset</h1>
    <p>Perbarui informasi aset di bawah ini.</p>
</div>

<div class="form-container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="list-style-type: disc; margin-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.assets.update', $aset->id_aset) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="kode_aset">Kode Aset</label>
            <input type="text" name="kode_aset" id="kode_aset" class="form-control" value="{{ old('kode_aset', $aset->kode_aset) }}" required>
        </div>

        <div class="form-group">
            <label for="nama_aset">Nama Aset</label>
            <input type="text" name="nama_aset" id="nama_aset" class="form-control" value="{{ old('nama_aset', $aset->nama_aset) }}" required>
        </div>

        <div class="form-group">
            <label for="kategori">Kategori</label>
            <input type="text" name="kategori" id="kategori" class="form-control" value="{{ old('kategori', $aset->kategori) }}" required>
        </div>

        <div class="form-group">
            <label for="merk">Merk</label>
            <input type="text" name="merk" id="merk" class="form-control" value="{{ old('merk', $aset->merk) }}" required>
        </div>

        <div class="form-group">
            <label for="lokasi">Lokasi Penyimpanan</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control" value="{{ old('lokasi', $aset->lokasi) }}" required>
        </div>

        <div class="form-group">
            <label for="kondisi">Kondisi Aset</label>
            <select name="kondisi" id="kondisi" class="form-control" required>
                <option value="baik" {{ old('kondisi', $aset->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak ringan" {{ old('kondisi', $aset->kondisi) == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="rusak berat" {{ old('kondisi', $aset->kondisi) == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="tersedia" {{ old('status', $aset->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="dipinjam" {{ old('status', $aset->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="rusak" {{ old('status', $aset->status) == 'rusak' ? 'selected' : '' }}>Rusak</option>
            </select>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-primary">Update Aset</button>
            <a href="{{ route('admin.assets.index') }}" class="btn-secondary" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</div>
@endsection