@extends('admin.layouts.app')

@section('title', 'Detail Pengembalian')

@section('content')
<div class="page-header">
    <h1>Detail Pengembalian</h1>
    <p>Informasi detail pengembalian aset.</p>
</div>

<div class="form-container">
    <div style="margin-bottom: 20px;">
        <strong>ID Pengembalian:</strong> #KMB-{{ str_pad($pengembalian->id_pengembalian, 3, '0', STR_PAD_LEFT) }}
    </div>

    <div style="margin-bottom: 20px;">
        <strong>ID Peminjaman:</strong> #PMJ-{{ str_pad($pengembalian->peminjaman->Id_peminjaman ?? 0, 3, '0', STR_PAD_LEFT) }}
    </div>
    
    <div style="margin-bottom: 20px;">
        <strong>User Peminjam:</strong> {{ $pengembalian->peminjaman->user->nama_pengguna ?? 'Unknown' }}
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Aset:</strong> {{ $pengembalian->peminjaman->aset->nama_Aset ?? 'Unknown' }} (Jenis: {{ $pengembalian->peminjaman->aset->jenis_barang ?? '-' }}, Tempat: {{ $pengembalian->peminjaman->aset->tempat_barang ?? '-' }})
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Tanggal Kembali:</strong> {{ \Carbon\Carbon::parse($pengembalian->tanggal_kembali)->format('d F Y H:i') }}
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Lama Pinjam:</strong> {{ $pengembalian->peminjaman->lama_pinjam }}
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Kondisi Saat Kembali:</strong> 
        @if($pengembalian->kondisi_Aset == 'baik')
            <span class="status-badge completed">Baik</span>
        @else
            <span class="status-badge rejected">Rusak</span>
        @endif
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Status Verifikasi:</strong> 
        @if($pengembalian->status_pengembalian == 'pending')
            <span class="status-badge pending" style="background-color: #fef3c7; color: #d97706;">Pending</span>
        @elseif($pengembalian->status_pengembalian == 'disetujui')
            <span class="status-badge completed" style="background-color: #d1fae5; color: #059669;">Disetujui</span>
        @else
            <span class="status-badge rejected" style="background-color: #fee2e2; color: #dc2626;">Ditolak</span>
        @endif
    </div>

    <div style="margin-top: 30px;">
        <a href="{{ route('admin.returns.index') }}" class="btn-secondary">Kembali</a>
        <a href="{{ route('admin.returns.edit', $pengembalian->id_pengembalian) }}" class="btn-primary" style="margin-left: 10px;">Edit</a>
    </div>
</div>
@endsection