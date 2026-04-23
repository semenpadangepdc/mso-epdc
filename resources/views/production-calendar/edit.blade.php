@extends('layouts.app')

@section('content')

<style>
    /* Root variables untuk konsistensi warna */
    :root {
        --red-primary: #dc2626;
        --red-dark: #991b1b;
        --red-light: #fecaca;
        --red-bg: #fef2f2;
        --black: #1f2937;
        --white: #ffffff;
        --gray-border: #e5e7eb;
        --gray-light: #f9fafb;
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

    .card-section {
        background: var(--red-bg);
        padding: 1.5rem;
        border-radius: 12px;
        border: 2px solid var(--red-light);
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(220, 38, 38, 0.1);
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
    }

    .btn-red:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
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

    .form-grid-2 {
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
        .form-grid-2, .form-grid-3 {
            grid-template-columns: 1fr;
        }
    }

    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid var(--gray-border);
    }

    .error-message {
        color: var(--red-dark);
        font-size: 0.75rem;
        margin-top: 0.25rem;
        font-weight: 500;
    }

    .select2-container--default .select2-selection--single {
        border: 2px solid var(--gray-border) !important;
        border-radius: 8px !important;
        height: auto !important;
        min-height: 46px !important;
        background: var(--white) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 46px !important;
        padding-left: 1rem !important;
        color: var(--black) !important;
        font-size: 0.875rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
        right: 1rem !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: var(--red-primary) !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
    }

    .select2-dropdown {
        border: 2px solid var(--red-primary) !important;
        border-radius: 8px !important;
    }

    .select2-results__option--highlighted {
        background-color: var(--red-primary) !important;
    }

    .form-group {
        margin-bottom: 0;
    }
</style>

<div class="container mx-auto px-4 py-6">

    <!-- Page Header -->
    <div class="page-header">
        <span style="font-size: 2rem;">✏️</span>
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; margin: 0;">Edit Production Calendar</h1>
            <p style="margin: 0; opacity: 0.85; font-size: 0.875rem;">Perbarui data kalender produksi</p>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <ul class="list-disc list-inside text-red-700 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Container -->
    <div class="form-container">
        <form method="POST" action="{{ route('production-calendar.update', $calendar->id) }}">
            @csrf
            @method('PUT')

            <!-- Section: Plant & Area Information -->
            <div class="section-header">
                <span>🏭</span>
                <span>Informasi Plant & Area</span>
            </div>

            <div class="card-section">
                <div class="form-grid-2">
                    <!-- Plant -->
                    <div class="form-group">
                        <label class="form-label">Plant</label>
                        <select name="plant_id" id="plant" class="form-select" required>
                            <option value="">-- Pilih Plant --</option>
                            @foreach($plants as $plant)
                                <option value="{{ $plant->id }}" {{ $calendar->plant_id == $plant->id ? 'selected' : '' }}>
                                    {{ $plant->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('plant_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Area -->
                    <div class="form-group">
                        <label class="form-label">Area</label>
                        <select name="area_id" id="area" class="form-select" required>
                            <option value="">-- Pilih Area --</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ $calendar->area_id == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('area_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section: Periode Information -->
            <div class="section-header">
                <span>📆</span>
                <span>Periode Produksi</span>
            </div>

            <div class="card-section">
                <div class="form-grid-2">
                    <!-- Tahun -->
                    <div class="form-group">
                        <label class="form-label">Tahun</label>
                        <input type="number" name="year" id="year"
                               value="{{ old('year', $calendar->year) }}"
                               class="form-input" min="2000" max="2100" required>
                        @error('year')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Bulan -->
                    <div class="form-group">
                        <label class="form-label">Bulan</label>
                        <select name="month" id="month" class="form-select" required>
                            <option value="">-- Pilih Bulan --</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('month', $calendar->month) == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                        @error('month')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section: Production Planning -->
            <div class="section-header">
                <span>⚙️</span>
                <span>Rencana Produksi</span>
            </div>

            <div class="card-section">
                <div class="form-grid-3">
                    <!-- Jumlah Hari dalam Bulan -->
                    <div class="form-group">
                        <label class="form-label">Jumlah Hari dalam Bulan</label>
                        <input type="number" name="total_days" id="total_days"
                               value="{{ old('total_days', $calendar->total_days) }}"
                               min="1" max="31" class="form-input" required>
                        @error('total_days')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Rencana Hari Jalan -->
                    <div class="form-group">
                        <label class="form-label">Rencana Hari Jalan</label>
                        <input type="number" name="planned_running_days" id="planned_running_days"
                               value="{{ old('planned_running_days', $calendar->planned_running_days) }}"
                               min="0" class="form-input" required>
                        @error('planned_running_days')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Rencana Stop Terencana -->
                    <div class="form-group">
                        <label class="form-label">Rencana Stop Terencana (Hari)</label>
                        <input type="number" name="planned_shutdown_days" id="planned_shutdown_days"
                               value="{{ old('planned_shutdown_days', $calendar->planned_shutdown_days) }}"
                               min="0" class="form-input" required>
                        @error('planned_shutdown_days')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Info jam (readonly, otomatis terhitung) -->
                <div class="form-grid-2 mt-4">
                    <div class="form-group">
                        <label class="form-label">Planned Production Hours (otomatis)</label>
                        <input type="text" id="preview_production_hours"
                               value="{{ $calendar->planned_production_hours }}"
                               class="form-input" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Planned Downtime Hours (otomatis)</label>
                        <input type="text" id="preview_downtime_hours"
                               value="{{ $calendar->planned_downtime_hours }}"
                               class="form-input" readonly>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="action-buttons">
                <a href="{{ route('production-calendar.index') }}" class="btn-gray">
                    <span>❌</span>
                    <span>Batal</span>
                </a>
                <button type="submit" class="btn-red">
                    <span>💾</span>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </form>
    </div>

</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Simpan area_id yang sedang aktif untuk di-restore setelah reload
    const currentAreaId = "{{ $calendar->area_id }}";

    $(document).ready(function() {
        $('#area').select2({
            placeholder: '-- Pilih Area --',
            allowClear: true,
            width: '100%'
        });

        // Auto-update preview jam ketika hari berubah
        $('#planned_running_days, #planned_shutdown_days').on('input', function() {
            updateHoursPreview();
        });

        $('#total_days, #planned_running_days').on('input', function() {
            calculatePlannedShutdown();
        });

        $('#year, #month').on('change', function() {
            calculateTotalDays();
        });
    });

    // Reload areas ketika Plant berubah
    document.getElementById('plant').addEventListener('change', function() {
        let plant_id = this.value;
        let areaSelect = $('#area');

        areaSelect.html('<option value="">Loading...</option>').trigger('change');

        if (!plant_id) {
            areaSelect.html('<option value="">-- Pilih Plant Terlebih Dahulu --</option>').trigger('change');
            return;
        }

        fetch(`/ajax/areas/${plant_id}`)
            .then(res => res.json())
            .then(data => {
                areaSelect.html('<option value="">-- Pilih Area --</option>');
                data.forEach(area => {
                    const selected = area.id == currentAreaId ? 'selected' : '';
                    areaSelect.append(`<option value="${area.id}" ${selected}>${area.name}</option>`);
                });
                areaSelect.trigger('change');
            })
            .catch(error => {
                console.error('Error:', error);
                areaSelect.html('<option value="">Error loading areas</option>').trigger('change');
            });
    });

    function calculateTotalDays() {
        const year  = document.getElementById('year').value;
        const month = document.getElementById('month').value;
        if (year && month) {
            const daysInMonth = new Date(year, month, 0).getDate();
            document.getElementById('total_days').value = daysInMonth;
            calculatePlannedShutdown();
        }
    }

    function calculatePlannedShutdown() {
        const totalDays   = parseInt(document.getElementById('total_days').value) || 0;
        const runningDays = parseInt(document.getElementById('planned_running_days').value) || 0;
        if (totalDays > 0 && runningDays <= totalDays) {
            document.getElementById('planned_shutdown_days').value = totalDays - runningDays;
            updateHoursPreview();
        }
    }

    function updateHoursPreview() {
        const runningDays  = parseInt(document.getElementById('planned_running_days').value) || 0;
        const shutdownDays = parseInt(document.getElementById('planned_shutdown_days').value) || 0;
        document.getElementById('preview_production_hours').value = runningDays * 24;
        document.getElementById('preview_downtime_hours').value   = shutdownDays * 24;
    }

    // Validasi running days tidak melebihi total days
    document.getElementById('planned_running_days').addEventListener('input', function() {
        const totalDays   = parseInt(document.getElementById('total_days').value) || 0;
        const runningDays = parseInt(this.value) || 0;
        if (runningDays > totalDays) {
            this.value = totalDays;
            calculatePlannedShutdown();
        }
    });
</script>

@endsection