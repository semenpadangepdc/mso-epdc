@extends('layouts.app')

@section('content')

<style>
    :root {
        --red-primary: #dc2626;
        --red-dark: #991b1b;
        --red-light: #fecaca;
        --red-bg: #fef2f2;
        --black: #1f2937;
        --white: #ffffff;
        --gray-border: #e5e7eb;
        --gray-light: #f9fafb;
        --blue-info: #3b82f6;
        --green: #16a34a;
        --green-dark: #15803d;
        --orange: #f59e0b;
    }

    .form-container {
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.1), 0 2px 4px -1px rgba(220, 38, 38, 0.06);
        padding: 2rem;
    }

    .page-header {
        background: linear-gradient(135deg, var(--red-dark) 0%, var(--red-primary) 100%);
        color: var(--white);
        padding: 1.5rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.2);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header .badge-mso {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 0.3rem;
        display: inline-block;
    }

    .section-header {
        background: linear-gradient(135deg, var(--red-dark) 0%, var(--red-primary) 100%);
        color: var(--white);
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-weight: 700;
        font-size: 1.125rem;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-header-blue {
        background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%);
        color: var(--white);
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-weight: 700;
        font-size: 1.125rem;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-section {
        background: var(--red-bg);
        padding: 1.5rem;
        border-radius: 12px;
        border: 2px solid var(--red-light);
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(220, 38, 38, 0.1);
    }

    .card-section-blue {
        background: #eff6ff;
        padding: 1.5rem;
        border-radius: 12px;
        border: 2px solid #bfdbfe;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(59, 130, 246, 0.1);
    }

    .card-section-green {
        background: #f0fdf4;
        padding: 1.5rem;
        border-radius: 12px;
        border: 2px solid #bbf7d0;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(22, 163, 74, 0.1);
    }

    /* Read-only info fields */
    .info-field {
        background: var(--white);
        border: 2px solid var(--gray-border);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #374151;
        font-weight: 500;
        min-height: 46px;
        display: flex;
        align-items: center;
    }

    .form-label {
        font-weight: 600;
        color: var(--red-dark);
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-label-blue {
        font-weight: 600;
        color: #1d4ed8;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-input, .form-select, .form-textarea {
        width: 100%;
        border: 2px solid var(--gray-border);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        font-size: 0.875rem;
        background: var(--white);
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: var(--red-primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .form-input:disabled, .form-input[readonly] {
        background: var(--gray-light);
        color: #6b7280;
        cursor: not-allowed;
    }

    /* Table */
    .findings-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
        background: var(--white);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.1);
    }

    .findings-table thead tr {
        background: linear-gradient(135deg, var(--red-dark) 0%, var(--red-primary) 100%);
        color: var(--white);
    }

    .findings-table th {
        padding: 1rem 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
        border: 1px solid var(--red-dark);
    }

    .findings-table td {
        padding: 0.875rem 0.75rem;
        border: 1px solid var(--gray-border);
        vertical-align: middle;
    }

    .findings-table tbody tr {
        transition: all 0.2s ease;
        background: var(--white);
    }

    .findings-table tbody tr:nth-child(even) {
        background: var(--red-bg);
    }

    .findings-table tbody tr:hover {
        background: var(--red-light);
    }

    /* Existing row badge */
    .row-badge-existing {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        border-radius: 20px;
        padding: 0.15rem 0.6rem;
        font-size: 0.7rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
    }

    .row-badge-new {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
        border-radius: 20px;
        padding: 0.15rem 0.6rem;
        font-size: 0.7rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
    }

    /* Buttons */
    .btn-red {
        background: linear-gradient(135deg, var(--red-primary) 0%, var(--red-dark) 100%);
        color: var(--white);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-red:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
    }

    .btn-green {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: var(--white);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(22, 163, 74, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-green:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(22, 163, 74, 0.3);
    }

    .btn-gray {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: var(--white);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-gray:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(107, 114, 128, 0.3);
    }

    .btn-remove {
        background: var(--red-primary);
        color: var(--white);
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .btn-remove:hover {
        background: var(--red-dark);
        transform: scale(1.05);
    }

    .btn-delete-finding {
        background: #7f1d1d;
        color: var(--white);
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        font-size: 0.8rem;
        width: 100%;
        margin-top: 0.25rem;
    }

    .btn-delete-finding:hover {
        background: #450a0a;
    }

    .file-input {
        font-size: 0.75rem;
        padding: 0.5rem;
        border: 2px dashed var(--red-light);
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
        width: 100%;
    }

    .file-input:hover {
        border-color: var(--red-primary);
        background: var(--red-bg);
    }

    .preview {
        border-radius: 8px;
        border: 2px solid var(--red-light);
        max-width: 80px;
        transition: all 0.2s ease;
        display: block;
        margin-top: 0.5rem;
    }

    .preview:hover {
        transform: scale(1.1);
        border-color: var(--red-primary);
        cursor: zoom-in;
    }

    .error-message {
        color: var(--red-dark);
        font-size: 0.75rem;
        margin-top: 0.25rem;
        font-weight: 500;
        display: block;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .form-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .form-grid, .form-grid-3 { grid-template-columns: 1fr; }
    }

    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid var(--red-light);
    }

    .alert-info {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
        padding: 0.875rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .status-open { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
    .status-progress { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
    .status-partial { background: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe; }
    .status-closed { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }

    /* Photo gallery existing */
    .photo-thumb-container {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 0.5rem;
    }

    .photo-thumb-wrap {
        position: relative;
    }

    .photo-thumb-wrap img {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid var(--gray-border);
        cursor: zoom-in;
        transition: all 0.2s;
    }

    .photo-thumb-wrap img:hover {
        border-color: var(--red-primary);
        transform: scale(1.05);
    }

    .photo-delete-btn {
        position: absolute;
        top: -6px;
        right: -6px;
        background: var(--red-primary);
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.65rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        transition: background 0.2s;
    }

    .photo-delete-btn:hover { background: var(--red-dark); }

    /* Photo preview modal */
    #previewModal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.85);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    #previewModal.active { display: flex; }

    #previewModal img {
        max-width: 90vw;
        max-height: 90vh;
        border-radius: 12px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
    }

    /* Select2 custom */
    .select2-container--default .select2-selection--single {
        border: 2px solid var(--gray-border) !important;
        border-radius: 8px !important;
        height: auto !important;
        min-height: 46px !important;
        background: var(--white) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding: 0.5rem 1rem !important;
        line-height: 1.5 !important;
        color: var(--black) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
        right: 8px !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: var(--red-primary) !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--red-primary) !important;
    }

    .select2-dropdown {
        border: 2px solid var(--red-primary) !important;
        border-radius: 8px !important;
    }

    .divider {
        border: none;
        border-top: 2px solid var(--red-light);
        margin: 2rem 0;
    }

    /* Select2 inside table — prevent overflow */
    .findings-table .select2-container {
        min-width: 200px;
        width: 100% !important;
    }

    /* Dropdown renders above/below without clipping */
    .select2-container--open .select2-dropdown {
        z-index: 9999;
    }
</style>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

<div class="max-w-6xl mx-auto p-6">

    <!-- ============================= PAGE HEADER ============================= -->
    <div class="page-header">
        <span style="font-size: 2rem;">✏️</span>
        <div>
            <h1 class="text-2xl font-bold">Edit MSO</h1>
            <span class="badge-mso">{{ $mso->no_mso }}</span>
        </div>
        <div class="ml-auto flex gap-2">
            <a href="{{ route('mso.show', $mso->id) }}" class="btn-gray" style="padding: 0.5rem 1rem; font-size:0.875rem;">
                👁 Lihat Detail
            </a>
            <a href="{{ route('mso.index') }}" class="btn-gray" style="padding: 0.5rem 1rem; font-size:0.875rem;">
                ⬅ Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-info" style="background:#f0fdf4; border-color:#86efac; color:#15803d; margin-bottom:1rem;">
            <span>✅</span> <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert-info" style="background:#fef2f2; border-color:#fecaca; color:#991b1b; margin-bottom:1rem;">
            <span>⚠️</span>
            <ul style="margin:0; padding-left:1rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">

        {{-- ================================================================== --}}
        {{-- SECTION 1: INFO HEADER (READ ONLY) --}}
        {{-- ================================================================== --}}
        <div class="section-header">
            <span>📋</span>
            <span>Informasi Dasar MSO</span>
            <span style="margin-left:auto; font-size:0.8rem; font-weight:400; opacity:0.85;">📌 Read-only — tidak dapat diubah</span>
        </div>

        <div class="card-section">
            <div class="form-grid" style="margin-bottom:1rem;">
                <div>
                    <label class="form-label">🏭 Plant</label>
                    <div class="info-field">{{ $mso->plant->name ?? '-' }}</div>
                </div>
                <div>
                    <label class="form-label">📍 Area</label>
                    <div class="info-field">{{ $mso->area->name ?? '-' }}</div>
                </div>
                <div>
                    <label class="form-label">🔧 Nomenclature</label>
                    <div class="info-field">{{ $mso->nomenclature->name ?? '-' }}</div>
                </div>
                <div>
                    <label class="form-label">⚙️ Jenis Maintenance</label>
                    <div class="info-field">{{ $mso->maintenanceType->name ?? '-' }}</div>
                </div>
                <div>
                    <label class="form-label">👤 User</label>
                    <div class="info-field">{{ $mso->user->name ?? '-' }}</div>
                </div>
                <div>
                    <label class="form-label">🕐 Dibuat</label>
                    <div class="info-field">{{ $mso->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>

        <hr class="divider">

        {{-- ================================================================== --}}
        {{-- SECTION 2: FORM UPDATE STATUS --}}
        {{-- ================================================================== --}}
        <div class="section-header">
            <span>🔄</span>
            <span>Update Status & Keterangan</span>
        </div>

        <form action="{{ route('mso.update', $mso->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-section">
                <div class="form-grid">
                    <div>
                        <label class="form-label">📊 Status Peralatan</label>
                        <select name="status_peralatan" class="form-select" required>
                            @foreach(['Active Operation','Ready Standby','Broken - Eliminated'] as $opt)
                                <option value="{{ $opt }}" @selected($mso->status_peralatan === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @error('status_peralatan')<small class="error-message">{{ $message }}</small>@enderror
                    </div>

                    <div>
                        <label class="form-label">📁 Status Pekerjaan</label>
                        <select name="status_pekerjaan" class="form-select" required>
                            @foreach(['Open','Partial Finish','Closed'] as $opt)
                                <option value="{{ $opt }}" @selected($mso->status_pekerjaan === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @error('status_pekerjaan')<small class="error-message">{{ $message }}</small>@enderror
                    </div>

                    <div style="grid-column: span 2;">
                        <label class="form-label">📝 Keterangan</label>
                        <textarea name="keterangan" class="form-textarea" rows="3"
                            placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('keterangan', $mso->keterangan) }}</textarea>
                    </div>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; margin-bottom:2rem;">
                <button type="submit" class="btn-red">
                    <span>💾</span>
                    <span>Simpan Perubahan Status</span>
                </button>
            </div>
        </form>

        <hr class="divider">

        {{-- ================================================================== --}}
        {{-- SECTION 3: UPDATE WAKTU PENGERJAAN --}}
        {{-- ================================================================== --}}
        <div class="section-header-blue">
            <span>⏱️</span>
            <span>Waktu Pengerjaan</span>
        </div>

        <form action="{{ route('mso.update-time', $mso->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-section-blue">
                <div class="form-grid">
                    <div>
                        <label class="form-label-blue">📅 Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-input"
                            value="{{ old('start_date', $mso->start_date ? \Carbon\Carbon::parse($mso->start_date)->format('Y-m-d') : '') }}">
                    </div>
                    <div>
                        <label class="form-label-blue">🕐 Jam Mulai</label>
                        <input type="time" name="start_hour" class="form-input"
                            value="{{ old('start_hour', $mso->start_hour) }}">
                    </div>
                    <div>
                        <label class="form-label-blue">📅 Tanggal Selesai</label>
                        <input type="date" name="finish_date" class="form-input"
                            value="{{ old('finish_date', $mso->finish_date ? \Carbon\Carbon::parse($mso->finish_date)->format('Y-m-d') : '') }}">
                    </div>
                    <div>
                        <label class="form-label-blue">🕐 Jam Selesai</label>
                        <input type="time" name="finish_hour" class="form-input"
                            value="{{ old('finish_hour', $mso->finish_hour) }}">
                    </div>
                </div>

                @if($mso->total_duration)
                    <div class="alert-info" style="margin-top:1rem; margin-bottom:0;">
                        <span>⏳</span>
                        <span>Total Durasi Tercatat: <strong>{{ $mso->total_duration }} jam</strong></span>
                    </div>
                @endif
            </div>

            <div style="display:flex; justify-content:flex-end; margin-bottom:2rem;">
                <button type="submit" class="btn-green">
                    <span>⏱️</span>
                    <span>Simpan Waktu Pengerjaan</span>
                </button>
            </div>
        </form>

        <hr class="divider">

        {{-- ================================================================== --}}
        {{-- SECTION 4: TABEL FINDING --}}
        {{-- ================================================================== --}}
        <div class="section-header">
            <span>🛠</span>
            <span>Temuan & Tindakan</span>
            <span style="margin-left:auto; font-size:0.8rem; opacity:0.85; font-weight:400;">
                {{ $mso->findings->count() }} temuan tercatat
            </span>
        </div>

        <div class="alert-info">
            <span>ℹ️</span>
            <span>Anda dapat mengupdate <strong>Material Master</strong> dan <strong>Action</strong> per baris temuan. Untuk menambah temuan baru, gunakan tombol di bawah tabel.</span>
        </div>

        <div style="overflow-x: auto; margin-bottom: 1.5rem;">
            <table class="findings-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Komponen</th>
                        <th>Temuan</th>
                        <th style="min-width:180px;">Material Master</th>
                        <th style="min-width:160px;">Action</th>
                        <th style="min-width:100px;">Foto Before</th>
                        <th style="min-width:100px;">Foto After</th>
                        @can('manage material')
                        <th style="width:100px;">Aksi</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($mso->findings as $i => $finding)
                    <tr>
                        <td class="text-center" style="font-weight:600; color:#6b7280;">{{ $i + 1 }}</td>

                        <td>
                            <span class="row-badge-existing">💾 Existing</span><br>
                            <strong>{{ $finding->component->name ?? '-' }}</strong>
                        </td>

                        <td>
                            <span style="font-size:0.8rem; background:#fef3c7; color:#92400e; padding:0.2rem 0.5rem; border-radius:4px;">
                                {{ $finding->temuan ?? '-' }}
                            </span>
                        </td>

                        {{-- Update Material Master (inline form per baris) --}}
                        <td>
                            @can('manage material')
                            <form action="{{ route('mso.finding.update-material', $finding->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="material_master_id" class="form-select material-select" style="font-size:0.8rem; padding:0.5rem;">
                                    <option value="">-- Pilih Material --</option>
                                    @foreach($materialMasters as $m)
                                        <option value="{{ $m->id }}"
                                            @selected($finding->material_master_id == $m->id)>
                                            {{ $m->material_code }} - {{ $m->material_description }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn-green" style="margin-top:0.4rem; padding:0.4rem 0.75rem; font-size:0.75rem; width:100%;">
                                    💾 Simpan
                                </button>
                            </form>
                            @else
                                <span>{{ $finding->materialMaster->name ?? '-' }}</span>
                            @endcan
                        </td>

                        {{-- Action text --}}
                        <td>
                            <span style="font-size:0.85rem;">{{ $finding->action ?? '-' }}</span>
                        </td>

                        {{-- Foto Before --}}
                        <td class="text-center">
                            @php $beforePhotos = $finding->photos->where('type','before'); @endphp
                            @if($beforePhotos->count())
                                <div class="photo-thumb-container">
                                    @foreach($beforePhotos as $photo)
                                        @if($photo->path)
                                        <div class="photo-thumb-wrap">
                                            <img src="{{ Storage::disk('image')->url($photo->path) }}"
                                                 onclick="openPreview('{{ Storage::disk('image')->url($photo->path) }}')"
                                                 alt="Before">
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <span style="color:#9ca3af; font-size:0.8rem;">—</span>
                            @endif
                        </td>

                        {{-- Foto After --}}
                        <td class="text-center">
                            @php $afterPhotos = $finding->photos->where('type','after'); @endphp
                            @if($afterPhotos->count())
                                <div class="photo-thumb-container">
                                    @foreach($afterPhotos as $photo)
                                        @if($photo->path)
                                        <div class="photo-thumb-wrap">
                                            <img src="{{ Storage::disk('image')->url($photo->path) }}"
                                                 onclick="openPreview('{{ Storage::disk('image')->url($photo->path) }}')"
                                                 alt="After">
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <span style="color:#9ca3af; font-size:0.8rem;">—</span>
                            @endif
                        </td>

                        {{-- Delete Finding --}}
                        @can('manage material')
                        <td class="text-center">
                            <form action="{{ route('mso.finding.destroy', $finding->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus temuan ini beserta fotonya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete-finding">🗑 Hapus</button>
                            </form>
                        </td>
                        @endcan
                    </tr>
                    @endforeach

                    {{-- ============ BARIS BARU (ADD FINDING) ============ --}}
                    <tbody id="new-finding-body">
                    </tbody>

                </tbody>
            </table>
        </div>

        {{-- Tombol Tambah Baris Baru --}}
        <button type="button" id="btn-add-finding" class="btn-green" style="margin-bottom:1.5rem;">
            <span>➕</span>
            <span>Tambah Baris Temuan Baru</span>
        </button>

        {{-- Form submit untuk finding baru --}}
        <form id="form-new-findings" action="{{ route('mso.store') }}" method="POST" enctype="multipart/form-data" style="display:none;">
            @csrf
            {{-- Hidden fields to pass MSO header context for new findings --}}
            <input type="hidden" name="_add_finding_to_mso" value="{{ $mso->id }}">
            <input type="hidden" name="plant_id" value="{{ $mso->plant_id }}">
            <input type="hidden" name="area_id" value="{{ $mso->area_id }}">
            <input type="hidden" name="nomenclature_id" value="{{ $mso->nomenclature_id }}">
            <input type="hidden" name="status_peralatan" value="{{ $mso->status_peralatan }}">
            <input type="hidden" name="maintenance_type_id" value="{{ $mso->maintenance_type_id }}">

            <div id="new-findings-wrapper" style="overflow-x:auto; margin-bottom:1rem;">
                <table class="findings-table" id="new-findings-table" style="display:none;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #15803d 0%, #16a34a 100%);">
                            <th>Komponen</th>
                            <th>Temuan</th>
                            <th>Action</th>
                            <th>Foto Before</th>
                            <th>Foto After</th>
                            <th style="width:80px;">Hapus</th>
                        </tr>
                    </thead>
                    <tbody id="new-finding-rows"></tbody>
                </table>
            </div>

            <div id="new-finding-actions" style="display:none; justify-content:flex-end; gap:1rem; margin-bottom:1rem;">
                <button type="button" id="btn-cancel-new" class="btn-gray">
                    <span>❌</span> <span>Batal</span>
                </button>
                <button type="submit" class="btn-green">
                    <span>💾</span> <span>Simpan Temuan Baru</span>
                </button>
            </div>
        </form>

        {{-- ================================================================== --}}
        {{-- FOOTER ACTIONS --}}
        {{-- ================================================================== --}}
        <div class="action-buttons">
            <a href="{{ route('mso.show', $mso->id) }}" class="btn-gray">
                <span>👁</span>
                <span>Lihat Detail</span>
            </a>
            <a href="{{ route('mso.index') }}" class="btn-gray">
                <span>⬅</span>
                <span>Kembali ke Daftar</span>
            </a>
        </div>

    </div>
</div>

{{-- ===================== IMAGE PREVIEW MODAL ===================== --}}
<div id="previewModal" onclick="this.classList.remove('active')">
    <img id="previewModalImg" src="" alt="Preview">
</div>

<!-- jQuery + Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // ============================
    // IMAGE PREVIEW
    // ============================
    function openPreview(url) {
        document.getElementById('previewModalImg').src = url;
        document.getElementById('previewModal').classList.add('active');
    }

    // ============================
    // TEMUAN OPTIONS LIST
    // ============================
    const TEMUAN_OPTIONS = [
        "Aksesories Tidak Lengkap","Basah / Rembes Air","Bengkok/Bending","Blockage",
        "Bocor/Robek/Retak","Buntu","Bushing Isolator Rusak","Card Sensor Rusak",
        "Celah Gap besar","Dioda Rusak","Hang/Tidak Merespon","Hilang / Missing",
        "Induksi/Interferenced","Isolasi Rusak","Kaku/Mengeras/Getas","Kendor - Getas",
        "Korosi/Karat","Kotor","Lemah","Lembab / Basah","Lepas","Limit Switch Rusak",
        "Macet/Stuck","Not Align","Oli Bocor","Oli Kotor / BDV Rendah","Over Limit",
        "Patah/Putus","Penerangan Lampu Minim","Penuh","Rendah","Retak/Sompel","Rusak",
        "Short Circuit","Suara Kasar","Temperatur Tinggi","Terminasi Longgar",
        "Vibrasi Tinggi","Bocor (Udara Komp)","Bocor (Bangunan)",
        "Rusak (Short Circuit-Terbakar)","Overload","Power Supply OFF","Thyristor Rusak"
    ];

    function populateTemuanSelect(el) {
        el.innerHTML = '<option value="">-- Pilih Temuan --</option>';
        TEMUAN_OPTIONS.forEach(t => {
            const o = document.createElement('option');
            o.value = t; o.textContent = t;
            el.appendChild(o);
        });
    }

    // ============================
    // NEW FINDING ROW
    // ============================
    const nomenclatureId = {{ $mso->nomenclature_id ?? 'null' }};
    let componentOptions = '<option value="">Loading...</option>';

    // Pre-load components for this nomenclature
    if (nomenclatureId) {
        fetch(`/ajax/components/by-nomenclature/${nomenclatureId}`)
            .then(r => r.json())
            .then(data => {
                componentOptions = '<option value="">-- Pilih Komponen --</option>';
                data.forEach(c => {
                    componentOptions += `<option value="${c.id}">${c.name}</option>`;
                });
            });
    }

    function buildNewRow() {
        const tr = document.createElement('tr');
        tr.className = 'finding-row-new';
        tr.innerHTML = `
            <td>
                <span class="row-badge-new">✨ Baru</span>
                <select name="component_id[]" class="form-select component-select" style="font-size:0.8rem;">${componentOptions}</select>
            </td>
            <td>
                <select name="temuan[]" class="form-select temuan-select" style="font-size:0.8rem;"></select>
            </td>
            <td>
                <textarea name="action[]" class="form-textarea" rows="2" style="font-size:0.8rem;" placeholder="Tindakan..."></textarea>
            </td>
            <td class="text-center">
                <input type="file" name="foto_before[]" accept="image/*" class="file-input">
                <img class="preview hidden" style="max-width:80px; margin-top:0.5rem;">
            </td>
            <td class="text-center">
                <input type="file" name="foto_after[]" accept="image/*" class="file-input">
                <img class="preview hidden" style="max-width:80px; margin-top:0.5rem;">
            </td>
            <td class="text-center">
                <button type="button" class="btn-remove remove-new-row">✖</button>
            </td>
        `;

        // Populate temuan select
        tr.querySelectorAll('.temuan-select').forEach(populateTemuanSelect);

        // File preview
        tr.querySelectorAll('.file-input').forEach(input => {
            input.addEventListener('change', function () {
                const preview = this.nextElementSibling;
                if (this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(this.files[0]);
                } else {
                    preview.classList.add('hidden');
                    preview.src = '';
                }
            });
        });

        // Remove row
        tr.querySelector('.remove-new-row').addEventListener('click', function () {
            tr.remove();
            checkNewFindingTable();
        });

        return tr;
    }

    function checkNewFindingTable() {
        const rows = document.querySelectorAll('#new-finding-rows tr').length;
        const table = document.getElementById('new-findings-table');
        const actions = document.getElementById('new-finding-actions');
        const form = document.getElementById('form-new-findings');

        if (rows === 0) {
            table.style.display = 'none';
            actions.style.display = 'none';
            form.style.display = 'none';
        }
    }

    document.getElementById('btn-add-finding').addEventListener('click', function () {
        const tbody = document.getElementById('new-finding-rows');
        const table = document.getElementById('new-findings-table');
        const actions = document.getElementById('new-finding-actions');
        const form = document.getElementById('form-new-findings');

        tbody.appendChild(buildNewRow());

        table.style.display = 'table';
        actions.style.display = 'flex';
        form.style.display = 'block';
    });

    document.getElementById('btn-cancel-new').addEventListener('click', function () {
        document.getElementById('new-finding-rows').innerHTML = '';
        checkNewFindingTable();
    });

    // ============================
    // SELECT2 — MATERIAL MASTER
    // ============================
    $(document).ready(function () {
        // Init semua .material-select existing di DOM
        initMaterialSelect($('.material-select'));
    });

    function initMaterialSelect(el) {
        el.select2({
            placeholder: '🔍 Cari kode / nama material...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function () { return 'Material tidak ditemukan'; },
                searching:  function () { return 'Mencari...'; }
            }
        });
    }
</script>

@endsection