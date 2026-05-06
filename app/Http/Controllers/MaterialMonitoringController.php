<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialMonitoring;
use App\Models\MsoTransaction;
use App\Models\MsoFinding;
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
     *   [5] Est. Complete → MSO Finish  : estimated_delivery → mso_finish_date (dari MsoTransaction)
     *   [6] Total Lead  → End           : tanggal           → estimated_delivery
     *
     * Setiap interval hanya dihitung jika KEDUA tanggal tersedia.
     * Hasilnya dikelompokkan per jenis Pengadaan.
     * Filter: tanggal dari s/d tanggal (dari kolom tanggal), dan/atau jenis pengadaan.
     * ============================================================
     */
    public function resume(Request $request)
    {
        /* ── Query dasar ── */
        $query = MaterialMonitoring::query();

        // Filter range tanggal (menggantikan filter tahun & bulan)
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('pengadaan')) {
            $query->where('pengadaan', $request->pengadaan);
        }

        $all = $query->get();

        /* ── Ambil finish_date dari MsoTransaction berdasarkan trans_id = id_trans ── */
        $transIds = $all->pluck('trans_id')->unique()->filter()->values();

        // Map: id_trans => finish_date (ambil finish_date terbaru jika ada beberapa)
        $msoFinishMap = MsoTransaction::whereIn('id_trans', $transIds)
            ->whereNotNull('finish_date')
            ->orderBy('finish_date', 'desc')
            ->get()
            ->groupBy('id_trans')
            ->map(fn($group) => $group->first()->finish_date);

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
                'virtual' => false,
            ],
            'reservasi_to_pr' => [
                'label'  => 'Reservasi → PR',
                'from'   => 'tanggal_reservasi',
                'to'     => 'tanggal_pr',
                'short'  => 'RSV → PR',
                'color'  => '#7C3AED',
                'virtual' => false,
            ],
            'pr_to_po' => [
                'label'  => 'PR → PO',
                'from'   => 'tanggal_pr',
                'to'     => 'tanggal_po',
                'short'  => 'PR → PO',
                'color'  => '#D97706',
                'virtual' => false,
            ],
            'po_to_delivery' => [
                'label'  => 'PO → Est. Complete',
                'from'   => 'tanggal_po',
                'to'     => 'estimated_delivery',
                'short'  => 'PO → Est.',
                'color'  => '#16a34a',
                'virtual' => false,
            ],
            'delivery_to_mso_finish' => [
                'label'  => 'Est. Complete → MSO Finish',
                'from'   => 'estimated_delivery',
                'to'     => 'mso_finish_date',   // kolom virtual, diisi manual
                'short'  => 'Est. → MSO',
                'color'  => '#0891B2',
                'virtual' => true,               // ditangani khusus
            ],
            'total_lead' => [
                'label'  => 'Total Lead Time',
                'from'   => 'tanggal',
                'to'     => 'estimated_delivery',
                'short'  => 'Total',
                'color'  => '#DC2626',
                'virtual' => false,
            ],
        ];

        /* ── Helper: hitung interval hari antar dua kolom tanggal ── */
        $calcDays = function ($row, $from, $to): ?int {
            $dateFrom = $row->{$from};
            $dateTo   = $row->{$to};

            if (! $dateFrom || ! $dateTo) return null;

            $diff = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo), false);

            return $diff >= 0 ? (int) $diff : null;
        };

        /* ── Hitung per baris semua interval (termasuk virtual delivery_to_mso_finish) ── */
        $allWithIntervals = $all->map(function ($row) use ($stages, $calcDays, $msoFinishMap) {
            // Sisipkan mso_finish_date ke object row secara virtual
            $row->mso_finish_date = $msoFinishMap->get($row->trans_id);

            foreach ($stages as $key => $stage) {
                $row->{"dur_{$key}"} = $calcDays($row, $stage['from'], $stage['to']);
            }
            return $row;
        });

        /* ── Agregasi per jenis pengadaan ── */
        $grouped = $allWithIntervals->groupBy('pengadaan');

        $tableSummary  = [];
        $chartLabels   = [];
        $chartDatasets = [];

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
                    'count' => $values->count(),
                ];

                $chartDatasets[$key][] = $rowData[$key]['avg'];
            }

            $tableSummary[] = $rowData;
        }

        /* ── Trend bulanan (12 bulan terakhir) ── */
        $trend = $this->buildMonthlyTrend($allWithIntervals, $jenisUrut);

        /* ── Stat cards keseluruhan (tidak difilter pengadaan) ── */
        $overallQuery = MaterialMonitoring::query();
        if ($request->filled('tanggal_dari')) {
            $overallQuery->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $overallQuery->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        $overallAll = $overallQuery->get()->map(function ($row) use ($stages, $calcDays, $msoFinishMap) {
            $row->mso_finish_date = $msoFinishMap->get($row->trans_id);
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

        /* ── stagesJs: versi ringan untuk JS ── */
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
        ));
    }

    /**
     * Build data trend bulanan: 12 bulan terakhir
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

    /**
     * Export data dari MsoTransaction & MsoFinding ke tabel material_monitorings.
     */
    public function export($trans_id)
    {
        // 1. Cek apakah transaksi MSO dengan id_trans ini ada
        $transaction = MsoTransaction::where('id_trans', $trans_id)->first();
        if (!$transaction) {
            return redirect()->route('monitoring.index')
                ->with('error', 'Transaksi MSO tidak ditemukan.');
        }

        // 2. Cegah duplikasi: jika sudah ada data monitoring untuk trans_id ini,
        //    langsung redirect ke halaman detail.
        $exists = MaterialMonitoring::where('trans_id', $trans_id)->exists();
        if ($exists) {
            return redirect()->route('monitoring.detail', $trans_id)
                ->with('info', 'Data monitoring untuk transaksi ini sudah ada.');
        }

        // 3. Ambil semua finding dari MSO transaction ini
        $findings = MsoFinding::with(['transaction.nomenclature', 'component', 'materialMaster'])
            ->where('mso_transaction_id', $transaction->id)
            ->get();

        if ($findings->isEmpty()) {
            return redirect()->route('monitoring.index')
                ->with('error', 'Tidak ada finding untuk transaksi ini.');
        }

        // 4. Simpan setiap finding ke tabel material_monitorings
        foreach ($findings as $finding) {
            MaterialMonitoring::create([
                'trans_id'        => $trans_id,
                'nomenclature'    => $finding->transaction->nomenclature->name ?? null,
                'component'       => $finding->component->name ?? null,
                'abnormality'     => $finding->temuan,
                'action'          => $finding->action,
                'material_master' => $finding->materialMaster->material_code ?? null,
                // Kolom lain diisi default NULL (bisa diisi kemudian di halaman detail)
                'tanggal'         => now(),
                'status'          => 'Open',
            ]);
        }

        // 5. Redirect ke halaman detail monitoring
        return redirect()->route('monitoring.detail', $trans_id)
            ->with('success', 'Data monitoring berhasil di-export dari MSO.');
    }
}