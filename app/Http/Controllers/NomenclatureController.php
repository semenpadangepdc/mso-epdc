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
            'description' => $request->description,
            'type'        => $request->type,
            'default_status' => $request->default_status,
        ]);

        return back()->with(
            'success',
            'Specification updated successfully'
        );
    }
}