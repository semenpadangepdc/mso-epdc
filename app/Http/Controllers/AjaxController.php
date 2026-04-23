<?php

namespace App\Http\Controllers;

use App\Models\Nomenclature;
use App\Models\Component;

class AjaxController extends Controller
{
    public function componentsByNomenclature($id)
    {
        $nomenclature = Nomenclature::findOrFail($id);

        $components = Component::where('type', $nomenclature->type)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($components);
    }
}
