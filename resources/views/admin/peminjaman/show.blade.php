@extends('admin.layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="page-header">
    <h1>Detail Peminjaman</h1>
    <p>Informasi detail peminjaman aset.</p>
</div>

<div class="form-container">
    <div style="margin-bottom: 20px;">
        <strong>ID Peminjaman:</strong> #PMJ-{{ str_pad($peminjaman->id_peminjaman, 3, '0', STR_PAD_LEFT) }}
    </div>
    
    <div style="margin-bottom: 20px;">
        <strong>User Peminjam:</strong> {{ $peminjaman->user->nama ?? 'Unknown' }} ({{ $peminjaman->user->email ?? '-' }})
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Aset:</strong> {{ $peminjaman->aset->nama_aset ?? 'Unknown' }} (Kode: {{ $peminjaman->aset->kode_aset ?? '-' }})
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Tanggal Pinjam:</strong> {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y H:i') }}
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Rencana Kembali:</strong> {{ \Carbon\Carbon::parse($peminjaman->rencana_kembali)->format('d M Y H:i') }}
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Status:</strong> 
        @if($peminjaman->status == 'dipinjam')
            <span class="status-badge active">Dipinjam</span>
        @elseif($peminjaman->status == 'dikembalikan')
            <span class="status-badge completed">Dikembalikan</span>
        @else
            <span class="status-badge">{{ ucfirst($peminjaman->status) }}</span>
        @endif
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Catatan:</strong> <br>
        {{ $peminjaman->catatan ?: '-' }}
    </div>

    <div style="margin-top: 30px;">
        <a href="{{ route('admin.loans.index') }}" class="btn-secondary">Kembali</a>
        <a href="{{ route('admin.loans.edit', $peminjaman->id_peminjaman) }}" class="btn-primary" style="margin-left: 10px;">Edit</a>
    </div>
</div>
@endsection