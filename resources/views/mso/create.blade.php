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

    /* Main container */
    .form-container {
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.1), 0 2px 4px -1px rgba(220, 38, 38, 0.06);
        padding: 2rem;
    }

    /* Page header */
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

    /* Section headers */
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

    /* Card sections */
    .card-section {
        background: var(--red-bg);
        padding: 1.5rem;
        border-radius: 12px;
        border: 2px solid var(--red-light);
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(220, 38, 38, 0.1);
    }

    /* Form labels */
    .form-label {
        font-weight: 600;
        color: var(--red-dark);
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Input styling */
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

    /* Table styling */
    .findings-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
        background: var(--white);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.1);
    }

    .findings-table thead tr {
        background: linear-gradient(135deg, var(--red-dark) 0%, var(--red-primary) 100%);
        color: var(--white);
    }

    .findings-table th {
        padding: 1rem 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
        border: 1px solid var(--red-dark);
    }

    .findings-table td {
        padding: 0.875rem 0.75rem;
        border: 1px solid var(--gray-border);
    }

    .findings-table tbody tr {
        transition: all 0.2s ease;
        background: var(--white);
    }

    .findings-table tbody tr:nth-child(even) {
        background: var(--red-bg);
    }

    .findings-table tbody tr:hover {
        background: var(--red-light);
        transform: scale(1.005);
    }

    /* Button styling */
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

    .btn-green {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: var(--white);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(22, 163, 74, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-green:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(22, 163, 74, 0.3);
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

    .btn-remove {
        background: var(--red-primary);
        color: var(--white);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .btn-remove:hover {
        background: var(--red-dark);
        transform: scale(1.05);
    }

    /* File input styling */
    .file-input {
        font-size: 0.75rem;
        padding: 0.5rem;
        border: 2px dashed var(--red-light);
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .file-input:hover {
        border-color: var(--red-primary);
        background: var(--red-bg);
    }

    /* Preview image */
    .preview {
        border-radius: 8px;
        border: 2px solid var(--red-light);
        max-width: 80px;
        transition: all 0.2s ease;
    }

    .preview:hover {
        transform: scale(1.1);
        border-color: var(--red-primary);
    }

    /* Error message */
    .error-message {
        color: var(--red-dark);
        font-size: 0.75rem;
        margin-top: 0.25rem;
        font-weight: 500;
    }

    /* Select2 custom styling */
    .select2-container--default .select2-selection--single {
        border: 2px solid var(--gray-border) !important;
        border-radius: 8px !important;
        height: auto !important;
        min-height: 46px !important;
        background: var(--white) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding: 0.5rem 1rem !important;
        line-height: 1.5 !important;
        color: var(--black) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #9ca3af !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
        right: 8px !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: var(--red-primary) !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--red-primary) !important;
    }

    .select2-dropdown {
        border: 2px solid var(--red-primary) !important;
        border-radius: 8px !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 2px solid var(--gray-border) !important;
        border-radius: 6px !important;
        padding: 0.5rem !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: var(--red-primary) !important;
        outline: none !important;
    }

    /* Grid styling */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Action buttons container */
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid var(--red-light);
    }
</style>

<div class="max-w-6xl mx-auto p-6">

    <!-- Page Header -->
    <div class="page-header">
        <span style="font-size: 2rem;">➕</span>
        <div>
            <h1 class="text-2xl font-bold">Tambah MSO Baru</h1>
            <p class="text-red-100 text-sm mt-1">Maintenance Service Order Form</p>
        </div>
    </div>

    <div class="form-container">
        <form action="{{ route('mso.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- =============================== -->
            <!-- HEADER SECTION -->
            <!-- =============================== -->
            <div class="section-header">
                <span>📋</span>
                <span>Informasi Dasar</span>
            </div>

            <div class="card-section">
                <div class="form-grid">

                    <div>
                        <label class="form-label">🏭 Plant</label>
                        <select name="plant_id" id="plant" class="form-select">
                            <option value="">-- Pilih Plant --</option>
                            @foreach($plants as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        @error('plant_id') <small class="error-message">{{ $message }}</small>@enderror
                    </div>

                    <div>
                        <label class="form-label">📍 Area</label>
                        <select name="area_id" id="area" class="form-select">
                            <option value="">-- Pilih Area --</option>
                        </select>
                        @error('area_id') <small class="error-message">{{ $message }}</small>@enderror
                    </div>

                    <div>
                        <label class="form-label">🔧 Nomenclature</label>
                        <select name="nomenclature_id" id="nomenclature" class="form-select">
                            <option value="">-- Pilih Nomenclature --</option>
                        </select>
                        @error('nomenclature_id') <small class="error-message">{{ $message }}</small>@enderror
                    </div>

                    <div>
                        <label class="form-label">📊 Status Peralatan</label>
                        <input
                            type="text"
                            name="status_peralatan"
                            id="status_peralatan"
                            class="form-input"
                            readonly
                            placeholder="Otomatis dari Nomenclature"
                        >
                    </div>

                    <div>
                        <label class="form-label">⚙️ Jenis Maintenance</label>
                        <select name="maintenance_type_id" class="form-select">
                            @foreach($maintenanceTypes as $mt)
                                <option value="{{ $mt->id }}">{{ $mt->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>


            <!-- =============================== -->
            <!-- FINDING TABLE -->
            <!-- =============================== -->
            <div class="section-header">
                <span>🛠</span>
                <span>Temuan & Tindakan</span>
            </div>

            <div style="overflow-x: auto; margin-bottom: 1.5rem;">
                <table class="findings-table" id="finding-table">
                    <thead>
                        <tr>
                            <th>Component</th>
                            <th>Temuan</th>
                            <th>Action</th>
                            <th>Foto Before</th>
                            <th>Foto After</th>
                            <th style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="finding-body">

                        <tr class="finding-row">
                            <td>
                                <select name="component_id[]" class="form-select component-select">
                                    <option value="">-- Pilih Nomenclature Terlebih Dahulu --</option>
                                </select>
                            </td>


                            <td>
                                <select name="temuan[]" class="form-select temuan-select">
                                    <option value="">-- Pilih Temuan --</option>
                                </select>
                            </td>

                            <td>
                                <textarea name="action[]" class="form-textarea" rows="2" placeholder="Tindakan yang dilakukan..."></textarea>
                            </td>

                            <td class="text-center">
                                <input type="file" name="foto_before[]" accept="image/*" class="file-input">
                                <img class="preview mt-2 hidden" style="margin: 0.5rem auto;">
                            </td>

                            <td class="text-center">
                                <input type="file" name="foto_after[]" accept="image/*" class="file-input">
                                <img class="preview mt-2 hidden" style="margin: 0.5rem auto;">
                            </td>

                            <td class="text-center">
                                <button type="button" class="remove-row btn-remove">✖</button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <button type="button" id="add-row" class="btn-green">
                <span>➕</span>
                <span>Tambah Baris Temuan</span>
            </button>


            <!-- =============================== -->
            <!-- SUBMIT BUTTONS -->
            <!-- =============================== -->
            <div class="action-buttons">
                <a href="{{ route('mso.index') }}" class="btn-gray">
                    <span>❌</span>
                    <span>Batal</span>
                </a>
                <button type="submit" class="btn-red">
                    <span>💾</span>
                    <span>Simpan MSO</span>
                </button>
            </div>

        </form>
    </div>

</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery (diperlukan untuk Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Inisialisasi Select2 pada nomenclature dropdown
    $(document).ready(function() {
        $('#nomenclature').select2({
            placeholder: '-- Pilih Nomenclature --',
            allowClear: true,
            width: '100%'
        });

        // Preview gambar saat dipilih
        $(document).on('change', '.file-input', function(e) {
            const file = e.target.files[0];
            const preview = $(this).siblings('.preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).removeClass('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.addClass('hidden').attr('src', '');
            }
        });
    });

    document.getElementById('plant').addEventListener('change', function() {

        let plant_id = this.value;
        let areaSelect = document.getElementById('area');
        let nomenSelect = $('#nomenclature');

        // Reset dropdown bawahnya
        areaSelect.innerHTML = '<option value="">Loading...</option>';
        
        // Reset Select2
        nomenSelect.html('<option value="">-- Pilih Area Terlebih Dahulu --</option>').trigger('change');

        fetch(`/ajax/areas/${plant_id}`)
            .then(res => res.json())
            .then(data => {
                areaSelect.innerHTML = '<option value="">-- Pilih Area --</option>';

                data.forEach(area => {
                    areaSelect.innerHTML += `<option value="${area.id}">${area.name}</option>`;
                });
            });
    });


    document.getElementById('area').addEventListener('change', function() {

        let area_id = this.value;
        let nomenSelect = $('#nomenclature');

        // Reset Select2
        nomenSelect.html('<option value="">Loading...</option>').trigger('change');

        fetch(`/ajax/nomenclatures/${area_id}`)
            .then(res => res.json())
            .then(data => {
                nomenSelect.html('<option value="">-- Pilih Nomenclature --</option>');

                data.forEach(item => {
                    nomenSelect.append(new Option(item.name, item.id));
                });

                nomenSelect.trigger('change');
            });
    });


    $('#nomenclature').on('change', function () {

        let nomenclatureId = $(this).val();
        let statusField = document.getElementById('status_peralatan');
        let componentSelect = $('.component-select');

        // Reset
        statusField.value = '';
        componentSelect.html('<option value="">Loading...</option>');

        if (!nomenclatureId) {
            componentSelect.html('<option value="">-- Pilih Nomenclature --</option>');
            return;
        }

        // === 1. Ambil STATUS PERALATAN ===
        fetch(`/ajax/nomenclature-status/${nomenclatureId}`)
            .then(res => res.json())
            .then(data => {
                statusField.value = data.status ?? '-';
            });

        // === 2. Ambil COMPONENT BERDASARKAN TYPE ===
        fetch(`/ajax/components/by-nomenclature/${nomenclatureId}`)
            .then(res => res.json())
            .then(data => {

                componentSelect.html('<option value="">-- Pilih Komponen --</option>');

                data.forEach(item => {
                    componentSelect.append(
                        new Option(item.name, item.id)
                    );
                });
            });
    });



    document.getElementById('add-row').addEventListener('click', function() {
        const tbody = document.getElementById('finding-body');
        const firstRow = document.querySelector('.finding-row');
        const newRow = firstRow.cloneNode(true);

        // reset input
        newRow.querySelectorAll('textarea').forEach(t => t.value = '');
        newRow.querySelectorAll('input[type="file"]').forEach(f => f.value = '');
        newRow.querySelectorAll('.preview').forEach(p => {
            p.classList.add('hidden');
            p.src = '';
        });

        // reset component dropdown
        newRow.querySelectorAll('.component-select').forEach(s => {
            s.innerHTML = '<option value="">-- Pilih Nomenclature Terlebih Dahulu --</option>';
        });

        tbody.appendChild(newRow);

        // trigger ulang filter component jika nomenclature sudah dipilih
        $('#nomenclature').trigger('change');
    });

    const TEMUAN_OPTIONS = [
        "Aksesories Tidak Lengkap",
        "Basah / Rembes Air",
        "Bengkok/Bending",
        "Blockage",
        "Bocor/Robek/Retak",
        "Buntu",
        "Bushing Isolator Rusak",
        "Card Sensor Rusak",
        "Celah Gap besar",
        "Dioda Rusak",
        "Hang/Tidak Merespon",
        "Hilang / Missing",
        "Induksi/Interferenced",
        "Isolasi Rusak",
        "Kaku/Mengeras/Getas",
        "Kendor - Getas",
        "Korosi/Karat",
        "Kotor",
        "Lemah",
        "Lembab / Basah",
        "Lepas",
        "Limit Switch Rusak",
        "Macet/Stuck",
        "Not Align",
        "Oli Bocor",
        "Oli Kotor / BDV Rendah",
        "Over Limit",
        "Patah/Putus",
        "Penerangan Lampu Minim",
        "Penuh",
        "Rendah",
        "Retak/Sompel",
        "Rusak",
        "Short Circuit",
        "Suara Kasar",
        "Temperatur Tinggi",
        "Terminasi Longgar",
        "Vibrasi Tinggi",
        "Bocor (Udara Komp)",
        "Bocor (Bangunan)",
        "Rusak (Short Circuit-Terbakar)",
        "Overload",
        "Power Supply OFF",
        "Thyristor Rusak"
    ];

    function populateTemuanSelect(selectElement) {
        selectElement.innerHTML = '<option value="">-- Pilih Temuan --</option>';

        TEMUAN_OPTIONS.forEach(item => {
            const option = document.createElement('option');
            option.value = item;
            option.textContent = item;
            selectElement.appendChild(option);
        });
    }

    // Populate saat halaman pertama kali load
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.temuan-select').forEach(select => {
            populateTemuanSelect(select);
        });
    });

</script>

@endsection