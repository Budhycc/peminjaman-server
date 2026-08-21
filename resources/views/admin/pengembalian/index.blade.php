@extends('admin.layouts.app')

@section('title', 'Pengembalian Aset')

@section('content')
<div class="page-header">
    <h1>Data Pengembalian</h1>
    <p>Riwayat pengembalian aset oleh user.</p>
</div>

<div class="recent-activities">
    <div class="section-header">
        <h2>Daftar Pengembalian</h2>
        <a href="{{ route('admin.returns.create') }}" class="action-btn approve" style="width: auto; padding: 5px 15px; font-weight: bold; text-decoration: none; display: inline-block;"><i class="fas fa-plus"></i> Tambah Pengembalian</a>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Aset</th>
                    <th>Jumlah</th>
                    <th>Tgl Kembali</th>
                    <th>Lama Pinjam</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengembalian as $item)
                <tr>
                    <td>#KMB-{{ str_pad($item->id_pengembalian, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->peminjaman->user->nama_pengguna ?? 'Unknown' }}</td>
                    <td>{{ $item->peminjaman->aset->nama_Aset ?? 'Unknown' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y H:i') }}</td>
                    <td>{{ $item->peminjaman->lama_pinjam }}</td>
                    <td>
                        @if($item->kondisi_Aset == 'baik')
                            <span class="status-badge completed">Baik</span>
                        @else
                            <span class="status-badge rejected">Rusak</span>
                        @endif
                    </td>
                    <td>
                        @if($item->status_pengembalian == 'pending')
                            <span class="status-badge pending" style="background-color: #fef3c7; color: #d97706;">Pending</span>
                        @elseif($item->status_pengembalian == 'disetujui')
                            <span class="status-badge completed" style="background-color: #d1fae5; color: #059669;">Disetujui</span>
                        @else
                            <span class="status-badge rejected" style="background-color: #fee2e2; color: #dc2626;">Ditolak</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;">
                        <a href="{{ route('admin.returns.show', $item->id_pengembalian) }}" class="action-btn view" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;" title="Detail"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.returns.edit', $item->id_pengembalian) }}" class="action-btn approve" style="background-color: rgba(245, 158, 11, 0.1); color: var(--warning); display: inline-flex; align-items: center; justify-content: center; text-decoration: none;" title="Edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.returns.destroy', $item->id_pengembalian) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus pengembalian ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn reject" title="Hapus" style="cursor: pointer;"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Belum ada data pengembalian.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection