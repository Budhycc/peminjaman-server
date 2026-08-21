@extends('admin.layouts.app')

@section('title', 'Tambah Peminjaman')

@section('content')
<div class="page-header">
    <h1>Tambah Peminjaman</h1>
    <p>Silakan isi form di bawah ini untuk menambahkan peminjaman baru.</p>
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

    <form action="{{ route('admin.loans.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="id_pengguna">User Peminjam</label>
            <select name="id_pengguna" id="id_pengguna" class="form-control" required>
                <option value="">Pilih User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id_pengguna }}" {{ old('id_pengguna') == $user->id_pengguna ? 'selected' : '' }}>
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
                    <option value="{{ $aset->Id_Aset }}" {{ old('Id_Aset') == $aset->Id_Aset ? 'selected' : '' }}>
                        {{ $aset->nama_Aset }} (Tersedia: {{ $aset->jumlah_tersedia }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah">Jumlah Pinjam</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah', 1) }}" min="1" required>
        </div>

        <div class="form-group">
            <label for="Tanggal_pinjam">Tanggal Pinjam</label>
            <input type="datetime-local" name="Tanggal_pinjam" id="Tanggal_pinjam" class="form-control" value="{{ old('Tanggal_pinjam', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}" required>
        </div>



        <div style="margin-top: 30px;">
            <button type="submit" class="btn-primary">Simpan Peminjaman</button>
            <a href="{{ route('admin.loans.index') }}" class="btn-secondary" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#id_pengguna').select2({
            placeholder: 'Pilih User',
            allowClear: true,
            width: '100%'
        });
        $('#Id_Aset').select2({
            placeholder: 'Pilih Aset',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush