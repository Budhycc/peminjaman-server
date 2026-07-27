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
        <strong>Aset:</strong> {{ $pengembalian->peminjaman->aset->nama_Aset ?? 'Unknown' }} (Row: {{ $pengembalian->peminjaman->aset->Row ?? '-' }})
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Tanggal Kembali:</strong> {{ \Carbon\Carbon::parse($pengembalian->tanggal_kembali)->format('d M Y H:i') }}
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Kondisi Saat Kembali:</strong> 
        @if($pengembalian->kondisi_Aset == 'baik')
            <span class="status-badge completed">Baik</span>
        @elseif($pengembalian->kondisi_Aset == 'rusak ringan')
            <span class="status-badge pending">Rusak Ringan</span>
        @else
            <span class="status-badge rejected">Rusak Berat</span>
        @endif
    </div>

    <div style="margin-top: 30px;">
        <a href="{{ route('admin.returns.index') }}" class="btn-secondary">Kembali</a>
        <a href="{{ route('admin.returns.edit', $pengembalian->id_pengembalian) }}" class="btn-primary" style="margin-left: 10px;">Edit</a>
    </div>
</div>
@endsection