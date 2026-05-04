@extends('layouts.app')

@section('content')

{{-- ============================================================
     MONITORING MATERIAL - INDEX
     Font & typography mengikuti layouts.app (sama dengan MSO Index)
     ============================================================ --}}

<style>
    /* === COLOR PALETTE (sama dengan MSO Index) === */
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
    }

    /* === MAIN CONTAINER === */
    .mon-container {
        background: linear-gradient(135deg, var(--off-white) 0%, var(--pure-white) 100%);
        min-height: 100vh;
        padding: 2rem;
    }

    /* === PAGE HEADER (identik dengan MSO Index) === */
    .page-header {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(220, 38, 38, 0.2);
        margin-bottom: 2rem;
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

    /* === FILTER CONTAINER (identik dengan MSO Index) === */
    .filter-container {
        background: var(--pure-white);
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-red);
    }

    .filter-label {
        font-size: 0.813rem;
        font-weight: 600;
        color: var(--dark-gray);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: block;
        margin-bottom: 0.5rem;
    }

    /* Search Input (identik dengan MSO Index) */
    .search-input {
        border: 2px solid var(--light-gray);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        width: 320px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        color: var(--dark-gray);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-red);
        box-shadow: 0 0 0 3px var(--light-red);
    }

    /* Search Button (identik dengan MSO Index) */
    .btn-search {
        background: var(--primary-red);
        color: var(--pure-white);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-search:hover {
        background: var(--dark-red);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    /* Reset Button */
    .btn-reset {
        background: var(--medium-gray);
        color: var(--pure-white);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-reset:hover {
        background: var(--dark-gray);
        transform: translateY(-2px);
        color: var(--pure-white);
    }

    /* === SCROLL TABLE WRAPPER (identik dengan MSO Index) === */
    .scroll-wrapper {
        width: 100%;
        overflow-x: auto;
        overflow-y: visible;
        background: var(--pure-white);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 2px solid var(--light-gray);
        position: relative;
    }

    .scroll-wrapper::-webkit-scrollbar        { height: 16px; }
    .scroll-wrapper::-webkit-scrollbar-track  { background: var(--light-gray); border-radius: 8px; margin: 0 10px; }
    .scroll-wrapper::-webkit-scrollbar-thumb  { background: var(--primary-red); border-radius: 8px; border: 3px solid var(--light-gray); }
    .scroll-wrapper::-webkit-scrollbar-thumb:hover { background: var(--dark-red); }

    /* === SECTION TITLE (di atas table) === */
    .section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--dark-gray);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .section-title::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 1.25rem;
        background: var(--primary-red);
        border-radius: 2px;
    }

    /* === TABLE (identik dengan MSO Index) === */
    .mon-table {
        min-width: 1200px;
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
    }

    /* === TFOOT — TOTAL ANGGARAN === */
    .mon-table tfoot tr {
        background: linear-gradient(135deg, var(--dark-gray) 0%, #374151 100%);
    }

    .mon-table tfoot td {
        padding: 1rem;
        color: var(--pure-white);
        font-size: 0.875rem;
        font-weight: 700;
        border: none;
    }

    /* === BADGES === */
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

    /* === ACTION BUTTONS (identik dengan MSO Index) === */
    .btn-detail {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: var(--pure-white);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.813rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        white-space: nowrap;
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.4);
        color: var(--pure-white);
    }

    /* === EMPTY STATE === */
    .empty-state {
        padding: 3rem;
        text-align: center;
        color: var(--medium-gray);
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .empty-state-text {
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* === ALERT (identik dengan MSO Index) === */
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

    /* === ID TRANS CHIP === */
    .id-trans-chip {
        background: var(--light-red);
        color: var(--dark-red);
        border: 1px solid var(--accent-red);
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        font-size: 0.813rem;
        font-weight: 700;
        letter-spacing: 0.03em;
    }


    @keyframes fadeInOut {
        0%, 100% { opacity: 0; }
        50%       { opacity: 1; }
    }
</style>

<div class="mon-container">

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap; justify-content:space-between;">
            <div>
                <h1 class="page-title">🔩 Monitoring Permintaan Material / Jasa</h1>
                <p class="page-subtitle">Kelola dan pantau data material secara real-time</p>
            </div>
            <div style="font-size:0.813rem; opacity:0.85; font-weight:500;">
                Total Anggaran: <strong style="font-size:1rem;">Rp {{ number_format($total, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- FILTER --}}
    <div class="filter-container">
        <form method="GET" action="{{ route('monitoring.index') }}" style="display:flex; align-items:flex-end; gap:1rem; flex-wrap:wrap;">
            <div>
                <label class="filter-label">🔍 Cari Material Master</label>
                <input name="material_master"
                       class="search-input"
                       placeholder="Ketik kode / nama Material Master..."
                       value="{{ request('material_master') }}">
            </div>
            <div>
                <button type="submit" class="btn-search">
                    🔎 Filter
                </button>
            </div>
            @if(request('material_master'))
            <div>
                <a href="{{ route('monitoring.index') }}" class="btn-reset">
                    ✖ Reset
                </a>
            </div>
            @endif
        </form>
    </div>

    {{-- TABLE --}}
    <div class="section-title">Data Material</div>

    <div class="scroll-wrapper">
        <table class="mon-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID Trans</th>
                    <th>Nomenclature</th>
                    <th>Komponen</th>
                    <th>Temuan / Abnormality</th>
                    <th>Action</th>
                    <th>Material Master</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data->unique('trans_id') as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><span class="id-trans-chip">{{ $row->trans_id }}</span></td>
                    <td style="font-weight:500;">{{ $row->nomenclature ?? '-' }}</td>
                    <td>{{ $row->component ?? '-' }}</td>
                    <td>{{ $row->abnormality ?? '-' }}</td>
                    <td>{{ $row->action ?? '-' }}</td>
                    <td style="font-weight:600; color:var(--dark-red);">{{ $row->material_master ?? '-' }}</td>
                    <td style="text-align:center;">
                        {{-- Tombol Detail: semua role yang login boleh melihat --}}
                        <a href="{{ route('monitoring.detail', $row->trans_id) }}"
                           class="btn-detail">
                            📋 Detail
                        </a>

                        {{-- Tombol Export: hanya Admin dan Supervisor --}}
                        @canany(['Admin', 'Supervisor'])
                            <a href="{{ route('monitoring.export', $row->trans_id) }}"
                               class="btn-detail"
                               style="background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); margin-left: 0.25rem;">
                                📤 Export
                            </a>
                        @endcanany
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-state-icon">📭</div>
                            <div class="empty-state-text">Belum ada data monitoring material.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6"><strong>Total Anggaran Keseluruhan</strong></td>
                    <td colspan="2"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>

@endsection