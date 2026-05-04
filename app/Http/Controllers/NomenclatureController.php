<?php

namespace App\Http\Controllers;

use App\Models\Nomenclature;
use App\Models\Component;
use Illuminate\Http\Request;

class NomenclatureController extends Controller
{
    public function index()
    {
        $nomenclatures = Nomenclature::with('area.plant')
            ->get()
            ->groupBy(function ($item) {
                return $item->area->plant->name ?? 'No Plant';
            })
            ->map(function ($group) {
                return $group->groupBy(function ($item) {
                    return $item->area->name ?? 'No Area';
                });
            });

        return view('nomenclature.index', compact('nomenclatures'));
    }

    public function specification(Nomenclature $nomenclature)
    {
        $components = Component::where(
            'type',
            $nomenclature->type
        )->get();

        // Load pivot data (material_number & description per component)
        $attachedPivot = $nomenclature
            ->components()
            ->withPivot('material_number', 'description')
            ->get()
            ->keyBy('id');

        $attached = $attachedPivot->keys()->toArray();

        return view(
            'nomenclature.specification',
            compact('nomenclature', 'components', 'attached', 'attachedPivot')
        );
    }

    public function updateSpecification(
        Request $request,
        Nomenclature $nomenclature
    ) {
        $request->validate([
            'components'               => 'nullable|array',
            'components.*'             => 'exists:components,id',
            'material_numbers'         => 'nullable|array',
            'material_numbers.*'       => 'nullable|string|max:50',
            'component_descriptions'   => 'nullable|array',
            'component_descriptions.*' => 'nullable|string|max:255',
        ]);

        // Build sync data with pivot values
        $syncData = [];
        foreach ($request->components ?? [] as $componentId) {
            $syncData[$componentId] = [
                'material_number' => $request->material_numbers[$componentId] ?? null,
                'description'     => $request->component_descriptions[$componentId] ?? null,
            ];
        }

        $nomenclature->components()->sync($syncData);

        $nomenclature->update([
            'description'    => $request->description,
            'type'           => $request->type,
            'default_status' => $request->default_status,
        ]);

        return back()->with(
            'success',
            'Specification updated successfully'
        );
    }

    public function addComponent(Request $request, Nomenclature $nomenclature)
    {
        // Hanya Admin (atau Supervisor) yang boleh
        if (!auth()->user()->hasRole('Admin')) {
            abort(403, 'Only Admin can add new components');
        }

        $request->validate([
            'component_name'  => 'required|string|max:255',
            'component_type'  => 'nullable|string|max:100',
            'material_number' => 'nullable|string|max:50',
            'description'     => 'nullable|string|max:255',
        ]);

        // Buat component baru
        $component = Component::create([
            'name' => $request->component_name,
            'type' => $request->component_type,
        ]);

        // Attach ke nomenclature dengan data pivot
        $nomenclature->components()->attach($component->id, [
            'material_number' => $request->material_number,
            'description'     => $request->description,
        ]);

        return redirect()
            ->route('nomenclatures.specification', $nomenclature->id)
            ->with('success', 'Komponen baru berhasil ditambahkan ke nomenclature ini.');
    }
}