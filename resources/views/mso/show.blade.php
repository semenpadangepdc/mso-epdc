@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    {{-- HEADER ACTION BUTTON --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Detail MSO: {{ $mso->no_mso }}</h2>

        <div class="space-x-2">
            <a href="{{ route('mso.edit', $mso->id) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                ✏ Edit
            </a>

            <a href="{{ route('mso.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                ⬅ Kembali
            </a>
        </div>
    </div>

    {{-- ========================== SECTION 1: HEADER INFO ========================== --}}
    <div class="grid grid-cols-2 gap-4 bg-white shadow p-4 rounded-lg mb-6">

        <div>
            <p class="text-gray-500">Plant</p>
            <p class="font-semibold">{{ $mso->plant->name ?? '-' }}</p>
        </div>

        <div>
            <p class="text-gray-500">Area</p>
            <p class="font-semibold">{{ $mso->area->name ?? '-' }}</p>
        </div>

        <div>
            <p class="text-gray-500">User</p>
            <p class="font-semibold">{{ $mso->user->name ?? '-' }}</p>
        </div>

        <div>
            <p class="text-gray-500">Timestamp</p>
            <p class="font-semibold">{{ $mso->created_at }}</p>
        </div>

        <div>
            <p class="text-gray-500">Status Peralatan</p>
            <p class="font-semibold">{{ $mso->status_peralatan ?? '-' }}</p>
        </div>

        <div>
            <p class="text-gray-500">Status Pekerjaan</p>
            <p class="font-semibold text-blue-600">{{ $mso->status_pekerjaan ?? '-' }}</p>
        </div>

        <div>
            <p class="text-gray-500">Jenis Maintenance</p>
            <p class="font-semibold">{{ $mso->maintenanceType->name ?? '-' }}</p>
        </div>

        <div>
            <p class="text-gray-500">Nomenclature</p>
            <p class="font-semibold">{{ $mso->nomenclature->name ?? '-' }}</p>
        </div>
    </div>


    {{-- ========================== SECTION 2: TEMUAN & KOMPONEN ========================== --}}
    <h3 class="text-xl font-bold mb-2">Temuan Maintenance</h3>

    <table class="w-full border text-sm mb-6">
        <thead class="bg-gray-200">
            <tr>
                <th class="border p-2">Komponen</th>
                <th class="border p-2">Temuan</th>
                <th class="border p-2">Material Master</th>
                <th class="border p-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mso->findings as $f)
                <tr>
                    <td class="border p-2">{{ $f->component->name ?? '-' }}</td>
                    <td class="border p-2">{{ $f->temuan ?? '-' }}</td>
                    <td class="border p-2">{{ $f->materialMaster->name ?? $f->material_master_id ?? '-' }}</td>
                    <td class="border p-2">{{ $f->action ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    {{-- ========================== SECTION 3: FOTO GALLERY ========================== --}}
    <h3 class="text-xl font-bold mb-2">Dokumentasi Foto</h3>

    <div class="grid grid-cols-2 gap-6">

        {{-- BEFORE --}}
        <div>
            <h4 class="font-semibold mb-2">📷 Foto Sebelum</h4>
            <div class="grid grid-cols-2 gap-2">
                @forelse($mso->photos->where('type','before') as $photo)
                    <img src="{{ Storage::disk('image')->url($photo->path) }}"
                         class="border rounded-lg shadow w-full h-32 object-cover cursor-pointer"
                         onclick="previewImage('{{ Storage::disk('image')->url($photo->path) }}')">
                @empty
                    <p class="text-gray-500">Tidak ada foto.</p>
                @endforelse
            </div>
        </div>

        {{-- AFTER --}}
        <div>
            <h4 class="font-semibold mb-2">📷 Foto Sesudah</h4>
            <div class="grid grid-cols-2 gap-2">
                @forelse($mso->photos->where('type','after') as $photo)
                    <img src="{{ Storage::disk('image')->url($photo->path) }}"
                         class="border rounded-lg shadow w-full h-32 object-cover cursor-pointer"
                         onclick="previewImage('{{ Storage::disk('image')->url($photo->path) }}')">
                @empty
                    <p class="text-gray-500">Tidak ada foto.</p>
                @endforelse
            </div>
        </div>

    </div>


    {{-- ========================== KETERANGAN ========================== --}}
    <div class="mt-6 p-4 bg-gray-50 border rounded-lg">
        <p class="text-gray-500 text-sm">Keterangan:</p>
        <p class="font-semibold">{{ $mso->keterangan ?? '-' }}</p>
    </div>

</div>

{{-- FULL SCREEN IMAGE PREVIEW --}}
<div id="previewModal" class="hidden fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center">
    <img id="previewImage" class="max-w-3xl rounded shadow-lg">
</div>

<script>
    function previewImage(url){
        document.getElementById('previewImage').src = url;
        document.getElementById('previewModal').classList.remove('hidden');
    }

    document.getElementById('previewModal').onclick = function(){
        this.classList.add('hidden');
    }
</script>

@endsection