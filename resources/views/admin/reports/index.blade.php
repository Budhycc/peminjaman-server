@extends('admin.layouts.app')

@section('title', 'Laporan Peminjaman')

@push('styles')
<style>
    .filter-section {
        background-color: var(--white);
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        margin-bottom: 20px;
    }
    .filter-form {
        display: flex;
        gap: 15px;
        align-items: flex-end;
        flex-wrap: wrap;
    }
    .form-group-inline {
        display: flex;
        flex-direction: column;
    }
    .form-group-inline label {
        font-weight: 500;
        margin-bottom: 5px;
        font-size: 14px;
        color: var(--text-dark);
    }
    .form-group-inline input {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        outline: none;
    }
    .btn-filter {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 9px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: 0.3s;
    }
    .btn-filter:hover {
        background-color: var(--primary-hover);
    }
    .btn-reset {
        background-color: #6b7280;
        color: white;
        border: none;
        padding: 9px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        text-decoration: none;
        transition: 0.3s;
        display: inline-block;
    }
    .btn-reset:hover {
        background-color: #4b5563;
    }
    .print-btn {
        background-color: var(--success);
        color: white;
        border: none;
        padding: 9px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .print-btn:hover {
        background-color: #059669;
    }
    
    @media print {
        body * {
            visibility: hidden;
        }
        .printable-area, .printable-area * {
            visibility: visible;
        }
        .printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .sidebar, .top-header, .filter-section, .print-btn {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Laporan Peminjaman</h1>
    <p>Data laporan peminjaman dan pengembalian aset.</p>
</div>

<div class="filter-section">
    <form action="{{ route('admin.reports.index') }}" method="GET" class="filter-form">
        <div class="form-group-inline">
            <label for="start_date">Tanggal Mulai</label>
            <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}">
        </div>
        <div class="form-group-inline">
            <label for="end_date">Tanggal Akhir</label>
            <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}">
        </div>
        <div class="form-group-inline">
            <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Filter</button>
        </div>
        <div class="form-group-inline">
            <a href="{{ route('admin.reports.index') }}" class="btn-reset"><i class="fas fa-sync"></i> Reset</a>
        </div>
    </form>
</div>

<div class="recent-activities printable-area">
    <div class="section-header">
        <h2>Hasil Laporan</h2>
        <button onclick="window.print()" class="print-btn"><i class="fas fa-print"></i> Cetak Laporan</button>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID Peminjaman</th>
                    <th>Nama Peminjam</th>
                    <th>Nama Aset</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Dikembalikan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $item)
                <tr>
                    <td>#PMJ-{{ str_pad($item->Id_peminjaman, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->user->nama_pengguna ?? 'Unknown' }}</td>
                    <td>{{ $item->aset->nama_Aset ?? 'Unknown' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->Tanggal_pinjam)->format('d M Y') }}</td>
                    <td>
                        @if($item->pengembalian)
                            {{ \Carbon\Carbon::parse($item->pengembalian->tanggal_dikembalikan)->format('d M Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if(!$item->pengembalian)
                            <span class="status-badge active">Dipinjam</span>
                        @else
                            <span class="status-badge completed">Dikembalikan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data peminjaman untuk periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
