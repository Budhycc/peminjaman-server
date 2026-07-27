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

    <form action="{{ route('admin.assets.update', $aset->Id_Aset) }}" method="POST">
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

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-primary">Update Aset</button>
            <a href="{{ route('admin.assets.index') }}" class="btn-secondary" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</div>
@endsection