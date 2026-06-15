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
                    <th>Tgl Kembali</th>
                    <th>Kondisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengembalian as $item)
                <tr>
                    <td>#KMB-{{ str_pad($item->id_pengembalian, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->peminjaman->user->nama ?? 'Unknown' }}</td>
                    <td>{{ $item->peminjaman->aset->nama_aset ?? 'Unknown' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}</td>
                    <td>
                        @if($item->kondisi_kembali == 'baik')
                            <span class="status-badge completed">Baik</span>
                        @elseif($item->kondisi_kembali == 'rusak ringan')
                            <span class="status-badge pending">Rusak Ringan</span>
                        @else
                            <span class="status-badge rejected">Rusak Berat</span>
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
                    <td colspan="6" style="text-align: center;">Belum ada data pengembalian.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection