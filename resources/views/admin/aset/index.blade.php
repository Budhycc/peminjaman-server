@extends('admin.layouts.app')

@section('title', 'Manajemen Aset')

@section('content')
<div class="page-header">
    <h1>Manajemen Aset</h1>
    <p>Kelola semua data aset pada sistem.</p>
</div>

<div class="recent-activities">
    <div class="section-header">
        <h2>Daftar Aset</h2>
        <a href="{{ route('admin.assets.create') }}" class="action-btn approve" style="width: auto; padding: 5px 15px; font-weight: bold; text-decoration: none; display: inline-block;"><i class="fas fa-plus"></i> Tambah Aset</a>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Aset</th>
                    <th>Kategori</th>
                    <th>Merk</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($asets as $aset)
                <tr>
                    <td>{{ $aset->kode_aset }}</td>
                    <td>{{ $aset->nama_aset }}</td>
                    <td>{{ $aset->kategori }}</td>
                    <td>{{ $aset->merk }}</td>
                    <td>{{ ucfirst($aset->kondisi) }}</td>
                    <td>
                        @if($aset->status == 'tersedia')
                            <span class="status-badge completed">Tersedia</span>
                        @elseif($aset->status == 'dipinjam')
                            <span class="status-badge active">Dipinjam</span>
                        @elseif($aset->status == 'rusak')
                            <span class="status-badge rejected">Rusak</span>
                        @else
                            <span class="status-badge">{{ ucfirst($aset->status) }}</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;">
                        <a href="{{ route('admin.assets.edit', $aset->id_aset) }}" class="action-btn approve" style="background-color: rgba(245, 158, 11, 0.1); color: var(--warning); display: inline-flex; align-items: center; justify-content: center; text-decoration: none;" title="Edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.assets.destroy', $aset->id_aset) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus aset ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn reject" title="Hapus" style="cursor: pointer;"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Belum ada data aset.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection