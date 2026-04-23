<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaterialMonitoring;
use App\Models\MsoTransaction;
use Carbon\Carbon;

class MaterialMonitoringSeeder extends Seeder
{
    // ================================================================
    // POOL DATA
    // ================================================================

    private array $uomList = ['Pcs', 'Kg', 'Ltr', 'Set', 'Unit', 'Roll', 'Mtr', 'Box'];

    private array $vendorList = [
        'PT Maju Bersama',
        'CV Teknik Jaya',
        'PT Solusi Prima',
        'UD Karya Mandiri',
        'PT Indotek Perkasa',
        'CV Mitra Sejati',
        'PT Anugerah Abadi',
        'PT Global Teknik',
        'CV Sumber Makmur',
        'PT Dinamika Utama',
        'PT Fajar Energi',
        'CV Bumi Persada',
    ];

    /**
     * Bobot pemilihan jenis pengadaan — tidak merata agar lebih realistis.
     */
    private array $pengadaanWeight = [
        'Jasa'            => 35,
        'Barang-Jasa'     => 30,
        'Via Peng.Barang' => 25,
        'Via Capex'       => 10,
    ];

    /**
     * Rata-rata lead time procurement (hari) per jenis pengadaan.
     * Digunakan untuk menentukan jarak antar tanggal dokumen.
     */
    private array $durasiMean = [
        'Jasa'            => 21,   // ~3 minggu
        'Barang-Jasa'     => 35,   // ~5 minggu
        'Via Peng.Barang' => 45,   // ~6 minggu
        'Via Capex'       => 90,   // ~3 bulan
    ];

    // ================================================================
    // MAIN RUN
    // ================================================================
    public function run(): void
    {
        $msoTransactions = MsoTransaction::with([
                'findings.component',
                'findings.materialMaster',
                'nomenclature',
            ])
            ->orderBy('id_trans')
            ->get();

        if ($msoTransactions->isEmpty()) {
            $this->command->error('❌ Tidak ada data MsoTransaction. Jalankan MsoTransactionSeeder terlebih dahulu.');
            return;
        }

        $this->command->info("ℹ️  Ditemukan {$msoTransactions->count()} MSO Transaction sebagai referensi trans_id.");

        $today   = Carbon::today();
        $created = 0;
        $skipped = 0;

        foreach ($msoTransactions as $mso) {
            $transId     = $mso->id_trans;
            $jumlahBaris = rand(1, 4);

            // Tanggal dasar: permintaan material dibuat setelah MSO dibuka
            $baseDate = $mso->start_date
                ? Carbon::parse($mso->start_date)
                : Carbon::parse($mso->created_at);

            for ($i = 0; $i < $jumlahBaris; $i++) {

                $pengadaan = $this->weightedRandom($this->pengadaanWeight);

                // Tanggal permintaan material (beberapa hari setelah MSO dibuka)
                $tanggal = $baseDate->copy()->addDays(rand(1, 7));
                if ($tanggal->gt($today)) {
                    $tanggal = $today->copy()->subDays(rand(1, 14));
                }

                // Lead time procurement untuk jenis pengadaan ini
                $mean       = $this->durasiMean[$pengadaan];
                $durasiHari = rand(max(3, (int)($mean * 0.5)), (int)($mean * 1.8));

                // Bangun timeline dokumen
                [
                    $tanggalReservasi, $nomorReservasi,
                    $tanggalPr,        $nomorPr,
                    $tanggalPo,        $nomorPo,
                    $estimatedDelivery,
                    $nomorNotif,
                ] = $this->buildTimeline($tanggal, $durasiHari, $i, $today);

                // ============================================================
                // STATUS MATERIAL — independen dari status MSO
                //
                // "Closed" = material SUDAH READY / SUDAH TIBA dan siap pakai.
                // "Open"   = material BELUM READY, masih dalam proses pengadaan.
                //
                // Aturan:
                //   • Belum ada PO               → Open  (proses pengadaan belum selesai)
                //   • Ada PO tapi delivery belum lewat → Open (menunggu barang/jasa tiba)
                //   • Ada PO + estimated_delivery sudah lewat → Closed (material sudah tiba)
                //   • Ada PO + tidak ada est. delivery + tanggal_po sudah lewat → Closed
                // ============================================================
                $status = $this->resolveStatusMaterial($tanggalPo, $estimatedDelivery, $today);

                // Ambil data finding MSO untuk konsistensi nomenclature & component
                $finding = $mso->findings->isNotEmpty()
                    ? $mso->findings->get($i % $mso->findings->count())
                    : null;

                $nomenclature   = $mso->nomenclature?->name
                    ?? ('NOM-' . strtoupper(substr($transId, 3, 3)));
                $component      = $finding?->component?->name ?? 'Komponen Umum';
                $materialMaster = $finding?->materialMaster?->kode_material
                    ?? $this->fakeMaterialMaster($pengadaan, $i);

                try {
                    MaterialMonitoring::create([
                        'trans_id'           => $transId,
                        'nomenclature'       => $nomenclature,
                        'component'          => $component,
                        'abnormality'        => $this->randomAbnormality(),
                        'action'             => $this->randomAction(),
                        'material_master'    => $materialMaster,

                        'tanggal'            => $tanggal->toDateString(),
                        'no_notifikasi'      => $nomorNotif,

                        'qty'                => rand(1, 20),
                        'uom'                => collect($this->uomList)->random(),

                        'pengadaan'          => $pengadaan,
                        'model'              => collect(['Tender', 'TL'])->random(),

                        'nomor_reservasi'    => $nomorReservasi,
                        'tanggal_reservasi'  => $tanggalReservasi,

                        'nomor_pr'           => $nomorPr,
                        'tanggal_pr'         => $tanggalPr,

                        'nomor_po'           => $nomorPo,
                        'tanggal_po'         => $tanggalPo,

                        'estimated_delivery' => $estimatedDelivery,

                        'estimasi_harga'     => $this->randomHarga($pengadaan),
                        'nama_vendor'        => collect($this->vendorList)->random(),

                        'status'             => $status,
                    ]);
                    $created++;
                } catch (\Exception $e) {
                    $this->command->warn("⚠️  Gagal insert trans_id={$transId}: " . $e->getMessage());
                    $skipped++;
                }
            }
        }

        // ── Ringkasan ──
        $this->command->info('');
        $this->command->info('✅ Seeder selesai.');
        $this->command->table(
            ['Keterangan', 'Jumlah'],
            [
                ['MSO direferensikan',       $msoTransactions->count()],
                ['Baris monitoring dibuat',   $created],
                ['Baris gagal (skip)',         $skipped],
                ['Rata-rata baris per MSO',    $msoTransactions->count() > 0
                    ? round($created / $msoTransactions->count(), 1) : 0],
            ]
        );

        // Distribusi status material per pengadaan
        $dist = MaterialMonitoring::selectRaw('pengadaan, status, COUNT(*) as total')
            ->whereNotNull('pengadaan')
            ->groupBy('pengadaan', 'status')
            ->orderBy('pengadaan')
            ->orderBy('status')
            ->get();

        $rows = [];
        foreach ($dist as $d) {
            $rows[] = [$d->pengadaan, $d->status, $d->total];
        }
        $this->command->table(['Pengadaan', 'Status Material', 'Jumlah'], $rows);
    }

    // ================================================================
    // HELPER: Tentukan status material berdasarkan progress dokumen
    //         dan apakah tanggal delivery sudah terlewati.
    // ================================================================
    private function resolveStatusMaterial(
        ?string $tanggalPo,
        ?string $estimatedDelivery,
        Carbon  $today
    ): string {
        // Belum ada PO → proses pengadaan belum selesai → Open
        if (! $tanggalPo) {
            return 'Open';
        }

        // Ada PO — cek apakah material sudah tiba
        if ($estimatedDelivery) {
            // Material tiba = estimated_delivery sudah terlewati
            return Carbon::parse($estimatedDelivery)->lte($today)
                ? 'Closed'
                : 'Open';  // PO ada tapi barang/jasa belum tiba
        }

        // Ada PO tapi tidak ada estimated_delivery:
        // anggap Closed jika tanggal_po sudah lewat (jasa/kerja sudah selesai)
        return Carbon::parse($tanggalPo)->lte($today) ? 'Closed' : 'Open';
    }

    // ================================================================
    // HELPER: Bangun timeline dokumen berurutan dan realistis
    //
    // Alur: Notifikasi → Reservasi → PR → PO → Estimated Delivery
    //
    // Probabilitas keberadaan dokumen:
    //   Reservasi : 80% ada
    //   PR        : 85% jika ada reservasi | 55% langsung
    //   PO        : 85% jika ada PR | tidak ada jika belum PR
    //   Est. Del  : selalu ada jika PO ada | 40% jika belum ada PO
    // ================================================================
    private function buildTimeline(
        Carbon $tanggal,
        int    $durasiHari,
        int    $index,
        Carbon $today
    ): array {
        $cursor = $tanggal->copy();

        $nomorNotif = '10-' . $tanggal->format('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // ── Reservasi (80%) ──
        $tanggalReservasi = null;
        $nomorReservasi   = null;
        if (rand(1, 100) <= 80) {
            $cursor = $cursor->copy()->addDays(rand(1, 5));
            if ($cursor->lte($today)) {
                $tanggalReservasi = $cursor->toDateString();
                $nomorReservasi   = 'RSV-' . $cursor->format('Ymd') . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            }
        }

        // ── PR (85% jika ada reservasi, 55% langsung) ──
        $tanggalPr = null;
        $nomorPr   = null;
        if (rand(1, 100) <= ($tanggalReservasi ? 85 : 55)) {
            $cursor = $cursor->copy()->addDays(rand(3, 10));
            if ($cursor->lte($today)) {
                $tanggalPr = $cursor->toDateString();
                $nomorPr   = '45' . $cursor->format('Y') . str_pad(rand(100000, 999999), 7, '0', STR_PAD_LEFT);
            }
        }

        // ── PO — hanya bisa ada jika PR sudah ada (85%) ──
        $tanggalPo = null;
        $nomorPo   = null;
        if ($tanggalPr && rand(1, 100) <= 85) {
            $cursor = $cursor->copy()->addDays(rand(5, 14));
            // Tanggal PO boleh masa depan (sudah dipesan, belum tiba)
            $tanggalPo = $cursor->toDateString();
            $nomorPo   = '45' . $cursor->format('Y') . str_pad(rand(1000000, 9999999), 8, '0', STR_PAD_LEFT);
        }

        // ── Estimated Delivery ──
        $estimatedDelivery = null;
        if ($tanggalPo) {
            $leadDays          = rand((int)($durasiHari * 0.3), $durasiHari);
            $estimatedDelivery = Carbon::parse($tanggalPo)->addDays($leadDays)->toDateString();
        } elseif (rand(1, 100) <= 40) {
            // Belum PO tapi sudah ada estimasi kasar dari awal
            $estimatedDelivery = $cursor->copy()
                ->addDays(rand($durasiHari, (int)($durasiHari * 1.5)))
                ->toDateString();
        }

        return [
            $tanggalReservasi, $nomorReservasi,
            $tanggalPr,        $nomorPr,
            $tanggalPo,        $nomorPo,
            $estimatedDelivery,
            $nomorNotif,
        ];
    }

    // ================================================================
    // HELPER: Weighted random
    // ================================================================
    private function weightedRandom(array $weights): string
    {
        $total      = array_sum($weights);
        $rand       = rand(1, $total);
        $cumulative = 0;
        foreach ($weights as $key => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) return $key;
        }
        return array_key_first($weights);
    }

    // ================================================================
    // HELPER: Kode material dummy per pengadaan
    // ================================================================
    private function fakeMaterialMaster(string $pengadaan, int $index): string
    {
        $prefix = match ($pengadaan) {
            'Jasa'            => 'SRV',
            'Barang-Jasa'     => 'BRJ',
            'Via Peng.Barang' => 'VPB',
            'Via Capex'       => 'CAP',
            default           => 'MAT',
        };
        return $prefix . '-' . str_pad(($index + 1) * rand(10, 99), 6, '0', STR_PAD_LEFT);
    }

    // ================================================================
    // HELPER: Estimasi harga realistis per jenis pengadaan
    // ================================================================
    private function randomHarga(string $pengadaan): float
    {
        return match ($pengadaan) {
            'Jasa'            => rand(5,    200)  * 1_000_000,
            'Barang-Jasa'     => rand(10,   500)  * 1_000_000,
            'Via Peng.Barang' => rand(2,    100)  * 1_000_000,
            'Via Capex'       => rand(100, 5000)  * 1_000_000,
            default           => rand(5,    100)  * 1_000_000,
        };
    }

    // ================================================================
    // HELPER: Pool teks abnormality
    // ================================================================
    private function randomAbnormality(): string
    {
        return collect([
            'Bearing aus dan mengeluarkan suara bising saat operasi',
            'Kebocoran seal pada stuffing box pompa',
            'Vibrasi tinggi pada motor induksi 22 kW',
            'Korosi pada body valve gate 6 inch',
            'O-ring coupling bocor, terjadi rembesan oli',
            'Retakan pada base plate unit kompresor',
            'Kerusakan impeller pompa sentrifugal',
            'Overheating pada winding motor listrik',
            'Keausan pada gear reducer transmisi',
            'Sensor temperatur tidak berfungsi normal',
            'Kebocoran pipa steam 4 inch line header',
            'Permukaan shaft scratch akibat gesekan',
            'Filter udara tersumbat, tekanan turun signifikan',
            'Katup pengaman tidak menutup sempurna',
            'Flange bocor pada sambungan pipa process line',
        ])->random();
    }

    // ================================================================
    // HELPER: Pool teks action / tindakan perbaikan
    // ================================================================
    private function randomAction(): string
    {
        return collect([
            'Penggantian bearing baru sesuai spesifikasi OEM',
            'Penggantian mechanical seal dan packing gland',
            'Balancing rotor dan alignment motor-pompa',
            'Penggantian body valve dan seat ring',
            'Penggantian O-ring dan re-torque baut coupling',
            'Pemasangan shim pada base plate dan grout ulang',
            'Penggantian impeller baru material SS 316',
            'Rewinding stator motor dan perbaikan insulation',
            'Penggantian gear set reducer lengkap',
            'Kalibrasi dan penggantian sensor thermocouple',
            'Penggantian gasket dan clamp pipa steam',
            'Pengerjaan hard chrome pada permukaan shaft',
            'Penggantian filter element dan housing sekaligus',
            'Overhaul valve pengaman dan setting ulang tekanan',
            'Penggantian gasket spiral wound dan stud bolt',
        ])->random();
    }
}