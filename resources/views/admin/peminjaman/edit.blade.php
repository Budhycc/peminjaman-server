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

    <form action="{{ route('admin.loans.update', $peminjaman->Id_peminjaman) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="id_pengguna">User Peminjam</label>
            <select name="id_pengguna" id="id_pengguna" class="form-control" required>
                <option value="">Pilih User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id_pengguna }}" {{ old('id_pengguna', $peminjaman->id_pengguna) == $user->id_pengguna ? 'selected' : '' }}>
                        {{ $user->nama_pengguna }} ({{ $user->Username }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="Id_Aset">Aset</label>
            <select name="Id_Aset" id="Id_Aset" class="form-control" required>
                <option value="">Pilih Aset</option>
                @foreach($asets as $aset)
                    <option value="{{ $aset->Id_Aset }}" {{ old('Id_Aset', $peminjaman->Id_Aset) == $aset->Id_Aset ? 'selected' : '' }}>
                        {{ $aset->nama_Aset }} (Tersedia: {{ $aset->jumlah_tersedia }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah">Jumlah Pinjam</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah', $peminjaman->jumlah) }}" min="1" required>
        </div>

        <div class="form-group">
            <label for="Tanggal_pinjam">Tanggal Pinjam</label>
            <input type="datetime-local" name="Tanggal_pinjam" id="Tanggal_pinjam" class="form-control" value="{{ old('Tanggal_pinjam', \Carbon\Carbon::parse($peminjaman->Tanggal_pinjam)->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-primary">Update Peminjaman</button>
            <a href="{{ route('admin.loans.index') }}" class="btn-secondary" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</div>
@endsection