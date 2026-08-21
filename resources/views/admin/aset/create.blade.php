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

    <form action="{{ route('admin.assets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        

        <div class="form-group">
            <label for="nama_Aset">Nama Aset</label>
            <input type="text" name="nama_Aset" id="nama_Aset" class="form-control" value="{{ old('nama_Aset') }}" required>
        </div>

        <div class="form-group">
            <label for="jumlah">Jumlah/Quantity</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah', 1) }}" min="1" required>
        </div>


        <div class="form-group">
            <label for="status_aset">Status</label>
            <select name="status_aset" id="status_aset" class="form-control" required>
                <option value="tersedia" {{ old('status_aset') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="dipinjam" {{ old('status_aset') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
            </select>
        </div>

        <div class="form-group">
            <label for="jenis_barang">Jenis Barang</label>
            <input type="text" name="jenis_barang" id="jenis_barang" class="form-control" value="{{ old('jenis_barang') }}" required>
        </div>

        <div class="form-group">
            <label for="tempat_barang">Tempat/Alamat Barang (Opsional)</label>
            <input type="text" name="tempat_barang" id="tempat_barang" class="form-control" value="{{ old('tempat_barang') }}">
        </div>

        <div class="form-group">
            <label for="foto_aset">Foto Aset</label>
            <input type="file" name="foto_aset" id="foto_aset" class="form-control" accept="image/*">
            <small class="text-muted" style="display: block; margin-top: 5px;">Format yang didukung: JPG, PNG, GIF, WEBP. Maksimal 2MB.</small>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-primary">Simpan Aset</button>
            <a href="{{ route('admin.assets.index') }}" class="btn-secondary" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</div>
@endsection