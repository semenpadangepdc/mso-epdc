@extends('layouts.app')

@section('content')

<style>
    /* ============================================================================
    ACTIVITY LOG - OPTIMIZED COMPACT STYLESHEET
    Using MSO color palette for consistency with reduced padding
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

    /* === MAIN CONTAINER === */
    .activity-container {
        background: linear-gradient(135deg, var(--off-white) 0%, var(--pure-white) 100%);
        min-height: 100vh;
        padding: 1.5rem;
    }

    /* === PAGE HEADER - COMPACT === */
    .page-header {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        padding: 1.25rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(220, 38, 38, 0.2);
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-subtitle {
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
        font-size: 0.875rem;
        font-weight: 400;
    }

    .header-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.4rem 0.85rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* === TABLE CONTAINER === */
    .table-container {
        background: var(--pure-white);
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 2px solid var(--light-gray);
        overflow: hidden;
    }

    /* === TABLE STYLING - COMPACT VERSION === */
    .activity-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }

    .activity-table thead {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .activity-table thead th {
        color: var(--pure-white);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        padding: 0.75rem 0.85rem;
        text-align: left;
        border: none;
        white-space: nowrap;
    }

    .activity-table thead th i {
        margin-right: 0.35rem;
        opacity: 0.9;
        font-size: 0.7rem;
    }

    .activity-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid var(--light-gray);
    }

    .activity-table tbody tr:hover {
        background-color: var(--light-red);
        transform: scale(1.001);
    }

    .activity-table tbody tr:last-child {
        border-bottom: none;
    }

    /* REDUCED PADDING FOR COMPACT ROWS */
    .activity-table tbody td {
        padding: 0.5rem 0.85rem;
        color: var(--dark-gray);
        vertical-align: middle;
        font-size: 0.875rem;
        line-height: 1.3;
    }

    /* === AVATAR CIRCLE - COMPACT === */
    .avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.3);
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .user-name {
        font-weight: 600;
        color: var(--dark-gray);
        font-size: 0.875rem;
    }

    /* === TIME DISPLAY - INLINE COMPACT === */
    .time-display {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
    }

    .time-date {
        font-weight: 600;
        color: var(--dark-gray);
    }

    .time-clock {
        color: var(--medium-gray);
    }

    .time-separator {
        color: var(--light-gray);
        font-weight: bold;
    }

    /* === ACTION BADGES - COMPACT === */
    .badge-action {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: 5px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .badge-action i {
        font-size: 0.7rem;
    }

    .badge-created {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: var(--pure-white);
        box-shadow: 0 2px 4px rgba(22, 163, 74, 0.2);
    }

    .badge-updated {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: var(--pure-white);
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
    }

    .badge-deleted {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: var(--pure-white);
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
    }

    .badge-login {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        color: var(--pure-white);
        box-shadow: 0 2px 4px rgba(124, 58, 237, 0.2);
    }

    .badge-default {
        background: linear-gradient(135deg, var(--medium-gray) 0%, var(--dark-gray) 100%);
        color: var(--pure-white);
        box-shadow: 0 2px 4px rgba(107, 114, 128, 0.2);
    }

    /* === OBJECT BADGE - COMPACT === */
    .badge-object {
        background: linear-gradient(135deg, var(--off-white) 0%, var(--light-gray) 100%);
        color: var(--dark-gray);
        padding: 0.35rem 0.75rem;
        border-radius: 5px;
        font-weight: 600;
        font-size: 0.75rem;
        border: 1px solid var(--light-gray);
        display: inline-block;
    }

    /* === DETAIL BUTTON - COMPACT === */
    .btn-detail {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        border: none;
        padding: 0.4rem 0.85rem;
        border-radius: 5px;
        font-weight: 600;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.3);
    }

    .btn-detail:hover {
        background: linear-gradient(135deg, var(--dark-red) 0%, #7f1d1d 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(220, 38, 38, 0.4);
    }

    .btn-detail i {
        font-size: 0.7rem;
    }

    /* === MODAL STYLING === */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        background-color: #ffffff !important;
    }

    .modal-body {
        background-color: #ffffff !important;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        border-radius: 12px 12px 0 0;
        padding: 1.25rem 1.5rem;
        border-bottom: none;
    }

    .modal-title {
        font-weight: 700;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        background: var(--off-white);
        border-top: 2px solid var(--light-gray);
        padding: 1rem 1.5rem;
        border-radius: 0 0 12px 12px;
    }

    /* === DETAIL SECTION === */
    .detail-section-title {
        font-weight: 700;
        color: var(--dark-gray);
        margin-bottom: 0.75rem;
        font-size: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--light-gray);
    }

    .info-table {
        margin: 0;
    }

    .info-table td {
        padding: 0.5rem 0;
        font-size: 0.875rem;
    }

    .info-label {
        font-weight: 600;
        color: var(--medium-gray);
        width: 120px;
    }

    .info-value {
        color: var(--dark-gray);
    }

    .log-detail-container {
        background: var(--off-white);
        border: 2px solid var(--light-gray);
        border-radius: 8px;
        padding: 1rem;
        max-height: 300px;
        overflow-y: auto;
    }

    .log-detail-container pre {
        margin: 0;
        color: var(--dark-gray);
        font-size: 0.875rem;
        line-height: 1.5;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .btn-close-modal {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        border: none;
        padding: 0.5rem 1.25rem;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.3);
    }

    .btn-close-modal:hover {
        background: linear-gradient(135deg, var(--dark-red) 0%, #7f1d1d 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(220, 38, 38, 0.4);
    }

    /* === EMPTY STATE === */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--medium-gray);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        margin: 0;
        font-size: 1rem;
    }

    /* === PAGINATION - COMPACT === */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background: var(--off-white);
        border-top: 2px solid var(--light-gray);
    }

    .pagination-info {
        color: var(--medium-gray);
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* === RESPONSIVE === */
    @media (max-width: 768px) {
        .activity-container {
            padding: 1rem;
        }

        .page-header {
            padding: 1rem;
        }

        .page-title {
            font-size: 1.25rem;
        }

        .header-badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
        }

        .activity-table thead th {
            font-size: 0.65rem;
            padding: 0.6rem 0.5rem;
        }

        .activity-table tbody td {
            padding: 0.4rem 0.5rem;
            font-size: 0.8rem;
        }

        .avatar-circle {
            width: 28px;
            height: 28px;
            font-size: 0.75rem;
        }

        .badge-action,
        .badge-object,
        .btn-detail {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
        }

        .time-display {
            flex-direction: column;
            gap: 0.15rem;
            align-items: flex-start;
        }

        .time-separator {
            display: none;
        }

        .pagination-container {
            flex-direction: column;
            gap: 1rem;
            padding: 1rem;
        }
    }

    /* === MODAL FALLBACK BASE === */
    #sharedLogModal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        z-index: 1055;
        overflow-x: hidden;
        overflow-y: auto;
        outline: 0;
    }
    #sharedLogModal.show {
        display: block !important;
    }

    /* === SCROLLBAR STYLING === */
    .log-detail-container::-webkit-scrollbar,
    .table-container::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .log-detail-container::-webkit-scrollbar-track,
    .table-container::-webkit-scrollbar-track {
        background: var(--light-gray);
        border-radius: 4px;
    }

    .log-detail-container::-webkit-scrollbar-thumb,
    .table-container::-webkit-scrollbar-thumb {
        background: var(--primary-red);
        border-radius: 4px;
    }

    .log-detail-container::-webkit-scrollbar-thumb:hover,
    .table-container::-webkit-scrollbar-thumb:hover {
        background: var(--dark-red);
    }
</style>

<!-- Main Container -->
<div class="activity-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-history"></i>
                    Activity Log
                </h1>
                <p class="page-subtitle">Riwayat aktivitas dan perubahan data sistem</p>
            </div>
            <div>
                <span class="header-badge">
                    <i class="fas fa-database me-2"></i>
                    Total: {{ $logs->total() }} Records
                </span>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <div style="overflow-x: auto;">
            <table class="activity-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">
                            <i class="far fa-clock"></i>
                            Waktu
                        </th>
                        <th style="width: 20%;">
                            <i class="far fa-user"></i>
                            User
                        </th>
                        <th style="width: 20%;">
                            <i class="fas fa-bolt"></i>
                            Aksi
                        </th>
                        <th style="width: 20%;">
                            <i class="fas fa-cube"></i>
                            Objek
                        </th>
                        <th style="width: 25%;">
                            <i class="fas fa-info-circle"></i>
                            Detail
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <!-- Time - Inline Display -->
                            <td>
                                <div class="time-display">
                                    <span class="time-date">{{ $log->created_at->format('d M Y') }}</span>
                                    <span class="time-separator">•</span>
                                    <span class="time-clock">{{ $log->created_at->format('H:i') }}</span>
                                </div>
                            </td>

                            <!-- User -->
                            <td>
                                <div class="user-info">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($log->causer->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <span class="user-name">{{ $log->causer->name ?? 'System' }}</span>
                                </div>
                            </td>

                            <!-- Action -->
                            <td>
                                @php
                                    $badgeClass = 'badge-default';
                                    $icon = 'fa-circle';
                                    $description = strtolower($log->description);
                                    
                                    if(str_contains($description, 'created') || str_contains($description, 'tambah')) {
                                        $badgeClass = 'badge-created';
                                        $icon = 'fa-plus-circle';
                                    } elseif(str_contains($description, 'updated') || str_contains($description, 'ubah') || str_contains($description, 'edit')) {
                                        $badgeClass = 'badge-updated';
                                        $icon = 'fa-edit';
                                    } elseif(str_contains($description, 'deleted') || str_contains($description, 'hapus')) {
                                        $badgeClass = 'badge-deleted';
                                        $icon = 'fa-trash-alt';
                                    } elseif(str_contains($description, 'login')) {
                                        $badgeClass = 'badge-login';
                                        $icon = 'fa-sign-in-alt';
                                    }
                                @endphp
                                <span class="badge-action {{ $badgeClass }}">
                                    <i class="fas {{ $icon }}"></i>
                                    {{ ucfirst($log->description) }}
                                </span>
                            </td>

                            <!-- Object -->
                            <td>
                                <span class="badge-object">
                                    {{ class_basename($log->subject_type) }}
                                </span>
                            </td>

                            <!-- Detail Button -->
                            <td>
                                @if($log->properties->isNotEmpty())
                                    <button type="button"
                                            class="btn-detail show-detail"
                                            data-waktu="{{ $log->created_at->format('d M Y, H:i') }} WIB"
                                            data-user="{{ $log->causer->name ?? 'System' }}"
                                            data-aksi="{{ ucfirst($log->description) }}"
                                            data-objek="{{ class_basename($log->subject_type) }}"
                                            data-properties="{{ htmlspecialchars(json_encode($log->properties, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') }}">
                                        <i class="fas fa-eye"></i>
                                        Lihat Detail
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Belum ada data aktivitas</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="pagination-container">
                <div class="pagination-info">
                    Menampilkan {{ $logs->firstItem() }} - {{ $logs->lastItem() }} dari {{ $logs->total() }} data
                </div>
                <div>
                    {{ $logs->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- ============================================================
     SINGLE SHARED MODAL (diisi dinamis via JavaScript)
     ============================================================ -->
<div class="modal fade" id="sharedLogModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i>
                    Detail Aktivitas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Info Section -->
                <div class="mb-4">
                    <h6 class="detail-section-title">Informasi Aktivitas</h6>
                    <table class="info-table w-100">
                        <tr>
                            <td class="info-label">Waktu:</td>
                            <td class="info-value" id="modal-waktu"></td>
                        </tr>
                        <tr>
                            <td class="info-label">User:</td>
                            <td class="info-value" id="modal-user"></td>
                        </tr>
                        <tr>
                            <td class="info-label">Aksi:</td>
                            <td class="info-value" id="modal-aksi"></td>
                        </tr>
                        <tr>
                            <td class="info-label">Objek:</td>
                            <td class="info-value" id="modal-objek"></td>
                        </tr>
                    </table>
                </div>

                <!-- Detail Section -->
                <div>
                    <h6 class="detail-section-title">Detail Perubahan</h6>
                    <div class="log-detail-container">
                        <pre><code id="modal-properties"></code></pre>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-close-modal" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openDetailModal(btn) {
        var waktu      = btn.getAttribute('data-waktu');
        var user       = btn.getAttribute('data-user');
        var aksi       = btn.getAttribute('data-aksi');
        var objek      = btn.getAttribute('data-objek');
        var properties = btn.getAttribute('data-properties');

        document.getElementById('modal-waktu').textContent  = waktu;
        document.getElementById('modal-user').textContent   = user;
        document.getElementById('modal-aksi').textContent   = aksi;
        document.getElementById('modal-objek').textContent  = objek;

        try {
            var parsed = JSON.parse(properties);
            document.getElementById('modal-properties').textContent = JSON.stringify(parsed, null, 2);
        } catch (e) {
            document.getElementById('modal-properties').textContent = properties;
        }

        var modalEl = document.getElementById('sharedLogModal');

        // Coba Bootstrap JS dulu, fallback ke vanilla CSS toggle
        if (typeof bootstrap !== 'undefined') {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else if (typeof $ !== 'undefined') {
            // Fallback: jQuery Bootstrap
            $(modalEl).modal('show');
        } else {
            // Fallback: pure CSS/JS manual
            modalEl.style.display = 'block';
            modalEl.style.paddingRight = '17px';
            modalEl.classList.add('show');
            document.body.classList.add('modal-open');

            // Overlay backdrop
            var backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'manualBackdrop';
            document.body.appendChild(backdrop);

            // Tutup saat klik backdrop atau tombol close
            backdrop.addEventListener('click', closeManualModal);
            modalEl.querySelectorAll('[data-bs-dismiss="modal"], .btn-close-modal').forEach(function(el) {
                el.addEventListener('click', closeManualModal);
            });
        }
    }

    function closeManualModal() {
        var modalEl  = document.getElementById('sharedLogModal');
        var backdrop = document.getElementById('manualBackdrop');
        modalEl.style.display  = 'none';
        modalEl.classList.remove('show');
        document.body.classList.remove('modal-open');
        if (backdrop) backdrop.remove();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.show-detail').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openDetailModal(this);
            });
        });
    });
</script>

@endsection