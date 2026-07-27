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

    <form action="{{ route('admin.assets.update', $aset->Id_Aset) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        

        <div class="form-group">
            <label for="nama_Aset">Nama Aset</label>
            <input type="text" name="nama_Aset" id="nama_Aset" class="form-control" value="{{ old('nama_Aset', $aset->nama_Aset) }}" required>
        </div>

        <div class="form-group">
            <label for="status_aset">Status</label>
            <select name="status_aset" id="status_aset" class="form-control" required>
                <option value="tersedia" {{ old('status_aset', $aset->status_aset) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="dipinjam" {{ old('status_aset', $aset->status_aset) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
            </select>
        </div>

        <div class="form-group">
            <label for="Row">Row</label>
            <input type="text" name="Row" id="Row" class="form-control" value="{{ old('Row', $aset->Row) }}">
        </div>

        <div class="form-group">
            <label for="foto_aset">Foto Aset (Opsional)</label>
            @if($aset->foto_aset)
                <div style="margin-bottom: 10px;">
                    <img src="{{ asset('storage/' . $aset->foto_aset) }}" alt="{{ $aset->nama_Aset }}" style="max-height: 100px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                </div>
            @endif
            <input type="file" name="foto_aset" id="foto_aset" class="form-control" accept="image/*">
            <small class="text-muted" style="display: block; margin-top: 5px;">Biarkan kosong jika tidak ingin mengubah foto. Format yang didukung: JPG, PNG, GIF, WEBP. Maksimal 2MB.</small>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-primary">Update Aset</button>
            <a href="{{ route('admin.assets.index') }}" class="btn-secondary" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</div>
@endsection