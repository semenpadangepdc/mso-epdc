<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MsoTransaction;
use App\Models\MsoFinding;
use App\Models\MsoPhoto;
use App\Services\DriveService;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;
use DB;

class PublicSubmissionController extends Controller
{
    protected $drive;

    public function __construct(DriveService $drive)
    {
        $this->drive = $drive;
    }

    // Show public form (mobile-friendly). Token required in query param ?token=...
    public function form(Request $request)
    {
        $token = $request->query('token');
        if ($token !== env('PUBLIC_FORM_TOKEN')) {
            abort(403, 'Unauthorized');
        }

        // Provide necessary masters via compact (plants, areas, nomenclatures, maintenance types, components)
        $plants = \App\Models\Plant::all();
        $maintenanceTypes = \App\Models\MaintenanceType::all();
        return view('public.form', compact('plants','maintenanceTypes'));
    }

    // Handle submission
    public function submit(Request $request)
    {
        $token = $request->get('token');
        if ($token !== env('PUBLIC_FORM_TOKEN')) {
            return response()->json(['error'=>'unauthorized'],403);
        }

        // Basic validation
        $request->validate([
            'plant_id'=>'required|exists:plants,id',
            'area_id'=>'required|exists:areas,id',
            'nomenclature_id'=>'required|exists:nomenclatures,id',
            'maintenance_type_id'=>'required|exists:maintenance_types,id',
            'descriptioni'=>'nullable|string',
            'findings'=>'required|array|min:1',
            'findings.*.component_id'=>'required|exists:components,id',
            'findings.*.temuan'=>'required|string',
            'photos.*'=>'sometimes|file|image',
            'photos_type'=>'nullable|in:before,after'
        ]);

        DB::beginTransaction();
        try {
            // create mso transaction
            $mso = MsoTransaction::create([
                'no_mso' => null,
                'user_id' => null,
                'plant_id'=> $request->plant_id,
                'area_id'=> $request->area_id,
                'nomenclature_id'=> $request->nomenclature_id,
                'descriptioni'=> $request->descriptioni,
                'status_peralatan'=> \App\Models\Nomenclature::find($request->nomenclature_id)->status_peralatan,
                'maintenance_type_id'=> $request->maintenance_type_id,
                'status_pekerjaan'=> 'Open'
            ]);

            // set no_mso: e.g. MSO-{Y}{m}{d}-{id}
            $mso->no_mso = 'MSO-'.now()->format('Ymd').'-'.$mso->id;
            $mso->save();

            // findings
            $seq = 1;
            foreach ($request->findings as $f) {
                $subId = 'MSO'.$mso->id.'-F'.$seq;
                $finding = MsoFinding::create([
                    'mso_transaction_id'=>$mso->id,
                    'sub_id'=>$subId,
                    'component_id'=>$f['component_id'],
                    'material_master_id'=>$f['material_master_id'] ?? null,
                    'temuan'=>$f['temuan'],
                    'action'=>$f['action'] ?? null,
                    'status_perbaikan'=>'Pending'
                ]);
                $seq++;
            }

            // photos: limit server-side to 4 before + 4 after
            if ($request->hasFile('photos')) {
                $files = $request->file('photos');
                $type = $request->get('photos_type','before'); // default
                $count = 0;
                foreach ($files as $file) {
                    if ($count >= 4) break; // cap 4
                    // compress via Intervention
                    $img = Image::make($file->getRealPath())->orientate()->resize(null, 1200, function($constraint){ $constraint->aspectRatio(); $constraint->upsize(); });
                    $tmpPath = sys_get_temp_dir().'/'.Str::random(12).'.jpg';
                    $img->save($tmpPath, 75); // compress
                    $contents = file_get_contents($tmpPath);
                    $driveFileId = $this->drive->uploadFromString($contents, now()->timestamp . '_' . $file->getClientOriginalName(), $file->getMimeType());
                    // make thumb base64 small
                    $thumb = Image::make($tmpPath)->fit(240,160)->encode('data-url');
                    MsoPhoto::create([
                        'mso_transaction_id'=>$mso->id,
                        'finding_id'=>null,
                        'type'=>$type,
                        'drive_file_id'=>$driveFileId,
                        'filename'=>$file->getClientOriginalName(),
                        'mime'=>$file->getMimeType(),
                        'filesize'=>filesize($tmpPath),
                        'thumb_base64'=>$thumb
                    ]);
                    @unlink($tmpPath);
                    $count++;
                }
            }

            DB::commit();
            return response()->json(['success'=>true,'no_mso'=>$mso->no_mso],201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('PublicSubmission error: '.$e->getMessage());
            return response()->json(['error'=>'server_error','message'=>$e->getMessage()],500);
        }
    }
}
