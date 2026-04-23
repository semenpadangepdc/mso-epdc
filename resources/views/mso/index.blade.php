@extends('layouts.app')

@section('content')

<style>
    /* ============================================================================
    MSO INDEX - STYLESHEET
    Organized and optimized CSS for MSO transaction management interface
    ============================================================================ */

    /* === COLOR PALETTE === */
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
    }


    /* ============================================================================
    LAYOUT COMPONENTS
    ============================================================================ */

    /* === MAIN CONTAINER === */
    .mso-container {
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
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }


    /* ============================================================================
    FILTER & SEARCH SECTION
    ============================================================================ */

    .filter-container {
        background: var(--pure-white);
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-red);
    }

    /* Search Input */
    .search-input {
        border: 2px solid var(--light-gray);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        width: 320px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-red);
        box-shadow: 0 0 0 3px var(--light-red);
    }

    /* Search Button */
    .btn-search {
        background: var(--primary-red);
        color: var(--pure-white);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-search:hover {
        background: var(--dark-red);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    /* Filter Dropdown Select */
    .filter-select {
        border: 2px solid var(--light-gray);
        padding: 0.75rem 2.5rem 0.75rem 1rem;
        border-radius: 8px;
        background-color: var(--pure-white);
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23DC2626'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .filter-select:hover {
        border-color: var(--primary-red);
        background-color: var(--light-red);
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary-red);
        box-shadow: 0 0 0 3px var(--light-red);
    }

    /* Add Button */
    .btn-add {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: var(--pure-white);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 8px rgba(22, 163, 74, 0.3);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.4);
    }


    /* ============================================================================
    TABLE CONTAINER & SCROLLING
    ============================================================================ */

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

    /* Custom Scrollbar */
    .scroll-wrapper::-webkit-scrollbar {
        height: 16px;
    }

    .scroll-wrapper::-webkit-scrollbar-track {
        background: var(--light-gray);
        border-radius: 8px;
        margin: 0 10px;
    }

    .scroll-wrapper::-webkit-scrollbar-thumb {
        background: var(--primary-red);
        border-radius: 8px;
        border: 3px solid var(--light-gray);
    }

    .scroll-wrapper::-webkit-scrollbar-thumb:hover {
        background: var(--dark-red);
    }

    /* Scroll Indicator */
    .scroll-wrapper::after {
        content: '← Scroll untuk melihat lebih banyak →';
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: var(--primary-red);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        opacity: 0;
        animation: fadeInOut 3s ease-in-out infinite;
        pointer-events: none;
        z-index: 5;
    }


    /* ============================================================================
    TABLE STRUCTURE & STYLING
    ============================================================================ */

    .force-scroll-table {
        min-width: 3500px;
        table-layout: auto;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    /* === TABLE HEADER === */
    .force-scroll-table thead {
        position: sticky;
        top: 0;
        z-index: 10;
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
    }

    .force-scroll-table th {
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

    /* === TABLE BODY === */
    .force-scroll-table tbody tr {
        background: var(--pure-white);
        transition: all 0.3s ease;
        border-bottom: 1px solid var(--light-gray);
    }

    .force-scroll-table tbody tr:hover {
        background: var(--light-red);
        transform: translateX(4px);
        box-shadow: -4px 0 0 var(--primary-red), 0 2px 8px rgba(220, 38, 38, 0.15);
    }

    .force-scroll-table td {
        padding: 1rem;
        color: var(--dark-gray);
        font-size: 0.875rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--light-gray);
    }

    /* Column Width Control */
    .force-scroll-table th:nth-child(1),
    .force-scroll-table td:nth-child(1) { min-width: 100px; } /* ID Trans */

    .force-scroll-table th:nth-child(2),
    .force-scroll-table td:nth-child(2) { min-width: 120px; } /* Sub ID */

    .force-scroll-table th:nth-child(3),
    .force-scroll-table td:nth-child(3) { min-width: 150px; } /* User */

    .force-scroll-table th:nth-child(4),
    .force-scroll-table td:nth-child(4) { min-width: 140px; } /* Timestamp */

    .force-scroll-table th:nth-child(5),
    .force-scroll-table td:nth-child(5) { min-width: 120px; } /* Plant */

    .force-scroll-table th:nth-child(6),
    .force-scroll-table td:nth-child(6) { min-width: 150px; } /* Area */

    .force-scroll-table th:nth-child(7),
    .force-scroll-table td:nth-child(7) { min-width: 180px; } /* Nomenclature */

    .force-scroll-table th:nth-child(8),
    .force-scroll-table td:nth-child(8) { min-width: 200px; } /* Deskripsi */

    .force-scroll-table th:nth-child(9),
    .force-scroll-table td:nth-child(9) { min-width: 150px; } /* Status Peralatan */

    .force-scroll-table th:nth-child(10),
    .force-scroll-table td:nth-child(10) { min-width: 160px; } /* Maintenance */

    .force-scroll-table th:nth-child(11),
    .force-scroll-table td:nth-child(11) { min-width: 150px; } /* Komponen */

    .force-scroll-table th:nth-child(12),
    .force-scroll-table td:nth-child(12) { min-width: 200px; } /* Temuan */

    .force-scroll-table th:nth-child(13),
    .force-scroll-table td:nth-child(13) { min-width: 280px; } /* Material Master */

    .force-scroll-table th:nth-child(14),
    .force-scroll-table td:nth-child(14) { min-width: 180px; } /* Action */

    .force-scroll-table th:nth-child(15),
    .force-scroll-table td:nth-child(15) { min-width: 140px; } /* Status Pekerjaan */

    .force-scroll-table th:nth-child(16),
    .force-scroll-table td:nth-child(16) { min-width: 140px; } /* Start Date */

    .force-scroll-table th:nth-child(17),
    .force-scroll-table td:nth-child(17) { min-width: 140px; } /* Finish Date */

    .force-scroll-table th:nth-child(18),
    .force-scroll-table td:nth-child(18) { min-width: 110px; } /* Start Hour */

    .force-scroll-table th:nth-child(19),
    .force-scroll-table td:nth-child(19) { min-width: 110px; } /* Finish Hour */

    .force-scroll-table th:nth-child(20),
    .force-scroll-table td:nth-child(20) { min-width: 140px; } /* Total Duration */

    .force-scroll-table th:nth-child(21),
    .force-scroll-table td:nth-child(21) { min-width: 120px; } /* Foto Sebelum */

    .force-scroll-table th:nth-child(22),
    .force-scroll-table td:nth-child(22) { min-width: 120px; } /* Foto Sesudah */

    .force-scroll-table th:nth-child(23),
    .force-scroll-table td:nth-child(23) { min-width: 200px; } /* Keterangan */

    .force-scroll-table th:nth-child(24),
    .force-scroll-table td:nth-child(24) { min-width: 120px; } /* No MSO */

    .force-scroll-table th:nth-child(25),
    .force-scroll-table td:nth-child(25) { min-width: 180px; } /* Options */


    /* ============================================================================
    STATUS BADGES
    ============================================================================ */

    .status-badge {
        display: inline-block;
        padding: 0.375rem 0.875rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.813rem;
        white-space: nowrap;
        text-align: center;
    }

    .status-open {
        background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
        color: #92400E;
        border: 1px solid #FCD34D;
    }

    .status-progress {
        background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);
        color: #1E40AF;
        border: 1px solid #93C5FD;
    }

    .status-closed {
        background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
        color: #065F46;
        border: 1px solid #6EE7B7;
    }


    /* ============================================================================
    ACTION BUTTONS
    ============================================================================ */

    .btn-view {
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        color: var(--pure-white);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.813rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
        white-space: nowrap;
    }

    .btn-view:hover {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }

    .btn-delete-confirm {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.813rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
        white-space: nowrap;
    }

    .btn-delete-confirm:hover {
        background: linear-gradient(135deg, var(--dark-red) 0%, #7F1D1D 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
    }


    /* ============================================================================
    TABLE IMAGES
    ============================================================================ */

    .table-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid var(--light-gray);
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .table-image:hover {
        transform: scale(1.1);
        border-color: var(--primary-red);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        z-index: 100;
    }


    /* ============================================================================
    PAGINATION
    ============================================================================ */

    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }


    /* ============================================================================
    UTILITY CLASSES
    ============================================================================ */

    .red-accent {
        width: 4px;
        height: 100%;
        background: var(--primary-red);
        position: absolute;
        left: 0;
        top: 0;
    }

    .data-empty {
        text-align: center;
        padding: 3rem;
        color: var(--medium-gray);
        font-size: 1.125rem;
    }


    /* ============================================================================
    ANIMATIONS
    ============================================================================ */

    @keyframes fadeInOut {
        0%, 100% { opacity: 0; }
        50% { opacity: 1; }
    }


    /* ============================================================================
    RESPONSIVE DESIGN
    ============================================================================ */

    @media (max-width: 768px) {
        .mso-container {
            padding: 1rem;
        }

        .page-header {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .filter-container {
            padding: 1rem;
        }

        .search-input {
            width: 100%;
        }

        .force-scroll-table {
            min-width: 3000px;
        }
        
        .force-scroll-table th,
        .force-scroll-table td {
            padding: 0.75rem;
            font-size: 0.813rem;
        }
    }

    @media (max-width: 480px) {
        .page-title {
            font-size: 1.25rem;
        }

        .btn-search,
        .btn-add {
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
        }
    }

    /* Material Master Form Container */
    .material-form {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        width: 100%;
    }

    /* Material Select Wrapper */
    .material-select-wrapper {
        width: 100%;
    }

    .material-select-wrapper select {
        width: 100% !important;
        min-width: 240px !important;
        max-width: 100% !important;
    }

    /* Material Master Confirm Button */
    .btn-confirm-material {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: var(--pure-white);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(22, 163, 74, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
        width: 100%;
        white-space: nowrap;
        margin-top: 0.5rem;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-confirm-material:hover {
        background: linear-gradient(135deg, #15803d 0%, #14532d 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(22, 163, 74, 0.4);
    }

    .btn-confirm-material:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(22, 163, 74, 0.2);
    }

    /* Adjust column width for Material Master */
    .force-scroll-table th:nth-child(13) { 
        min-width: 280px !important; 
    }

    .force-scroll-table td:nth-child(13) {
        min-width: 280px !important;
        padding: 0.75rem !important;
    }
</style>

<div class="mso-container">

    <!-- HEADER -->
    <div class="page-header">
        <h1 class="page-title">📋 MSO Transactions</h1>
        <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Manajemen dan Monitoring Transaksi MSO</p>
    </div>

    <!-- FILTERS & SEARCH -->
    <div class="filter-container">
        <form method="GET" action="{{ route('mso.index') }}" id="filter-form">
            <div class="flex flex-wrap justify-between items-center gap-4">

                <!-- Search Box -->
                <div class="flex items-center gap-3">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="🔍 Cari No MSO / Area / Komponen..."
                           class="search-input">
                    
                    <button type="submit" class="btn-search">
                        Search
                    </button>
                </div>

                <!-- Dropdown Filters -->
                <div class="flex gap-3 flex-wrap items-center">

                    <!-- Filter Plant -->
                    <select name="plant" 
                            onchange="document.getElementById('filter-form').submit()" 
                            class="filter-select">
                        <option value="">🔧 Filter Plant</option>
                        @foreach($plants as $pl)
                            <option value="{{ $pl->id }}" {{ request('plant') == $pl->id ? 'selected' : '' }}>
                                {{ $pl->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Filter Status -->
                    <select name="status" 
                            onchange="document.getElementById('filter-form').submit()" 
                            class="filter-select">
                        <option value="">📌 Status Pekerjaan</option>
                        <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="On Progress" {{ request('status') == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                        <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>

                    <!-- Button Tambah -->
                    <a href="{{ route('mso.create') }}" class="btn-add">
                        <span>➕</span>
                        <span>Tambah MSO</span>
                    </a>

                </div>

            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="scroll-wrapper">
        <table class="force-scroll-table">
            <thead>
                <tr>
                    <th>ID Trans</th>
                    <th>Sub ID</th>
                    <th>User</th>
                    <th>Time Stamp</th>
                    <th>Plant</th>
                    <th>Area</th>
                    <th>Nomenclature</th>
                    <th>Deskripsi</th>
                    <th>Status Peralatan</th>
                    <th>Jenis Maintenance</th>
                    <th>Komponen</th>
                    <th>Temuan Abnormalitas</th>
                    <th>Material Master</th>
                    <th>Action</th>
                    <th>Status Pekerjaan</th>
                    <th>Start Date</th>
                    <th>Finish Date</th>
                    <th>Start Hour</th>
                    <th>Finish Hour</th>
                    <th>Total Duration</th>
                    <th>Foto Sebelum</th>
                    <th>Foto Sesudah</th>
                    <th>Keterangan</th>
                    <th>No MSO</th>
                    <th>Options</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($rows as $row)
                <tr>
                    {{-- ID TRANS --}}
                    <td><strong>{{ $row->transaction->id_trans }}</strong></td>

                    {{-- SUB ID --}}
                    <td>{{ $row->sub_id }}</td>

                    {{-- USER --}}
                    <td>{{ $row->transaction->user->name ?? '-' }}</td>

                    {{-- TIMESTAMP --}}
                    <td>{{ $row->created_at->format('d/m/Y H:i') }}</td>

                    {{-- PLANT --}}
                    <td>{{ $row->transaction->plant->name ?? '-' }}</td>

                    {{-- AREA --}}
                    <td>{{ $row->transaction->area->name ?? '-' }}</td>

                    {{-- NOMENCLATURE --}}
                    <td>{{ $row->transaction->nomenclature->name ?? '-' }}</td>

                    {{-- DESKRIPSI --}}
                    <td>{{ $row->transaction->nomenclature->description ?? '-' }}</td>

                    {{-- STATUS PERALATAN --}}
                    <td>{{ $row->transaction->status_peralatan ?? '-' }}</td>

                    {{-- MAINTENANCE TYPE --}}
                    <td>{{ $row->transaction->maintenanceType->name ?? '-' }}</td>

                    {{-- KOMPONEN --}}
                    <td>{{ $row->component->name ?? '-' }}</td>

                    {{-- TEMUAN --}}
                    <td>{{ $row->temuan ?? '-' }}</td>

                    {{-- MATERIAL MASTER COLUMN --}}
                    <td>
                        @if(auth()->user()->hasRole('Supervisor'))
                            {{-- SUPERVISOR: Can Edit with Select2 + Confirm Button --}}
                            <form method="POST"
                                  action="{{ route('mso.finding.update-material', $row->id) }}"
                                  class="material-form"
                                  id="material-form-{{ $row->id }}">
                                @csrf
                                @method('PUT')

                                <div class="material-select-wrapper">
                                    <select name="material_master_id"
                                            class="filter-select select2-material"
                                            data-finding-id="{{ $row->id }}"
                                            data-original-value="{{ $row->material_master_id ?? '' }}">
                                        <option value="">- Pilih Material -</option>

                                        @foreach($materialMasters as $mm)
                                            <option value="{{ $mm->id }}"
                                                {{ (old('material_master_id') == $mm->id || $row->material_master_id == $mm->id) ? 'selected' : '' }}>
                                                {{ $mm->material_code }} - {{ $mm->material_description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Confirm Button - HIDDEN by default --}}
                                <button type="submit" 
                                        class="btn-confirm-material"
                                        id="confirm-btn-{{ $row->id }}"
                                        style="display: none;"
                                        title="Simpan Material Master">
                                    <span>✅</span>
                                    <span>Simpan Perubahan</span>
                                </button>
                            </form>
                        @else
                            {{-- NON-SUPERVISOR: View Only --}}
                            <span style="color: var(--dark-gray); font-weight: 500;">
                                @if($row->materialMaster)
                                    {{ $row->materialMaster->material_code }}
                                    - {{ $row->materialMaster->material_description }}
                                @else
                                    -
                                @endif
                            </span>
                        @endif
                    </td>

                    {{-- ACTION --}}
                    <td>{{ $row->action ?? '-' }}</td>

                    {{-- STATUS PEKERJAAN (dengan auto-update indicator) --}}
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 0.25rem; align-items: flex-start;">
                            @if($row->transaction->status_pekerjaan == 'Open')
                                <span class="status-badge status-open">⏸️ Open</span>
                            @elseif($row->transaction->status_pekerjaan == 'On Progress')
                                <span class="status-badge status-progress">🔄 On Progress</span>
                            @elseif($row->transaction->status_pekerjaan == 'Closed')
                                <span class="status-badge status-closed">✅ Closed</span>
                            @else
                                <span class="status-badge">{{ $row->transaction->status_pekerjaan }}</span>
                            @endif

                            {{-- Indicator Auto-Update --}}
                            @if($row->transaction->status_pekerjaan == 'Closed' && $row->transaction->total_duration)
                                <span style="font-size: 0.65rem; color: var(--medium-gray); font-style: italic;">
                                    (Auto-updated)
                                </span>
                            @endif
                        </div>
                    </td>

                    {{-- START DATE --}}
                    <td>
                        @if(auth()->user()->hasRole('Supervisor'))
                            <form method="POST" action="{{ route('mso.update-time', $row->transaction->id) }}" style="margin: 0;">
                                @csrf
                                @method('PUT')
                                <input type="date"
                                    name="start_date"
                                    value="{{ $row->transaction->start_date ? \Carbon\Carbon::parse($row->transaction->start_date)->format('Y-m-d') : '' }}"
                                    onchange="this.form.submit()"
                                    style="border: 2px solid var(--light-gray); padding: 0.5rem; border-radius: 6px; width: 140px; cursor: pointer; transition: all 0.3s ease;"
                                    onfocus="this.style.borderColor='var(--primary-red)'; this.style.boxShadow='0 0 0 3px var(--light-red)';"
                                    onblur="this.style.borderColor='var(--light-gray)'; this.style.boxShadow='none';">
                            </form>
                        @else
                            <span style="color: var(--dark-gray); font-weight: 500;">
                                {{ $row->transaction->start_date ? \Carbon\Carbon::parse($row->transaction->start_date)->format('d-m-Y') : '-' }}
                            </span>
                        @endif
                    </td>

                    {{-- FINISH DATE --}}
                    <td>
                        @if(auth()->user()->hasRole('Supervisor'))
                            <form method="POST" action="{{ route('mso.update-time', $row->transaction->id) }}" style="margin: 0;">
                                @csrf
                                @method('PUT')
                                <input type="date"
                                    name="finish_date"
                                    value="{{ $row->transaction->finish_date ? \Carbon\Carbon::parse($row->transaction->finish_date)->format('Y-m-d') : '' }}"
                                    onchange="this.form.submit()"
                                    style="border: 2px solid var(--light-gray); padding: 0.5rem; border-radius: 6px; width: 140px; cursor: pointer; transition: all 0.3s ease;"
                                    onfocus="this.style.borderColor='var(--primary-red)'; this.style.boxShadow='0 0 0 3px var(--light-red)';"
                                    onblur="this.style.borderColor='var(--light-gray)'; this.style.boxShadow='none';">
                            </form>
                        @else
                            <span style="color: var(--dark-gray); font-weight: 500;">
                                {{ $row->transaction->finish_date ? \Carbon\Carbon::parse($row->transaction->finish_date)->format('d-m-Y') : '-' }}
                            </span>
                        @endif
                    </td>

                    {{-- START HOUR --}}
                    <td>
                        @if(auth()->user()->hasRole('Supervisor'))
                            <form method="POST" action="{{ route('mso.update-time', $row->transaction->id) }}" style="margin: 0;">
                                @csrf
                                @method('PUT')
                                <input type="time"
                                    name="start_hour"
                                    value="{{ $row->transaction->start_hour }}"
                                    onchange="this.form.submit()"
                                    style="border: 2px solid var(--light-gray); padding: 0.5rem; border-radius: 6px; width: 110px; cursor: pointer; transition: all 0.3s ease;"
                                    onfocus="this.style.borderColor='var(--primary-red)'; this.style.boxShadow='0 0 0 3px var(--light-red)';"
                                    onblur="this.style.borderColor='var(--light-gray)'; this.style.boxShadow='none';">
                            </form>
                        @else
                            <span style="color: var(--dark-gray); font-weight: 500;">
                                {{ $row->transaction->start_hour ?? '-' }}
                            </span>
                        @endif
                    </td>

                    {{-- FINISH HOUR --}}
                    <td>
                        @if(auth()->user()->hasRole('Supervisor'))
                            <form method="POST" action="{{ route('mso.update-time', $row->transaction->id) }}" style="margin: 0;">
                                @csrf
                                @method('PUT')
                                <input type="time"
                                    name="finish_hour"
                                    value="{{ $row->transaction->finish_hour }}"
                                    onchange="this.form.submit()"
                                    style="border: 2px solid var(--light-gray); padding: 0.5rem; border-radius: 6px; width: 110px; cursor: pointer; transition: all 0.3s ease;"
                                    onfocus="this.style.borderColor='var(--primary-red)'; this.style.boxShadow='0 0 0 3px var(--light-red)';"
                                    onblur="this.style.borderColor='var(--light-gray)'; this.style.boxShadow='none';">
                            </form>
                        @else
                            <span style="color: var(--dark-gray); font-weight: 500;">
                                {{ $row->transaction->finish_hour ?? '-' }}
                            </span>
                        @endif
                    </td>

                    {{-- TOTAL DURATION (READ ONLY FOR ALL) --}}
                    <td style="text-align: center;">
                        @if($row->transaction->total_duration)
                            <strong style="color: var(--primary-red); font-size: 1rem;">
                                {{ number_format($row->transaction->total_duration, 2) }} jam
                            </strong>
                            <div style="font-size: 0.75rem; color: var(--medium-gray); margin-top: 0.25rem;">
                                {{ floor($row->transaction->total_duration) }}h {{ round(($row->transaction->total_duration - floor($row->transaction->total_duration)) * 60) }}m
                            </div>
                        @else
                            <span style="color: var(--medium-gray);">-</span>
                        @endif
                    </td>

                    {{-- FOTO BEFORE --}}
                    <td>
                        @foreach($row->transaction->photos as $p)
                            @if($p->type == 'before')
                                <img src="{{ asset('storage/'.$p->path) }}" class="table-image" alt="Before">
                            @endif
                        @endforeach
                    </td>

                    {{-- FOTO AFTER --}}
                    <td>
                        @foreach($row->transaction->photos as $p)
                            @if($p->type == 'after')
                                <img src="{{ asset('storage/'.$p->path) }}" class="table-image" alt="After">
                            @endif
                        @endforeach
                    </td>

                    {{-- KETERANGAN --}}
                    <td>{{ $row->transaction->keterangan ?? '-' }}</td>

                    {{-- NO MSO --}}
                    <td><strong>{{ $row->transaction->no_mso }}</strong></td>

                    {{-- OPTIONS --}}
                    <td style="text-align: center;">
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; align-items: center;">
                            {{-- View Button (for all roles) --}}
                            <a href="{{ route('mso.show', $row->transaction->id) }}" class="btn-view">
                                <span>👁️</span>
                                <span>View</span>
                            </a>

                            <a href="{{ route('monitoring.export', $row->transaction->id_trans) }}" 
                            class="btn-view" style="background:#7c3aed">
                                📦 Export Monitoring
                            </a>

                            {{-- Delete Button (Supervisor only) --}}
                            @if(auth()->user()->hasRole('Supervisor'))
                                @php
                                    $totalFindings = $row->transaction->findings->count();
                                    $isLastFinding = $totalFindings <= 1;
                                @endphp

                                @if($isLastFinding)
                                    {{-- Jika ini finding terakhir, hapus seluruh transaction --}}
                                    <form method="POST"
                                        action="{{ route('mso.destroy', $row->transaction->id) }}"
                                        onsubmit="return confirm(
                                            '⚠️ HAPUS MSO TRANSACTION?\n\n' +
                                            'Ini adalah finding terakhir!\n\n' +
                                            'No MSO : {{ $row->transaction->no_mso }}\n' +
                                            'ID Trans : {{ $row->transaction->id_trans }}\n' +
                                            'Sub ID : {{ $row->sub_id }}\n\n' +
                                            'SELURUH MSO & FOTO AKAN TERHAPUS PERMANEN!'
                                        )">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-delete-confirm">
                                            🗑️ Delete MSO
                                        </button>
                                    </form>
                                @else
                                    {{-- Jika masih ada finding lain, hapus finding ini saja --}}
                                    <form method="POST"
                                        action="{{ route('mso.finding.destroy', $row->id) }}"
                                        onsubmit="return confirm(
                                            '⚠️ HAPUS FINDING?\n\n' +
                                            'ID Trans : {{ $row->transaction->id_trans }}\n' +
                                            'Sub ID : {{ $row->sub_id }}\n' +
                                            'Komponen : {{ $row->component->name ?? '-' }}\n' +
                                            'Temuan : {{ Str::limit($row->temuan ?? '-', 50) }}\n\n' +
                                            'Sisa Finding : {{ $totalFindings - 1 }}\n\n' +
                                            'Data finding ini akan terhapus permanen!'
                                        )">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-delete-confirm">
                                            🗑️ Delete Row
                                        </button>
                                    </form>
                                @endif
                            @endif

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="25" class="data-empty">
                        📭 Tidak ada data MSO yang ditemukan
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>
    </div>

    <!-- PAGINATION -->
    <div class="pagination-wrapper">
        {{ $rows->appends(request()->query())->links() }}
    </div>

</div>

@endsection

@push('scripts')
<script>
    // ============================================================================
    // 🛡️ PREVENT DOUBLE LOADING - Check if jQuery exists
    // ============================================================================
    if (typeof jQuery === 'undefined') {
        console.error('❌ jQuery NOT FOUND! Loading jQuery...');
        
        // Load jQuery if not available
        var jQueryScript = document.createElement('script');
        jQueryScript.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
        document.head.appendChild(jQueryScript);
        
        // Load Select2 after jQuery
        var select2Script = document.createElement('script');
        select2Script.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
        
        jQueryScript.onload = function() {
            console.log('✅ jQuery loaded successfully!');
            document.head.appendChild(select2Script);
            select2Script.onload = function() {
                console.log('✅ Select2 loaded successfully!');
                initializeSelect2();
            };
        };
    } else {
        console.log('✅ jQuery already available, version:', jQuery.fn.jquery);
        
        // Check if Select2 is available
        if (typeof jQuery.fn.select2 === 'undefined') {
            console.log('⚠️ Select2 not loaded yet, loading...');
            var select2Script = document.createElement('script');
            select2Script.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
            select2Script.onload = function() {
                console.log('✅ Select2 loaded successfully!');
                initializeSelect2();
            };
            document.head.appendChild(select2Script);
        } else {
            console.log('✅ Select2 already available!');
            // Initialize immediately
            jQuery(document).ready(function($) {
                initializeSelect2();
            });
        }
    }

    // ============================================================================
    // 🎯 MAIN INITIALIZATION FUNCTION
    // ============================================================================
    function initializeSelect2() {
        // Use jQuery with $ alias to ensure compatibility
        (function($) {
            'use strict';
            
            console.log('🔧 Starting Select2 Initialization...');
            console.log('jQuery version:', $.fn.jquery);
            console.log('Select2 available:', typeof $.fn.select2 !== 'undefined');
            
            // ====================================================================
            // 🐛 DEBUG: Check if elements exist
            // ====================================================================
            var selectElements = $('.select2-material');
            console.log('📊 Found', selectElements.length, 'select2-material elements');
            
            if (selectElements.length === 0) {
                console.error('❌ NO SELECT2 ELEMENTS FOUND!');
                return;
            }
            
            // ====================================================================
            // 🎯 INITIALIZE SELECT2
            // ====================================================================
            selectElements.each(function(index) {
                var $select = $(this);
                var findingId = $select.data('finding-id');
                var originalValue = $select.data('original-value');
                
                console.log('🔧 Initializing Select2 #' + (index + 1) + ':', {
                    findingId: findingId,
                    originalValue: originalValue,
                    currentValue: $select.val()
                });
                
                // Destroy existing Select2 instance if exists
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
                
                // Initialize Select2
                $select.select2({
                    placeholder: '- Pilih Material -',
                    allowClear: true,
                    width: '100%',
                    dropdownAutoWidth: true
                });
                
                console.log('✅ Select2 initialized for Finding', findingId);
            });
            
            // ====================================================================
            // 🎯 HANDLE CHANGE EVENTS
            // ====================================================================
            
            // Method 1: Select2 specific events
            $('.select2-material').on('select2:select select2:clear', function(e) {
                console.log('🎯 Select2 event triggered:', e.type);
                handleMaterialChange($(this));
            });
            
            // Method 2: Native change event (backup)
            $('.select2-material').on('change', function(e) {
                console.log('🎯 Native change triggered');
                handleMaterialChange($(this));
            });
            
            // ====================================================================
            // 📝 HANDLE MATERIAL CHANGE
            // ====================================================================
            function handleMaterialChange($select) {
                var findingId = $select.data('finding-id');
                var originalValue = String($select.data('original-value') || '');
                var currentValue = String($select.val() || '');
                var confirmBtn = $('#confirm-btn-' + findingId);
                
                console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                console.log('📋 Material Change Details:');
                console.log('  Finding ID:', findingId);
                console.log('  Original Value:', originalValue);
                console.log('  Current Value:', currentValue);
                console.log('  Values Different?', currentValue !== originalValue);
                console.log('  Confirm Button Found?', confirmBtn.length > 0);
                console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                
                // Check for changes from original value
                if (currentValue !== originalValue) {
                    // ✅ CHANGED - SHOW BUTTON
                    confirmBtn.addClass('active');
                    console.log('✅ Button SHOWN for Finding', findingId);
                } else {
                    // ❌ NO CHANGE - HIDE BUTTON
                    confirmBtn.removeClass('active');
                    console.log('❌ Button HIDDEN for Finding', findingId);
                }
            }
            
            // ====================================================================
            // ✅ FORM SUBMISSION CONFIRMATION
            // ====================================================================
            $('.material-form').on('submit', function(e) {
                console.log('📤 Form submit triggered!');
                
                var $form = $(this);
                var $select = $form.find('select[name="material_master_id"]');
                var selectedOption = $select.find('option:selected');
                var materialText = selectedOption.text();
                var findingId = $select.data('finding-id');
                var selectVal = $select.val();
                
                console.log('Form submit details:', {
                    findingId: findingId,
                    selectedValue: selectVal,
                    materialText: materialText
                });
                
                if (!selectVal || selectVal === '' || selectVal === null) {
                    e.preventDefault();
                    alert('⚠️ Pilih Material Master terlebih dahulu!');
                    console.log('❌ Submit blocked: No material selected');
                    return false;
                }
                
                var confirmMessage = 
                    '✅ Konfirmasi Simpan Material Master\n\n' +
                    'Finding ID: ' + findingId + '\n' +
                    'Material Baru: ' + materialText + '\n\n' +
                    'Apakah Anda yakin ingin menyimpan perubahan ini?';
                
                if (!confirm(confirmMessage)) {
                    e.preventDefault();
                    console.log('❌ Submit cancelled by user');
                    return false;
                }
                
                console.log('✅ Form will be submitted!');
                return true;
            });
            
            // ====================================================================
            // 🐛 DEBUG: Test button visibility
            // ====================================================================
            console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            console.log('🧪 Testing button visibility...');
            
            selectElements.each(function() {
                var findingId = $(this).data('finding-id');
                var confirmBtn = $('#confirm-btn-' + findingId);
                
                console.log('Finding ' + findingId + ':', {
                    selectExists: $(this).length > 0,
                    buttonExists: confirmBtn.length > 0,
                    buttonVisible: confirmBtn.is(':visible'),
                    buttonDisplay: confirmBtn.css('display')
                });
            });
            console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            
            console.log('✅ Select2 initialization completed!');
            
        })(jQuery); // End of jQuery wrapper
    }

    // ============================================================================
    // 💫 ANIMATION STYLES
    // ============================================================================
    var style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 2px 4px rgba(22, 163, 74, 0.2);
            }
            50% {
                transform: scale(1.02);
                box-shadow: 0 4px 8px rgba(22, 163, 74, 0.4);
            }
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Default state - HIDDEN */
        .btn-confirm-material {
            display: none !important;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }
        
        /* Active state - SHOWN */
        .btn-confirm-material.active {
            display: flex !important;
            opacity: 1 !important;
            visibility: visible !important;
            animation: slideDown 0.3s ease-out, pulse 1.5s infinite;
        }
        
        .btn-confirm-material:hover {
            background: linear-gradient(135deg, #15803d 0%, #166534 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.4);
        }
    `;
    document.head.appendChild(style);

    console.log('🎨 Animation styles added!');
</script>
@endpush