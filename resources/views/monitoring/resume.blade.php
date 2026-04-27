@extends('layouts.app')

@section('content')

{{-- ============================================================
     MONITORING MATERIAL - RESUME
     Rata-rata durasi per TAHAPAN PROSES per jenis Pengadaan:
       Notifikasi → Reservasi → PR → PO → Est. Complete → MSO Finish
     ============================================================ --}}

<style>
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

        /* Warna tiap tahapan */
        --c-notif:    #2563EB;
        --c-rsv:      #7C3AED;
        --c-pr:       #D97706;
        --c-po:       #16a34a;
        --c-mso:      #0891B2;
        --c-total:    #DC2626;
    }

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
        box-shadow: 0 4px 6px rgba(220,38,38,0.2);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title   { font-size: 2rem; font-weight: 700; margin: 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
    .page-subtitle { font-size: 0.875rem; margin: 0.25rem 0 0; opacity: 0.85; }

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
    .btn-back:hover { background: rgba(255,255,255,0.25); color: var(--pure-white); transform: translateY(-2px); }

    /* === FILTER === */
    .filter-container {
        background: var(--pure-white);
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
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

    .filter-input, .filter-select {
        border: 2px solid var(--light-gray);
        padding: 0.65rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        color: var(--dark-gray);
        background: var(--pure-white);
        transition: all 0.3s ease;
    }
    .filter-input { min-width: 160px; }
    .filter-select { min-width: 160px; }
    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: var(--primary-red);
        box-shadow: 0 0 0 3px var(--light-red);
    }

    /* Separator "s/d" antar tanggal */
    .date-range-sep {
        font-size: 0.813rem;
        font-weight: 600;
        color: var(--medium-gray);
        padding-bottom: 0.2rem;
        align-self: flex-end;
        padding: 0.65rem 0.25rem;
    }

    .btn-filter {
        background: var(--primary-red);
        color: var(--pure-white);
        padding: 0.7rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-filter:hover { background: var(--dark-red); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(220,38,38,0.3); }

    .btn-reset {
        background: var(--medium-gray);
        color: var(--pure-white);
        padding: 0.7rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    .btn-reset:hover { background: var(--dark-gray); transform: translateY(-2px); color: var(--pure-white); }

    /* Active filter badge */
    .active-filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        background: #FEF3C7;
        border: 1px solid #FCD34D;
        color: #92400E;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.75rem;
    }

    /* === PIPELINE HEADER === */
    .pipeline-banner {
        background: var(--pure-white);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        overflow-x: auto;
    }

    .pipeline-flow {
        display: flex;
        align-items: center;
        gap: 0;
        min-width: 700px;
    }

    .pipeline-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
    }

    .pipeline-dot {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: var(--pure-white);
        font-weight: 700;
        margin-bottom: 0.5rem;
        box-shadow: 0 4px 8px rgba(0,0,0,0.12);
        z-index: 1;
    }

    .pipeline-step-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--dark-gray);
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        line-height: 1.3;
    }

    .pipeline-arrow {
        flex: 1;
        height: 3px;
        position: relative;
        margin-bottom: 2rem;
    }

    .pipeline-arrow::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: var(--light-gray);
    }

    .pipeline-arrow-label {
        position: absolute;
        top: -22px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--medium-gray);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
        background: var(--pure-white);
        padding: 0 4px;
    }

    /* === STAT CARDS (6 tahapan) === */
    .stage-cards {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1400px) { .stage-cards { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px)  { .stage-cards { grid-template-columns: 1fr 1fr; } }

    .stage-card {
        background: var(--pure-white);
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-top: 4px solid;
        transition: all 0.3s ease;
    }
    .stage-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }

    .stage-card-icon { font-size: 1.5rem; margin-bottom: 0.5rem; }
    .stage-card-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--medium-gray); margin-bottom: 0.375rem; }

    .stage-card-value {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stage-card-sub { font-size: 0.7rem; color: var(--medium-gray); }

    /* === SECTION TITLE === */
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
        width: 4px; height: 1.25rem;
        background: var(--primary-red);
        border-radius: 2px;
    }

    /* === CHART GRID === */
    .chart-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    @media (max-width: 900px) { .chart-grid-2 { grid-template-columns: 1fr; } }

    .chart-card {
        background: var(--pure-white);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .chart-card-header {
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chart-card-body {
        padding: 1.5rem;
        min-height: 300px;
        position: relative;
    }

    /* === SUMMARY TABLE === */
    .summary-wrap {
        overflow-x: auto;
        margin-bottom: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .summary-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: var(--pure-white);
        min-width: 1100px;
    }

    .summary-table thead {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
    }

    .summary-table th {
        padding: 0.875rem 1rem;
        text-align: left;
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--pure-white);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .summary-table th.stage-header {
        text-align: center;
        border-left: 1px solid rgba(255,255,255,0.15);
    }

    .summary-table tbody tr {
        border-bottom: 1px solid var(--light-gray);
        transition: all 0.2s ease;
    }

    .summary-table tbody tr:hover { background: var(--light-red); }

    .summary-table td {
        padding: 0.875rem 1rem;
        font-size: 0.875rem;
        color: var(--dark-gray);
        vertical-align: middle;
        border-bottom: 1px solid var(--light-gray);
    }

    .summary-table tfoot td {
        padding: 0.875rem 1rem;
        font-weight: 700;
        font-size: 0.875rem;
        background: linear-gradient(135deg, var(--dark-gray) 0%, #374151 100%);
        color: var(--pure-white);
    }

    .dur-cell {
        text-align: center;
        border-left: 1px solid var(--light-gray);
    }

    .dur-na {
        color: var(--medium-gray);
        font-size: 0.813rem;
    }

    /* Chip pengadaan */
    .chip { display: inline-block; padding: 0.2rem 0.65rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
    .chip-jasa        { background:#DBEAFE; color:#1E40AF; border:1px solid #93C5FD; }
    .chip-barangjasa  { background:#FEF3C7; color:#92400E; border:1px solid #FCD34D; }
    .chip-viapeng     { background:#D1FAE5; color:#065F46; border:1px solid #6EE7B7; }
    .chip-viacapex    { background:#EDE9FE; color:#4C1D95; border:1px solid #C4B5FD; }

    /* Info note */
    .info-note {
        background: #EFF6FF;
        border-left: 4px solid #2563EB;
        color: #1E40AF;
        padding: 0.875rem 1.25rem;
        border-radius: 8px;
        font-size: 0.813rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        line-height: 1.6;
    }

    /* MSO highlight note */
    .mso-note {
        background: #ECFEFF;
        border-left: 4px solid #0891B2;
        color: #164E63;
        padding: 0.875rem 1.25rem;
        border-radius: 8px;
        font-size: 0.813rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        line-height: 1.6;
    }
</style>

<div class="mon-container">

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">📊 Resume Lead Time Pengadaan Material</h1>
            <p class="page-subtitle">Rata-rata durasi per tahapan proses: Notifikasi → Reservasi → PR → PO → Est. Complete → MSO Finish</p>
        </div>
        <a href="{{ route('monitoring.index') }}" class="btn-back">← Kembali</a>
    </div>

    {{-- FILTER --}}
    <div class="filter-container">
        <form method="GET" action="{{ route('monitoring.resume') }}" style="display:flex; align-items:flex-end; gap:1rem; flex-wrap:wrap;">

            {{-- Range Tanggal --}}
            <div>
                <label class="filter-label">📅 Tanggal Dari</label>
                <input
                    type="date"
                    name="tanggal_dari"
                    class="filter-input"
                    value="{{ request('tanggal_dari') }}"
                >
            </div>

            <div class="date-range-sep">s/d</div>

            <div>
                <label class="filter-label">📅 Tanggal Sampai</label>
                <input
                    type="date"
                    name="tanggal_sampai"
                    class="filter-input"
                    value="{{ request('tanggal_sampai') }}"
                >
            </div>

            {{-- Filter Jenis Pengadaan --}}
            <div>
                <label class="filter-label">🔖 Pengadaan</label>
                <select name="pengadaan" class="filter-select">
                    <option value="">Semua Pengadaan</option>
                    @foreach(['Jasa','Barang-Jasa','Via Peng.Barang','Via Capex'] as $p)
                        <option value="{{ $p }}" {{ request('pengadaan') == $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex; gap:0.75rem;">
                <button type="submit" class="btn-filter">🔎 Terapkan</button>
                @if(request()->anyFilled(['tanggal_dari','tanggal_sampai','pengadaan']))
                    <a href="{{ route('monitoring.resume') }}" class="btn-reset">✖ Reset</a>
                @endif
            </div>
        </form>

        {{-- Tampilkan rentang aktif jika ada filter --}}
        @if(request()->anyFilled(['tanggal_dari','tanggal_sampai','pengadaan']))
            <div style="margin-top:0.75rem; display:flex; flex-wrap:wrap; gap:0.5rem; align-items:center;">
                @if(request('tanggal_dari') || request('tanggal_sampai'))
                    <span class="active-filter-badge">
                        📅
                        {{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('d M Y') : '—' }}
                        s/d
                        {{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d M Y') : 'sekarang' }}
                    </span>
                @endif
                @if(request('pengadaan'))
                    <span class="active-filter-badge">🔖 {{ request('pengadaan') }}</span>
                @endif
            </div>
        @endif
    </div>

    {{-- INFO NOTE --}}
    <div class="info-note">
        ℹ️ <span>
            Durasi setiap interval dihitung dalam <strong>hari kalender</strong>.
            Interval hanya dihitung apabila <strong>kedua tanggal tersedia</strong> di data.
            Data dengan tanggal tidak lengkap dikecualikan dari rata-rata tahap tersebut.
        </span>
    </div>

    <div class="mso-note">
        🔵 <span>
            Tahapan <strong>Est. Complete → MSO Finish</strong> dihitung dari <code>estimated_delivery</code> (Material Monitoring)
            ke <code>finish_date</code> (MSO Transaction) berdasarkan kecocokan <code>trans_id</code>.
            Data hanya muncul apabila MSO terkait sudah memiliki tanggal selesai.
        </span>
    </div>

    {{-- PIPELINE VISUAL --}}
    <div class="pipeline-banner">
        <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--medium-gray); margin-bottom:1.25rem;">
            Alur Tahapan Proses
        </div>
        <div class="pipeline-flow">

            <div class="pipeline-step">
                <div class="pipeline-dot" style="background:var(--c-notif);">📋</div>
                <div class="pipeline-step-label">Notifikasi<br><small style="font-weight:400; text-transform:none;">(tanggal)</small></div>
            </div>

            <div class="pipeline-arrow">
                <div class="pipeline-arrow-label" style="color:var(--c-notif);">① Notif → RSV</div>
            </div>

            <div class="pipeline-step">
                <div class="pipeline-dot" style="background:var(--c-rsv);">📌</div>
                <div class="pipeline-step-label">Reservasi<br><small style="font-weight:400; text-transform:none;">Planner</small></div>
            </div>

            <div class="pipeline-arrow">
                <div class="pipeline-arrow-label" style="color:var(--c-rsv);">② RSV → PR</div>
            </div>

            <div class="pipeline-step">
                <div class="pipeline-dot" style="background:var(--c-pr);">📄</div>
                <div class="pipeline-step-label">Purchase<br>Request</div>
            </div>

            <div class="pipeline-arrow">
                <div class="pipeline-arrow-label" style="color:var(--c-pr);">③ PR → PO</div>
            </div>

            <div class="pipeline-step">
                <div class="pipeline-dot" style="background:var(--c-po);">🧾</div>
                <div class="pipeline-step-label">Purchase<br>Order</div>
            </div>

            <div class="pipeline-arrow">
                <div class="pipeline-arrow-label" style="color:var(--c-po);">④ PO → Est.</div>
            </div>

            <div class="pipeline-step">
                <div class="pipeline-dot" style="background:var(--c-mso);">📦</div>
                <div class="pipeline-step-label">Est.<br>Complete</div>
            </div>

            <div class="pipeline-arrow">
                <div class="pipeline-arrow-label" style="color:var(--c-mso);">⑤ Est. → MSO</div>
            </div>

            <div class="pipeline-step">
                <div class="pipeline-dot" style="background:var(--c-total);">✅</div>
                <div class="pipeline-step-label">MSO<br>Finish</div>
            </div>

        </div>
    </div>

    {{-- STAT CARDS — rata-rata keseluruhan per tahapan --}}
    <div class="section-title">Rata-rata Keseluruhan per Tahapan</div>
    <div class="stage-cards" style="margin-bottom:2rem;">
        @php
            $stageIcons  = [
                'notif_to_reservasi'     => '①',
                'reservasi_to_pr'        => '②',
                'pr_to_po'               => '③',
                'po_to_delivery'         => '④',
                'delivery_to_mso_finish' => '⑤',
                'total_lead'             => '⏱',
            ];
            $stageBorder = [
                'notif_to_reservasi'     => 'var(--c-notif)',
                'reservasi_to_pr'        => 'var(--c-rsv)',
                'pr_to_po'               => 'var(--c-pr)',
                'po_to_delivery'         => 'var(--c-po)',
                'delivery_to_mso_finish' => 'var(--c-mso)',
                'total_lead'             => 'var(--c-total)',
            ];
            $stageColor = $stageBorder;
        @endphp

        @foreach($stages as $key => $stage)
        <div class="stage-card" style="border-top-color:{{ $stageBorder[$key] }};">
            <div class="stage-card-icon">{{ $stageIcons[$key] }}</div>
            <div class="stage-card-label">{{ $stage['short'] }}</div>
            @if($stats[$key]['avg'] !== null)
                <div class="stage-card-value" style="color:{{ $stageColor[$key] }};">
                    {{ $stats[$key]['avg'] }} <span style="font-size:1rem; font-weight:500;">hr</span>
                </div>
                <div class="stage-card-sub">dari {{ $stats[$key]['count'] }} data</div>
            @else
                <div class="stage-card-value" style="color:var(--medium-gray); font-size:1.25rem;">—</div>
                <div class="stage-card-sub">data tidak tersedia</div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- CHART ROW 1: Grouped Bar + Stacked Bar --}}
    <div class="section-title">Perbandingan Durasi per Jenis Pengadaan</div>
    <div class="chart-grid-2" style="margin-bottom:2rem;">

        <div class="chart-card">
            <div class="chart-card-header">📊 Rata-rata Hari per Tahapan & Pengadaan</div>
            <div class="chart-card-body">
                <canvas id="chartGrouped"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">📐 Komposisi Total Lead Time (Stacked)</div>
            <div class="chart-card-body">
                <canvas id="chartStacked"></canvas>
            </div>
        </div>

    </div>

    {{-- CHART ROW 2: Radar + Trend Bulanan --}}
    <div class="chart-grid-2" style="margin-bottom:2rem;">

        <div class="chart-card">
            <div class="chart-card-header">🕸 Profil Lead Time per Pengadaan (Radar)</div>
            <div class="chart-card-body" style="display:flex; align-items:center; justify-content:center;">
                <canvas id="chartRadar" style="max-height:280px;"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">📈 Trend Total Lead Time (12 Bulan Terakhir)</div>
            <div class="chart-card-body">
                <canvas id="chartTrend"></canvas>
            </div>
        </div>

    </div>

    {{-- TABEL RINGKASAN DETAIL --}}
    <div class="section-title">Tabel Detail per Jenis Pengadaan & Tahapan</div>
    <div class="summary-wrap">
        <table class="summary-table">
            <thead>
                <tr>
                    <th rowspan="2" style="vertical-align:middle; min-width:140px;">Jenis Pengadaan</th>
                    <th rowspan="2" style="vertical-align:middle; text-align:center;">Jumlah<br>Data</th>
                    @foreach($stages as $key => $stage)
                        <th class="stage-header" colspan="3" style="border-top:3px solid {{ $stage['color'] }};">
                            {{ $stage['short'] }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($stages as $key => $stage)
                        <th class="stage-header" style="font-size:0.65rem; background:rgba(0,0,0,0.15);">Rata²</th>
                        <th class="stage-header" style="font-size:0.65rem; background:rgba(0,0,0,0.15);">Min</th>
                        <th class="stage-header" style="font-size:0.65rem; background:rgba(0,0,0,0.15);">Max</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($tableSummary as $row)
                @php
                    $chipClass = match($row['pengadaan']) {
                        'Jasa'            => 'chip-jasa',
                        'Barang-Jasa'     => 'chip-barangjasa',
                        'Via Peng.Barang' => 'chip-viapeng',
                        'Via Capex'       => 'chip-viacapex',
                        default           => '',
                    };
                @endphp
                <tr>
                    <td>
                        <span class="chip {{ $chipClass }}">{{ $row['pengadaan'] }}</span>
                    </td>
                    <td style="text-align:center; font-weight:700;">{{ $row['total'] }}</td>

                    @foreach($stages as $key => $stage)
                        @php $d = $row[$key]; @endphp
                        <td class="dur-cell">
                            @if($d['avg'] !== null)
                                <span style="font-weight:800; color:{{ $stage['color'] }};">{{ $d['avg'] }}</span>
                                <span style="font-size:0.7rem; color:var(--medium-gray);"> hr</span>
                            @else
                                <span class="dur-na">—</span>
                            @endif
                        </td>
                        <td class="dur-cell" style="color:var(--medium-gray); font-size:0.813rem;">
                            {{ $d['min'] !== null ? $d['min'].' hr' : '—' }}
                        </td>
                        <td class="dur-cell" style="color:var(--medium-gray); font-size:0.813rem;">
                            {{ $d['max'] !== null ? $d['max'].' hr' : '—' }}
                        </td>
                    @endforeach
                </tr>
                @empty
                <tr>
                    <td colspan="{{ 2 + count($stages) * 3 }}" style="text-align:center; padding:2rem; color:var(--medium-gray);">
                        📭 Tidak ada data untuk ditampilkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td>RATA-RATA SEMUA</td>
                    <td style="text-align:center;">{{ $stats['total_records'] }}</td>
                    @foreach($stages as $key => $stage)
                        <td style="text-align:center;">
                            {{ $stats[$key]['avg'] !== null ? $stats[$key]['avg'].' hr' : '—' }}
                        </td>
                        <td colspan="2"></td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>

</div>{{-- end mon-container --}}

{{-- ============================================================
     CHARTS — Chart.js 4
     ============================================================ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
/* ── DATA DARI CONTROLLER ── */
const pengadaanLabels = @json($chartLabels);

/* Stage metadata untuk JS (label, short, color) */
const stagesMeta = @json($stagesJs);

/* Data rata-rata per stage per pengadaan */
const stageDatasets = @json($chartDatasets);

/* Trend bulanan */
const trendLabels   = @json($trend['labels']);
const trendDatasets = @json($trend['datasets']);

/* Warna per pengadaan */
const PENG_COLORS = {
    'Jasa'            : { main:'#2563EB', light:'rgba(37,99,235,0.15)'   },
    'Barang-Jasa'     : { main:'#D97706', light:'rgba(217,119,6,0.15)'   },
    'Via Peng.Barang' : { main:'#16a34a', light:'rgba(22,163,74,0.15)'   },
    'Via Capex'       : { main:'#7C3AED', light:'rgba(124,58,237,0.15)'  },
};

Chart.defaults.font = { family:"'Inter','Segoe UI',sans-serif", size:11 };
Chart.defaults.color = '#6B7280';

/* ── Susun dataset untuk Grouped Bar ──
   Setiap dataset = 1 tahapan, berisi nilai avg untuk setiap pengadaan
*/
function buildGroupedDatasets() {
    const stageKeys   = Object.keys(stageDatasets);
    const stageColors = stagesMeta.map(s => s.color);

    return stageKeys.map((key, i) => ({
        label        : stagesMeta[i].short,
        data         : stageDatasets[key],
        backgroundColor: stageColors[i],
        borderRadius : 5,
        borderSkipped: false,
    }));
}

/* ── 1. GROUPED BAR ── */
new Chart(document.getElementById('chartGrouped'), {
    type: 'bar',
    data: {
        labels  : pengadaanLabels,
        datasets: buildGroupedDatasets(),
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } },
            tooltip: {
                callbacks: { label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y ?? '—'} hari` }
            },
        },
        scales: {
            x: { grid: { display: false } },
            y: {
                beginAtZero: true,
                grid: { color: '#E5E7EB' },
                ticks: { callback: v => v + ' hr' },
            },
        },
    },
});

/* ── 2. STACKED BAR — 5 interval (termasuk delivery_to_mso_finish, bukan total) ── */
function buildStackedDatasets() {
    const keys   = ['notif_to_reservasi','reservasi_to_pr','pr_to_po','po_to_delivery','delivery_to_mso_finish'];
    const labels = stagesMeta.slice(0, 5).map(s => s.short);
    const colors = stagesMeta.slice(0, 5).map(s => s.color);

    return keys.map((key, i) => ({
        label           : labels[i],
        data            : stageDatasets[key],
        backgroundColor : colors[i],
        borderRadius    : i === 4 ? { topRight:5, topLeft:0 } : 0,
        borderSkipped   : false,
    }));
}

new Chart(document.getElementById('chartStacked'), {
    type: 'bar',
    data: {
        labels  : pengadaanLabels,
        datasets: buildStackedDatasets(),
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } },
            tooltip: {
                mode: 'index',
                intersect: false,
                callbacks: { label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y ?? '—'} hari` },
            },
        },
        scales: {
            x: { stacked: true, grid: { display: false } },
            y: {
                stacked: true,
                beginAtZero: true,
                grid: { color: '#E5E7EB' },
                ticks: { callback: v => v + ' hr' },
            },
        },
    },
});

/* ── 3. RADAR — 5 interval termasuk delivery_to_mso_finish ── */
{
    const keys = ['notif_to_reservasi','reservasi_to_pr','pr_to_po','po_to_delivery','delivery_to_mso_finish'];
    const radarLabels = stagesMeta.slice(0, 5).map(s => s.short);

    const radarDatasets = pengadaanLabels.map(peng => {
        const color = (PENG_COLORS[peng] ?? { main:'#6B7280', light:'rgba(107,114,128,0.15)' });
        const idx   = pengadaanLabels.indexOf(peng);

        return {
            label          : peng,
            data           : keys.map(k => stageDatasets[k][idx] ?? 0),
            backgroundColor: color.light,
            borderColor    : color.main,
            borderWidth    : 2,
            pointBackgroundColor: color.main,
            pointRadius    : 4,
        };
    });

    new Chart(document.getElementById('chartRadar'), {
        type: 'radar',
        data: { labels: radarLabels, datasets: radarDatasets },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } },
                tooltip: { callbacks: { label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.r} hari` } },
            },
            scales: {
                r: {
                    beginAtZero: true,
                    grid       : { color: '#E5E7EB' },
                    ticks      : { callback: v => v + ' hr', font: { size: 10 } },
                },
            },
        },
    });
}

/* ── 4. TREND LINE — total lead time per pengadaan per bulan ── */
{
    const trendDs = Object.entries(trendDatasets).map(([peng, vals]) => {
        const color = (PENG_COLORS[peng] ?? { main:'#6B7280' }).main;
        return {
            label      : peng,
            data       : vals,
            borderColor: color,
            backgroundColor: color,
            borderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            tension    : 0.35,
            spanGaps   : true,
        };
    });

    new Chart(document.getElementById('chartTrend'), {
        type: 'line',
        data: { labels: trendLabels, datasets: trendDs },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: { label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y ?? '—'} hari` },
                },
            },
            scales: {
                x: { grid: { color: '#E5E7EB' }, ticks: { maxRotation: 45 } },
                y: {
                    beginAtZero: true,
                    grid: { color: '#E5E7EB' },
                    ticks: { callback: v => v + ' hr' },
                },
            },
        },
    });
}
</script>

@endsection