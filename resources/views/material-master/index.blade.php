@extends('layouts.app')

@section('content')

<style>
    :root {
        --primary-red: #DC2626;
        --dark-red: #991B1B;
        --light-red: #FEE2E2;
        --red-bg: #fef2f2;
        --pure-white: #FFFFFF;
        --off-white: #F9FAFB;
        --dark-gray: #1F2937;
        --medium-gray: #6B7280;
        --light-gray: #E5E7EB;
        --green: #16a34a;
        --green-dark: #15803d;
        --blue: #3b82f6;
        --blue-dark: #2563eb;
    }

    /* ============================
       LAYOUT
    ============================ */
    .mm-container {
        background: linear-gradient(135deg, var(--off-white) 0%, var(--pure-white) 100%);
        min-height: 100vh;
        padding: 2rem;
    }

    /* ============================
       PAGE HEADER
    ============================ */
    .page-header {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        padding: 1.75rem 2rem;
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .page-header-left { display: flex; align-items: center; gap: 1rem; }
    .page-title { font-size: 1.75rem; font-weight: 700; margin: 0; }
    .page-subtitle { font-size: 0.875rem; opacity: 0.85; margin-top: 0.2rem; }

    .admin-badge {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.35);
        color: white;
        padding: 0.3rem 0.85rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 0.35rem;
    }

    /* ============================
       STAT CARDS
    ============================ */
    .stat-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) { .stat-cards { grid-template-columns: 1fr; } }

    .stat-card {
        background: var(--pure-white);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        border-left: 4px solid var(--primary-red);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .stat-card-icon.red   { background: var(--light-red); }
    .stat-card-icon.green { background: #dcfce7; }
    .stat-card-icon.blue  { background: #dbeafe; }

    .stat-card-value { font-size: 1.6rem; font-weight: 800; color: var(--dark-gray); line-height: 1; }
    .stat-card-label { font-size: 0.8rem; color: var(--medium-gray); margin-top: 0.2rem; font-weight: 500; }

    /* ============================
       FILTER BAR
    ============================ */
    .filter-bar {
        background: var(--pure-white);
        padding: 1.25rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--primary-red);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .filter-bar form { display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center; width: 100%; }

    .search-input {
        border: 2px solid var(--light-gray);
        padding: 0.65rem 1rem;
        border-radius: 8px;
        flex: 1;
        min-width: 220px;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-red);
        box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
    }

    .filter-select {
        border: 2px solid var(--light-gray);
        padding: 0.65rem 2.5rem 0.65rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23DC2626'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1.2rem;
        background-color: white;
        cursor: pointer;
        transition: all 0.2s;
        min-width: 140px;
    }

    .filter-select:focus { outline:none; border-color: var(--primary-red); box-shadow: 0 0 0 3px rgba(220,38,38,0.1); }

    /* ============================
       BUTTONS
    ============================ */
    .btn {
        padding: 0.65rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-red   { background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%); color: white; box-shadow: 0 2px 6px rgba(220,38,38,0.25); }
    .btn-green { background: linear-gradient(135deg, var(--green) 0%, var(--green-dark) 100%); color: white; box-shadow: 0 2px 6px rgba(22,163,74,0.25); }
    .btn-blue  { background: linear-gradient(135deg, var(--blue) 0%, var(--blue-dark) 100%); color: white; box-shadow: 0 2px 6px rgba(59,130,246,0.2); }
    .btn-gray  { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; }
    .btn-outline-red { background: white; border: 2px solid var(--primary-red); color: var(--primary-red); }

    .btn:hover { transform: translateY(-2px); filter: brightness(1.05); }

    .btn-sm { padding: 0.4rem 0.85rem; font-size: 0.8rem; }

    /* ============================
       TABLE
    ============================ */
    .scroll-wrapper {
        background: var(--pure-white);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border: 1.5px solid var(--light-gray);
        overflow-x: auto;
        margin-bottom: 1.5rem;
    }

    .mm-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
        font-size: 0.875rem;
    }

    .mm-table thead {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        position: sticky;
        top: 0;
        z-index: 5;
    }

    .mm-table th {
        padding: 1rem 1.1rem;
        text-align: left;
        font-weight: 700;
        font-size: 0.78rem;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
        border-bottom: 3px solid var(--dark-red);
    }

    .mm-table tbody tr {
        border-bottom: 1px solid var(--light-gray);
        transition: all 0.2s;
    }

    .mm-table tbody tr:hover {
        background: var(--light-red);
        transform: translateX(3px);
        box-shadow: -3px 0 0 var(--primary-red);
    }

    .mm-table td {
        padding: 0.9rem 1.1rem;
        color: var(--dark-gray);
        vertical-align: middle;
    }

    .mm-table tbody tr:nth-child(even) { background: var(--off-white); }
    .mm-table tbody tr:nth-child(even):hover { background: var(--light-red); }

    /* Code badge */
    .code-badge {
        background: var(--red-bg);
        color: var(--dark-red);
        border: 1px solid #fecaca;
        padding: 0.25rem 0.65rem;
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        font-size: 0.82rem;
        font-weight: 700;
        white-space: nowrap;
    }

    /* UOM badge */
    .uom-badge {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    /* Price */
    .price-text {
        font-weight: 600;
        color: var(--green-dark);
        white-space: nowrap;
    }

    /* No data */
    .no-data {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--medium-gray);
    }

    .no-data-icon { font-size: 3.5rem; margin-bottom: 1rem; }
    .no-data-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem; }

    /* ============================
       PAGINATION
    ============================ */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .pagination-info {
        font-size: 0.875rem;
        color: var(--medium-gray);
    }

    /* Override Laravel pagination */
    nav[aria-label="Pagination"] .flex { gap: 0.3rem; }

    /* ============================
       ALERTS
    ============================ */
    .alert {
        padding: 0.9rem 1.25rem;
        border-radius: 10px;
        margin-bottom: 1.25rem;
        font-size: 0.875rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #15803d; }
    .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

    /* ============================
       MODAL
    ============================ */
    .modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-backdrop.active { display: flex; }

    .modal-box {
        background: white;
        border-radius: 16px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        width: 100%;
        max-width: 520px;
        animation: modalIn 0.2s ease;
    }

    @keyframes modalIn {
        from { transform: scale(0.95); opacity: 0; }
        to   { transform: scale(1);    opacity: 1; }
    }

    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 2px solid var(--light-gray);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-radius: 16px 16px 0 0;
    }

    .modal-header.red-header {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: white;
        border-bottom: none;
    }

    .modal-title { font-weight: 700; font-size: 1.1rem; }

    .modal-close {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .modal-close:hover { background: rgba(255,255,255,0.35); }
    .modal-close.dark { color: var(--dark-gray); background: var(--light-gray); }
    .modal-close.dark:hover { background: #d1d5db; }

    .modal-body { padding: 1.5rem; }
    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 2px solid var(--light-gray);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    /* Form inside modal */
    .form-label {
        font-weight: 600;
        color: var(--dark-red);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.4rem;
        display: block;
    }

    .form-control {
        width: 100%;
        border: 2px solid var(--light-gray);
        padding: 0.65rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-red);
        box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
    }

    .form-group { margin-bottom: 1rem; }

    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    .error-msg { color: var(--dark-red); font-size: 0.75rem; margin-top: 0.25rem; font-weight: 500; }

    /* Delete confirm modal */
    .delete-icon {
        width: 64px;
        height: 64px;
        background: var(--light-red);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1rem;
    }
</style>

<div class="mm-container">

    {{-- ======================== HEADER ======================== --}}
    <div class="page-header">
        <div class="page-header-left">
            <span style="font-size:2.5rem;">🗄️</span>
            <div>
                <h1 class="page-title">Material Master</h1>
                <p class="page-subtitle">Kelola data material untuk kebutuhan MSO</p>
                <span class="admin-badge">🔐 Admin Only</span>
            </div>
        </div>
        <button class="btn btn-green" onclick="openAddModal()">
            <span>➕</span> Tambah Material
        </button>
    </div>

    {{-- ======================== ALERTS ======================== --}}
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">⚠️ {{ session('error') }}</div>
    @endif

    {{-- ======================== STAT CARDS ======================== --}}
    <div class="stat-cards">
        <div class="stat-card">
            <div class="stat-card-icon red">🗄️</div>
            <div>
                <div class="stat-card-value">{{ number_format($materials->total()) }}</div>
                <div class="stat-card-label">Total Material</div>
            </div>
        </div>
        <div class="stat-card" style="border-color: var(--green);">
            <div class="stat-card-icon green">📦</div>
            <div>
                <div class="stat-card-value">{{ $uoms->count() }}</div>
                <div class="stat-card-label">Jenis UOM</div>
            </div>
        </div>
        <div class="stat-card" style="border-color: var(--blue);">
            <div class="stat-card-icon blue">📄</div>
            <div>
                <div class="stat-card-value">{{ $materials->lastPage() }}</div>
                <div class="stat-card-label">Halaman</div>
            </div>
        </div>
    </div>

    {{-- ======================== FILTER BAR ======================== --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('material-master.index') }}">
            <input type="text" name="search" class="search-input"
                placeholder="🔍 Cari kode / nama / deskripsi material..."
                value="{{ request('search') }}">

            <select name="uom" class="filter-select">
                <option value="">Semua UOM</option>
                @foreach($uoms as $uom)
                    <option value="{{ $uom }}" @selected(request('uom') === $uom)>{{ $uom }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-red">
                🔍 Cari
            </button>

            @if(request('search') || request('uom'))
                <a href="{{ route('material-master.index') }}" class="btn btn-gray">
                    ✖ Reset
                </a>
            @endif

            <span style="color:var(--medium-gray); font-size:0.8rem; margin-left:auto;">
                Menampilkan {{ $materials->firstItem() }}–{{ $materials->lastItem() }}
                dari {{ number_format($materials->total()) }} material
            </span>
        </form>
    </div>

    {{-- ======================== TABLE ======================== --}}
    <div class="scroll-wrapper">
        <table class="mm-table">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Kode Material</th>
                    <th>Nama Material</th>
                    <th>Deskripsi</th>
                    <th>UOM</th>
                    <th>Harga</th>
                    <th style="width:150px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $i => $m)
                    <tr>
                        <td style="color:var(--medium-gray); font-weight:600;">
                            {{ $materials->firstItem() + $i }}
                        </td>
                        <td>
                            <span class="code-badge">{{ $m->material_code }}</span>
                        </td>
                        <td>
                            <span style="font-weight:600;">{{ $m->material_description }}</span>
                        </td>
                        <td style="color:var(--medium-gray); max-width:260px;">
                            {{ Str::limit($m->long_text, 70) ?? '—' }}
                        </td>
                        <td>
                            @if($m->base_uom)
                                <span class="uom-badge">{{ $m->base_uom }}</span>
                            @else
                                <span style="color:#d1d5db;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($m->price)
                                <span class="price-text">
                                    Rp {{ number_format($m->price, 0, ',', '.') }}
                                </span>
                            @else
                                <span style="color:#d1d5db;">—</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <div style="display:flex; gap:0.4rem; justify-content:center;">
                                <button class="btn btn-blue btn-sm"
                                    onclick='openEditModal(@json($m))'>
                                    ✏️ Edit
                                </button>
                                <button class="btn btn-red btn-sm"
                                    onclick='openDeleteModal({{ $m->id }}, "{{ addslashes($m->material_code) }}", "{{ addslashes($m->material_description) }}")'>
                                    🗑️ Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="no-data">
                                <div class="no-data-icon">📦</div>
                                <div class="no-data-title">Tidak ada data material</div>
                                <p style="font-size:0.85rem;">
                                    @if(request('search') || request('uom'))
                                        Coba ubah filter pencarian Anda.
                                    @else
                                        Klik <strong>Tambah Material</strong> untuk menambahkan data pertama.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ======================== PAGINATION ======================== --}}
    @if($materials->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Halaman {{ $materials->currentPage() }} dari {{ $materials->lastPage() }}
            </div>
            {{ $materials->links() }}
        </div>
    @endif

</div>


{{-- ======================== MODAL: ADD ======================== --}}
<div id="addModal" class="modal-backdrop" onclick="closeModalBackdrop(event,'addModal')">
    <div class="modal-box">
        <div class="modal-header red-header">
            <span class="modal-title">➕ Tambah Material Master</span>
            <button class="modal-close" onclick="closeModal('addModal')">✕</button>
        </div>
        <form action="{{ route('material-master.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Kode Material *</label>
                        <input type="text" name="material_code" class="form-control"
                            placeholder="Contoh: MAT-0001" required
                            value="{{ old('material_code') }}">
                        @error('material_code')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">UOM</label>
                        <input type="text" name="base_uom" class="form-control"
                            placeholder="Contoh: PCS, MTR, KG"
                            value="{{ old('base_uom') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Material *</label>
                    <input type="text" name="material_description" class="form-control"
                        placeholder="Nama lengkap material" required
                        value="{{ old('material_description') }}">
                    @error('material_description')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="long_text" class="form-control" rows="2"
                        placeholder="Deskripsi atau spesifikasi material (opsional)">{{ old('long_text') }}</textarea>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Harga Satuan (Rp)</label>
                    <input type="number" name="price" class="form-control"
                        placeholder="0" min="0" step="1"
                        value="{{ old('price') }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-gray" onclick="closeModal('addModal')">Batal</button>
                <button type="submit" class="btn btn-green">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>


{{-- ======================== MODAL: EDIT ======================== --}}
<div id="editModal" class="modal-backdrop" onclick="closeModalBackdrop(event,'editModal')">
    <div class="modal-box">
        <div class="modal-header red-header">
            <span class="modal-title">✏️ Edit Material Master</span>
            <button class="modal-close" onclick="closeModal('editModal')">✕</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Kode Material *</label>
                        <input type="text" id="edit_material_code" name="material_code"
                            class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">UOM</label>
                        <input type="text" id="edit_uom" name="base_uom" class="form-control"
                            placeholder="PCS, MTR, KG, dll">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Material *</label>
                    <input type="text" id="edit_name" name="material_description" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea id="edit_description" name="long_text" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Harga Satuan (Rp)</label>
                    <input type="number" id="edit_price" name="price"
                        class="form-control" placeholder="0" min="0" step="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-gray" onclick="closeModal('editModal')">Batal</button>
                <button type="submit" class="btn btn-blue">💾 Update</button>
            </div>
        </form>
    </div>
</div>


{{-- ======================== MODAL: DELETE CONFIRM ======================== --}}
<div id="deleteModal" class="modal-backdrop" onclick="closeModalBackdrop(event,'deleteModal')">
    <div class="modal-box" style="max-width:420px;">
        <div class="modal-header" style="border-color:var(--light-gray);">
            <span class="modal-title" style="color:var(--dark-gray);">Konfirmasi Hapus</span>
            <button class="modal-close dark" onclick="closeModal('deleteModal')">✕</button>
        </div>
        <div class="modal-body" style="text-align:center;">
            <div class="delete-icon">🗑️</div>
            <p style="font-weight:600; font-size:1rem; margin-bottom:0.4rem;">Hapus material ini?</p>
            <p style="color:var(--medium-gray); font-size:0.875rem; margin-bottom:1rem;">
                Anda akan menghapus:
            </p>
            <div style="background:var(--red-bg); border:1px solid #fecaca; border-radius:8px; padding:0.75rem 1rem; margin-bottom:0.5rem;">
                <div id="delete_code" style="font-family:monospace; font-weight:700; color:var(--dark-red); font-size:0.9rem;"></div>
                <div id="delete_name" style="font-weight:600; color:var(--dark-gray); margin-top:0.2rem;"></div>
            </div>
            <p style="color:var(--primary-red); font-size:0.8rem; font-weight:500;">
                ⚠️ Material yang masih terhubung ke data temuan MSO tidak dapat dihapus.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-gray" onclick="closeModal('deleteModal')">Batal</button>
            <form id="deleteForm" method="POST" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-red">🗑️ Hapus</button>
            </form>
        </div>
    </div>
</div>


<script>
    // ============================
    // MODAL HELPERS
    // ============================
    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    function closeModalBackdrop(event, id) {
        if (event.target === document.getElementById(id)) closeModal(id);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            ['addModal','editModal','deleteModal'].forEach(closeModal);
        }
    });

    // ============================
    // ADD MODAL
    // ============================
    function openAddModal() {
        openModal('addModal');
    }

    // Open add modal jika ada validation error (dari old input)
    @if($errors->any() && old('_method') === null && old('material_code'))
        document.addEventListener('DOMContentLoaded', () => openModal('addModal'));
    @endif

    // ============================
    // EDIT MODAL
    // ============================
    function openEditModal(m) {
        document.getElementById('editForm').action = `/material-master/${m.id}`;
        document.getElementById('edit_material_code').value = m.material_code ?? '';
        document.getElementById('edit_name').value           = m.material_description ?? '';
        document.getElementById('edit_description').value   = m.long_text ?? '';
        document.getElementById('edit_uom').value           = m.base_uom ?? '';
        document.getElementById('edit_price').value         = m.price ?? '';
        openModal('editModal');
    }

    // ============================
    // DELETE MODAL
    // ============================
    function openDeleteModal(id, code, name) {
        document.getElementById('deleteForm').action = `/material-master/${id}`;
        document.getElementById('delete_code').textContent = code;
        document.getElementById('delete_name').textContent = name;
        openModal('deleteModal');
    }

    // Auto-dismiss alerts setelah 4 detik
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.alert').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            }, 4000);
        });
    });
</script>

@endsection