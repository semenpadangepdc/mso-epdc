<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialMonitoring;
use Carbon\Carbon;

class MaterialMonitoringController extends Controller
{
    /**
     * Tampilkan daftar semua data monitoring material.
     */
    public function index(Request $request)
    {
        $query = MaterialMonitoring::query();

        if ($request->filled('material_master')) {
            $query->where('material_master', 'like', '%' . $request->material_master . '%');
        }

        $data  = $query->orderBy('trans_id')->get();
        $total = $data->sum('estimasi_harga');

        return view('monitoring.index', compact('data', 'total'));
    }

    /**
     * Tampilkan layar detail untuk satu ID Trans.
     */
    public function detail($trans_id)
    {
        $data  = MaterialMonitoring::where('trans_id', $trans_id)
                    ->orderBy('created_at')
                    ->get();

        $total = $data->sum('estimasi_harga');

        return view('monitoring.detail', compact('data', 'total', 'trans_id'));
    }

    /**
     * ============================================================
     * RESUME — Rata-rata durasi per tahapan proses pengadaan
     *
     * Tahapan yang dihitung (interval antar tanggal):
     *   [1] Notifikasi  → Reservasi     : tanggal          → tanggal_reservasi
     *   [2] Reservasi   → PR            : tanggal_reservasi → tanggal_pr
     *   [3] PR          → PO            : tanggal_pr        → tanggal_po
     *   [4] PO          → Est. Complete : tanggal_po        → estimated_delivery
     *   [5] Total Lead  → End           : tanggal           → estimated_delivery
     *
     * Setiap interval hanya dihitung jika KEDUA tanggal tersedia.
     * Hasilnya dikelompokkan per jenis Pengadaan.
     * Filter: tahun (dari kolom tanggal), dan/atau jenis pengadaan.
     * ============================================================
     */
    public function resume(Request $request)
    {
        /* ── Query dasar ── */
        $query = MaterialMonitoring::query();

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('pengadaan')) {
            $query->where('pengadaan', $request->pengadaan);
        }

        $all = $query->get();

        /* ── Urutan jenis pengadaan ── */
        $jenisUrut = ['Jasa', 'Barang-Jasa', 'Via Peng.Barang', 'Via Capex'];

        /* ── Tahapan yang akan dihitung ── */
        $stages = [
            'notif_to_reservasi' => [
                'label'  => 'Notifikasi → Reservasi',
                'from'   => 'tanggal',
                'to'     => 'tanggal_reservasi',
                'short'  => 'Notif → RSV',
                'color'  => '#2563EB',
            ],
            'reservasi_to_pr' => [
                'label'  => 'Reservasi → PR',
                'from'   => 'tanggal_reservasi',
                'to'     => 'tanggal_pr',
                'short'  => 'RSV → PR',
                'color'  => '#7C3AED',
            ],
            'pr_to_po' => [
                'label'  => 'PR → PO',
                'from'   => 'tanggal_pr',
                'to'     => 'tanggal_po',
                'short'  => 'PR → PO',
                'color'  => '#D97706',
            ],
            'po_to_delivery' => [
                'label'  => 'PO → Est. Complete',
                'from'   => 'tanggal_po',
                'to'     => 'estimated_delivery',
                'short'  => 'PO → Est.',
                'color'  => '#16a34a',
            ],
            'total_lead' => [
                'label'  => 'Total Lead Time',
                'from'   => 'tanggal',
                'to'     => 'estimated_delivery',
                'short'  => 'Total',
                'color'  => '#DC2626',
            ],
        ];

        /* ── Helper: hitung interval hari antar dua kolom tanggal ── */
        $calcDays = function ($row, $from, $to): ?int {
            $dateFrom = $row->{$from};
            $dateTo   = $row->{$to};

            if (! $dateFrom || ! $dateTo) return null;

            $diff = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo), false);

            // Abaikan jika urutan terbalik (data tidak valid)
            return $diff >= 0 ? (int) $diff : null;
        };

        /* ── Hitung per baris semua interval ── */
        $allWithIntervals = $all->map(function ($row) use ($stages, $calcDays) {
            foreach ($stages as $key => $stage) {
                $row->{"dur_{$key}"} = $calcDays($row, $stage['from'], $stage['to']);
            }
            return $row;
        });

        /* ── Agregasi per jenis pengadaan ── */
        $grouped = $allWithIntervals->groupBy('pengadaan');

        $tableSummary  = [];
        $chartLabels   = [];
        $chartDatasets = [];          // per stage → array nilai rata-rata
        $chartDatasetsTrend = [];     // untuk trend bulanan

        // Inisialisasi dataset per stage
        foreach ($stages as $key => $stage) {
            $chartDatasets[$key] = [];
        }

        foreach ($jenisUrut as $jenis) {
            $group = $grouped->get($jenis, collect());
            if ($group->isEmpty()) continue;

            $chartLabels[] = $jenis;

            $rowData = ['pengadaan' => $jenis, 'total' => $group->count()];

            foreach ($stages as $key => $stage) {
                $values = $group->pluck("dur_{$key}")->filter()->values();

                $rowData[$key] = [
                    'avg'   => $values->count() > 0 ? round($values->avg(), 1) : null,
                    'max'   => $values->count() > 0 ? $values->max() : null,
                    'min'   => $values->count() > 0 ? $values->min() : null,
                    'count' => $values->count(),  // berapa baris yang punya kedua tanggal
                ];

                $chartDatasets[$key][] = $rowData[$key]['avg'];
            }

            $tableSummary[] = $rowData;
        }

        /* ── Trend bulanan (12 bulan terakhir) ── */
        // Untuk setiap bulan → rata-rata total_lead per pengadaan
        $trend = $this->buildMonthlyTrend($allWithIntervals, $jenisUrut);

        /* ── Stat cards keseluruhan (tidak difilter pengadaan) ── */
        $overallQuery = MaterialMonitoring::query();
        if ($request->filled('tahun')) {
            $overallQuery->whereYear('tanggal', $request->tahun);
        }
        if ($request->filled('bulan')) {
            $overallQuery->whereMonth('tanggal', $request->bulan);
        }

        $overallAll = $overallQuery->get()->map(function ($row) use ($stages, $calcDays) {
            foreach ($stages as $key => $stage) {
                $row->{"dur_{$key}"} = $calcDays($row, $stage['from'], $stage['to']);
            }
            return $row;
        });

        $stats = [];
        foreach ($stages as $key => $stage) {
            $values = $overallAll->pluck("dur_{$key}")->filter()->values();
            $stats[$key] = [
                'avg'   => $values->count() > 0 ? round($values->avg(), 1) : null,
                'count' => $values->count(),
            ];
        }
        $stats['total_records'] = $overallAll->count();

        /* ── Daftar tahun & bulan untuk filter ── */
        $tahunList = MaterialMonitoring::selectRaw('YEAR(tanggal) as tahun')
                        ->whereNotNull('tanggal')
                        ->distinct()
                        ->orderBy('tahun', 'desc')
                        ->pluck('tahun');

        $bulanList = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember',
        ];

        /* ── stagesJs: versi ringan untuk JS (tanpa key 'from'/'to') ── */
        $stagesJs = array_values(array_map(fn($s) => [
            'label' => $s['label'],
            'short' => $s['short'],
            'color' => $s['color'],
        ], $stages));

        return view('monitoring.resume', compact(
            'stats',
            'tableSummary',
            'stages',
            'stagesJs',
            'chartLabels',
            'chartDatasets',
            'trend',
            'tahunList',
            'bulanList'
        ));
    }

    /**
     * Build data trend bulanan: 12 bulan terakhir
     * Return: ['labels' => [...], 'datasets' => [ pengadaan => [avg_total_lead, ...] ]]
     */
    private function buildMonthlyTrend($allWithIntervals, array $jenisUrut): array
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $months[] = Carbon::today()->startOfMonth()->subMonths($i);
        }

        $labels   = [];
        $datasets = [];

        foreach ($jenisUrut as $jenis) {
            $datasets[$jenis] = [];
        }

        foreach ($months as $month) {
            $labels[] = $month->translatedFormat('M Y') ?: $month->format('M Y');

            $monthRows = $allWithIntervals->filter(function ($row) use ($month) {
                if (! $row->tanggal) return false;
                $d = Carbon::parse($row->tanggal);
                return $d->year === $month->year && $d->month === $month->month;
            });

            foreach ($jenisUrut as $jenis) {
                $values = $monthRows
                    ->where('pengadaan', $jenis)
                    ->pluck('dur_total_lead')
                    ->filter()
                    ->values();

                $datasets[$jenis][] = $values->count() > 0 ? round($values->avg(), 1) : null;
            }
        }

        return compact('labels', 'datasets');
    }

    /**
     * Simpan baris baru ke material_monitorings dari form di layar detail.
     */
    public function store(Request $request)
    {
        $request->validate([
            'trans_id'           => 'required|string|max:255',
            'qty'                => 'nullable|integer',
            'estimasi_harga'     => 'nullable|numeric',
            'pengadaan'          => 'nullable|in:Jasa,Barang-Jasa,Via Peng.Barang,Via Capex',
            'model'              => 'nullable|in:Tender,TL',
            'status'             => 'nullable|in:Open,Closed',
            'tanggal'            => 'nullable|date',
            'tanggal_reservasi'  => 'nullable|date',
            'tanggal_pr'         => 'nullable|date',
            'tanggal_po'         => 'nullable|date',
            'estimated_delivery' => 'nullable|date',
        ]);

        MaterialMonitoring::create([
            'trans_id'           => $request->trans_id,
            'nomenclature'       => $request->nomenclature,
            'component'          => $request->component,
            'abnormality'        => $request->abnormality,
            'action'             => $request->action,
            'material_master'    => $request->material_master,
            'tanggal'            => $request->tanggal,
            'no_notifikasi'      => $request->no_notifikasi,
            'qty'                => $request->qty,
            'uom'                => $request->uom,
            'pengadaan'          => $request->pengadaan,
            'model'              => $request->model,
            'nomor_reservasi'    => $request->nomor_reservasi,
            'tanggal_reservasi'  => $request->tanggal_reservasi,
            'nomor_pr'           => $request->nomor_pr,
            'tanggal_pr'         => $request->tanggal_pr,
            'nomor_po'           => $request->nomor_po,
            'tanggal_po'         => $request->tanggal_po,
            'estimated_delivery' => $request->estimated_delivery,
            'estimasi_harga'     => $request->estimasi_harga,
            'nama_vendor'        => $request->nama_vendor,
            'status'             => $request->status ?? 'Open',
        ]);

        return redirect()
            ->route('monitoring.detail', ['trans_id' => $request->trans_id])
            ->with('success', 'Data baru berhasil ditambahkan.');
    }

    /**
     * Update baris monitoring dari modal edit.
     */
    public function update(Request $request, MaterialMonitoring $monitoring)
    {
        $request->validate([
            'qty'                => 'nullable|integer',
            'estimasi_harga'     => 'nullable|numeric',
            'pengadaan'          => 'nullable|in:Jasa,Barang-Jasa,Via Peng.Barang,Via Capex',
            'model'              => 'nullable|in:Tender,TL',
            'status'             => 'nullable|in:Open,Closed',
            'tanggal'            => 'nullable|date',
            'tanggal_reservasi'  => 'nullable|date',
            'tanggal_pr'         => 'nullable|date',
            'tanggal_po'         => 'nullable|date',
            'estimated_delivery' => 'nullable|date',
        ]);

        $monitoring->update([
            'tanggal'            => $request->tanggal,
            'no_notifikasi'      => $request->no_notifikasi,
            'qty'                => $request->qty,
            'uom'                => $request->uom,
            'pengadaan'          => $request->pengadaan,
            'model'              => $request->model,
            'nomor_reservasi'    => $request->nomor_reservasi,
            'tanggal_reservasi'  => $request->tanggal_reservasi,
            'nomor_pr'           => $request->nomor_pr,
            'tanggal_pr'         => $request->tanggal_pr,
            'nomor_po'           => $request->nomor_po,
            'tanggal_po'         => $request->tanggal_po,
            'estimated_delivery' => $request->estimated_delivery,
            'estimasi_harga'     => $request->estimasi_harga,
            'nama_vendor'        => $request->nama_vendor,
            'status'             => $request->status ?? 'Open',
        ]);

        return redirect()
            ->route('monitoring.detail', ['trans_id' => $monitoring->trans_id])
            ->with('success', 'Data monitoring berhasil diperbarui.');
    }
}