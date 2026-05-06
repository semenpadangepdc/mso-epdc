@extends('layouts.app')

@section('content')

<style>
    /* ============================================================================
       DASHBOARD INDEX - STYLESHEET
       Consistent palette with MSO Index (Red-based professional theme)
    ============================================================================ */

    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    /* === COLOR PALETTE (Same as MSO Index) === */
    :root {
        --primary-red: #DC2626;
        --dark-red: #991B1B;
        --light-red: #FEE2E2;
        --accent-red: #EF4444;
        --pure-white: #FFFFFF;
        --off-white: #F9FAFB;
        --dark-gray: #1F2937;
        --medium-gray: #6B7280;
        --light-gray: #E5E7EB;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.07);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.1);
        --shadow-red: 0 4px 16px rgba(220,38,38,0.2);
    }

    * { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ============================================================================
       LAYOUT
    ============================================================================ */

    .dashboard-container {
        background: linear-gradient(160deg, var(--off-white) 0%, #F3F4F6 100%);
        min-height: 100vh;
        padding: 2rem;
    }

    /* === PAGE HEADER === */
    .page-header {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        padding: 2rem 2.5rem;
        border-radius: 14px;
        box-shadow: var(--shadow-red);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
        pointer-events: none;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -60px; left: 30%;
        width: 300px; height: 160px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
        pointer-events: none;
    }

    .page-header-left { position: relative; z-index: 1; }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.2);
        letter-spacing: -0.5px;
    }

    .page-subtitle {
        margin: 0.4rem 0 0 0;
        opacity: 0.88;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .header-date {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        backdrop-filter: blur(4px);
        position: relative;
        z-index: 1;
    }

    /* ============================================================================
       FILTER CONTAINER (Same style as MSO index)
    ============================================================================ */

    .filter-container {
        background: var(--pure-white);
        padding: 1.25rem 1.5rem;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-red);
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .filter-label {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--dark-gray);
        white-space: nowrap;
    }

    .filter-select {
        border: 2px solid var(--light-gray);
        padding: 0.65rem 2.5rem 0.65rem 1rem;
        border-radius: 8px;
        background-color: var(--pure-white);
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23DC2626'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1.1rem;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--dark-gray);
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary-red);
        box-shadow: 0 0 0 3px var(--light-red);
    }

    .filter-select:hover {
        border-color: var(--primary-red);
        background-color: var(--off-white);
    }

    .btn-filter {
        background: var(--primary-red);
        color: var(--pure-white);
        padding: 0.65rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        background: var(--dark-red);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220,38,38,0.3);
    }

    .quick-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-left: auto;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.65rem 1.25rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-action.primary {
        background: linear-gradient(135deg, var(--primary-red), var(--dark-red));
        color: var(--pure-white);
        box-shadow: 0 3px 10px rgba(220,38,38,0.25);
    }

    .btn-action.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(220,38,38,0.35);
    }

    .btn-action.secondary {
        background: var(--pure-white);
        color: var(--primary-red);
        border: 2px solid var(--primary-red);
    }

    .btn-action.secondary:hover {
        background: var(--light-red);
        transform: translateY(-2px);
    }

    /* ============================================================================
       STAT CARDS
    ============================================================================ */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--pure-white);
        border-radius: 14px;
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--light-gray);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        animation: fadeSlideUp 0.5s ease both;
    }

    .stat-card:nth-child(1) { animation-delay: 0.05s; }
    .stat-card:nth-child(2) { animation-delay: 0.10s; }
    .stat-card:nth-child(3) { animation-delay: 0.15s; }
    .stat-card:nth-child(4) { animation-delay: 0.20s; }
    .stat-card:nth-child(5) { animation-delay: 0.25s; }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        border-radius: 14px 14px 0 0;
    }

    .stat-card.red::before    { background: linear-gradient(90deg, var(--primary-red), var(--accent-red)); }
    .stat-card.blue::before   { background: linear-gradient(90deg, #3B82F6, #60A5FA); }
    .stat-card.green::before  { background: linear-gradient(90deg, #16A34A, #4ADE80); }
    .stat-card.amber::before  { background: linear-gradient(90deg, #D97706, #FBBF24); }
    .stat-card.purple::before { background: linear-gradient(90deg, #7C3AED, #A78BFA); }

    .stat-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .stat-card.red    .stat-icon { background: var(--light-red); }
    .stat-card.blue   .stat-icon { background: #DBEAFE; }
    .stat-card.green  .stat-icon { background: #D1FAE5; }
    .stat-card.amber  .stat-icon { background: #FEF3C7; }
    .stat-card.purple .stat-icon { background: #EDE9FE; }

    .stat-value {
        font-size: 2rem; font-weight: 800;
        line-height: 1; margin-bottom: 0.35rem;
    }

    .stat-card.red    .stat-value { color: var(--primary-red); }
    .stat-card.blue   .stat-value { color: #2563EB; }
    .stat-card.green  .stat-value { color: #15803D; }
    .stat-card.amber  .stat-value { color: #B45309; }
    .stat-card.purple .stat-value { color: #6D28D9; }

    .stat-label {
        font-size: 0.8rem;
        color: var(--medium-gray);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* ============================================================================
       PANEL (Generic card with red header)
    ============================================================================ */

    .panel {
        background: var(--pure-white);
        border-radius: 14px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--light-gray);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .panel-header {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .panel-title {
        font-size: 1rem; font-weight: 700; margin: 0;
        display: flex; align-items: center; gap: 0.5rem;
    }

    .panel-body { padding: 1.5rem; }

    .panel-tag {
        font-size: 0.78rem; opacity: 0.85;
        background: rgba(255,255,255,0.15);
        padding: 0.2rem 0.75rem; border-radius: 4px; font-weight: 600;
    }

    /* ============================================================================
       TWO-COLUMN GRID
    ============================================================================ */

    .two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 0;
    }

    @media (max-width: 900px) { .two-col { grid-template-columns: 1fr; } }

    /* ============================================================================
       TOP 5 TABLES
    ============================================================================ */

    .top5-table {
        width: 100%; border-collapse: collapse;
    }

    .top5-table th {
        font-size: 0.75rem; font-weight: 700; color: var(--medium-gray);
        text-transform: uppercase; letter-spacing: 0.05em;
        padding: 0.6rem 0.75rem;
        border-bottom: 2px solid var(--light-gray);
        text-align: left;
    }

    .top5-table td {
        padding: 0.75rem;
        font-size: 0.875rem; color: var(--dark-gray);
        border-bottom: 1px solid var(--light-gray);
        vertical-align: middle;
    }

    .top5-table tbody tr:last-child td { border-bottom: none; }
    .top5-table tbody tr { transition: background 0.2s ease; }
    .top5-table tbody tr:hover { background: var(--light-red); }

    .rank-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 26px; height: 26px; border-radius: 50%;
        font-size: 0.75rem; font-weight: 800;
        background: var(--light-red); color: var(--primary-red);
    }

    .rank-badge.gold   { background: #FEF3C7; color: #B45309; }
    .rank-badge.silver { background: #F3F4F6; color: #4B5563; }
    .rank-badge.bronze { background: #FEF9C3; color: #78350F; }

    .mini-bar-wrap {
        flex: 1; height: 6px; background: var(--light-gray);
        border-radius: 10px; overflow: hidden;
    }

    .mini-bar {
        height: 100%; border-radius: 10px;
        animation: barGrow 1.2s cubic-bezier(0.4,0,0.2,1) both;
    }

    .bar-red    { background: linear-gradient(90deg, var(--primary-red), var(--accent-red)); }
    .bar-blue   { background: linear-gradient(90deg, #3B82F6, #60A5FA); }
    .bar-green  { background: linear-gradient(90deg, #16A34A, #4ADE80); }
    .bar-amber  { background: linear-gradient(90deg, #D97706, #FBBF24); }
    .bar-purple { background: linear-gradient(90deg, #7C3AED, #A78BFA); }

    /* ============================================================================
       PENDING TABLE
    ============================================================================ */

    .pending-table {
        width: 100%; border-collapse: separate; border-spacing: 0;
    }

    .pending-table thead {
        background: linear-gradient(135deg, var(--primary-red), var(--dark-red));
    }

    .pending-table th {
        padding: 0.875rem 1rem;
        text-align: left; font-size: 0.78rem; font-weight: 700;
        color: var(--pure-white);
        text-transform: uppercase; letter-spacing: 0.06em;
        white-space: nowrap;
        border-bottom: 3px solid var(--dark-red);
    }

    .pending-table tbody tr {
        transition: all 0.25s ease;
        border-bottom: 1px solid var(--light-gray);
    }

    .pending-table tbody tr:last-child { border-bottom: none; }

    .pending-table tbody tr:hover {
        background: var(--light-red);
        box-shadow: -4px 0 0 var(--primary-red) inset;
    }

    .pending-table td {
        padding: 0.875rem 1rem;
        font-size: 0.875rem; color: var(--dark-gray);
        vertical-align: middle;
    }

    .status-badge {
        display: inline-block;
        padding: 0.3rem 0.75rem; border-radius: 6px;
        font-weight: 600; font-size: 0.78rem; white-space: nowrap;
    }

    .status-open     { background: linear-gradient(135deg,#FEF3C7,#FDE68A); color:#92400E; border:1px solid #FCD34D; }
    .status-progress { background: linear-gradient(135deg,#DBEAFE,#BFDBFE); color:#1E40AF; border:1px solid #93C5FD; }
    .status-closed   { background: linear-gradient(135deg,#D1FAE5,#A7F3D0); color:#065F46; border:1px solid #6EE7B7; }

    /* ============================================================================
       AVAILABILITY CARDS
    ============================================================================ */

    .avail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1rem;
    }

    .avail-card {
        background: var(--pure-white);
        border: 2px solid var(--light-gray);
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .avail-card::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, var(--primary-red), var(--accent-red));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .avail-card:hover {
        border-color: var(--accent-red);
        transform: translateY(-3px);
        box-shadow: var(--shadow-red);
    }

    .avail-card:hover::after { transform: scaleX(1); }

    .avail-area {
        font-size: 0.78rem; font-weight: 700; color: var(--dark-gray);
        margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.04em;
    }

    .avail-pct {
        font-size: 1.875rem; font-weight: 800; line-height: 1; margin-bottom: 0.25rem;
    }

    .avail-good   { color: #15803D; }
    .avail-medium { color: #B45309; }
    .avail-low    { color: var(--primary-red); }

    .avail-sub { font-size: 0.72rem; color: var(--medium-gray); font-weight: 500; }

    .avail-bar-wrap {
        margin-top: 0.625rem;
        height: 5px; background: var(--light-gray);
        border-radius: 10px; overflow: hidden;
    }

    .avail-bar {
        height: 100%; border-radius: 10px;
        transition: width 1.5s cubic-bezier(0.4,0,0.2,1);
    }

    /* ============================================================================
       MAINTENANCE SUMMARY TABLE
    ============================================================================ */

    .maint-table { width: 100%; border-collapse: collapse; }

    .maint-table th {
        background: var(--off-white);
        font-size: 0.78rem; font-weight: 700;
        color: var(--medium-gray); text-transform: uppercase;
        letter-spacing: 0.05em; padding: 0.75rem 1rem;
        border-bottom: 2px solid var(--light-gray);
        white-space: nowrap;
    }

    .maint-table td {
        padding: 0.75rem 1rem; font-size: 0.875rem;
        color: var(--dark-gray); border-bottom: 1px solid var(--light-gray);
    }

    .maint-table tbody tr:last-child td { border-bottom: none; }
    .maint-table tbody tr { transition: background 0.2s ease; }
    .maint-table tbody tr:hover { background: var(--light-red); }

    .maint-num { font-weight: 700; color: var(--primary-red); }

    /* ============================================================================
       EMPTY STATE
    ============================================================================ */

    .empty-state {
        text-align: center; padding: 3rem 2rem; color: var(--medium-gray);
    }
    .empty-icon { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.5; display:block; }
    .empty-text { font-size: 0.95rem; font-weight: 600; }

    /* ============================================================================
       ANIMATIONS
    ============================================================================ */

    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes barGrow {
        from { width: 0 !important; }
    }

    /* ============================================================================
       RESPONSIVE
    ============================================================================ */

    @media (max-width: 768px) {
        .dashboard-container { padding: 1rem; }
        .page-title          { font-size: 1.5rem; }
        .stats-grid          { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
        .page-title { font-size: 1.25rem; }
    }

    /* ============================================================================
       MSO DETAIL MODAL
    ============================================================================ */

    .mso-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 9000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        backdrop-filter: blur(3px);
    }

    .mso-modal-overlay.active { display: flex; }

    .mso-modal {
        background: var(--pure-white);
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        width: 100%;
        max-width: 820px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        animation: modalIn 0.25s cubic-bezier(0.4,0,0.2,1);
        overflow: hidden;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.93) translateY(16px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .mso-modal-header {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        padding: 1.1rem 1.5rem;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-shrink: 0;
    }

    .mso-modal-title {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.3;
    }

    .mso-modal-subtitle {
        font-size: 0.78rem;
        opacity: 0.82;
        margin-top: 0.2rem;
        font-weight: 500;
    }

    .mso-modal-close {
        background: rgba(255,255,255,0.18);
        border: none;
        color: var(--pure-white);
        width: 30px; height: 30px;
        border-radius: 50%;
        font-size: 1.1rem;
        line-height: 1;
        cursor: pointer;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        transition: background 0.2s;
    }

    .mso-modal-close:hover { background: rgba(255,255,255,0.32); }

    .mso-modal-body {
        overflow-y: auto;
        padding: 1.25rem 1.5rem;
        flex: 1;
    }

    .mso-modal-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.83rem;
    }

    .mso-modal-table th {
        background: var(--off-white);
        color: var(--medium-gray);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.73rem;
        padding: 0.6rem 0.85rem;
        border-bottom: 2px solid var(--light-gray);
        white-space: nowrap;
        text-align: left;
    }

    .mso-modal-table td {
        padding: 0.7rem 0.85rem;
        border-bottom: 1px solid var(--light-gray);
        color: var(--dark-gray);
        vertical-align: middle;
    }

    .mso-modal-table tbody tr:last-child td { border-bottom: none; }
    .mso-modal-table tbody tr:hover { background: #FFF5F5; }

    .mso-modal-empty {
        text-align: center;
        padding: 2.5rem;
        color: var(--medium-gray);
        font-size: 0.9rem;
        font-weight: 600;
    }

    .mso-modal-footer {
        padding: 0.9rem 1.5rem;
        border-top: 1px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: var(--medium-gray);
        flex-shrink: 0;
        background: var(--off-white);
    }

    .btn-lihat {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.35rem 0.8rem;
        background: linear-gradient(135deg, var(--primary-red), var(--dark-red));
        color: var(--pure-white);
        border: none;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .btn-lihat:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(220,38,38,0.35);
    }</style>

<div class="dashboard-container">

    {{-- ====================================================================
         PAGE HEADER
    ==================================================================== --}}
    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">📊 Dashboard EPDC MSO</h1>
            <p class="page-subtitle">Ringkasan & Monitoring Performa Maintenance</p>
        </div>
        <div class="header-date">
            🗓️ {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    {{-- ====================================================================
         FILTER BAR
         Route name: 'dashboard' (bukan 'dashboard.index')
    ==================================================================== --}}
    <div class="filter-container">
        <span class="filter-label">🔍 Filter:</span>

        <form method="GET" action="{{ route('dashboard') }}" id="filterForm" class="d-flex flex-wrap gap-2 align-items-center" style="display:flex; flex-wrap:wrap; gap:0.75rem; align-items:center;">

            {{-- Period --}}
            <select name="period" id="periodSelect" class="filter-select">
                <option value="yearly"  {{ ($filters['period'] ?? 'yearly') == 'yearly'  ? 'selected' : '' }}>Tahunan</option>
                <option value="monthly" {{ ($filters['period'] ?? '') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                <option value="weekly"  {{ ($filters['period'] ?? '') == 'weekly'  ? 'selected' : '' }}>Mingguan</option>
            </select>

            {{-- Year --}}
            <select name="year" id="yearSelect" class="filter-select">
                @for($y = (int)date('Y'); $y >= 2022; $y--)
                    <option value="{{ $y }}" {{ (int)($filters['year'] ?? date('Y')) === $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            {{-- Month — always rendered, hidden when not monthly --}}
            <select name="month" id="monthSelect" class="filter-select"
                style="{{ ($filters['period'] ?? 'yearly') !== 'monthly' ? 'display:none;' : '' }}">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ (int)($filters['month'] ?? date('n')) === $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            {{-- Week — always rendered, hidden when not weekly --}}
            <select name="week" id="weekSelect" class="filter-select"
                style="{{ ($filters['period'] ?? 'yearly') !== 'weekly' ? 'display:none;' : '' }}">
                @for($i = 1; $i <= 53; $i++)
                    <option value="{{ $i }}" {{ (int)($filters['week'] ?? date('W')) === $i ? 'selected' : '' }}>
                        Week {{ $i }}
                    </option>
                @endfor
            </select>

            <button type="submit" class="btn-filter">🔍 Terapkan</button>

        </form>

        <script>
        (function() {
            var form         = document.getElementById('filterForm');
            var periodSelect = document.getElementById('periodSelect');
            var monthSelect  = document.getElementById('monthSelect');
            var weekSelect   = document.getElementById('weekSelect');

            function updateVisibility() {
                var period = periodSelect.value;

                var showMonth = (period === 'monthly');
                var showWeek  = (period === 'weekly');

                monthSelect.style.display = showMonth ? '' : 'none';
                weekSelect.style.display  = showWeek  ? '' : 'none';

                // Disable hidden fields so they are NOT submitted
                monthSelect.disabled = !showMonth;
                weekSelect.disabled  = !showWeek;
            }

            periodSelect.addEventListener('change', updateVisibility);

            // Also enforce on submit (safety net)
            form.addEventListener('submit', function() {
                var period = periodSelect.value;
                monthSelect.disabled = (period !== 'monthly');
                weekSelect.disabled  = (period !== 'weekly');
            });

            updateVisibility();
        })();
        </script>

        <div class="quick-actions">
            <a href="{{ route('mso.create') }}" class="btn-action primary">➕ Tambah MSO</a>
            <a href="{{ route('mso.index') }}"  class="btn-action secondary">📋 Lihat MSO</a>
        </div>
    </div>

    {{-- ====================================================================
         STAT CARDS
    ==================================================================== --}}
    @php
        // allMso = semua MSO On Progress + Closed (dari mso_list_summary, filter unitTypes + start_date)
        $allMso          = $mso_list_summary->flatten(1);
        $totalClosed     = $allMso->filter(fn($m) => strtolower($m->status_pekerjaan ?? '') === 'closed')->count();
        $totalOnProgress = $allMso->filter(fn($m) => strtolower($m->status_pekerjaan ?? '') === 'on progress')->count();
        // Pending = MSO Open, filter by created_at (query terpisah tanpa filter unitTypes)
        $totalPending    = $pending_abnormality->sum('total_pending');
        // Total MSO = gabungan ketiganya
        $totalMso        = $totalClosed + $totalOnProgress + $totalPending;
        $completePct     = $totalMso > 0 ? round(($totalClosed / $totalMso) * 100) : 0;
    @endphp

    <div class="stats-grid">

        <div class="stat-card red">
            <div class="stat-icon">📋</div>
            <div class="stat-value">{{ $totalMso }}</div>
            <div class="stat-label">Total MSO {{ $filters['year'] ?? date('Y') }}</div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon">✅</div>
            <div class="stat-value">{{ $totalClosed }}</div>
            <div class="stat-label">Pekerjaan Closed</div>
        </div>

        <div class="stat-card amber">
            <div class="stat-icon">⚠️</div>
            <div class="stat-value">{{ $totalPending }}</div>
            <div class="stat-label">Pending Abnormality</div>
        </div>

        <div class="stat-card blue">
            <div class="stat-icon">🔄</div>
            <div class="stat-value">{{ $totalOnProgress }}</div>
            <div class="stat-label">On Progress</div>
        </div>

        <div class="stat-card purple">
            <div class="stat-icon">📈</div>
            <div class="stat-value">{{ $completePct }}%</div>
            <div class="stat-label">Completion Rate</div>
        </div>

    </div>

    {{-- ====================================================================
         AVAILABILITY + TOP 5 FREKUENSI (TAHUNAN)
    ==================================================================== --}}
    <div class="two-col" style="margin-bottom:2rem;">

        {{-- AVAILABILITY PER AREA --}}
        <div class="panel" style="margin-bottom:0;">
            <div class="panel-header">
                <h2 class="panel-title">⚙️ Availability Main Filter</h2>
                <span class="panel-tag">
                    @if(($filters['period'] ?? 'yearly') === 'monthly')
                        {{ \Carbon\Carbon::create()->month($filters['month'] ?? date('n'))->translatedFormat('F') }}
                    @endif
                    {{ $filters['year'] ?? date('Y') }}
                </span>
            </div>
            <div class="panel-body">
                @if(count($availability))
                    <div class="avail-grid">
                        @foreach($availability as $avail)
                            @php
                                $pct      = $avail['availability'] ?? 0;
                                $cls      = $pct >= 90 ? 'avail-good' : ($pct >= 75 ? 'avail-medium' : 'avail-low');
                                $barClass = $pct >= 90 ? 'bar-green' : ($pct >= 75 ? 'bar-amber' : 'bar-red');
                            @endphp
                            <div class="avail-card">
                                <div class="avail-area">{{ $avail['area'] ?? '-' }}</div>
                                <div class="avail-pct {{ $cls }}">{{ number_format($pct, 1) }}%</div>
                                <div class="avail-sub">Availability</div>
                                <div class="avail-bar-wrap">
                                    <div class="avail-bar {{ $barClass }}" style="width:{{ min($pct,100) }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <span class="empty-icon">📭</span>
                        <div class="empty-text">Belum ada data availability</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- TOP 5 FREKUENSI TAHUNAN --}}
        <div class="panel" style="margin-bottom:0;">
            <div class="panel-header">
                <h2 class="panel-title">🏆 Top 5 Frekuensi Breakdown</h2>
                <span class="panel-tag">Tahunan {{ $filters['year'] ?? date('Y') }}</span>
            </div>
            <div class="panel-body">
                @if($top5_freq_year->count())
                    @php $maxFreq = $top5_freq_year->max('total') ?: 1; @endphp
                    <table class="top5-table">
                        <thead>
                            <tr><th>#</th><th>Nomenclature</th><th style="min-width:120px;">Total</th></tr>
                        </thead>
                        <tbody>
                            @foreach($top5_freq_year as $i => $item)
                                <tr>
                                    <td>
                                        <span class="rank-badge {{ $i==0?'gold':($i==1?'silver':($i==2?'bronze':'')) }}">
                                            {{ $i+1 }}
                                        </span>
                                    </td>
                                    <td>{{ $item->nomenclature->name ?? $item->name ?? $item->nomenclature_id ?? '-' }}</td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:0.5rem;">
                                            <div class="mini-bar-wrap">
                                                <div class="mini-bar bar-red" style="width:{{ round(($item->total/$maxFreq)*100) }}%;"></div>
                                            </div>
                                            <span style="font-size:0.78rem; font-weight:700; color:var(--primary-red); white-space:nowrap;">{{ $item->total }}x</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <span class="empty-icon">📭</span>
                        <div class="empty-text">Tidak ada data</div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ====================================================================
         TOP 5 FREKUENSI BULAN INI
    ==================================================================== --}}
    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title">🏅 Top 5 Frekuensi — Bulan Ini</h2>
            <span class="panel-tag">
                {{ \Carbon\Carbon::create()->month((int)($filters['month'] ?? date('n')))->translatedFormat('F') }}
                {{ $filters['year'] ?? date('Y') }}
            </span>
        </div>
        <div class="panel-body">
            @if($top5_freq_month->count())
                @php $maxFreqM = $top5_freq_month->max('total') ?: 1; @endphp
                <table class="top5-table">
                    <thead>
                        <tr><th>#</th><th>Nomenclature</th><th style="min-width:120px;">Total</th></tr>
                    </thead>
                    <tbody>
                        @foreach($top5_freq_month as $i => $item)
                            <tr>
                                <td>
                                    <span class="rank-badge {{ $i==0?'gold':($i==1?'silver':($i==2?'bronze':'')) }}">
                                        {{ $i+1 }}
                                    </span>
                                </td>
                                <td>{{ $item->nomenclature->name ?? $item->name ?? $item->nomenclature_id ?? '-' }}</td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:0.5rem;">
                                        <div class="mini-bar-wrap">
                                            <div class="mini-bar bar-amber" style="width:{{ round(($item->total/$maxFreqM)*100) }}%;"></div>
                                        </div>
                                        <span style="font-size:0.78rem; font-weight:700; color:#B45309; white-space:nowrap;">{{ $item->total }}x</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <span class="empty-icon">📭</span>
                    <div class="empty-text">Tidak ada data frekuensi bulan ini</div>
                </div>
            @endif
        </div>
    </div>

    {{-- ====================================================================
         TOP 5 DURASI BULAN INI + TAHUNAN
    ==================================================================== --}}
    <div class="two-col" style="margin-bottom:2rem;">

        {{-- TOP 5 DURASI BULAN INI --}}
        <div class="panel" style="margin-bottom:0;">
            <div class="panel-header">
                <h2 class="panel-title">⏱️ Top 5 Durasi — Bulan Ini</h2>
                <span class="panel-tag">
                    {{ \Carbon\Carbon::create()->month((int)($filters['month'] ?? date('n')))->translatedFormat('F') }}
                    {{ $filters['year'] ?? date('Y') }}
                </span>
            </div>
            <div class="panel-body">
                @if($top5_dur_month->count())
                    @php $maxDur = $top5_dur_month->max('total_duration') ?: 1; @endphp
                    <table class="top5-table">
                        <thead>
                            <tr><th>#</th><th>Nomenclature</th><th style="min-width:130px;">Durasi</th></tr>
                        </thead>
                        <tbody>
                            @foreach($top5_dur_month as $i => $item)
                                <tr>
                                    <td>
                                        <span class="rank-badge {{ $i==0?'gold':($i==1?'silver':($i==2?'bronze':'')) }}">
                                            {{ $i+1 }}
                                        </span>
                                    </td>
                                    <td>{{ $item->nomenclature->name ?? $item->nomenclature_id }}</td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:0.5rem;">
                                            <div class="mini-bar-wrap">
                                                <div class="mini-bar bar-blue" style="width:{{ round(($item->total_duration/$maxDur)*100) }}%;"></div>
                                            </div>
                                            <span style="font-size:0.78rem; font-weight:700; color:#2563EB; white-space:nowrap;">
                                                {{ number_format($item->total_duration, 1) }}h
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <span class="empty-icon">📭</span>
                        <div class="empty-text">Tidak ada data bulan ini</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- TOP 5 DURASI TAHUNAN --}}
        <div class="panel" style="margin-bottom:0;">
            <div class="panel-header">
                <h2 class="panel-title">⏱️ Top 5 Durasi — Tahunan</h2>
                <span class="panel-tag">{{ $filters['year'] ?? date('Y') }}</span>
            </div>
            <div class="panel-body">
                @if($top5_dur_year->count())
                    @php $maxDurY = $top5_dur_year->max('total_duration') ?: 1; @endphp
                    <table class="top5-table">
                        <thead>
                            <tr><th>#</th><th>Nomenclature</th><th style="min-width:130px;">Durasi</th></tr>
                        </thead>
                        <tbody>
                            @foreach($top5_dur_year as $i => $item)
                                <tr>
                                    <td>
                                        <span class="rank-badge {{ $i==0?'gold':($i==1?'silver':($i==2?'bronze':'')) }}">
                                            {{ $i+1 }}
                                        </span>
                                    </td>
                                    <td>{{ $item->nomenclature->name ?? $item->nomenclature_id }}</td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:0.5rem;">
                                            <div class="mini-bar-wrap">
                                                <div class="mini-bar bar-purple" style="width:{{ round(($item->total_duration/$maxDurY)*100) }}%;"></div>
                                            </div>
                                            <span style="font-size:0.78rem; font-weight:700; color:#6D28D9; white-space:nowrap;">
                                                {{ number_format($item->total_duration, 1) }}h
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <span class="empty-icon">📭</span>
                        <div class="empty-text">Tidak ada data</div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ====================================================================
         PENDING ABNORMALITY TABLE
    ==================================================================== --}}
    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title">⚠️ Pending Abnormality</h2>
            <span class="panel-tag">{{ $pending_abnormality->sum('total_pending') }} item</span>
        </div>
        <div style="overflow-x:auto;">
            @if($pending_abnormality->count())
                <table class="pending-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Area</th>
                            <th>Nomenclature</th>
                            <th>Pending</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending_abnormality as $i => $item)
                            @php
                                $groupKey   = $item->area_id . '_' . $item->nomenclature_id;
                                $msoGroup   = $mso_list_pending->get($groupKey, collect());
                                $msoJson    = $msoGroup->map(fn($m) => [
                                    'no_mso'       => $m->no_mso ?? '-',
                                    'start_date'   => $m->start_date  ? \Carbon\Carbon::parse($m->start_date)->format('d M Y')  : '-',
                                    'finish_date'  => $m->finish_date ? \Carbon\Carbon::parse($m->finish_date)->format('d M Y') : '-',
                                    'duration'     => $m->total_duration ? number_format($m->total_duration, 1).'h' : '-',
                                    'status'       => $m->status_pekerjaan ?? '-',
                                    'type'         => $m->maintenanceType->name ?? $m->maintenance_type_id ?? '-',
                                    'description'  => $m->description ?? '-',
                                ])->values()->toJson();
                                $modalTitle = ($item->nomenclature->name ?? $item->nomenclature_id ?? '-')
                                            . ' — ' . ($item->area->name ?? $item->area_id ?? '-');
                            @endphp
                            <tr>
                                <td style="color:var(--medium-gray); font-size:0.8rem; width:40px;">{{ $i+1 }}</td>
                                <td>{{ $item->area->name ?? $item->area_id ?? '-' }}</td>
                                <td><strong>{{ $item->nomenclature->name ?? $item->nomenclature_id ?? '-' }}</strong></td>
                                <td>
                                    <span class="status-badge status-open">⚠️ {{ $item->total_pending }}</span>
                                </td>
                                <td style="text-align:center;">
                                    <button class="btn-lihat"
                                        onclick="openMsoModal({{ json_encode($modalTitle) }}, 'Pending Abnormality', {{ $msoJson }})">
                                        👁️ Lihat
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <span class="empty-icon">✅</span>
                    <div class="empty-text">Tidak ada pending abnormality</div>
                </div>
            @endif
        </div>
    </div>

    {{-- ====================================================================
         MAINTENANCE SUMMARY TABLE
    ==================================================================== --}}
    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title">🔧 Maintenance Summary</h2>
            <span class="panel-tag">{{ $filters['year'] ?? date('Y') }}</span>
        </div>
        <div style="overflow-x:auto;">
            @if($maintenance_summary->count())
                <table class="maint-table">
                    <thead>
                        <tr>
                            <th>Nomenclature</th>
                            <th>Jenis Maintenance</th>
                            <th>Area</th>
                            <th style="text-align:center;">Total</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($maintenance_summary as $row)
                            @php
                                $groupKey   = $row->area_id . '_' . $row->nomenclature_id . '_' . $row->maintenance_type_id;
                                $msoGroup   = $mso_list_summary->get($groupKey, collect());
                                $msoJson    = $msoGroup->map(fn($m) => [
                                    'no_mso'      => $m->no_mso ?? '-',
                                    'start_date'  => $m->start_date  ? \Carbon\Carbon::parse($m->start_date)->format('d M Y')  : '-',
                                    'finish_date' => $m->finish_date ? \Carbon\Carbon::parse($m->finish_date)->format('d M Y') : '-',
                                    'duration'    => $m->total_duration ? number_format($m->total_duration, 1).'h' : '-',
                                    'status'      => $m->status_pekerjaan ?? '-',
                                    'type'        => $m->maintenanceType->name ?? $m->maintenance_type_id ?? '-',
                                    'description' => $m->description ?? '-',
                                ])->values()->toJson();
                                $modalTitle = ($row->nomenclature->name ?? $row->nomenclature_id ?? '-')
                                           . ' — ' . ($row->area->name ?? $row->area_id ?? '-');
                                $modalSub   = $row->maintenanceType->name ?? $row->maintenance_type_id ?? '-';
                            @endphp
                            <tr>
                                <td>{{ $row->nomenclature->name ?? $row->nomenclature_id ?? '-' }}</td>
                                <td>{{ $row->maintenanceType->name ?? $row->maintenance_type_id ?? '-' }}</td>
                                <td>{{ $row->area->name ?? $row->area_id ?? '-' }}</td>
                                <td style="text-align:center;"><span class="maint-num">{{ $row->total }}</span></td>
                                <td style="text-align:center;">
                                    <button class="btn-lihat"
                                        onclick="openMsoModal({{ json_encode($modalTitle) }}, {{ json_encode($modalSub) }}, {{ $msoJson }})">
                                        👁️ Lihat
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:var(--off-white);">
                            <td colspan="4" style="padding:0.75rem 1rem; font-size:0.875rem; font-weight:700; color:var(--dark-gray);">
                                Total Keseluruhan
                            </td>
                            <td style="padding:0.75rem 1rem; text-align:center;">
                                <span class="maint-num" style="font-size:1.05rem;">{{ $maintenance_summary->sum('total') }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div class="empty-state">
                    <span class="empty-icon">📭</span>
                    <div class="empty-text">Belum ada data maintenance</div>
                </div>
            @endif
        </div>
    </div>

</div>

{{-- ====================================================================
     MSO DETAIL MODAL
==================================================================== --}}
<div class="mso-modal-overlay" id="msoModalOverlay" onclick="closeMsoModalOnOverlay(event)">
    <div class="mso-modal" role="dialog" aria-modal="true" aria-labelledby="msoModalTitle">
        <div class="mso-modal-header">
            <div>
                <p class="mso-modal-title" id="msoModalTitle">Detail MSO</p>
                <p class="mso-modal-subtitle" id="msoModalSubtitle"></p>
            </div>
            <button class="mso-modal-close" onclick="closeMsoModal()" title="Tutup">✕</button>
        </div>
        <div class="mso-modal-body">
            <div id="msoModalContent"></div>
        </div>
        <div class="mso-modal-footer">
            <span id="msoModalCount" style="font-weight:600;"></span>
            <button onclick="closeMsoModal()" style="padding:0.45rem 1.1rem; border-radius:7px; border:2px solid var(--primary-red); background:transparent; color:var(--primary-red); font-weight:700; cursor:pointer; font-size:0.82rem;">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    var overlay = document.getElementById('msoModalOverlay');

    window.openMsoModal = function (title, subtitle, msoList) {
        document.getElementById('msoModalTitle').textContent    = title;
        document.getElementById('msoModalSubtitle').textContent = subtitle;
        document.getElementById('msoModalCount').textContent    = msoList.length + ' MSO ditemukan';

        var content = document.getElementById('msoModalContent');

        if (!msoList || msoList.length === 0) {
            content.innerHTML = '<div class="mso-modal-empty">📭 Tidak ada data MSO untuk grup ini.</div>';
        } else {
            var statusClass = function (s) {
                var sl = (s || '').toLowerCase();
                if (sl === 'closed')   return 'status-closed';
                if (sl === 'progress' || sl === 'in progress' || sl === 'on progress') return 'status-progress';
                return 'status-open';
            };

            var rows = msoList.map(function (m, i) {
                return '<tr>' +
                    '<td style="color:var(--medium-gray);font-size:0.78rem;width:32px;">' + (i + 1) + '</td>' +
                    '<td><strong>' + m.no_mso + '</strong></td>' +
                    '<td>' + m.type + '</td>' +
                    '<td>' + m.start_date + '</td>' +
                    '<td>' + m.finish_date + '</td>' +
                    '<td style="text-align:right;">' + m.duration + '</td>' +
                    '<td><span class="status-badge ' + statusClass(m.status) + '">' + m.status + '</span></td>' +
                '</tr>';
            }).join('');

            content.innerHTML =
                '<table class="mso-modal-table">' +
                    '<thead><tr>' +
                        '<th>#</th>' +
                        '<th>No. MSO</th>' +
                        '<th>Jenis</th>' +
                        '<th>Tgl Mulai</th>' +
                        '<th>Tgl Selesai</th>' +
                        '<th style="text-align:right;">Durasi</th>' +
                        '<th>Status</th>' +
                    '</tr></thead>' +
                    '<tbody>' + rows + '</tbody>' +
                '</table>';
        }

        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    window.closeMsoModal = function () {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    };

    window.closeMsoModalOnOverlay = function (e) {
        if (e.target === overlay) closeMsoModal();
    };

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMsoModal();
    });
})();
</script>

@endsection