@extends('admin.layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="page-header">
    <h1>Manajemen User</h1>
    <p>Kelola semua pengguna terdaftar pada sistem.</p>
</div>

<div class="recent-activities">
    <div class="section-header">
        <h2>Daftar User</h2>
        <a href="{{ route('admin.users.create') }}" class="action-btn approve" style="width: auto; padding: 5px 15px; font-weight: bold; text-decoration: none; display: inline-block;"><i class="fas fa-plus"></i> Tambah User</a>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>#USR-{{ $user->id_pengguna }}</td>
                    <td>{{ $user->nama_pengguna }}</td>
                    <td>{{ $user->Username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role == 'admin')
                            <span class="status-badge pending" style="background-color: rgba(79, 70, 229, 0.1); color: var(--primary-color);">Admin</span>
                        @else
                            <span class="status-badge active">User</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;">
                        <a href="{{ route('admin.users.edit', $user->id_pengguna) }}" class="action-btn approve" style="background-color: rgba(245, 158, 11, 0.1); color: var(--warning); display: inline-flex; align-items: center; justify-content: center; text-decoration: none;" title="Edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.users.destroy', $user->id_pengguna) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn reject" title="Hapus" style="cursor: pointer;"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada data user.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection