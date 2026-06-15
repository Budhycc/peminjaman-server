@extends('admin.layouts.app')

@section('title', 'Peminjaman Aset')

@section('content')
<div class="page-header">
    <h1>Data Peminjaman</h1>
    <p>Riwayat seluruh peminjaman aset.</p>
</div>

<div class="recent-activities">
    <div class="section-header">
        <h2>Daftar Peminjaman</h2>
        <a href="{{ route('admin.loans.create') }}" class="action-btn approve" style="width: auto; padding: 5px 15px; font-weight: bold; text-decoration: none; display: inline-block;"><i class="fas fa-plus"></i> Tambah Peminjaman</a>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Aset</th>
                    <th>Tgl Pinjam</th>
                    <th>Rencana Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $item)
                <tr>
                    <td>#PMJ-{{ str_pad($item->id_peminjaman, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->user->nama ?? 'Unknown' }}</td>
                    <td>{{ $item->aset->nama_aset ?? 'Unknown' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->rencana_kembali)->format('d M Y') }}</td>
                    <td>
                        @if($item->status == 'dipinjam')
                            <span class="status-badge active">Dipinjam</span>
                        @elseif($item->status == 'dikembalikan')
                            <span class="status-badge completed">Dikembalikan</span>
                        @else
                            <span class="status-badge">{{ ucfirst($item->status) }}</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;">
                        <a href="{{ route('admin.loans.show', $item->id_peminjaman) }}" class="action-btn view" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;" title="Detail"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.loans.edit', $item->id_peminjaman) }}" class="action-btn approve" style="background-color: rgba(245, 158, 11, 0.1); color: var(--warning); display: inline-flex; align-items: center; justify-content: center; text-decoration: none;" title="Edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.loans.destroy', $item->id_peminjaman) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus peminjaman ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn reject" title="Hapus" style="cursor: pointer;"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Belum ada data peminjaman.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection