<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MsoTransaction;
use App\Models\MsoFinding;
use App\Models\MsoPhoto;
use App\Models\Nomenclature;
use App\Models\Component;
use App\Models\Plant;
use App\Models\Area;
use App\Models\MaintenanceType;
use App\Models\MaterialMaster;
use App\Services\DriveService;
use Intervention\Image\ImageManagerStatic as Image;
use PDF;
use Excel;
use DB;
use Illuminate\Support\Facades\Storage;


class MsoTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // further role-based middleware per method can be applied
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', MsoTransaction::class);

        // ====================================================================
        // 🔧 QUERY BUILDER - POLA BARU (1 ROW = 1 FINDING)
        // ====================================================================
        
        // PENTING: Load semua relationship termasuk materialMaster
        $query = MsoFinding::with([
            'transaction.user',
            'transaction.plant',
            'transaction.area',
            'transaction.nomenclature',
            'transaction.maintenanceType',
            'transaction.findings', // untuk count findings di delete confirmation
            'component',
            'materialMaster',
            'photos'
        ])->orderByDesc('created_at');

        // ====================================================================
        // 🔍 FILTERS
        // ====================================================================
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('temuan', 'like', "%$search%")
                ->orWhereHas('transaction', function ($t) use ($search) {
                    $t->where('no_mso', 'like', "%$search%")
                        ->orWhere('id_trans', 'like', "%$search%");
                })
                ->orWhereHas('component', function ($c) use ($search) {
                    $c->where('name', 'like', "%$search%");
                })
                ->orWhereHas('transaction.area', function ($a) use ($search) {
                    $a->where('name', 'like', "%$search%");
                });
            });
        }

        // Plant filter - ✅ FIX: Gunakan whereHas untuk relasi transaction
        if ($request->filled('plant')) {
            $query->whereHas('transaction', function($q) use ($request) {
                $q->where('plant_id', $request->plant);
            });
        }

        // Status filter - ✅ FIX: Gunakan whereHas untuk relasi transaction
        if ($request->filled('status')) {
            $query->whereHas('transaction', function($q) use ($request) {
                $q->where('status_pekerjaan', $request->status);
            });
        }

        // ====================================================================
        // 📊 PAGINATION
        // ====================================================================
        
        $rows = $query->paginate(25);

        // ====================================================================
        // 📦 DATA UNTUK DROPDOWNS
        // ====================================================================
        
        // Get all plants for filter dropdown
        $plants = Plant::orderBy('name')->get();
        
        // Get all areas for reference
        $areas = Area::orderBy('name')->get();
        
        // Get all maintenance types
        $maintenanceTypes = MaintenanceType::orderBy('name')->get();
        
        // PENTING: Load material masters dengan urutan berdasarkan code
        $materialMasters = MaterialMaster::orderBy('material_code')->get();

        // ====================================================================
        // 📤 RETURN VIEW WITH ALL REQUIRED VARIABLES
        // ====================================================================
        
        return view('mso.index', compact(
            'rows',
            'plants',
            'areas',
            'maintenanceTypes',
            'materialMasters'
        ));
    }

    public function show($id)
    {
        $mso = MsoTransaction::with([
            'findings.component',
            'findings.materialMaster',  // ✅ tambah ini
            'photos',
            'plant',                    // ✅ tambah ini
            'area',                     // ✅ tambah ini
            'user',                     // ✅ tambah ini
            'maintenanceType',          // ✅ tambah ini
            'nomenclature',             // ✅ tambah ini
        ])->findOrFail($id);

        $this->authorize('view', $mso);

        return view('mso.show', compact('mso'));
    }

    public function edit($id)
    {
        $mso = MsoTransaction::with('findings.component', 'findings.materialMaster')->findOrFail($id);
        $this->authorize('update', $mso);

        // ✅ Kirim semua variable yang dibutuhkan view
        $nomenclatures     = Nomenclature::all();
        $plants            = Plant::all();
        $areas             = Area::all();
        $maintenanceTypes  = MaintenanceType::all();
        $components        = Component::all();
        $materialMasters   = MaterialMaster::orderBy('material_code')->get(); // ✅ FIX UTAMA

        return view('mso.edit', compact(
            'mso',
            'nomenclatures',
            'plants',
            'areas',
            'maintenanceTypes',
            'components',
            'materialMasters'   // ✅ ini yang bikin 500 error kalau tidak ada
        ));
    }

    public function update(Request $request, $id)
    {
        $mso = MsoTransaction::findOrFail($id);
        $this->authorize('update', $mso);
        $request->validate([
            'status_pekerjaan'=>'required|in:Open,Partial Finish,Closed',
            'status_peralatan'=>'required|in:Active Operation,Ready Standby,Broken - Eliminated'
        ]);
        $mso->update($request->only(['status_pekerjaan','status_peralatan','keterangan']));
        return back()->with('success','MSO updated');
    }

    // export as PDF
    public function exportPdf($id)
    {
        $mso = MsoTransaction::with('findings','photos')->findOrFail($id);
        $this->authorize('view', $mso);
        $pdf = PDF::loadView('mso.pdf', compact('mso'));
        return $pdf->download($mso->no_mso . '.pdf');
    }

    public function create()
    {
        $plants = Plant::all();
        $areas = Area::all();
        $nomenclatures = Nomenclature::all();
        $maintenanceTypes = MaintenanceType::all();
        $components = Component::all();
        $materialMasters = MaterialMaster::all();

        return view('mso.create', [
            'plants' => Plant::all(),
            'areas' => [], // Kosong dulu sampai user pilih plant
            'nomenclatures' => [],
            'maintenanceTypes' => MaintenanceType::all(),
            'components' => Component::all(),
            'materialMasters' => MaterialMaster::all(),
            'maintenanceTypes' => MaintenanceType::all(),
        ]);
    }


    public function store(Request $request)
    {
        // ================================================
        // 1️⃣ VALIDASI
        // ================================================
        $request->validate([
            'plant_id' => 'required',
            'area_id' => 'required',
            'nomenclature_id' => 'required',
            'status_peralatan' => 'required|string',
            'maintenance_type_id' => 'required',
            'start_date' => 'nullable|date',
            'finish_date' => 'nullable|date',

            'component_id.*' => 'nullable|exists:components,id',
            'temuan.*' => 'nullable|string',
            'action.*' => 'nullable|string',
            'material_master_id.*' => 'nullable|exists:material_master,id',

            'foto_before.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_after.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ================================================
        // 2️⃣ GENERATE ID TRANS (FORMAT: ID-XXX-YYYYMMDD-0001)
        // ================================================
        $today = now()->format('Ymd');

        // Ambil data nomenclature untuk mendapatkan kode
        $nomenclature = Nomenclature::findOrFail($request->nomenclature_id);

        // Ambil 3 karakter pertama dari nama nomenclature dan uppercase
        $nomenclatureCode = strtoupper(substr($nomenclature->name, 0, 3));

        // Cari transaksi terakhir hari ini dengan nomenclature yang sama
        $lastTrans = MsoTransaction::whereDate('created_at', now())
            ->where('nomenclature_id', $request->nomenclature_id)
            ->orderBy('id_trans', 'desc')
            ->first();

        $sequence = 1;

        if ($lastTrans) {
            // Ambil 4 digit terakhir dari id_trans
            $lastSeq = intval(substr($lastTrans->id_trans, -4));
            $sequence = $lastSeq + 1;
        }

        // Format: ID-XXX-YYYYMMDD-0001
        $idTrans = sprintf(
            'ID-%s-%s-%04d',
            $nomenclatureCode,
            $today,
            $sequence
        );

        // ================================================
        // 3️⃣ SIMPAN HEADER MSO
        // ================================================
        $mso = MsoTransaction::create([
            'id_trans' => $idTrans,
            'sub_id' => null, // sub akan untuk each finding
            'no_mso' => 'MSO-' . time(),
            'user_id' => auth()->id(),
            'plant_id' => $request->plant_id,
            'area_id' => $request->area_id,
            'nomenclature_id' => $request->nomenclature_id,
            'maintenance_type_id' => $request->maintenance_type_id,
            'description' => $request->description,
            'status_pekerjaan' => 'Open',
            'start_date' => $request->start_date,
            'finish_date' => $request->finish_date,
            'keterangan' => $request->keterangan,
        ]);

        // ================================================
        // 4️⃣ SIMPAN FINDINGS
        // ================================================
        if ($request->has('component_id')) {

            // Sub counter dimulai dari 1 untuk setiap transaksi baru
            $subCounter = 1;

            foreach ($request->component_id as $i => $componentId) {
                if (
                    !$componentId &&
                    empty($request->temuan[$i]) &&
                    empty($request->action[$i])
                ) {
                    continue;
                }

                // Format sub_id: ID-XXX-YYYYMMDD-0001-01
                $subIdTrans = $idTrans . '-' . str_pad($subCounter, 2, '0', STR_PAD_LEFT);
                $subCounter++;

                $finding = MsoFinding::create([
                    'mso_transaction_id' => $mso->id,
                    'sub_id' => $subIdTrans,
                    'component_id' => $componentId,
                    'material_master_id' => $request->material_master_id[$i] ?? null,
                    'temuan' => $request->temuan[$i] ?? null,
                    'action' => $request->action[$i] ?? null,
                    'status_perbaikan' => 'Pending',
                ]);

                // Foto BEFORE
                if ($request->hasFile("foto_before.$i")) {

                    $file = $request->file("foto_before.$i");

                    $path = $file->store('mso_photos', 'image');

                    MsoPhoto::create([
                        'mso_transaction_id' => $mso->id,
                        'finding_id'         => $finding->id,
                        'type'               => 'before',
                        'filename'           => $path,                  // WAJIB
                        'mime'               => $file->getMimeType(),   // WAJIB
                        'filesize'           => $file->getSize(),       // WAJIB
                        'drive_file_id'      => null,
                        'thumb_base64'       => null,
                    ]);
                }


                // Foto AFTER
                if ($request->hasFile("foto_after.$i")) {

                    $file = $request->file("foto_after.$i");

                    $path = $file->store('mso_photos', 'image');

                    MsoPhoto::create([
                        'mso_transaction_id' => $mso->id,
                        'finding_id'         => $finding->id,
                        'type'               => 'after',
                        'filename'           => $path,
                        'mime'               => $file->getMimeType(),
                        'filesize'           => $file->getSize(),
                        'drive_file_id'      => null,
                        'thumb_base64'       => null,
                    ]);
                }

            }
        }

        return redirect()->route('mso.index')->with('success', 'MSO berhasil dibuat.');
    }


    public function getAreas($plant_id)
    {
        $areas = \App\Models\Area::where('plant_id', $plant_id)->get();
        return response()->json($areas);
    }

    public function getNomenclatures($area_id)
    {
        $nomenclatures = \App\Models\Nomenclature::where('area_id', $area_id)->get();
        return response()->json($nomenclatures);
    }

    /**
     * Update Material Master untuk Finding (Supervisor Only)
     */
    public function updateMaterialMaster(Request $request, MsoFinding $finding)
    {
        // 🔒 AUTHORIZATION: Hanya Supervisor yang bisa update
        if (!auth()->user()->hasRole('Supervisor')) {
            return back()->with('error', '❌ Unauthorized: Only Supervisor can update Material Master');
        }

        try {
            // ✅ VALIDASI
            $validated = $request->validate([
                'material_master_id' => 'nullable|exists:material_master,id'
            ]);

            // 💾 UPDATE dengan nilai baru
            $finding->material_master_id = $validated['material_master_id'];
            $finding->save();

            // Log untuk debugging
            \Log::info('Material Master Updated', [
                'finding_id' => $finding->id,
                'sub_id' => $finding->sub_id,
                'material_master_id' => $finding->material_master_id,
                'material_code' => $finding->materialMaster->material_code ?? null,
                'user_id' => auth()->id()
            ]);

            // ✅ REDIRECT dengan success message
            return redirect()->route('mso.index')
                ->with('success', '✅ Material Master berhasil diperbarui untuk Finding ' . $finding->sub_id);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->with('error', '❌ Validasi gagal: Material Master tidak valid');
        } catch (\Exception $e) {
            \Log::error('Error updating material master', [
                'finding_id' => $finding->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', '❌ Gagal memperbarui Material Master: ' . $e->getMessage());
        }
    }

    public function destroyFinding(MsoFinding $finding)
    {
        // 🔐 AUTHORIZATION: Hanya Supervisor
        if (!auth()->user()->hasRole('Supervisor')) {
            abort(403, 'Unauthorized: Only Supervisor can delete findings');
        }

        try {
            DB::beginTransaction();

            // 🗑️ Hapus foto terkait dari storage dan database
            foreach ($finding->photos as $photo) {
                if ($photo->filename && Storage::disk('image')->exists($photo->filename)) {
                    Storage::disk('image')->delete($photo->filename);
                }
                $photo->delete();
            }

            // 🗑️ Hapus finding
            $finding->delete();

            DB::commit();

            return back()->with('success', 'Row komponen berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function destroy(MsoTransaction $mso)
    {
        // 🔐 AUTHORIZATION
        if (!auth()->user()->hasRole('Supervisor')) {
            abort(403, 'Unauthorized: Only Supervisor can delete MSO');
        }

        try {
            DB::beginTransaction();

            // 🗑️ HAPUS SEMUA FOTO (STORAGE + DB)
            foreach ($mso->photos as $photo) {
                if ($photo->filename && Storage::disk('image')->exists($photo->filename)) {
                    Storage::disk('image')->delete($photo->filename);
                }
                $photo->delete();
            }

            // 🗑️ HAPUS FOTO YANG TERKAIT DENGAN FINDING (double safety)
            foreach ($mso->findings as $finding) {
                foreach ($finding->photos as $photo) {
                    if ($photo->filename && Storage::disk('image')->exists($photo->filename)) {
                        Storage::disk('image')->delete($photo->filename);
                    }
                    $photo->delete();
                }
            }

            // 🗑️ HAPUS FINDINGS
            $mso->findings()->delete();

            // 🗑️ HAPUS MSO (HEADER)
            $mso->delete();

            DB::commit();

            return redirect()
                ->route('mso.index')
                ->with('success', 'MSO berhasil dihapus beserta seluruh data terkait');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with(
                'error',
                'Gagal menghapus MSO: ' . $e->getMessage()
            );
        }
    }

    public function exportExcel(Request $request)
    {
        // ====================================================================
        // 1️⃣ Ambil data dengan filter yang sama seperti di index
        // ====================================================================
        $query = MsoFinding::with([
            'transaction.user',
            'transaction.plant',
            'transaction.area',
            'transaction.nomenclature',
            'transaction.maintenanceType',
            'component',
            'materialMaster',
            'photos'
        ])->orderByDesc('created_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('temuan', 'like', "%$search%")
                    ->orWhereHas('transaction', function ($t) use ($search) {
                        $t->where('no_mso', 'like', "%$search%")
                            ->orWhere('id_trans', 'like', "%$search%");
                    })
                    ->orWhereHas('component', function ($c) use ($search) {
                        $c->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('transaction.area', function ($a) use ($search) {
                        $a->where('name', 'like', "%$search%");
                    });
            });
        }

        if ($request->filled('plant')) {
            $query->whereHas('transaction', function($q) use ($request) {
                $q->where('plant_id', $request->plant);
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('transaction', function($q) use ($request) {
                $q->where('status_pekerjaan', $request->status);
            });
        }

        $rows = $query->get();

        // ====================================================================
        // 2️⃣ Buat file Excel
        // ====================================================================
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('MSO Data');

        $headers = [
            'ID Trans', 'Sub ID', 'User', 'Time Stamp', 'Plant', 'Area', 'Nomenclature',
            'Deskripsi', 'Status Peralatan', 'Jenis Maintenance', 'Komponen', 'Temuan Abnormalitas',
            'Material Master', 'Action', 'Status Pekerjaan', 'Start Date', 'Finish Date',
            'Start Hour', 'Finish Hour', 'Total Duration (jam)', 'Foto Sebelum (URL)', 'Foto Sesudah (URL)',
            'Keterangan', 'No MSO'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $rowNum = 2;
        foreach ($rows as $row) {
            $beforeUrls = [];
            $afterUrls = [];
            foreach ($row->photos as $photo) {
                if ($photo->type == 'before') {
                    $beforeUrls[] = Storage::disk('image')->url($photo->filename);
                } elseif ($photo->type == 'after') {
                    $afterUrls[] = Storage::disk('image')->url($photo->filename);
                }
            }

            $sheet->setCellValue('A' . $rowNum, $row->transaction->id_trans ?? '');
            $sheet->setCellValue('B' . $rowNum, $row->sub_id ?? '');
            $sheet->setCellValue('C' . $rowNum, $row->transaction->user->name ?? '');
            $sheet->setCellValue('D' . $rowNum, $row->created_at ? $row->created_at->format('d/m/Y H:i') : '');
            $sheet->setCellValue('E' . $rowNum, $row->transaction->plant->name ?? '');
            $sheet->setCellValue('F' . $rowNum, $row->transaction->area->name ?? '');
            $sheet->setCellValue('G' . $rowNum, $row->transaction->nomenclature->name ?? '');
            $sheet->setCellValue('H' . $rowNum, $row->transaction->nomenclature->description ?? '');
            $sheet->setCellValue('I' . $rowNum, $row->transaction->status_peralatan ?? '');
            $sheet->setCellValue('J' . $rowNum, $row->transaction->maintenanceType->name ?? '');
            $sheet->setCellValue('K' . $rowNum, $row->component->name ?? '');
            $sheet->setCellValue('L' . $rowNum, $row->temuan ?? '');
            $sheet->setCellValue('M' . $rowNum, $row->materialMaster->material_code ?? '');
            $sheet->setCellValue('N' . $rowNum, $row->action ?? '');
            $sheet->setCellValue('O' . $rowNum, $row->transaction->status_pekerjaan ?? '');
            $sheet->setCellValue('P' . $rowNum, $row->transaction->start_date ? \Carbon\Carbon::parse($row->transaction->start_date)->format('Y-m-d') : '');
            $sheet->setCellValue('Q' . $rowNum, $row->transaction->finish_date ? \Carbon\Carbon::parse($row->transaction->finish_date)->format('Y-m-d') : '');
            $sheet->setCellValue('R' . $rowNum, $row->transaction->start_hour ?? '');
            $sheet->setCellValue('S' . $rowNum, $row->transaction->finish_hour ?? '');
            $sheet->setCellValue('T' . $rowNum, $row->transaction->total_duration ?? '');
            $sheet->setCellValue('U' . $rowNum, implode(', ', $beforeUrls));
            $sheet->setCellValue('V' . $rowNum, implode(', ', $afterUrls));
            $sheet->setCellValue('W' . $rowNum, $row->transaction->keterangan ?? '');
            $sheet->setCellValue('X' . $rowNum, $row->transaction->no_mso ?? '');
            $rowNum++;
        }

        foreach (range('A', 'X') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // ====================================================================
        // 3️⃣ Download file
        // ====================================================================
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'MSO_Export_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
        exit;
    }

    public function updateTime(Request $request, MsoTransaction $mso)
    {
        // 🔐 AUTHORIZATION: Hanya Supervisor yang bisa update
        if (!auth()->user()->hasRole('Supervisor')) {
            abort(403, 'Only Supervisor can update work time');
        }

        // ✅ VALIDASI INPUT
        $validated = $request->validate([
            'start_date'  => 'nullable|date',
            'finish_date' => 'nullable|date|after_or_equal:start_date',
            'start_hour'  => 'nullable|date_format:H:i',
            'finish_hour' => 'nullable|date_format:H:i',
        ]);

        // 📝 UPDATE DATA WAKTU
        // Hanya update field yang dikirim dalam request
        if ($request->has('start_date')) {
            $mso->start_date = $request->start_date 
                ? \Carbon\Carbon::parse($request->start_date)->format('Y-m-d H:i:s')
                : null;
        }

        if ($request->has('finish_date')) {
            $mso->finish_date = $request->finish_date 
                ? \Carbon\Carbon::parse($request->finish_date)->format('Y-m-d H:i:s')
                : null;
        }

        if ($request->has('start_hour')) {
            $mso->start_hour = $request->start_hour;
        }

        if ($request->has('finish_hour')) {
            $mso->finish_hour = $request->finish_hour;
        }

        // ⏱️ HITUNG TOTAL DURATION (JAM DESIMAL)
        // Syarat: Semua field waktu harus terisi
        if (
            $mso->start_date &&
            $mso->finish_date &&
            $mso->start_hour &&
            $mso->finish_hour
        ) {
            try {
                // Parse start_date (handle both string and Carbon instance)
                $startDate = $mso->start_date instanceof \Carbon\Carbon 
                    ? $mso->start_date->format('Y-m-d') 
                    : \Carbon\Carbon::parse($mso->start_date)->format('Y-m-d');

                // Parse finish_date (handle both string and Carbon instance)
                $finishDate = $mso->finish_date instanceof \Carbon\Carbon 
                    ? $mso->finish_date->format('Y-m-d') 
                    : \Carbon\Carbon::parse($mso->finish_date)->format('Y-m-d');

                // Gabungkan tanggal dan jam untuk create datetime lengkap
                $startDateTime = \Carbon\Carbon::parse($startDate . ' ' . $mso->start_hour);
                $finishDateTime = \Carbon\Carbon::parse($finishDate . ' ' . $mso->finish_hour);

                // Pastikan finish setelah start
                if ($finishDateTime->greaterThan($startDateTime)) {
                    // Hitung durasi dalam menit, lalu convert ke jam (desimal)
                    $totalMinutes = $startDateTime->diffInMinutes($finishDateTime);
                    $mso->total_duration = round($totalMinutes / 60, 2);

                    // 🎯 AUTO-UPDATE STATUS PEKERJAAN MENJADI "CLOSED"
                    // Ketika finish_date & finish_hour sudah terisi
                    if ($mso->finish_date && $mso->finish_hour) {
                        $mso->status_pekerjaan = 'Closed';
                    }
                } else {
                    // Jika finish sebelum start, set duration ke null
                    $mso->total_duration = null;
                }
            } catch (\Exception $e) {
                // Jika ada error parsing, set null
                $mso->total_duration = null;
                \Log::error('Error calculating duration: ' . $e->getMessage());
            }
        } else {
            // Jika ada field yang kosong, set duration ke null
            $mso->total_duration = null;

            // 🔄 LOGIKA STATUS OTOMATIS
            // Jika start_date terisi tapi finish belum → "On Progress"
            if ($mso->start_date && $mso->start_hour && 
                (!$mso->finish_date || !$mso->finish_hour)) {
                $mso->status_pekerjaan = 'On Progress';
            }
            // Jika semua kosong → tetap "Open" (atau biarkan status existing)
            elseif (!$mso->start_date && !$mso->start_hour) {
                // Biarkan status existing atau set ke "Open" jika Anda mau
                // $mso->status_pekerjaan = 'Open';
            }
        }

        // 💾 SIMPAN KE DATABASE
        $mso->save();

        // ✅ REDIRECT DENGAN SUCCESS MESSAGE
        $message = '✅ Waktu pengerjaan berhasil diperbarui!';
        
        // Tambahkan info status jika berubah
        if ($mso->status_pekerjaan == 'Closed') {
            $message .= ' Status pekerjaan otomatis diubah menjadi CLOSED.';
        } elseif ($mso->status_pekerjaan == 'On Progress') {
            $message .= ' Status pekerjaan: ON PROGRESS.';
        }

        return back()->with('success', $message);
    }
}