@extends('admin.layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
<div class="page-header">
    <h1>Dashboard Overview</h1>
    <p>Selamat datang kembali, Admin!</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon users"><i class="fas fa-users"></i></div>
        <div class="stat-details">
            <h3>Total Users</h3>
            <p class="stat-number">{{ $totalUsers }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon assets"><i class="fas fa-box"></i></div>
        <div class="stat-details">
            <h3>Total Aset</h3>
            <p class="stat-number">{{ $totalAset }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon borrows"><i class="fas fa-hand-holding"></i></div>
        <div class="stat-details">
            <h3>Peminjaman Aktif</h3>
            <p class="stat-number">{{ $peminjamanAktif }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon returns"><i class="fas fa-check-circle"></i></div>
        <div class="stat-details">
            <h3>Dikembalikan (Bulan ini)</h3>
            <p class="stat-number">{{ $dikembalikanBulanIni }}</p>
        </div>
    </div>
</div>

<!-- Recent Activities Table -->
<div class="recent-activities">
    <div class="section-header">
        <h2>Peminjaman Terbaru</h2>
        <a href="{{ url('/admin/loans') }}" class="btn-view-all">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Aset</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPeminjaman as $peminjaman)
                <tr>
                    <td>#PMJ-{{ str_pad($peminjaman->id_peminjaman, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $peminjaman->user->nama ?? 'Unknown User' }}</td>
                    <td>{{ $peminjaman->aset->nama_aset ?? 'Unknown Asset' }}</td>
                    <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                    <td>
                        @if($peminjaman->status == 'dipinjam')
                            <span class="status-badge active">Dipinjam</span>
                        @elseif($peminjaman->status == 'dikembalikan')
                            <span class="status-badge completed">Dikembalikan</span>
                        @else
                            <span class="status-badge">{{ ucfirst($peminjaman->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ url('/admin/loans/'.$peminjaman->id_peminjaman) }}" class="action-btn view" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada data peminjaman terbaru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection