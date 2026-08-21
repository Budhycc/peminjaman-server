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
            <label for="Id_peminjaman">Peminjaman</label>
            <select name="Id_peminjaman" id="Id_peminjaman" class="form-control" required>
                <option value="">Pilih Peminjaman</option>
                @foreach($peminjamans as $peminjaman)
                    <option value="{{ $peminjaman->Id_peminjaman }}" {{ old('Id_peminjaman', $pengembalian->Id_peminjaman) == $peminjaman->Id_peminjaman ? 'selected' : '' }}>
                        #PMJ-{{ str_pad($peminjaman->Id_peminjaman, 3, '0', STR_PAD_LEFT) }} - {{ $peminjaman->user->nama_pengguna ?? 'Unknown' }} - {{ $peminjaman->aset->nama_Aset ?? 'Unknown' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah">Jumlah Dikembalikan</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah', $pengembalian->jumlah) }}" min="1" required>
        </div>

        <div class="form-group">
            <label for="tanggal_kembali">Tanggal Kembali</label>
            <input type="datetime-local" name="tanggal_kembali" id="tanggal_kembali" class="form-control" value="{{ old('tanggal_kembali', \Carbon\Carbon::parse($pengembalian->tanggal_kembali)->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="form-group">
            <label for="kondisi_Aset">Kondisi Aset Saat Dikembalikan</label>
            <select name="kondisi_Aset" id="kondisi_Aset" class="form-control" required>
                <option value="baik" {{ old('kondisi_Aset', $pengembalian->kondisi_Aset) == 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak" {{ old('kondisi_Aset', $pengembalian->kondisi_Aset) == 'rusak' ? 'selected' : '' }}>Rusak</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status_pengembalian">Status Verifikasi Pengembalian</label>
            <select name="status_pengembalian" id="status_pengembalian" class="form-control" required>
                <option value="pending" {{ old('status_pengembalian', $pengembalian->status_pengembalian) == 'pending' ? 'selected' : '' }}>Pending (Menunggu Pengecekan)</option>
                <option value="disetujui" {{ old('status_pengembalian', $pengembalian->status_pengembalian) == 'disetujui' ? 'selected' : '' }}>Disetujui (Kembali ke Stok)</option>
                <option value="ditolak" {{ old('status_pengembalian', $pengembalian->status_pengembalian) == 'ditolak' ? 'selected' : '' }}>Ditolak (Batal Dikembalikan)</option>
            </select>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-primary">Update Pengembalian</button>
            <a href="{{ route('admin.returns.index') }}" class="btn-secondary" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</div>
@endsection