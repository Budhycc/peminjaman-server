@extends('admin.layouts.app')

@section('title', 'Dashboard Overview')

@push('styles')
<style>
    .chart-container {
        background-color: var(--white);
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        margin-bottom: 30px;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="page-header">
    <h1>Dashboard Overview</h1>
    <p>Selamat datang kembali, {{ auth()->user()->nama_pengguna ?? 'Admin' }}!</p>
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
            <h3>Total Barang</h3>
            <p class="stat-number">{{ $totalAset }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon returns" style="background-color: rgba(16, 185, 129, 0.1); color: var(--success);"><i class="fas fa-check"></i></div>
        <div class="stat-details">
            <h3>Barang Tersedia</h3>
            <p class="stat-number">{{ $asetTersedia }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon borrows"><i class="fas fa-hand-holding"></i></div>
        <div class="stat-details">
            <h3>Sedang Dipinjam</h3>
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

<!-- Chart Section -->
<div class="chart-container">
    <div class="section-header">
        <h2>Statistik Peminjaman ({{ date('Y') }})</h2>
    </div>
    <canvas id="peminjamanChart" height="80"></canvas>
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
                    <td>#PMJ-{{ str_pad($peminjaman->Id_peminjaman, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $peminjaman->user->nama_pengguna ?? 'Unknown User' }}</td>
                    <td>{{ $peminjaman->aset->nama_Aset ?? 'Unknown Asset' }}</td>
                    <td>{{ \Carbon\Carbon::parse($peminjaman->Tanggal_pinjam)->format('d M Y') }}</td>
                    <td>
                        @if(!$peminjaman->pengembalian)
                            <span class="status-badge active">Dipinjam</span>
                        @else
                            <span class="status-badge completed">Dikembalikan</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ url('/admin/loans/'.$peminjaman->Id_peminjaman) }}" class="action-btn view" title="Lihat Detail"><i class="fas fa-eye"></i></a>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('peminjamanChart').getContext('2d');
        const monthlyData = @json($monthlyPeminjaman);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: monthlyData,
                    backgroundColor: 'rgba(79, 70, 229, 0.8)',
                    borderColor: '#4f46e5',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection