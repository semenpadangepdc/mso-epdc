@extends('layouts.app')

@section('content')

<style>
    /* ============================================================================
    NOMENCLATURE INDEX - STYLESHEET
    Matching MSO / Specification visual style
    ============================================================================ */

    /* === COLOR PALETTE === */
    :root {
        --primary-red:   #DC2626;
        --dark-red:      #991B1B;
        --light-red:     #FEE2E2;
        --accent-red:    #EF4444;
        --pure-white:    #FFFFFF;
        --off-white:     #F9FAFB;
        --dark-gray:     #1F2937;
        --medium-gray:   #6B7280;
        --light-gray:    #E5E7EB;

        /* Status Colors */
        --status-broken-bg:      #FEF2F2;
        --status-broken-border:  #DC2626;
        --status-broken-text:    #991B1B;
        --status-broken-badge:   #FEE2E2;

        --status-standby-bg:     #FFFBEB;
        --status-standby-border: #D97706;
        --status-standby-text:   #92400E;
        --status-standby-badge:  #FDE68A;

        --status-active-bg:      #F0FDF4;
        --status-active-border:  #16A34A;
        --status-active-text:    #14532D;
        --status-active-badge:   #BBF7D0;

        --status-unknown-bg:     var(--pure-white);
        --status-unknown-border: var(--light-gray);
        --status-unknown-text:   var(--medium-gray);
        --status-unknown-badge:  var(--light-gray);
    }

    /* === MAIN CONTAINER === */
    .nomen-container {
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
        font-size: 0.95rem;
        opacity: 0.85;
        margin-top: 0.25rem;
    }

    /* === LEGEND === */
    .legend-bar {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
        background: var(--pure-white);
        border: 2px solid var(--light-gray);
        border-radius: 10px;
        padding: 0.75rem 1.25rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }

    .legend-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--medium-gray);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-right: 0.25rem;
        white-space: nowrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        flex-shrink: 0;
    }

    .legend-dot.broken  { background: var(--status-broken-border); }
    .legend-dot.standby { background: var(--status-standby-border); }
    .legend-dot.active  { background: var(--status-active-border); }
    .legend-dot.unknown { background: var(--light-gray); border: 1px solid #ccc; }

    /* === SECTION GROUP (per Indarung) === */
    .group-card {
        background: var(--pure-white);
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-red);
    }

    .group-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--dark-gray);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--light-gray);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .group-title::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 1.1rem;
        background: var(--primary-red);
        border-radius: 2px;
        flex-shrink: 0;
    }

    /* === AREA SUB-SECTION === */
    .area-section {
        margin-bottom: 1.75rem;
    }

    .area-section:last-child {
        margin-bottom: 0;
    }

    .area-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--medium-gray);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .area-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--light-gray);
    }

    /* === NOMENCLATURE CARD GRID === */
    .nomen-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1rem;
    }

    /* === NOMENCLATURE CARD === */
    .nomen-card {
        background: var(--pure-white);
        border: 2px solid var(--light-gray);
        border-radius: 10px;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }

    /* Left accent bar — default gray */
    .nomen-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 5px;
        height: 100%;
        background: var(--light-gray);
        transition: background 0.3s ease;
    }

    /* ── STATUS VARIANTS ── */

    /* Not Aktif (Broken/Eliminated) */
    .nomen-card.status-broken {
        background: var(--status-broken-bg);
        border-color: #FECACA;
    }
    .nomen-card.status-broken::before {
        background: var(--status-broken-border);
    }
    .nomen-card.status-broken:hover {
        border-color: var(--status-broken-border);
        box-shadow: 0 4px 16px rgba(220, 38, 38, 0.18);
    }

    /* Ready Standby */
    .nomen-card.status-standby {
        background: var(--status-standby-bg);
        border-color: #FDE68A;
    }
    .nomen-card.status-standby::before {
        background: var(--status-standby-border);
    }
    .nomen-card.status-standby:hover {
        border-color: var(--status-standby-border);
        box-shadow: 0 4px 16px rgba(217, 119, 6, 0.18);
    }

    /* Active Operation */
    .nomen-card.status-active {
        background: var(--status-active-bg);
        border-color: #BBF7D0;
    }
    .nomen-card.status-active::before {
        background: var(--status-active-border);
    }
    .nomen-card.status-active:hover {
        border-color: var(--status-active-border);
        box-shadow: 0 4px 16px rgba(22, 163, 74, 0.18);
    }

    /* Hover lift — all cards */
    .nomen-card:hover {
        transform: translateY(-3px);
    }

    /* === CARD CONTENT === */
    .nomen-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--dark-gray);
        margin-bottom: 0.5rem;
        padding-left: 0.5rem;
    }

    .nomen-card-meta {
        font-size: 0.8rem;
        color: var(--medium-gray);
        margin-bottom: 0.25rem;
        padding-left: 0.5rem;
        line-height: 1.5;
    }

    .nomen-card-meta strong {
        color: var(--dark-gray);
    }

    .nomen-card-desc {
        font-size: 0.8rem;
        color: var(--medium-gray);
        padding-left: 0.5rem;
        margin-bottom: 1rem;
        line-height: 1.5;
        flex: 1;
    }

    /* === STATUS BADGE === */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.7rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-left: 0.5rem;
        margin-bottom: 0.75rem;
        width: fit-content;
    }

    .status-badge .badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .badge-broken {
        background: var(--status-broken-badge);
        color: var(--status-broken-text);
    }
    .badge-broken .badge-dot {
        background: var(--status-broken-border);
    }

    .badge-standby {
        background: var(--status-standby-badge);
        color: var(--status-standby-text);
    }
    .badge-standby .badge-dot {
        background: var(--status-standby-border);
    }

    .badge-active {
        background: var(--status-active-badge);
        color: var(--status-active-text);
    }
    .badge-active .badge-dot {
        background: var(--status-active-border);
    }

    .badge-unknown {
        background: var(--status-unknown-badge);
        color: var(--status-unknown-text);
    }
    .badge-unknown .badge-dot {
        background: #9CA3AF;
    }

    /* === SPEC BUTTON === */
    .btn-spec {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        padding: 0.6rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.25);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-top: auto;
    }

    .btn-spec:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
        color: var(--pure-white);
        text-decoration: none;
    }

    /* === EMPTY STATE === */
    .data-empty {
        text-align: center;
        padding: 3rem;
        color: var(--medium-gray);
        font-size: 1.125rem;
    }

    /* === RESPONSIVE === */
    @media (max-width: 768px) {
        .nomen-container { padding: 1rem; }
        .page-header { padding: 1.5rem; }
        .page-title { font-size: 1.5rem; }
        .nomen-grid { grid-template-columns: 1fr; }
        .legend-bar { gap: 0.75rem; }
    }
</style>

<div class="nomen-container">
    <div style="max-width: 1200px; margin: 0 auto;">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Nomenclature List</h1>
                <p class="page-subtitle">Daftar seluruh nomenclature berdasarkan Indarung dan Area</p>
            </div>
        </div>

        {{-- Legend --}}
        <div class="legend-bar">
            <span class="legend-label">Keterangan:</span>
            <div class="legend-item">
                <div class="legend-dot active"></div>
                <span style="color: var(--status-active-text);">Active Operation</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot standby"></div>
                <span style="color: var(--status-standby-text);">Ready Standby</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot broken"></div>
                <span style="color: var(--status-broken-text);">Not Aktif (Broken/Eliminated)</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot unknown"></div>
                <span style="color: var(--medium-gray);">Tidak Diketahui</span>
            </div>
        </div>

        @forelse($nomenclatures as $indarung => $areas)
            <div class="group-card">

                <div class="group-title">
                    Indarung {{ $indarung }}
                </div>

                @foreach($areas as $areaName => $items)
                    <div class="area-section">

                        <div class="area-label">
                            Area: {{ $areaName }}
                        </div>

                        <div class="nomen-grid">
                            @foreach($items as $nomen)

                                @php
                                    $status = $nomen->default_status ?? '';
                                    if ($status === 'Active Operation') {
                                        $cardClass   = 'status-active';
                                        $badgeClass  = 'badge-active';
                                        $badgeLabel  = 'Active Operation';
                                    } elseif ($status === 'Ready Standby') {
                                        $cardClass   = 'status-standby';
                                        $badgeClass  = 'badge-standby';
                                        $badgeLabel  = 'Ready Standby';
                                    } elseif ($status === 'Not Aktif (Broken/Eliminated)') {
                                        $cardClass   = 'status-broken';
                                        $badgeClass  = 'badge-broken';
                                        $badgeLabel  = 'Not Aktif';
                                    } else {
                                        $cardClass   = '';
                                        $badgeClass  = 'badge-unknown';
                                        $badgeLabel  = 'Tidak Diketahui';
                                    }
                                @endphp

                                <div class="nomen-card {{ $cardClass }}">

                                    <div class="nomen-card-title">{{ $nomen->name }}</div>

                                    {{-- Status Badge --}}
                                    <span class="status-badge {{ $badgeClass }}">
                                        <span class="badge-dot"></span>
                                        {{ $badgeLabel }}
                                    </span>

                                    <div class="nomen-card-meta">
                                        <strong>Type:</strong> {{ $nomen->type ?? '-' }}
                                    </div>

                                    <div class="nomen-card-desc">
                                        <strong>Deskripsi:</strong><br>
                                        {{ Str::limit($nomen->description, 80) }}
                                    </div>

                                    <a href="{{ route('nomenclatures.specification', $nomen) }}"
                                       class="btn-spec">
                                        &#9881; Lihat Spesifikasi
                                    </a>

                                </div>
                            @endforeach
                        </div>

                    </div>
                @endforeach

            </div>
        @empty
            <div class="group-card">
                <div class="data-empty">
                    <svg style="width:3rem;height:3rem;color:#FCD34D;margin:0 auto 1rem;display:block;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Tidak ada data nomenclature tersedia.
                </div>
            </div>
        @endforelse

    </div>
</div>

@endsection