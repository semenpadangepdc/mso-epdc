@extends('layouts.app')

@section('content')

<style>
    /* ============================================================================
       DASHBOARD - PROFESSIONAL STYLESHEET
    ============================================================================ */
    @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap');

    :root {
        --primary: #DC2626;
        --primary-dark: #991B1B;
        --primary-light: #FEE2E2;
        --primary-soft: #FEF2F2;
        --gray-50: #F9FAFB;
        --gray-100: #F3F4F6;
        --gray-200: #E5E7EB;
        --gray-300: #D1D5DB;
        --gray-400: #9CA3AF;
        --gray-500: #6B7280;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-800: #1F2937;
        --gray-900: #111827;
        --success: #10B981;
        --warning: #F59E0B;
        --info: #3B82F6;
        --purple: #8B5CF6;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        --radius-sm: 0.5rem;
        --radius-md: 0.75rem;
        --radius-lg: 1rem;
    }

    * {
        font-family: 'Inter', sans-serif;
    }

    body {
        background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%);
    }

    /* Dashboard Container */
    .dashboard-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    /* Header */
    .dashboard-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-xl);
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        pointer-events: none;
    }

    .dashboard-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.03);
        border-radius: 50%;
        pointer-events: none;
    }

    .header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-title h1 {
        font-size: 1.875rem;
        font-weight: 800;
        margin: 0;
        letter-spacing: -0.025em;
        color: white;
    }

    .header-title p {
        margin: 0.25rem 0 0;
        color: rgba(255,255,255,0.85);
        font-size: 0.875rem;
    }

    .header-date {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(4px);
        padding: 0.5rem 1rem;
        border-radius: 40px;
        font-size: 0.875rem;
        font-weight: 500;
        color: white;
    }

    /* Filter Bar */
    .filter-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-md);
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        border-left: 4px solid var(--primary);
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .filter-select {
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-sm);
        padding: 0.5rem 2rem 0.5rem 0.75rem;
        background-color: white;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray-700);
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary);
        ring: 2px solid var(--primary-light);
    }

    .btn-apply {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius-sm);
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-apply:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    .quick-actions {
        margin-left: auto;
        display: flex;
        gap: 0.75rem;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-outline {
        border: 1px solid var(--primary);
        color: var(--primary);
        background: white;
    }

    .btn-outline:hover {
        background: var(--primary-light);
        transform: translateY(-2px);
    }

    /* Stat Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background: white;
        border-radius: var(--radius-md);
        padding: 1.25rem;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        border: 1px solid var(--gray-100);
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--primary-dark));
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        background: var(--primary-light);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--gray-800);
        line-height: 1.2;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--gray-500);
    }

    /* Panel */
    .panel {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-100);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .panel-header {
        background: white;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .panel-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--gray-800);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .panel-badge {
        background: var(--primary-light);
        color: var(--primary-dark);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .panel-body {
        padding: 1.5rem;
    }

    /* Two Column Layout */
    .two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .two-col {
            grid-template-columns: 1fr;
        }
    }

    /* Availability Cards */
    .avail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }

    .avail-card {
        background: var(--gray-50);
        border-radius: var(--radius-sm);
        padding: 1rem;
        text-align: center;
        transition: all 0.2s;
        border: 1px solid var(--gray-200);
    }

    .avail-card:hover {
        transform: translateY(-2px);
        border-color: var(--primary-light);
        box-shadow: var(--shadow-sm);
    }

    .avail-area {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
    }

    .avail-pct {
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1;
    }

    .avail-good { color: var(--success); }
    .avail-medium { color: var(--warning); }
    .avail-low { color: var(--primary); }

    .avail-bar-wrap {
        margin-top: 0.75rem;
        height: 6px;
        background: var(--gray-200);
        border-radius: 3px;
        overflow: hidden;
    }

    .avail-bar {
        height: 100%;
        border-radius: 3px;
        transition: width 1s ease;
    }

    /* Tables */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        text-align: left;
        padding: 0.75rem 1rem;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--gray-500);
        background: var(--gray-50);
        border-bottom: 1px solid var(--gray-200);
    }

    .data-table td {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        color: var(--gray-700);
        border-bottom: 1px solid var(--gray-100);
    }

    .data-table tbody tr:hover {
        background: var(--primary-soft);
    }

    .rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-size: 0.75rem;
        font-weight: 800;
    }

    .rank-gold { background: #FEF3C7; color: #D97706; }
    .rank-silver { background: #F3F4F6; color: #4B5563; }
    .rank-bronze { background: #FEF9C3; color: #78350F; }

    .mini-bar-wrap {
        flex: 1;
        height: 6px;
        background: var(--gray-200);
        border-radius: 3px;
        overflow: hidden;
    }

    .mini-bar {
        height: 100%;
        border-radius: 3px;
    }

    /* Status Badges */
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .status-open { background: #FEF3C7; color: #D97706; }
    .status-progress { background: #DBEAFE; color: #2563EB; }
    .status-closed { background: #D1FAE5; color: #059669; }

    /* Buttons */
    .btn-view {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.3rem 0.8rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-view:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--gray-400);
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    /* Chart Container */
    .chart-container {
        height: 300px;
        position: relative;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .dashboard-container {
            padding: 1rem;
        }
        .dashboard-header {
            padding: 1.5rem;
        }
        .panel-body {
            padding: 1rem;
        }
    }
</style>

<div class="dashboard-container">

    {{-- HEADER --}}
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title">
                <h1>📊 Dashboard EPDC MSO</h1>
                <p>Ringkasan & Monitoring Performa Maintenance</p>
            </div>
            <div class="header-date">
                🗓️ {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="filter-card">
        <span class="filter-label">🔍 Filter:</span>
        <form method="GET" action="{{ route('dashboard') }}" id="filterForm" style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center;">
            <select name="period" id="periodSelect" class="filter-select">
                <option value="yearly" {{ ($filters['period'] ?? 'yearly') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                <option value="monthly" {{ ($filters['period'] ?? '') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                <option value="weekly" {{ ($filters['period'] ?? '') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
            </select>
            <select name="year" class="filter-select">
                @for($y = date('Y'); $y >= 2022; $y--)
                    <option value="{{ $y }}" {{ ($filters['year'] ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <select name="month" id="monthSelect" class="filter-select" style="{{ ($filters['period'] ?? 'yearly') != 'monthly' ? 'display:none;' : '' }}">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ ($filters['month'] ?? date('n')) == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                @endforeach
            </select>
            <select name="week" id="weekSelect" class="filter-select" style="{{ ($filters['period'] ?? 'yearly') != 'weekly' ? 'display:none;' : '' }}">
                @for($i=1; $i<=53; $i++)
                    <option value="{{ $i }}" {{ ($filters['week'] ?? date('W')) == $i ? 'selected' : '' }}>Week {{ $i }}</option>
                @endfor
            </select>
            <button type="submit" class="btn-apply">Terapkan</button>
        </form>
        <div class="quick-actions">
            <a href="{{ route('mso.create') }}" class="btn-action btn-primary">➕ Tambah MSO</a>
            <a href="{{ route('mso.index') }}" class="btn-action btn-outline">📋 Lihat MSO</a>
        </div>
    </div>

    <script>
    (function() {
        var period = document.getElementById('periodSelect');
        var month = document.getElementById('monthSelect');
        var week = document.getElementById('weekSelect');
        function toggle() {
            var val = period.value;
            month.style.display = val === 'monthly' ? '' : 'none';
            week.style.display = val === 'weekly' ? '' : 'none';
            month.disabled = val !== 'monthly';
            week.disabled = val !== 'weekly';
        }
        period.addEventListener('change', toggle);
        toggle();
    })();
    </script>

    {{-- STAT CARDS --}}
    @php
        $allMso = $mso_list_summary->flatten(1);
        $totalClosed = $allMso->filter(fn($m) => strtolower($m->status_pekerjaan ?? '') === 'closed')->count();
        $totalOnProgress = $allMso->filter(fn($m) => strtolower($m->status_pekerjaan ?? '') === 'on progress')->count();
        $totalPending = $pending_abnormality->sum('total_pending');
        $totalMso = $totalClosed + $totalOnProgress + $totalPending;
        $completePct = $totalMso > 0 ? round(($totalClosed / $totalMso) * 100) : 0;
    @endphp

    <div class="stats-grid">
        <div class="stat-card"><div class="stat-icon">📋</div><div class="stat-value">{{ $totalMso }}</div><div class="stat-label">Total MSO {{ $filters['year'] ?? date('Y') }}</div></div>
        <div class="stat-card"><div class="stat-icon">✅</div><div class="stat-value">{{ $totalClosed }}</div><div class="stat-label">Closed</div></div>
        <div class="stat-card"><div class="stat-icon">⚠️</div><div class="stat-value">{{ $totalPending }}</div><div class="stat-label">Pending Abnormality</div></div>
        <div class="stat-card"><div class="stat-icon">🔄</div><div class="stat-value">{{ $totalOnProgress }}</div><div class="stat-label">On Progress</div></div>
        <div class="stat-card"><div class="stat-icon">📈</div><div class="stat-value">{{ $completePct }}%</div><div class="stat-label">Completion Rate</div></div>
    </div>

    {{-- AVAILABILITY CHART + TOP 5 FREKUENSI --}}
    <div class="two-col">
        {{-- Bar Chart Availability --}}
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">📊 Availability Main Filter</h3>
                <span class="panel-badge">{{ $filters['year'] ?? date('Y') }} @if(($filters['period'] ?? '') == 'monthly') {{ \Carbon\Carbon::create()->month($filters['month'] ?? date('n'))->translatedFormat('F') }} @endif</span>
            </div>
            <div class="panel-body">
                <div class="chart-container">
                    <canvas id="availabilityChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Bar Chart Top 5 Frekuensi Tahunan --}}
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">🏆 Top 5 Frekuensi Breakdown (Tahunan)</h3>
                <span class="panel-badge">{{ $filters['year'] ?? date('Y') }}</span>
            </div>
            <div class="panel-body">
                <div class="chart-container">
                    <canvas id="topFreqChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW: Availability Cards + Top 5 Frekuensi Bulan Ini (tabel) --}}
    <div class="two-col">
        {{-- Availability Cards (alternatif visual) --}}
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">⚙️ Detail Availability per Area</h3>
            </div>
            <div class="panel-body">
                @if(count($availability))
                    <div class="avail-grid">
                        @foreach($availability as $avail)
                            @php
                                $pct = $avail['availability'] ?? 0;
                                $cls = $pct >= 90 ? 'avail-good' : ($pct >= 75 ? 'avail-medium' : 'avail-low');
                                $barColor = $pct >= 90 ? '#10B981' : ($pct >= 75 ? '#F59E0B' : '#DC2626');
                            @endphp
                            <div class="avail-card">
                                <div class="avail-area">{{ $avail['area'] ?? '-' }}</div>
                                <div class="avail-pct {{ $cls }}">{{ number_format($pct, 1) }}%</div>
                                <div class="avail-bar-wrap"><div class="avail-bar" style="width: {{ min($pct,100) }}%; background: {{ $barColor }};"></div></div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state"><span class="empty-icon">📭</span><div>Tidak ada data availability</div></div>
                @endif
            </div>
        </div>

        {{-- Top 5 Frekuensi Bulan Ini (Tabel) --}}
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">🏅 Top 5 Frekuensi Breakdown Bulan Ini</h3>
                <span class="panel-badge">{{ \Carbon\Carbon::create()->month($filters['month'] ?? date('n'))->translatedFormat('F') }} {{ $filters['year'] ?? date('Y') }}</span>
            </div>
            <div class="panel-body">
                @if($top5_freq_month->count())
                    @php $maxFreq = $top5_freq_month->max('total') ?: 1; @endphp
                    <table class="data-table">
                        <thead><tr><th>#</th><th>Nomenclature</th><th>Total</th></tr></thead>
                        <tbody>
                            @foreach($top5_freq_month as $i => $item)
                            <tr>
                                <td><span class="rank-badge {{ $i==0?'rank-gold':($i==1?'rank-silver':($i==2?'rank-bronze':'')) }}">{{ $i+1 }}</span></td>
                                <td>{{ $item->nomenclature->name ?? $item->nomenclature_id }}</td>
                                <td><div style="display:flex; align-items:center; gap:0.5rem;"><div class="mini-bar-wrap"><div class="mini-bar" style="width:{{ round(($item->total/$maxFreq)*100) }}%; background: #DC2626;"></div></div><span style="font-weight:700;">{{ $item->total }}x</span></div></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state"><span class="empty-icon">📭</span><div>Tidak ada data</div></div>
                @endif
            </div>
        </div>
    </div>

    {{-- ROW: Top 5 Durasi Bulan Ini & Tahunan --}}
    <div class="two-col">
        <div class="panel">
            <div class="panel-header"><h3 class="panel-title">⏱️ Top 5 Durasi Breakdown Bulan Ini</h3><span class="panel-badge">{{ \Carbon\Carbon::create()->month($filters['month'] ?? date('n'))->translatedFormat('F') }} {{ $filters['year'] ?? date('Y') }}</span></div>
            <div class="panel-body">
                @if($top5_dur_month->count())
                    @php $maxDur = $top5_dur_month->max('total_duration') ?: 1; @endphp
                    <table class="data-table">
                        <thead><tr><th>#</th><th>Nomenclature</th><th>Durasi</th></tr></thead>
                        <tbody>
                            @foreach($top5_dur_month as $i => $item)
                            <tr>
                                <td><span class="rank-badge {{ $i==0?'rank-gold':($i==1?'rank-silver':($i==2?'rank-bronze':'')) }}">{{ $i+1 }}</span></td>
                                <td>{{ $item->nomenclature->name ?? $item->nomenclature_id }}</td>
                                <td><div style="display:flex; align-items:center; gap:0.5rem;"><div class="mini-bar-wrap"><div class="mini-bar" style="width:{{ round(($item->total_duration/$maxDur)*100) }}%; background: #3B82F6;"></div></div><span style="font-weight:700;">{{ number_format($item->total_duration, 1) }} jam</span></div></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else <div class="empty-state"><span class="empty-icon">📭</span><div>Tidak ada data</div></div> @endif
            </div>
        </div>
        <div class="panel">
            <div class="panel-header"><h3 class="panel-title">⏱️ Top 5 Durasi Breakdown Tahunan</h3><span class="panel-badge">{{ $filters['year'] ?? date('Y') }}</span></div>
            <div class="panel-body">
                @if($top5_dur_year->count())
                    @php $maxDurY = $top5_dur_year->max('total_duration') ?: 1; @endphp
                    <table class="data-table">
                        <thead><tr><th>#</th><th>Nomenclature</th><th>Durasi</th></tr></thead>
                        <tbody>
                            @foreach($top5_dur_year as $i => $item)
                            <tr>
                                <td><span class="rank-badge {{ $i==0?'rank-gold':($i==1?'rank-silver':($i==2?'rank-bronze':'')) }}">{{ $i+1 }}</span></td>
                                <td>{{ $item->nomenclature->name ?? $item->nomenclature_id }}</td>
                                <td><div style="display:flex; align-items:center; gap:0.5rem;"><div class="mini-bar-wrap"><div class="mini-bar" style="width:{{ round(($item->total_duration/$maxDurY)*100) }}%; background: #8B5CF6;"></div></div><span style="font-weight:700;">{{ number_format($item->total_duration, 1) }} jam</span></div></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else <div class="empty-state"><span class="empty-icon">📭</span><div>Tidak ada data</div></div> @endif
            </div>
        </div>
    </div>

    {{-- Pending Abnormality Table --}}
    <div class="panel">
        <div class="panel-header"><h3 class="panel-title">⚠️ Pending Abnormality</h3><span class="panel-badge">{{ $pending_abnormality->sum('total_pending') }} item</span></div>
        <div class="panel-body" style="overflow-x:auto;">
            @if($pending_abnormality->count())
                <table class="data-table">
                    <thead><tr><th>#</th><th>Area</th><th>Nomenclature</th><th>Pending</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach($pending_abnormality as $i => $item)
                        @php
                            $groupKey = $item->area_id . '_' . $item->nomenclature_id;
                            $msoGroup = $mso_list_pending->get($groupKey, collect());
                            $msoJson = $msoGroup->map(fn($m) => [
                                'no_mso' => $m->no_mso ?? '-',
                                'start_date' => $m->start_date ? \Carbon\Carbon::parse($m->start_date)->format('d M Y') : '-',
                                'finish_date' => $m->finish_date ? \Carbon\Carbon::parse($m->finish_date)->format('d M Y') : '-',
                                'duration' => $m->total_duration ? number_format($m->total_duration,1).'h' : '-',
                                'status' => $m->status_pekerjaan ?? '-',
                                'type' => $m->maintenanceType->name ?? '-',
                                'description' => $m->description ?? '-',
                            ])->values()->toJson();
                            $modalTitle = ($item->nomenclature->name ?? '-') . ' — ' . ($item->area->name ?? '-');
                        @endphp
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $item->area->name ?? '-' }}</td>
                            <td><strong>{{ $item->nomenclature->name ?? '-' }}</strong></td>
                            <td><span class="status-badge status-open">⚠️ {{ $item->total_pending }}</span></td>
                            <td><button class="btn-view" onclick="openMsoModal({{ json_encode($modalTitle) }}, 'Pending Abnormality', {{ $msoJson }})">👁️ Lihat</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state"><span class="empty-icon">✅</span><div>Tidak ada pending abnormality</div></div>
            @endif
        </div>
    </div>

    {{-- Maintenance Summary Table --}}
    <div class="panel">
        <div class="panel-header"><h3 class="panel-title">🔧 Maintenance Summary</h3><span class="panel-badge">{{ $filters['year'] ?? date('Y') }}</span></div>
        <div class="panel-body" style="overflow-x:auto;">
            @if($maintenance_summary->count())
                <table class="data-table">
                    <thead><tr><th>Nomenclature</th><th>Jenis Maintenance</th><th>Area</th><th style="text-align:center;">Total</th><th style="text-align:center;">Aksi</th></tr></thead>
                    <tbody>
                        @foreach($maintenance_summary as $row)
                        @php
                            $groupKey = $row->area_id . '_' . $row->nomenclature_id . '_' . $row->maintenance_type_id;
                            $msoGroup = $mso_list_summary->get($groupKey, collect());
                            $msoJson = $msoGroup->map(fn($m) => [
                                'no_mso' => $m->no_mso ?? '-',
                                'start_date' => $m->start_date ? \Carbon\Carbon::parse($m->start_date)->format('d M Y') : '-',
                                'finish_date' => $m->finish_date ? \Carbon\Carbon::parse($m->finish_date)->format('d M Y') : '-',
                                'duration' => $m->total_duration ? number_format($m->total_duration,1).'h' : '-',
                                'status' => $m->status_pekerjaan ?? '-',
                                'type' => $m->maintenanceType->name ?? '-',
                                'description' => $m->description ?? '-',
                            ])->values()->toJson();
                            $modalTitle = ($row->nomenclature->name ?? '-') . ' — ' . ($row->area->name ?? '-');
                            $modalSub = $row->maintenanceType->name ?? '-';
                        @endphp
                        <tr>
                            <td>{{ $row->nomenclature->name ?? '-' }}</td>
                            <td>{{ $row->maintenanceType->name ?? '-' }}</td>
                            <td>{{ $row->area->name ?? '-' }}</td>
                            <td style="text-align:center;"><strong>{{ $row->total }}</strong></td>
                            <td style="text-align:center;"><button class="btn-view" onclick="openMsoModal({{ json_encode($modalTitle) }}, {{ json_encode($modalSub) }}, {{ $msoJson }})">👁️ Lihat</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot><tr><td colspan="3"><strong>Total Keseluruhan</strong></td><td style="text-align:center;"><strong>{{ $maintenance_summary->sum('total') }}</strong></td><td></td></tr></tfoot>
                </table>
            @else
                <div class="empty-state"><span class="empty-icon">📭</span><div>Belum ada data maintenance</div></div>
            @endif
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="mso-modal-overlay" id="msoModalOverlay" onclick="closeMsoModalOnOverlay(event)">
    <div class="mso-modal">
        <div class="mso-modal-header"><div><p class="mso-modal-title" id="msoModalTitle">Detail MSO</p><p class="mso-modal-subtitle" id="msoModalSubtitle"></p></div><button class="mso-modal-close" onclick="closeMsoModal()">✕</button></div>
        <div class="mso-modal-body"><div id="msoModalContent"></div></div>
        <div class="mso-modal-footer"><span id="msoModalCount"></span><button onclick="closeMsoModal()" style="padding:0.45rem 1.1rem; border-radius:6px; border:2px solid var(--primary); background:transparent; color:var(--primary); font-weight:700; cursor:pointer;">Tutup</button></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Data untuk chart availability
    const availabilityData = @json($availability->map(fn($item) => ['area' => $item['area'], 'availability' => $item['availability'] ?? 0]));
    const availLabels = availabilityData.map(item => item.area);
    const availValues = availabilityData.map(item => item.availability);

    const ctxAvail = document.getElementById('availabilityChart')?.getContext('2d');
    if (ctxAvail && availLabels.length) {
        new Chart(ctxAvail, {
            type: 'bar',
            data: { labels: availLabels, datasets: [{ label: 'Availability (%)', data: availValues, backgroundColor: '#DC2626', borderRadius: 6 }] },
            options: { responsive: true, maintainAspectRatio: true, scales: { y: { min: 0, max: 100, title: { display: true, text: 'Persentase (%)' } } }, plugins: { tooltip: { callbacks: { label: (ctx) => `${ctx.raw}%` } } } }
        });
    }

    // Data top 5 frekuensi tahunan
    const topFreq = @json($top5_freq_year->map(fn($item) => ['name' => $item->nomenclature->name ?? $item->nomenclature_id, 'total' => $item->total]));
    const freqLabels = topFreq.map(item => item.name);
    const freqValues = topFreq.map(item => item.total);
    const ctxFreq = document.getElementById('topFreqChart')?.getContext('2d');
    if (ctxFreq && freqLabels.length) {
        new Chart(ctxFreq, {
            type: 'bar', data: { labels: freqLabels, datasets: [{ label: 'Frekuensi Breakdown', data: freqValues, backgroundColor: '#F59E0B', borderRadius: 6 }] },
            options: { responsive: true, maintainAspectRatio: true, scales: { y: { beginAtZero: true, title: { display: true, text: 'Jumlah Kejadian' } } } }
        });
    }

    // Modal logic (sama seperti sebelumnya)
    (function() {
        var overlay = document.getElementById('msoModalOverlay');
        window.openMsoModal = function(title, subtitle, msoList) {
            document.getElementById('msoModalTitle').textContent = title;
            document.getElementById('msoModalSubtitle').textContent = subtitle;
            document.getElementById('msoModalCount').textContent = msoList.length + ' MSO ditemukan';
            var content = document.getElementById('msoModalContent');
            if (!msoList || msoList.length === 0) content.innerHTML = '<div class="mso-modal-empty">📭 Tidak ada data MSO untuk grup ini.</div>';
            else {
                var statusClass = (s) => { let sl = (s||'').toLowerCase(); if(sl==='closed') return 'status-closed'; if(sl==='progress'||sl==='in progress'||sl==='on progress') return 'status-progress'; return 'status-open'; };
                var rows = msoList.map((m,i) => `<tr><td style="color:var(--gray-500);">${i+1}</td><td><strong>${m.no_mso}</strong></td><td>${m.type}</td><td>${m.start_date}</td><td>${m.finish_date}</td><td style="text-align:right;">${m.duration}</td><td><span class="status-badge ${statusClass(m.status)}">${m.status}</span></td></tr>`).join('');
                content.innerHTML = `<table class="mso-modal-table"><thead><tr><th>#</th><th>No. MSO</th><th>Jenis</th><th>Tgl Mulai</th><th>Tgl Selesai</th><th style="text-align:right;">Durasi</th><th>Status</th></tr></thead><tbody>${rows}</tbody></table>`;
            }
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        };
        window.closeMsoModal = function() { overlay.classList.remove('active'); document.body.style.overflow = ''; };
        window.closeMsoModalOnOverlay = function(e) { if (e.target === overlay) closeMsoModal(); };
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeMsoModal(); });
    })();
</script>

<style>
    .mso-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; backdrop-filter: blur(3px); }
    .mso-modal-overlay.active { display: flex; }
    .mso-modal { background: white; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); width: 100%; max-width: 900px; max-height: 85vh; display: flex; flex-direction: column; overflow: hidden; animation: modalIn 0.2s ease; }
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .mso-modal-header { background: linear-gradient(135deg, #DC2626, #991B1B); color: white; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: flex-start; }
    .mso-modal-title { font-weight: 800; margin: 0; font-size: 1rem; }
    .mso-modal-subtitle { font-size: 0.75rem; opacity: 0.8; margin-top: 0.25rem; }
    .mso-modal-close { background: rgba(255,255,255,0.2); border: none; color: white; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; }
    .mso-modal-body { overflow-y: auto; padding: 1.5rem; flex: 1; }
    .mso-modal-table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
    .mso-modal-table th { text-align: left; padding: 0.5rem; background: #F9FAFB; border-bottom: 1px solid #E5E7EB; }
    .mso-modal-table td { padding: 0.5rem; border-bottom: 1px solid #F3F4F6; }
    .mso-modal-footer { padding: 0.75rem 1.5rem; border-top: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center; background: #F9FAFB; }
    .mso-modal-empty { text-align: center; padding: 2rem; color: #9CA3AF; }
</style>

@endsection