@extends('admin.layouts.app')

@section('title', 'Edit Pengembalian')

@section('content')
<div class="page-header">
    <h1>Edit Pengembalian</h1>
    <p>Perbarui informasi pengembalian di bawah ini.</p>
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

    <form action="{{ route('admin.returns.update', $pengembalian->id_pengembalian) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="id_peminjaman">Peminjaman</label>
            <select name="id_peminjaman" id="id_peminjaman" class="form-control" required>
                <option value="">Pilih Peminjaman</option>
                @foreach($peminjamans as $peminjaman)
                    <option value="{{ $peminjaman->id_peminjaman }}" {{ old('id_peminjaman', $pengembalian->id_peminjaman) == $peminjaman->id_peminjaman ? 'selected' : '' }}>
                        #PMJ-{{ str_pad($peminjaman->id_peminjaman, 3, '0', STR_PAD_LEFT) }} - {{ $peminjaman->user->nama ?? 'Unknown' }} - {{ $peminjaman->aset->nama_aset ?? 'Unknown' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="tanggal_kembali">Tanggal Kembali</label>
            <input type="datetime-local" name="tanggal_kembali" id="tanggal_kembali" class="form-control" value="{{ old('tanggal_kembali', \Carbon\Carbon::parse($pengembalian->tanggal_kembali)->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="form-group">
            <label for="kondisi_kembali">Kondisi Aset Saat Dikembalikan</label>
            <select name="kondisi_kembali" id="kondisi_kembali" class="form-control" required>
                <option value="baik" {{ old('kondisi_kembali', $pengembalian->kondisi_kembali) == 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak ringan" {{ old('kondisi_kembali', $pengembalian->kondisi_kembali) == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="rusak berat" {{ old('kondisi_kembali', $pengembalian->kondisi_kembali) == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
            </select>
        </div>

        <div class="form-group">
            <label for="catatan">Catatan</label>
            <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ old('catatan', $pengembalian->catatan) }}</textarea>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-primary">Update Pengembalian</button>
            <a href="{{ route('admin.returns.index') }}" class="btn-secondary" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</div>
@endsection