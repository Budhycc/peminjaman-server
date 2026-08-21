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
                    <th>ID Aset</th>
                    <th style="text-align: center;">Foto</th>
                    <th>Nama Aset</th>
                    <th>Jumlah (Sedia/Rusak/Total)</th>
                    <th>Jenis Barang</th>
                    <th>Tempat/Alamat</th>
                    <th>Status</th>
                    <th style="text-align: center;">Kode QR</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($asets as $aset)
                <tr>
                    <td>{{ $aset->Id_Aset }}</td>
                    <td style="text-align: center; vertical-align: middle;">
                        @if($aset->foto_aset)
                            <img src="{{ asset('storage/' . $aset->foto_aset) }}" alt="{{ $aset->nama_Aset }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin: 0 auto; display: block;">
                        @else
                            <div style="width: 50px; height: 50px; background-color: #f1f5f9; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 20px; margin: 0 auto;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>{{ $aset->nama_Aset }}</td>
                    <td>
                        <span style="font-weight: 600; color: var(--primary);" title="Tersedia">{{ $aset->jumlah_tersedia }}</span>
                        <span style="color: #ef4444; font-size: 0.9em; margin: 0 4px;" title="Rusak">{{ $aset->jumlah_rusak > 0 ? $aset->jumlah_rusak : '' }}</span>
                        <span style="color: #94a3b8; font-size: 0.9em;" title="Total">/ {{ $aset->jumlah }}</span>
                    </td>
                    <td>{{ $aset->jenis_barang }}</td>
                    <td>{{ $aset->tempat_barang ?? '-' }}</td>
                    <td>
                        @if($aset->status_aset == 'tersedia')
                            <span class="status-badge completed">Tersedia</span>
                        @elseif($aset->status_aset == 'dipinjam')
                            <span class="status-badge active">Dipinjam</span>
                        @else
                            <span class="status-badge">{{ ucfirst($aset->status_aset) }}</span>
                        @endif
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        @if($aset->qrCode)
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                                <div>
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(40)->margin(0)->generate($aset->qrCode->kode_unik) !!}
                                </div>
                                <span style="font-size: 11px; font-family: monospace; font-weight: bold; color: #475569;">{{ $aset->qrCode->kode_unik }}</span>
                            </div>
                        @else
                            <span style="font-size: 12px; color: #94a3b8; font-style: italic;">Belum ada QR</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;">
                        @if($aset->qrCode)
                            <button type="button" class="action-btn" style="background-color: rgba(59, 130, 246, 0.1); color: var(--primary); display: inline-flex; align-items: center; justify-content: center; border: none; cursor: pointer; margin-right: 4px;" title="Lihat QR" data-id="{{ $aset->qrCode->kode_unik }}" data-name="{{ $aset->nama_Aset }}" onclick="viewAssetQr(this)">
                                <i class="fas fa-qrcode"></i>
                            </button>
                            <button type="button" class="action-btn" style="background-color: rgba(16, 185, 129, 0.1); color: var(--success); display: inline-flex; align-items: center; justify-content: center; border: none; cursor: pointer; margin-right: 4px;" title="Print QR" data-id="{{ $aset->qrCode->kode_unik }}" data-name="{{ $aset->nama_Aset }}" onclick="printAssetQr(this)">
                                <i class="fas fa-print"></i>
                            </button>
                            <div id="qr-svg-{{ $aset->qrCode->kode_unik }}" style="display: none;">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($aset->qrCode->kode_unik) !!}
                            </div>
                        @endif
                        <a href="{{ route('admin.assets.edit', $aset->Id_Aset) }}" class="action-btn approve" style="background-color: rgba(245, 158, 11, 0.1); color: var(--warning); display: inline-flex; align-items: center; justify-content: center; text-decoration: none;" title="Edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.assets.destroy', $aset->Id_Aset) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus aset ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn reject" title="Hapus" style="cursor: pointer;"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada data aset.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<style>
    /* Specific print styles for the card */
    @media print {
        body * {
            visibility: hidden;
        }
        #print-area, #print-area * {
            visibility: visible;
        }
        #print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 40px;
        }
        .print-card {
            border: 2px solid #000;
            border-radius: 16px;
            padding: 30px;
            width: 320px;
            text-align: center;
            font-family: "Inter", -apple-system, sans-serif;
            color: #000;
            box-sizing: border-box;
        }
        .print-card h2 { margin: 0 0 20px 0; font-size: 22px; }
        .print-card .qr-wrapper { display: inline-block; border: 2px dashed #000; padding: 15px; border-radius: 12px; margin-bottom: 20px; }
        .print-card img { width: 200px; height: 200px; display: block; margin: 0 auto; }
        .print-card .label-text { font-size: 13px; font-weight: 600; }
        .print-card .code-text { margin-top: 8px; font-size: 20px; font-weight: 800; font-family: monospace; }
    }
    
    /* Hide print area on screen */
    @media screen {
        #print-area {
            display: none;
        }
    }
</style>

<div id="print-area"></div>

<!-- Modal Preview QR -->
<div id="qr-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(4px);" onclick="if(event.target === this) this.style.display='none'">
    <div style="background: white; padding: 30px; border-radius: 16px; text-align: center; max-width: 350px; width: 90%; position: relative; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
        <button onclick="document.getElementById('qr-modal').style.display='none'" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer; color: #9ca3af; transition: color 0.2s;" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#9ca3af'">&times;</button>
        <h2 id="modal-qr-title" style="margin-top: 0; margin-bottom: 20px; font-size: 20px; color: #111827; font-weight: 700;"></h2>
        <div id="modal-qr-content" style="margin-bottom: 15px; border: 2px dashed #cbd5e1; padding: 15px; border-radius: 12px; display: inline-block; background: #f8fafc;"></div>
        <div style="font-size: 13px; color: #6b7280; font-weight: 600; letter-spacing: 1px;">KODE ASET</div>
        <div id="modal-qr-code" style="font-size: 20px; font-weight: 800; font-family: monospace; color: #0f172a; margin-top: 5px; letter-spacing: 1px;"></div>
    </div>
</div>

<script>
    window.viewAssetQr = function(el) {
        var kodeAset = el.getAttribute('data-id');
        var namaAset = el.getAttribute('data-name');
        var qrSvg = document.getElementById('qr-svg-' + kodeAset).innerHTML;
        
        document.getElementById('modal-qr-title').innerText = namaAset;
        document.getElementById('modal-qr-content').innerHTML = qrSvg;
        document.getElementById('modal-qr-code').innerText = kodeAset;
        
        var modal = document.getElementById('qr-modal');
        modal.style.display = 'flex';
    };

    window.printAssetQr = function(btn) {
        var kodeAset = btn.getAttribute('data-id');
        var namaAset = btn.getAttribute('data-name');
        
        var qrSvg = document.getElementById('qr-svg-' + kodeAset).innerHTML;
        var printArea = document.getElementById('print-area');
        
        printArea.innerHTML = '<div class="print-card">' +
            '<h2>' + namaAset + '</h2>' +
            '<div class="qr-wrapper">' + qrSvg + '</div>' +
            '<div class="label-text">KODE ASET</div>' +
            '<div class="code-text">' + kodeAset + '</div>' +
            '</div>';
            
        window.print();
    };
</script>
@endpush
@endsection