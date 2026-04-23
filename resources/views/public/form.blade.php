<!doctype html>
<html>
<head>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>MSO - Public Submission</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-50">
  <div class="max-w-xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Lapor Temuan (Public)</h1>
    <form id="msoForm" action="{{ route('public.submit') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="token" value="{{ request()->query('token') }}">
      <label class="block mb-2">Pilih Plant</label>
      <select name="plant_id" required class="w-full p-2 border rounded mb-3">
        <option value="">-- Pilih Plant --</option>
        @foreach($plants as $p)
          <option value="{{ $p->id }}">{{ $p->name }}</option>
        @endforeach
      </select>

      <label class="block mb-2">Maintenance Type</label>
      <select name="maintenance_type_id" class="w-full p-2 border rounded mb-3" required>
        <option value="">-- Pilih --</option>
        @foreach($maintenanceTypes as $mt)
          <option value="{{ $mt->id }}">{{ $mt->name }}</option>
        @endforeach
      </select>

      <label class="block mb-2">descriptioni singkat</label>
      <textarea name="descriptioni" class="w-full p-2 border rounded mb-3" rows="3"></textarea>

      <hr class="my-3"/>

      <div id="findingsWrap">
        <div class="finding mb-3 p-2 border rounded">
          <label>Component (id)</label>
          <input name="findings[0][component_id]" type="number" class="w-full p-2 border rounded mb-2" required placeholder="Masukkan component id (atau gunakan dropdown by ID)">
          <label>Temuan</label>
          <textarea name="findings[0][temuan]" class="w-full p-2 border rounded mb-2" required></textarea>
        </div>
      </div>
      <button type="button" onclick="addFinding()" class="mb-3 px-3 py-2 bg-blue-600 text-white rounded">Tambah Temuan</button>

      <label class="block mb-2">Photo (before/after) - up to 4</label>
      <select name="photos_type" class="w-full p-2 border rounded mb-2">
        <option value="before">Before</option>
        <option value="after">After</option>
      </select>
      <input type="file" name="photos[]" accept="image/*" multiple capture="environment" class="w-full mb-3"/>

      <button class="w-full p-3 bg-green-600 text-white rounded">Kirim</button>
    </form>
  </div>

<script>
function addFinding(){
  const wrap = document.getElementById('findingsWrap');
  const idx = wrap.querySelectorAll('.finding').length;
  const div = document.createElement('div');
  div.className = 'finding mb-3 p-2 border rounded';
  div.innerHTML = `
    <label>Component (id)</label>
    <input name="findings[${idx}][component_id]" type="number" class="w-full p-2 border rounded mb-2" required>
    <label>Temuan</label>
    <textarea name="findings[${idx}][temuan]" class="w-full p-2 border rounded mb-2" required></textarea>
  `;
  wrap.appendChild(div);
}
</script>
</body>
</html>
