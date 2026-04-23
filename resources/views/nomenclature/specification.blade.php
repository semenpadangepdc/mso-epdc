@extends('layouts.app')

@section('content')

<style>
    /* ============================================================================
    SPECIFICATION - STYLESHEET
    Matching MSO Index visual style
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
    .spec-container {
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

    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: var(--pure-white);
        border: 2px solid rgba(255, 255, 255, 0.5);
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(4px);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.35);
        transform: translateY(-2px);
        color: var(--pure-white);
        text-decoration: none;
    }

    /* === FORM CARD === */
    .form-card {
        background: var(--pure-white);
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-red);
    }

    .section-title {
        font-size: 0.875rem;
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

    .section-title::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 1.1rem;
        background: var(--primary-red);
        border-radius: 2px;
    }

    /* === FORM FIELDS === */
    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--medium-gray);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.4rem;
    }

    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        border: 2px solid var(--light-gray);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        color: var(--dark-gray);
        background: var(--pure-white);
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        padding-right: 2.5rem;
        cursor: pointer;
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--primary-red);
        box-shadow: 0 0 0 3px var(--light-red);
    }

    .form-input[readonly] {
        background: var(--off-white);
        color: var(--medium-gray);
        cursor: not-allowed;
        border-color: var(--light-gray);
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    /* === STATUS BADGE HINTS === */
    .status-hint {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        margin-left: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-hint.active   { background: #D1FAE5; color: #065F46; }
    .status-hint.inactive { background: #FEE2E2; color: #991B1B; }
    .status-hint.pending  { background: #FEF3C7; color: #92400E; }

    /* === TABLE CONTAINER === */
    .table-wrapper {
        background: var(--pure-white);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 2px solid var(--light-gray);
        overflow: hidden;
    }

    /* === TABLE === */
    .spec-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .spec-table thead {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
    }

    .spec-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 700;
        font-size: 0.875rem;
        color: var(--pure-white);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
        border-bottom: 3px solid var(--dark-red);
    }

    .spec-table tbody tr {
        background: var(--pure-white);
        transition: all 0.3s ease;
        border-bottom: 1px solid var(--light-gray);
    }

    .spec-table tbody tr:hover {
        background: var(--light-red);
    }

    .spec-table td {
        padding: 0.75rem 1rem;
        color: var(--dark-gray);
        font-size: 0.875rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--light-gray);
    }

    .spec-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Checkbox styling */
    .spec-checkbox {
        width: 1.1rem;
        height: 1.1rem;
        accent-color: var(--primary-red);
        cursor: pointer;
        transition: transform 0.2s;
    }

    .spec-checkbox:hover {
        transform: scale(1.15);
    }

    /* Inline table inputs */
    .table-input {
        width: 100%;
        border: 1.5px solid var(--light-gray);
        padding: 0.45rem 0.65rem;
        border-radius: 6px;
        font-size: 0.8rem;
        color: var(--dark-gray);
        background: var(--pure-white);
        transition: all 0.25s ease;
        box-sizing: border-box;
    }

    .table-input:focus {
        outline: none;
        border-color: var(--primary-red);
        box-shadow: 0 0 0 2px var(--light-red);
    }

    .table-input:disabled {
        background: var(--off-white);
        color: var(--medium-gray);
        cursor: not-allowed;
    }

    /* === EMPTY STATE === */
    .data-empty {
        text-align: center;
        padding: 3rem;
        color: var(--medium-gray);
        font-size: 1.125rem;
    }

    /* === SAVE BUTTON === */
    .btn-save {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        color: var(--pure-white);
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
    }

    .btn-cancel {
        background: var(--pure-white);
        color: var(--medium-gray);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        border: 2px solid var(--light-gray);
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .btn-cancel:hover {
        border-color: var(--primary-red);
        color: var(--primary-red);
        transform: translateY(-2px);
        text-decoration: none;
    }

    .action-bar {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 2px solid var(--light-gray);
    }

    /* === RESPONSIVE === */
    @media (max-width: 768px) {
        .spec-container { padding: 1rem; }
        .page-header { padding: 1.5rem; }
        .page-title { font-size: 1.5rem; }
    }
</style>

<div class="spec-container">
    <div style="max-width: 960px; margin: 0 auto;">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Specification</h1>
                <p class="page-subtitle">{{ $nomenclature->name }}</p>
            </div>
            <a href="{{ url()->previous() }}" class="btn-back">
                &#8592; Kembali
            </a>
        </div>

        @if(session('success'))
            <div style="background:#D1FAE5;color:#065F46;padding:0.85rem 1.25rem;border-radius:8px;margin-bottom:1.5rem;font-size:0.875rem;font-weight:600;border-left:4px solid #10B981;">
                &#10003; {{ session('success') }}
            </div>
        @endif

        <form method="POST"
              action="{{ route('nomenclatures.specification.update', $nomenclature->id) }}">
            @csrf

            {{-- Unit Information --}}
            <div class="form-card">
                <div class="section-title">Unit Information</div>

                <div class="form-group">
                    <label class="form-label">Nomenclature</label>
                    <input type="text"
                           class="form-input"
                           value="{{ $nomenclature->name }}"
                           readonly>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Type</label>
                        <input type="text"
                               name="type"
                               class="form-input"
                               value="{{ old('type', $nomenclature->type) }}">
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Status</label>
                        <select name="default_status" class="form-select">
                            @php
                                $statuses = [
                                    'Active Operation',
                                    'Ready Standby',
                                    'Not Aktif (Broken/Eliminated)',
                                ];
                                $current = old('default_status', $nomenclature->default_status ?? '');
                            @endphp
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" {{ $current === $s ? 'selected' : '' }}>
                                    {{ $s }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-top:1.25rem;margin-bottom:0;">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                              class="form-textarea"
                              rows="3">{{ old('description', $nomenclature->description) }}</textarea>
                </div>
            </div>

            {{-- Components --}}
            <div class="form-card">
                <div class="section-title">Components</div>

                @if($components->isEmpty())
                    <div class="data-empty">
                        <svg style="width:3rem;height:3rem;color:#FCD34D;margin:0 auto 1rem;display:block;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Tidak ada komponen tersedia.
                    </div>
                @else
                    <div class="table-wrapper">
                        <table class="spec-table">
                            <thead>
                                <tr>
                                    <th style="width:60px;text-align:center;">Pilih</th>
                                    <th style="width:200px;">Component Name</th>
                                    <th style="width:160px;">Material Number</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($components as $component)
                                @php
                                    $isAttached   = in_array($component->id, $attached);
                                    $pivotData    = $attachedPivot->get($component->id);
                                    $matNumber    = old("material_numbers.{$component->id}", $pivotData?->pivot?->material_number ?? '');
                                    $compDesc     = old("component_descriptions.{$component->id}", $pivotData?->pivot?->description ?? '');
                                @endphp
                                <tr>
                                    <td style="text-align:center;">
                                        <input type="checkbox"
                                               class="spec-checkbox component-toggle"
                                               name="components[]"
                                               value="{{ $component->id }}"
                                               data-id="{{ $component->id }}"
                                               {{ $isAttached ? 'checked' : '' }}>
                                    </td>
                                    <td>{{ $component->name }}</td>
                                    <td>
                                        <input type="text"
                                               class="table-input"
                                               name="material_numbers[{{ $component->id }}]"
                                               placeholder="e.g. 1000XXXXX"
                                               value="{{ $matNumber }}"
                                               {{ !$isAttached ? 'disabled' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="table-input"
                                               name="component_descriptions[{{ $component->id }}]"
                                               placeholder="Component description..."
                                               value="{{ $compDesc }}"
                                               {{ !$isAttached ? 'disabled' : '' }}>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Action Bar --}}
            <div class="action-bar">
                <button type="submit" class="btn-save">
                    &#10003; Save Specification
                </button>
                <a href="{{ url()->previous() }}" class="btn-cancel">
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>

<script>
    // Enable / disable pivot inputs when checkbox is toggled
    document.querySelectorAll('.component-toggle').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const id     = this.dataset.id;
            const inputs = document.querySelectorAll(
                `input[name="material_numbers[${id}]"], input[name="component_descriptions[${id}]"]`
            );
            inputs.forEach(function (input) {
                input.disabled = !checkbox.checked;
                if (!checkbox.checked) {
                    input.value = '';
                }
            });
        });
    });
</script>

@endsection