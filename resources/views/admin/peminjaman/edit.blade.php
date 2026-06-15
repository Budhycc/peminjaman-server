@extends('admin.layouts.app')

@section('title', 'Edit Peminjaman')

@section('content')
<div class="page-header">
    <h1>Edit Peminjaman</h1>
    <p>Perbarui informasi peminjaman di bawah ini.</p>
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

    <form action="{{ route('admin.loans.update', $peminjaman->id_peminjaman) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="id_user">User Peminjam</label>
            <select name="id_user" id="id_user" class="form-control" required>
                <option value="">Pilih User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id_user }}" {{ old('id_user', $peminjaman->id_user) == $user->id_user ? 'selected' : '' }}>
                        {{ $user->nama }} ({{ $user->username }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="id_aset">Aset</label>
            <select name="id_aset" id="id_aset" class="form-control" required>
                <option value="">Pilih Aset</option>
                @foreach($asets as $aset)
                    <option value="{{ $aset->id_aset }}" {{ old('id_aset', $peminjaman->id_aset) == $aset->id_aset ? 'selected' : '' }}>
                        {{ $aset->kode_aset }} - {{ $aset->nama_aset }} ({{ $aset->kondisi }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="tanggal_pinjam">Tanggal Pinjam</label>
            <input type="datetime-local" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="form-group">
            <label for="rencana_kembali">Rencana Kembali</label>
            <input type="datetime-local" name="rencana_kembali" id="rencana_kembali" class="form-control" value="{{ old('rencana_kembali', \Carbon\Carbon::parse($peminjaman->rencana_kembali)->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="dipinjam" {{ old('status', $peminjaman->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan" {{ old('status', $peminjaman->status) == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </div>

        <div class="form-group">
            <label for="catatan">Catatan</label>
            <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ old('catatan', $peminjaman->catatan) }}</textarea>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-primary">Update Peminjaman</button>
            <a href="{{ route('admin.loans.index') }}" class="btn-secondary" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</div>
@endsection