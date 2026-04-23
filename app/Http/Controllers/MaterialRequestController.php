<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialRequest;

class MaterialRequestController extends Controller
{
    public function index(Request $request)
    {

        $query = MaterialRequest::query();

        if($request->material_master){
            $query->where('material_master',$request->material_master);
        }

        $data = $query->get();

        $total = $query->sum('estimasi_harga');

        return view('monitoring.index',compact('data','total'));

    }

    public function export($trans_id)
    {

        $data = Abnormality::where('trans_id',$trans_id)->get();

        foreach($data as $item){

            MaterialRequest::create([

                'trans_id'=>$item->trans_id,
                'nomenclature'=>$item->nomenclature->name,
                'component'=>$item->component->name,
                'abnormality'=>$item->description,
                'action'=>$item->action,
                'material_master'=>$item->material_master

            ]);

        }

        return redirect()->route('material.monitoring');

    }

    public function store(Request $request)
    {

    MaterialRequest::create($request->all());

    return back();

    }
}
