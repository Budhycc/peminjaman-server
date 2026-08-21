@extends('admin.layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="page-header">
    <h1>Detail Peminjaman</h1>
    <p>Informasi detail peminjaman aset.</p>
</div>

<div class="form-container">
    <div style="margin-bottom: 20px;">
        <strong>ID Peminjaman:</strong> #PMJ-{{ str_pad($peminjaman->Id_peminjaman, 3, '0', STR_PAD_LEFT) }}
    </div>
    
    <div style="margin-bottom: 20px;">
        <strong>User Peminjam:</strong> {{ $peminjaman->user->nama_pengguna ?? 'Unknown' }} ({{ $peminjaman->user->email ?? '-' }})
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Aset:</strong> {{ $peminjaman->aset->nama_Aset ?? 'Unknown' }} (Jenis: {{ $peminjaman->aset->jenis_barang ?? '-' }}, Tempat: {{ $peminjaman->aset->tempat_barang ?? '-' }})
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Tanggal Pinjam:</strong> {{ \Carbon\Carbon::parse($peminjaman->Tanggal_pinjam)->format('d M Y H:i') }}
    </div>



    <div style="margin-bottom: 20px;">
        <strong>Lama Pinjam:</strong> {{ $peminjaman->lama_pinjam }}
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Status:</strong> 
        @if($peminjaman->sisa_pinjaman > 0)
            <span class="status-badge active">Dipinjam</span>
        @else
            <span class="status-badge completed">Dikembalikan</span>
        @endif
    </div>

    <div style="margin-top: 30px;">
        <a href="{{ route('admin.loans.index') }}" class="btn-secondary">Kembali</a>
        <a href="{{ route('admin.loans.edit', $peminjaman->Id_peminjaman) }}" class="btn-primary" style="margin-left: 10px;">Edit</a>
    </div>
</div>
@endsection