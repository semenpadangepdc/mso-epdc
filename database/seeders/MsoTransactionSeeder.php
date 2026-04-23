<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MsoTransaction;
use App\Models\MsoFinding;
use App\Models\User;
use App\Models\Plant;
use App\Models\Area;
use App\Models\Nomenclature;
use App\Models\MaintenanceType;
use App\Models\Component;
use App\Models\MaterialMaster;
use Carbon\Carbon;

class MsoTransactionSeeder extends Seeder
{
    public function run(): void
    {
        // ================================================================
        // LOAD MASTER DATA
        // ================================================================
        $user             = User::first();
        $plants           = Plant::all();
        $areas            = Area::all();
        $nomenclatures    = Nomenclature::all();
        $maintenanceTypes = MaintenanceType::all();
        $components       = Component::all();
        $materials        = MaterialMaster::all();

        if (
            !$user || $plants->isEmpty() || $areas->isEmpty() ||
            $nomenclatures->isEmpty() || $maintenanceTypes->isEmpty() ||
            $components->isEmpty()
        ) {
            $this->command->error('❌ Master data belum lengkap (user / plant / area / component)');
            return;
        }

        // ================================================================
        // PISAHKAN NOMENCLATURE BERDASARKAN DESCRIPTION
        //
        // NomenclatureSeeder menyimpan tipe alat di dalam kolom 'description'
        // dengan prefix: "JPF -", "ESP ", "DDS -", "BHF "
        // Tidak ada kolom 'type' — filter dilakukan via LIKE pada description.
        // ================================================================
        $unitPrefixes = ['JPF', 'ESP', 'DDS', 'BHF'];

        $nomenclaturesUnit = $nomenclatures->filter(function ($n) use ($unitPrefixes) {
            foreach ($unitPrefixes as $prefix) {
                if (stripos($n->description ?? '', $prefix) === 0) {
                    return true;
                }
            }
            return false;
        });

        $nomenclaturesOther = $nomenclatures->filter(function ($n) use ($unitPrefixes) {
            foreach ($unitPrefixes as $prefix) {
                if (stripos($n->description ?? '', $prefix) === 0) {
                    return false;
                }
            }
            return true;
        });

        // Fallback: jika tidak ada yang match, pakai semua nomenclature
        if ($nomenclaturesUnit->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada nomenclature dengan prefix JPF/ESP/DDS/BHF — semua nomenclature dipakai.');
            $nomenclaturesUnit = $nomenclatures;
        }

        $this->command->info("ℹ️  Nomenclature unit (JPF/ESP/DDS/BHF): {$nomenclaturesUnit->count()}");
        $this->command->info("ℹ️  Nomenclature lain: {$nomenclaturesOther->count()}");

        // ================================================================
        // MAINTENANCE TYPE ID = 2 (Breakdown/Abnormality)
        // Diperlukan untuk Top 5 Frekuensi & Durasi
        // ================================================================
        $maintenanceTypeBreakdown = $maintenanceTypes->firstWhere('id', 2)
            ?? $maintenanceTypes->first();

        // ================================================================
        // DISTRIBUSI BULAN
        // Data tersebar Jan–bulan sekarang agar filter bulanan ada isi
        // ================================================================
        $currentYear  = (int) now()->year;
        $currentMonth = (int) now()->month;

        $datePool = [];
        for ($m = 1; $m <= $currentMonth; $m++) {
            $daysInMonth = Carbon::create($currentYear, $m)->daysInMonth;
            for ($d = 0; $d < 8; $d++) {
                $datePool[] = Carbon::create($currentYear, $m, rand(1, $daysInMonth));
            }
        }
        shuffle($datePool);

        // ================================================================
        // COUNTER & TRACKING
        // ================================================================
        $counter      = 1;
        $totalCreated = 0;

        // ================================================================
        // HELPER: resolve area_id yang sesuai dengan plant_id nomenclature
        //
        // NomenclatureSeeder selalu menyimpan plant_id & area_id pada
        // setiap nomenclature. MSO wajib menggunakan plant & area yang
        // sama dengan nomenclature yang dipilih agar data konsisten.
        // ================================================================
        $resolveArea = function (Nomenclature $nomenclature) use ($areas) {
            // Cari area yang benar-benar terikat pada plant yang sama
            $matched = $areas->where('id', $nomenclature->area_id)->first();
            return $matched ?? $areas->where('plant_id', $nomenclature->plant_id)->first();
        };

        // ================================================================
        // HELPER: buat satu MSO + findings
        // ================================================================
        $createMso = function (
            Nomenclature  $nomenclature,
            string        $statusPekerjaan,
            Carbon        $createdAt,
            ?Carbon       $startDate,
            ?int          $maintenanceTypeId = null,
            ?float        $forceDuration     = null
        ) use (
            $user, $maintenanceTypes,
            $components, $materials,
            $resolveArea,
            &$counter, &$totalCreated
        ) {
            $mtId = $maintenanceTypeId ?? $maintenanceTypes->random()->id;

            $idTrans = sprintf(
                'ID-%s-%s-%04d',
                strtoupper(substr($nomenclature->name, 0, 3)),
                $createdAt->format('Ymd'),
                $counter
            );

            // Gunakan plant & area yang sesuai dengan nomenclature
            $plantId = $nomenclature->plant_id;
            $area    = $resolveArea($nomenclature);
            $areaId  = $area ? $area->id : null;

            // Tentukan jam & finish berdasarkan status
            $startTime  = null;
            $finishDate = null;
            $finishTime = null;
            $duration   = null;

            if ($statusPekerjaan === 'On Progress' && $startDate) {
                $startHour   = rand(7, 16);
                $startMinute = collect([0, 15, 30, 45])->random();
                $startTime   = sprintf('%02d:%02d', $startHour, $startMinute);

            } elseif ($statusPekerjaan === 'Closed' && $startDate) {
                $startHour     = rand(7, 16);
                $startMinute   = collect([0, 15, 30, 45])->random();
                $startTime     = sprintf('%02d:%02d', $startHour, $startMinute);

                $duration      = $forceDuration ?? (rand(20, 80) / 10); // 2.0–8.0 jam
                $finishDate    = $startDate->copy()->addMinutes((int) ($duration * 60));

                $totalMinutes  = ($startHour * 60) + $startMinute + (int) ($duration * 60);
                $finishHour    = (int) floor($totalMinutes / 60) % 24;
                $finishMinute  = (int) ($totalMinutes % 60);
                $finishTime    = sprintf('%02d:%02d', $finishHour, $finishMinute);
            }

            $mso = MsoTransaction::create([
                'id_trans'            => $idTrans,
                'no_mso'              => 'MSO-' . now()->timestamp . '-' . $counter,
                'user_id'             => $user->id,
                'plant_id'            => $plantId,
                'area_id'             => $areaId,
                'nomenclature_id'     => $nomenclature->id,
                'maintenance_type_id' => $mtId,
                'description'         => 'Dummy MSO ke-' . $counter,
                'status_peralatan'    => collect([
                    'Active Operation',
                    'Ready Standby',
                    'Broken - Eliminated',
                ])->random(),
                'status_pekerjaan'    => $statusPekerjaan,
                'start_date'          => $startDate,
                'start_hour'          => $startTime,
                'finish_date'         => $finishDate,
                'finish_hour'         => $finishTime,
                'total_duration'      => ($statusPekerjaan === 'Closed') ? $duration : null,
                'keterangan'          => 'Seeder auto generated',
                'created_at'          => $createdAt,
                'updated_at'          => $createdAt,
            ]);

            // Findings: 1–3 per MSO
            $totalFindings = rand(1, 3);
            for ($f = 1; $f <= $totalFindings; $f++) {
                $hasMaterial = rand(1, 100) <= 60;
                MsoFinding::create([
                    'mso_transaction_id' => $mso->id,
                    'sub_id'             => $idTrans . '-' . str_pad($f, 2, '0', STR_PAD_LEFT),
                    'component_id'       => $components->random()->id,
                    'material_master_id' => ($hasMaterial && $materials->isNotEmpty())
                        ? $materials->random()->id
                        : null,
                    'temuan'             => 'Temuan dummy #' . $f,
                    'action'             => 'Tindakan perbaikan dummy',
                    'status_perbaikan'   => collect(['Pending', 'Done', 'On Hold'])->random(),
                ]);
            }

            $counter++;
            $totalCreated++;
        };

        // ================================================================
        // BAGIAN 1: MSO STATUS OPEN (30 MSO)
        // - start_date = NULL
        // - created_at tersebar di bulan-bulan tahun ini
        // ================================================================
        $this->command->info('📝 Membuat MSO Open...');
        $openCount = 30;
        for ($i = 0; $i < $openCount; $i++) {
            $createdAt    = collect($datePool)->random();
            $nomenclature = $nomenclatures->random();
            $createMso($nomenclature, 'Open', $createdAt, null);
        }

        // ================================================================
        // BAGIAN 2: MSO STATUS ON PROGRESS (30 MSO)
        // - start_date ada, finish_date = NULL
        // ================================================================
        $this->command->info('🔄 Membuat MSO On Progress...');
        $onProgressCount = 30;
        for ($i = 0; $i < $onProgressCount; $i++) {
            $startDate    = collect($datePool)->random();
            $createdAt    = $startDate->copy()->subDays(rand(0, 3));
            $nomenclature = $nomenclaturesUnit->random();
            $createMso($nomenclature, 'On Progress', $createdAt, $startDate);
        }

        // ================================================================
        // BAGIAN 3: MSO STATUS CLOSED — BREAKDOWN (maintenance_type_id = 2)
        // Untuk TOP 5 FREKUENSI & DURASI — tersebar merata per bulan
        //
        // Ambil 5 nomenclature dengan prefix berbeda (JPF, ESP, DDS, BHF)
        // agar top 5 lebih representatif terhadap tipe alat nyata.
        // ================================================================
        $this->command->info('✅ Membuat MSO Closed Breakdown (Top 5)...');

        // Kelompokkan nomenclature unit per prefix lalu ambil satu wakil tiap prefix
        $top5Nomenclatures = collect();
        foreach ($unitPrefixes as $prefix) {
            $pick = $nomenclaturesUnit->first(
                fn($n) => stripos($n->description ?? '', $prefix) === 0
            );
            if ($pick) {
                $top5Nomenclatures->push($pick);
            }
        }
        // Jika kurang dari 5, tambal dari nomenclaturesUnit
        if ($top5Nomenclatures->count() < 5) {
            $remaining = $nomenclaturesUnit->whereNotIn('id', $top5Nomenclatures->pluck('id'))
                ->take(5 - $top5Nomenclatures->count());
            $top5Nomenclatures = $top5Nomenclatures->merge($remaining);
        }
        // Fallback final
        if ($top5Nomenclatures->isEmpty()) {
            $top5Nomenclatures = $nomenclatures->take(5);
        }

        $closedBreakdownCount = 0;
        for ($m = 1; $m <= $currentMonth; $m++) {
            $daysInMonth = Carbon::create($currentYear, $m)->daysInMonth;
            $perMonth    = ($m === $currentMonth) ? 5 : max(1, (int) round(20 / $currentMonth));

            for ($j = 0; $j < $perMonth; $j++) {
                $startDate    = Carbon::create($currentYear, $m, rand(1, $daysInMonth));
                $createdAt    = $startDate->copy()->subDays(rand(0, 2));
                $nomenclature = $top5Nomenclatures->values()->get($j % $top5Nomenclatures->count());
                $baseDuration = 80 - ($j % 5) * 10; // 80, 70, 60, 50, 40
                $duration     = rand($baseDuration - 10, $baseDuration) / 10;
                $createMso(
                    $nomenclature,
                    'Closed',
                    $createdAt,
                    $startDate,
                    $maintenanceTypeBreakdown->id,
                    $duration
                );
                $closedBreakdownCount++;
            }
        }

        // ================================================================
        // BAGIAN 4: MSO STATUS CLOSED — MAINTENANCE LAIN
        // Untuk Maintenance Summary & Completion Rate
        // ================================================================
        $this->command->info('✅ Membuat MSO Closed lainnya...');
        $closedOtherCount = max(0, 100 - $openCount - $onProgressCount - $closedBreakdownCount);

        $otherTypes = $maintenanceTypes->where('id', '!=', $maintenanceTypeBreakdown->id);

        for ($i = 0; $i < $closedOtherCount; $i++) {
            $startDate    = collect($datePool)->random();
            $createdAt    = $startDate->copy()->subDays(rand(0, 3));
            $nomenclature = $nomenclaturesUnit->random();
            $mt           = $otherTypes->isNotEmpty()
                ? $otherTypes->random()
                : $maintenanceTypes->random();
            $createMso($nomenclature, 'Closed', $createdAt, $startDate, $mt->id);
        }

        // ================================================================
        // RINGKASAN
        // ================================================================
        $this->command->info("✅ Total {$totalCreated} MSO berhasil dibuat:");
        $this->command->table(
            ['Status', 'Jumlah', 'Keterangan'],
            [
                ['Open',             $openCount,            'start_date NULL, filter by created_at'],
                ['On Progress',      $onProgressCount,      'start_date ada, finish_date NULL'],
                ['Closed Breakdown', $closedBreakdownCount, 'maintenance_type_id=2, untuk Top 5 Frekuensi & Durasi'],
                ['Closed Lainnya',   $closedOtherCount,     'maintenance type lain, untuk Maintenance Summary'],
                ['TOTAL',            $totalCreated,         ''],
            ]
        );
    }
}