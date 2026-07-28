@extends('admin.layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="page-header">
    <h1>Tambah User Baru</h1>
    <p>Silakan isi form di bawah ini untuk menambahkan user baru.</p>
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

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama_pengguna">Nama Lengkap</label>
            <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" value="{{ old('nama_pengguna') }}" required>
        </div>

        <div class="form-group">
            <label for="Username">Username</label>
            <input type="text" name="Username" id="Username" class="form-control" value="{{ old('Username') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="Unit_Kerja">Unit Kerja</label>
            <input type="text" name="Unit_Kerja" id="Unit_Kerja" class="form-control" value="{{ old('Unit_Kerja') }}">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required minlength="6">
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-primary">Simpan User</button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</div>
@endsection