@extends('admin.layouts.app')

@section('title', 'Tambah Aset')

@section('content')
<div class="page-header">
    <h1>Tambah Aset Baru</h1>
    <p>Silakan isi form di bawah ini untuk menambahkan aset baru.</p>
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

    <form action="{{ route('admin.assets.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="kode_aset">Kode Aset</label>
            <input type="text" name="kode_aset" id="kode_aset" class="form-control" value="{{ old('kode_aset') }}" required>
        </div>

        <div class="form-group">
            <label for="nama_aset">Nama Aset</label>
            <input type="text" name="nama_aset" id="nama_aset" class="form-control" value="{{ old('nama_aset') }}" required>
        </div>

        <div class="form-group">
            <label for="kategori">Kategori</label>
            <input type="text" name="kategori" id="kategori" class="form-control" value="{{ old('kategori') }}" required>
        </div>

        <div class="form-group">
            <label for="merk">Merk</label>
            <input type="text" name="merk" id="merk" class="form-control" value="{{ old('merk') }}" required>
        </div>

        <div class="form-group">
            <label for="lokasi">Lokasi Penyimpanan</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control" value="{{ old('lokasi') }}" required>
        </div>

        <div class="form-group">
            <label for="kondisi">Kondisi Aset</label>
            <select name="kondisi" id="kondisi" class="form-control" required>
                <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak ringan" {{ old('kondisi') == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="rusak berat" {{ old('kondisi') == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="dipinjam" {{ old('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="rusak" {{ old('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
            </select>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-primary">Simpan Aset</button>
            <a href="{{ route('admin.assets.index') }}" class="btn-secondary" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</div>
@endsection