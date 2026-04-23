@extends('layouts.app')

@section('content')

{{-- ============================================================
     MONITORING MATERIAL - DETAIL PER ID TRANS
     Font & typography mengikuti layouts.app (sama dengan MSO Index)
     ============================================================ --}}

<style>
    /* === COLOR PALETTE === */
    :root {
        --primary-red:  #DC2626;
        --dark-red:     #991B1B;
        --light-red:    #FEE2E2;
        --accent-red:   #EF4444;
        --pure-white:   #FFFFFF;
        --off-white:    #F9FAFB;
        --dark-gray:    #1F2937;
        --medium-gray:  #6B7280;
        --light-gray:   #E5E7EB;
        --green-p:      #16a34a;
        --green-dark:   #15803d;
        --green-light:  #D1FAE5;
        --green-bg:     #F0FDF4;
    }

    /* === MAIN CONTAINER === */
    .mon-container {
        background: linear-gradient(135deg, var(--off-white) 0%, var(--pure-white) 100%);
        min-height: 100vh;
        padding: 2rem;
    }

    /* === PAGE HEADER === */
    .page-header {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(220, 38, 38, 0.2);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .page-subtitle {
        font-size: 0.875rem;
        font-weight: 400;
        margin: 0.25rem 0 0;
        opacity: 0.85;
    }

    .trans-badge {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.4);
        padding: 0.375rem 1rem;
        border-radius: 20px;
        font-size: 0.813rem;
        font-weight: 700;
        letter-spacing: 0.05em;
    }

    /* === BACK BUTTON === */
    .btn-back {
        background: rgba(255,255,255,0.15);
        color: var(--pure-white);
        border: 1px solid rgba(255,255,255,0.3);
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.25);
        color: var(--pure-white);
        transform: translateY(-2px);
    }

    /* === SECTION CARD === */
    .section-card {
        background: var(--pure-white);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .section-card-header {
        padding: 1rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--pure-white);
    }

    .section-card-header.red {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        border-bottom: 3px solid var(--dark-red);
    }

    /* === SCROLL TABLE WRAPPER === */
    .scroll-wrapper {
        width: 100%;
        overflow-x: auto;
        overflow-y: visible;
        background: var(--pure-white);
        position: relative;
    }

    .scroll-wrapper::-webkit-scrollbar        { height: 16px; }
    .scroll-wrapper::-webkit-scrollbar-track  { background: var(--light-gray); border-radius: 8px; margin: 0 10px; }
    .scroll-wrapper::-webkit-scrollbar-thumb  { background: var(--primary-red); border-radius: 8px; border: 3px solid var(--light-gray); }
    .scroll-wrapper::-webkit-scrollbar-thumb:hover { background: var(--dark-red); }

    /* === TABLE === */
    .mon-table {
        min-width: 2800px;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .mon-table thead {
        position: sticky;
        top: 0;
        z-index: 10;
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
    }

    .mon-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 700;
        font-size: 0.875rem;
        color: var(--pure-white);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
        border-bottom: 3px solid var(--dark-red);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .mon-table tbody tr {
        background: var(--pure-white);
        transition: all 0.3s ease;
        border-bottom: 1px solid var(--light-gray);
    }

    .mon-table tbody tr:hover {
        background: var(--light-red);
        transform: translateX(4px);
        box-shadow: -4px 0 0 var(--primary-red), 0 2px 8px rgba(220, 38, 38, 0.15);
    }

    .mon-table td {
        padding: 1rem;
        color: var(--dark-gray);
        font-size: 0.875rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--light-gray);
        white-space: nowrap;
    }

    /* === BADGE STATUS === */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-open   { background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D; }
    .badge-closed { background: #D1FAE5; color: #065F46; border: 1px solid #6EE7B7; }

    /* === BUTTON EDIT === */
    .btn-edit {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        color: var(--pure-white);
        padding: 0.4rem 0.9rem;
        border-radius: 6px;
        font-size: 0.775rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        white-space: nowrap;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
    }

    /* === RESUME BOX === */
    .resume-box {
        background: linear-gradient(135deg, var(--dark-gray) 0%, #374151 100%);
        color: var(--pure-white);
        padding: 1.5rem 2rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin-top: 1.5rem;
    }

    .resume-label {
        font-size: 0.813rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        opacity: 0.75;
        margin-bottom: 0.25rem;
    }

    .resume-value {
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .resume-meta {
        font-size: 0.813rem;
        opacity: 0.65;
        margin-top: 0.25rem;
    }

    /* === ALERTS === */
    .alert-success {
        background: #D1FAE5;
        border-left: 4px solid #10B981;
        color: #065F46;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.1);
    }

    .alert-error {
        background: var(--light-red);
        border-left: 4px solid var(--primary-red);
        color: var(--dark-red);
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.1);
    }

    /* === EMPTY STATE === */
    .empty-state {
        padding: 3rem;
        text-align: center;
        color: var(--medium-gray);
    }

    .empty-state-icon { font-size: 3rem; margin-bottom: 1rem; }
    .empty-state-text { font-size: 0.875rem; font-weight: 500; }

    /* ============================================================
       MODAL EDIT
       ============================================================ */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        backdrop-filter: blur(3px);
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-box {
        background: var(--pure-white);
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 920px;
        max-height: 90vh;
        overflow-y: auto;
        animation: modalIn 0.25s ease;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(-10px); }
        to   { opacity: 1; transform: scale(1)    translateY(0);     }
    }

    .modal-header {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        color: var(--pure-white);
        padding: 1.25rem 1.5rem;
        border-radius: 16px 16px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .modal-header-title {
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .modal-close {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.35);
        color: var(--pure-white);
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        line-height: 1;
    }

    .modal-close:hover {
        background: rgba(255,255,255,0.35);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 1.5rem;
        background: var(--green-bg);
    }

    /* === FORM INSIDE MODAL === */
    .form-label {
        font-size: 0.813rem;
        font-weight: 600;
        color: var(--dark-gray);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: block;
        margin-bottom: 0.4rem;
    }

    .form-input, .form-select {
        width: 100%;
        border: 2px solid var(--light-gray);
        padding: 0.65rem 0.9rem;
        border-radius: 8px;
        font-size: 0.875rem;
        color: var(--dark-gray);
        background: var(--pure-white);
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .form-group-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--medium-gray);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin: 1.25rem 0 0.75rem;
        padding-bottom: 0.4rem;
        border-bottom: 1px dashed var(--light-gray);
    }

    .form-group-label:first-child { margin-top: 0; }

    .form-grid {
        display: grid;
        gap: 1rem;
    }

    .form-grid-3 { grid-template-columns: repeat(3, 1fr); }
    .form-grid-4 { grid-template-columns: repeat(4, 1fr); }

    @media (max-width: 768px) {
        .form-grid-3,
        .form-grid-4 { grid-template-columns: repeat(2, 1fr); }
    }

    /* === MODAL FOOTER === */
    .modal-footer {
        padding: 1rem 1.5rem;
        background: var(--pure-white);
        border-top: 1px solid var(--light-gray);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        border-radius: 0 0 16px 16px;
        position: sticky;
        bottom: 0;
    }

    .btn-save {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        color: var(--pure-white);
        padding: 0.7rem 1.75rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
    }

    .btn-cancel {
        background: var(--light-gray);
        color: var(--dark-gray);
        padding: 0.7rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover { background: #D1D5DB; }
</style>

<div class="mon-container">

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">📋 Detail Monitoring Material</h1>
            <p class="page-subtitle">Kelola data permintaan material untuk transaksi ini</p>
        </div>
        <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
            <span class="trans-badge">ID Trans: {{ $trans_id }}</span>
            <a href="{{ route('monitoring.index') }}" class="btn-back">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            ⚠️ <strong>Terdapat kesalahan input:</strong>
            <ul style="margin:0.5rem 0 0 1.25rem; font-weight:400;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ============================================================
         TABEL DATA
         ============================================================ --}}
    <div class="section-card">
        <div class="section-card-header red">
            📊 Data Monitoring — ID Trans: {{ $trans_id }}
            <span style="margin-left:auto; font-weight:400; font-size:0.813rem; opacity:0.85; text-transform:none; letter-spacing:0;">
                {{ $data->count() }} baris data
            </span>
        </div>

        <div class="scroll-wrapper">
            <table class="mon-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Aksi</th>
                        <th>ID Trans</th>
                        <th>Nomenclature</th>
                        <th>Komponen</th>
                        <th>Temuan</th>
                        <th>Action</th>
                        <th>Material Master</th>
                        <th>Tanggal</th>
                        <th>No. Notifikasi</th>
                        <th>Qty</th>
                        <th>UoM</th>
                        <th>Pengadaan</th>
                        <th>Model</th>
                        <th>No. Reservasi</th>
                        <th>Tgl. Reservasi</th>
                        <th>No. PR</th>
                        <th>Tgl. PR</th>
                        <th>No. PO</th>
                        <th>Tgl. PO</th>
                        <th>Est. Delivery</th>
                        <th>Estimasi Harga</th>
                        <th>Nama Vendor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <button type="button"
                                    class="btn-edit"
                                    onclick="openEditModal({{ $row->id }})">
                                ✏️ Edit
                            </button>
                        </td>
                        <td style="font-weight:700; color:var(--dark-red);">{{ $row->trans_id }}</td>
                        <td style="font-weight:500;">{{ $row->nomenclature    ?? '-' }}</td>
                        <td>{{ $row->component       ?? '-' }}</td>
                        <td>{{ $row->abnormality     ?? '-' }}</td>
                        <td>{{ $row->action          ?? '-' }}</td>
                        <td style="font-weight:600;">{{ $row->material_master ?? '-' }}</td>
                        <td>
                            {{ $row->tanggal
                                ? \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y')
                                : '-' }}
                        </td>
                        <td>{{ $row->no_notifikasi   ?? '-' }}</td>
                        <td>{{ $row->qty !== null ? number_format($row->qty, 0, ',', '.') : '-' }}</td>
                        <td>{{ $row->uom             ?? '-' }}</td>
                        <td>{{ $row->pengadaan       ?? '-' }}</td>
                        <td>{{ $row->model           ?? '-' }}</td>
                        <td>{{ $row->nomor_reservasi ?? '-' }}</td>
                        <td>
                            {{ $row->tanggal_reservasi
                                ? \Carbon\Carbon::parse($row->tanggal_reservasi)->format('d/m/Y')
                                : '-' }}
                        </td>
                        <td>{{ $row->nomor_pr        ?? '-' }}</td>
                        <td>
                            {{ $row->tanggal_pr
                                ? \Carbon\Carbon::parse($row->tanggal_pr)->format('d/m/Y')
                                : '-' }}
                        </td>
                        <td>{{ $row->nomor_po        ?? '-' }}</td>
                        <td>
                            {{ $row->tanggal_po
                                ? \Carbon\Carbon::parse($row->tanggal_po)->format('d/m/Y')
                                : '-' }}
                        </td>
                        <td>
                            {{ $row->estimated_delivery
                                ? \Carbon\Carbon::parse($row->estimated_delivery)->format('d/m/Y')
                                : '-' }}
                        </td>
                        <td style="font-weight:600;">
                            {{ $row->estimasi_harga !== null
                                ? 'Rp ' . number_format($row->estimasi_harga, 0, ',', '.')
                                : '-' }}
                        </td>
                        <td>{{ $row->nama_vendor ?? '-' }}</td>
                        <td>
                            @if($row->status === 'Closed')
                                <span class="badge badge-closed">✔ Closed</span>
                            @else
                                <span class="badge badge-open">⏳ Open</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="24">
                            <div class="empty-state">
                                <div class="empty-state-icon">📭</div>
                                <div class="empty-state-text">Belum ada data untuk ID Trans ini.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- RESUME TOTAL ANGGARAN --}}
        <div style="padding: 0 1.5rem 1.5rem;">
            <div class="resume-box">
                <div>
                    <div class="resume-label">Resume Nilai Total Anggaran</div>
                    <div class="resume-value">Rp {{ number_format($total, 0, ',', '.') }}</div>
                    <div class="resume-meta">
                        {{ $data->count() }} baris · ID Trans: {{ $trans_id }}
                    </div>
                </div>
                <div style="text-align:right;">
                    <div class="resume-label">Rekap Status</div>
                    <div style="display:flex; gap:1.5rem; margin-top:0.25rem;">
                        <div>
                            <div style="font-size:1.5rem; font-weight:700;">{{ $data->where('status','Open')->count() }}</div>
                            <div style="font-size:0.75rem; opacity:0.65;">⏳ Open</div>
                        </div>
                        <div>
                            <div style="font-size:1.5rem; font-weight:700;">{{ $data->where('status','Closed')->count() }}</div>
                            <div style="font-size:0.75rem; opacity:0.65;">✔ Closed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>{{-- end mon-container --}}

{{-- ============================================================
     MODAL EDIT MONITORING
     ============================================================ --}}

{{-- Embed semua data rows sebagai JSON untuk diisi ke form via JS --}}
<script>
    const monitoringRows = @json($data->keyBy('id'));
</script>

<div class="modal-overlay" id="editModal" onclick="closeOnOverlay(event)">
    <div class="modal-box">

        {{-- MODAL HEADER --}}
        <div class="modal-header">
            <span class="modal-header-title">✏️ Edit Monitoring — <span id="modalTransLabel"></span></span>
            <button type="button" class="modal-close" onclick="closeEditModal()">✕</button>
        </div>

        {{-- FORM --}}
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="modal-body">

                {{-- BAGIAN 1: Notifikasi & Pengadaan --}}
                <p class="form-group-label">📅 Notifikasi & Pengadaan</p>
                <div class="form-grid form-grid-4" style="margin-bottom:0.5rem;">
                    <div>
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="f_tanggal" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">No. Notifikasi / Permintaan</label>
                        <input name="no_notifikasi" id="f_no_notifikasi" class="form-input" placeholder="No. Notifikasi">
                    </div>
                    <div>
                        <label class="form-label">Qty</label>
                        <input type="number" name="qty" id="f_qty" class="form-input" placeholder="0" min="0">
                    </div>
                    <div>
                        <label class="form-label">UoM</label>
                        <input name="uom" id="f_uom" class="form-input" placeholder="Pcs, Kg, Ltr...">
                    </div>
                    <div>
                        <label class="form-label">Pengadaan</label>
                        <select name="pengadaan" id="f_pengadaan" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="Jasa">Jasa</option>
                            <option value="Barang-Jasa">Barang-Jasa</option>
                            <option value="Via Peng.Barang">Via Peng.Barang</option>
                            <option value="Via Capex">Via Capex</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Model</label>
                        <select name="model" id="f_model" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="Tender">Tender</option>
                            <option value="TL">TL</option>
                        </select>
                    </div>
                </div>

                {{-- BAGIAN 2: Dokumen & Anggaran --}}
                <p class="form-group-label">📄 Dokumen & Anggaran</p>
                <div class="form-grid form-grid-4">
                    <div>
                        <label class="form-label">Nomor Reservasi</label>
                        <input name="nomor_reservasi" id="f_nomor_reservasi" class="form-input" placeholder="No. Reservasi">
                    </div>
                    <div>
                        <label class="form-label">Tanggal Reservasi</label>
                        <input type="date" name="tanggal_reservasi" id="f_tanggal_reservasi" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Nomor PR</label>
                        <input name="nomor_pr" id="f_nomor_pr" class="form-input" placeholder="No. PR">
                    </div>
                    <div>
                        <label class="form-label">Tanggal PR</label>
                        <input type="date" name="tanggal_pr" id="f_tanggal_pr" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Nomor PO</label>
                        <input name="nomor_po" id="f_nomor_po" class="form-input" placeholder="No. PO">
                    </div>
                    <div>
                        <label class="form-label">Tanggal PO</label>
                        <input type="date" name="tanggal_po" id="f_tanggal_po" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Estimated Delivery</label>
                        <input type="date" name="estimated_delivery" id="f_estimated_delivery" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Estimasi Harga (Rp)</label>
                        <input type="number" step="0.01" name="estimasi_harga" id="f_estimasi_harga" class="form-input" placeholder="0" min="0">
                    </div>
                    <div>
                        <label class="form-label">Nama Vendor</label>
                        <input name="nama_vendor" id="f_nama_vendor" class="form-input" placeholder="Nama Vendor">
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" id="f_status" class="form-select">
                            <option value="Open">Open</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                </div>

            </div>{{-- end modal-body --}}

            {{-- MODAL FOOTER --}}
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">✖ Batal</button>
                <button type="submit" class="btn-save">💾 Simpan Perubahan</button>
            </div>

        </form>
    </div>
</div>

<script>
    /**
     * Buka modal dan populate semua field dengan data baris yang dipilih.
     */
    function openEditModal(id) {
        const row = monitoringRows[id];
        if (!row) return;

        // Set action form → route monitoring.update  (PUT /monitoring-material/{id})
        document.getElementById('editForm').action = `/monitoring-material/${id}`;

        // Update judul modal
        document.getElementById('modalTransLabel').textContent = `ID Trans: ${row.trans_id}`;

        // Helper set value (handle null/undefined)
        const set = (fieldId, value) => {
            const el = document.getElementById(fieldId);
            if (el) el.value = value ?? '';
        };

        // Helper date: ambil 10 karakter pertama (yyyy-MM-dd) agar cocok dengan input[type=date]
        const setDate = (fieldId, value) => {
            const el = document.getElementById(fieldId);
            if (el) el.value = value ? String(value).substring(0, 10) : '';
        };

        // --- Notifikasi & Pengadaan ---
        setDate('f_tanggal',           row.tanggal);
        set('f_no_notifikasi',         row.no_notifikasi);
        set('f_qty',                   row.qty);
        set('f_uom',                   row.uom);
        set('f_pengadaan',             row.pengadaan);
        set('f_model',                 row.model);

        // --- Dokumen & Anggaran ---
        set('f_nomor_reservasi',       row.nomor_reservasi);
        setDate('f_tanggal_reservasi', row.tanggal_reservasi);
        set('f_nomor_pr',              row.nomor_pr);
        setDate('f_tanggal_pr',        row.tanggal_pr);
        set('f_nomor_po',              row.nomor_po);
        setDate('f_tanggal_po',        row.tanggal_po);
        setDate('f_estimated_delivery',row.estimated_delivery);
        set('f_estimasi_harga',        row.estimasi_harga);
        set('f_nama_vendor',           row.nama_vendor);
        set('f_status',                row.status ?? 'Open');

        // Tampilkan modal & kunci scroll background
        document.getElementById('editModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Klik area gelap di luar modal box → tutup
    function closeOnOverlay(event) {
        if (event.target === document.getElementById('editModal')) {
            closeEditModal();
        }
    }

    // Tekan Escape → tutup modal
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeEditModal();
    });
</script>

@endsection