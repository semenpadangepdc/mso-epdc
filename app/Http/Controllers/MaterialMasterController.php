<?php

namespace App\Http\Controllers;

use App\Models\MaterialMaster;
use Illuminate\Http\Request;

class MaterialMasterController extends Controller
{
    /**
     * Hanya admin yang boleh mengakses semua method di controller ini.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin']);
    }

    /**
     * Tampilkan daftar material master dengan search & pagination.
     */
    public function index(Request $request)
    {
        $query = MaterialMaster::query();

        // Search by material_code, material_description, long_text, base_uom
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('material_code', 'like', "%{$search}%")
                  ->orWhere('material_description', 'like', "%{$search}%")
                  ->orWhere('long_text', 'like', "%{$search}%")
                  ->orWhere('base_uom', 'like', "%{$search}%");
            });
        }

        // Filter by UOM jika ada
        if ($request->filled('uom')) {
            $query->where('base_uom', $request->uom);
        }

        $materials = $query->orderBy('material_code')->paginate(20)->withQueryString();

        // Untuk dropdown filter UOM
        $uoms = MaterialMaster::select('base_uom')->distinct()->orderBy('base_uom')->pluck('base_uom');

        return view('material-master.index', compact('materials', 'uoms'));
    }

    /**
     * Form tambah material master baru.
     */
    public function create()
    {
        return view('material-master.create');
    }

    /**
     * Simpan material master baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_code'        => 'required|string|max:50|unique:material_master,material_code',
            'material_description' => 'required|string|max:255',
            'long_text'            => 'nullable|string',
            'base_uom'             => 'nullable|string|max:20',
            'price'                => 'nullable|numeric|min:0',
        ], [
            'material_code.unique'        => 'Kode material sudah digunakan.',
            'material_code.required'      => 'Kode material wajib diisi.',
            'material_description.required' => 'Nama material wajib diisi.',
        ]);

        MaterialMaster::create($validated);

        return redirect()->route('material-master.index')
            ->with('success', 'Material Master berhasil ditambahkan.');
    }

    /**
     * Form edit material master.
     */
    public function edit(MaterialMaster $materialMaster)
    {
        return view('material-master.edit', compact('materialMaster'));
    }

    /**
     * Update material master.
     */
    public function update(Request $request, MaterialMaster $materialMaster)
    {
        $validated = $request->validate([
            'material_code'        => "required|string|max:50|unique:material_master,material_code,{$materialMaster->id}",
            'material_description' => 'required|string|max:255',
            'long_text'            => 'nullable|string',
            'base_uom'             => 'nullable|string|max:20',
            'price'                => 'nullable|numeric|min:0',
        ], [
            'material_code.unique' => 'Kode material sudah digunakan.',
        ]);

        $materialMaster->update($validated);

        return redirect()->route('material-master.index')
            ->with('success', 'Material Master berhasil diupdate.');
    }

    /**
     * Hapus material master.
     */
    public function destroy(MaterialMaster $materialMaster)
    {
        // Cek apakah masih dipakai di finding
        if ($materialMaster->findings()->exists()) {
            return back()->with('error', 'Material ini masih digunakan di data temuan MSO dan tidak dapat dihapus.');
        }

        $materialMaster->delete();

        return redirect()->route('material-master.index')
            ->with('success', 'Material Master berhasil dihapus.');
    }
}