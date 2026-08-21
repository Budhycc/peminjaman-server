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
                    <th>Jumlah</th>
                    <th>Tgl Pinjam</th>
                    <th>Lama Pinjam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $item)
                <tr>
                    <td>#PMJ-{{ str_pad($item->Id_peminjaman, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->user->nama_pengguna ?? 'Unknown' }}</td>
                    <td>{{ $item->aset->nama_Aset ?? 'Unknown' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->Tanggal_pinjam)->format('d M Y H:i') }}</td>
                    <td>{{ $item->lama_pinjam }}</td>
                    <td>
                        @if(!$item->pengembalian)
                            <span class="status-badge active">Dipinjam</span>
                        @else
                            <span class="status-badge completed">Dikembalikan</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;">
                        <a href="{{ route('admin.loans.show', $item->Id_peminjaman) }}" class="action-btn view" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;" title="Detail"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.loans.edit', $item->Id_peminjaman) }}" class="action-btn approve" style="background-color: rgba(245, 158, 11, 0.1); color: var(--warning); display: inline-flex; align-items: center; justify-content: center; text-decoration: none;" title="Edit"><i class="fas fa-edit"></i></a>
                        @if(!$item->pengembalian)
                            <a href="{{ route('admin.returns.create', ['peminjaman_id' => $item->Id_peminjaman]) }}" class="action-btn approve" style="background-color: rgba(16, 185, 129, 0.1); color: var(--success); display: inline-flex; align-items: center; justify-content: center; text-decoration: none;" title="Kembalikan Aset"><i class="fas fa-undo"></i></a>
                        @endif
                        <form action="{{ route('admin.loans.destroy', $item->Id_peminjaman) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus peminjaman ini?');">
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