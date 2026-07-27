@extends('admin.layouts.app')

@section('title', 'Tambah Pengembalian')

@section('content')
<div class="page-header">
    <h1>Tambah Pengembalian</h1>
    <p>Silakan isi form di bawah ini untuk mencatat pengembalian aset.</p>
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

    <form action="{{ route('admin.returns.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="Id_peminjaman">Peminjaman</label>
            <select name="Id_peminjaman" id="Id_peminjaman" class="form-control" required>
                <option value="">Pilih Peminjaman</option>
                @foreach($peminjamans as $peminjaman)
                    <option value="{{ $peminjaman->Id_peminjaman }}" {{ old('Id_peminjaman') == $peminjaman->Id_peminjaman ? 'selected' : '' }}>
                        #PMJ-{{ str_pad($peminjaman->Id_peminjaman, 3, '0', STR_PAD_LEFT) }} - {{ $peminjaman->user->nama_pengguna ?? 'Unknown' }} - {{ $peminjaman->aset->nama_Aset ?? 'Unknown' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="tanggal_kembali">Tanggal Kembali</label>
            <input type="datetime-local" name="tanggal_kembali" id="tanggal_kembali" class="form-control" value="{{ old('tanggal_kembali', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="form-group">
            <label for="kondisi_Aset">Kondisi Aset Saat Dikembalikan</label>
            <select name="kondisi_Aset" id="kondisi_Aset" class="form-control" required>
                <option value="baik" {{ old('kondisi_Aset') == 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak ringan" {{ old('kondisi_Aset') == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="rusak berat" {{ old('kondisi_Aset') == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
            </select>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-primary">Simpan Pengembalian</button>
            <a href="{{ route('admin.returns.index') }}" class="btn-secondary" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</div>
@endsection