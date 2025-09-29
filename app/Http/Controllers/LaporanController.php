<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\PengeluaranProyek;
use App\Models\PemasukanProyek;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{






    public function keuanganlama(Request $request)
    {
        $bulan    = $request->input('bulan', Carbon::now()->month);
        $tahun    = $request->input('tahun', Carbon::now()->year);
        $jenis    = $request->input('jenis', 'semua'); // pemasukan / pengeluaran / semua
        $grouping = $request->input('grouping', 'minggu'); // default per minggu

        // Buat range tanggal sesuai bulan & tahun
        $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $endDate   = Carbon::create($tahun, $bulan, 1)->endOfMonth();

        // Ambil data
        $pemasukan = PemasukanProyek::whereBetween('tanggal_pemasukan', [$startDate, $endDate])
            ->get()
            ->map(function ($item) {
                return (object)[
                    'tanggal' => $item->tanggal_pemasukan,
                    'tipe'    => 'pemasukan',
                    'jumlah'  => $item->jumlah,
                ];
            });

        $pengeluaran = PengeluaranProyek::whereBetween('tanggal_pengeluaran', [$startDate, $endDate])
            ->get()
            ->map(function ($item) {
                return (object)[
                    'tanggal' => $item->tanggal_pengeluaran,
                    'tipe'    => 'pengeluaran',
                    'jumlah'  => $item->jumlah,
                ];
            });

        // Gabung data
        $transaksi = $pemasukan->concat($pengeluaran);

        // Filter jenis
        if ($jenis !== 'semua') {
            $transaksi = $transaksi->where('tipe', $jenis);
        }

        // Grouping
        if ($grouping === 'bulan') {
            $grouped = $transaksi->groupBy(function ($item) {
                return Carbon::parse($item->tanggal)->format('m'); // bulan angka
            });
        } else {
            $grouped = $transaksi->groupBy(function ($item) {
                return Carbon::parse($item->tanggal)->format('W'); // minggu ke-
            });
        }

        return view('laporan.keuangan', compact(
            'bulan',
            'tahun',
            'jenis',
            'grouping',
            'grouped'
        ));
    }




    public function index2(Request $request)
    {
        $proyekList = Proyek::all(); // untuk dropdown
        $laporan = null;
        $detailTransaksi = collect();

        if ($request->id_proyek) {
            $proyek = Proyek::find($request->id_proyek);

            if ($proyek) {
                // Ambil total addendum dari tb_addendum_proyek
                $totalAddendum = \DB::table('tb_addendum_proyek')
                    ->where('id_proyek', $proyek->id_proyek)
                    ->selectRaw('COALESCE(SUM(nilai_proyek_addendum), 0) as nilai_addendum,
                             COALESCE(SUM(estimasi_hpp_addendum), 0) as estimasi_hpp_addendum')
                    ->first();

                $nilaiProyek = $proyek->nilai_proyek;
                $adendum = $totalAddendum->nilai_addendum; // total nilai addendum

                // Estimasi HPP proyek + addendum
                $estimasiPersen = $proyek->estimasi_hpp ?? 0;
                $estimasiHpp = ($nilaiProyek * $estimasiPersen / 100)
                    + ($totalAddendum->estimasi_hpp_addendum ?? 0);

                // Total nilai proyek termasuk addendum
                $totalNilaiProyek = $nilaiProyek + $adendum;

                // Estimasi profit = total nilai proyek - estimasi HPP
                $estimasiProfit = $totalNilaiProyek - $estimasiHpp;

                // total pemasukan dari client
                $totalPembayaranClient = \DB::table('tb_pemasukan_proyek')
                    ->where('id_proyek', $proyek->id_proyek)
                    ->sum('jumlah');

                // total pengeluaran aktual
                $totalPengeluaran = \DB::table('tb_pengeluaran_proyek')
                    ->where('id_proyek', $proyek->id_proyek)
                    ->where('status', 'sudah dibayar')

                    ->sum('jumlah');

                // real profit = total nilai proyek - pengeluaran aktual
                $realProfit = $totalNilaiProyek - $totalPengeluaran;

                // persentase margin
                $marginHpp = $estimasiHpp > 0 ? round(($estimasiProfit / $estimasiHpp) * 100, 2) : 0;
                $marginNilai = $totalNilaiProyek > 0 ? round(($estimasiProfit / $totalNilaiProyek) * 100, 2) : 0;

                $detailTransaksi = \DB::table('tb_pemasukan_proyek')
                    ->where('id_proyek', $proyek->id_proyek)
                    ->select(
                        'tanggal_pemasukan as tanggal',
                        'jumlah',
                        'keterangan',
                        \DB::raw("'pemasukan' as tipe")
                    )
                    ->unionAll(
                        \DB::table('tb_pengeluaran_proyek')
                            ->where('id_proyek', $proyek->id_proyek)
                            ->where('status', 'Sudah dibayar')
                            ->select(
                                'tanggal_pengeluaran as tanggal',
                                'jumlah',
                                'keterangan',
                                \DB::raw("'pengeluaran' as tipe")
                            )
                    )
                    ->orderBy('tanggal')
                    ->get();

                $laporan = [
                    'nama_proyek' => $proyek->nama_proyek,
                    'nilai_proyek' => $nilaiProyek,
                    'adendum' => $adendum,
                    'total_nilai_proyek' => $totalNilaiProyek,
                    'estimasi_hpp' => $estimasiHpp,
                    'estimasi_profit' => $estimasiProfit,
                    'margin_hpp' => $marginHpp,
                    'margin_nilai' => $marginNilai,
                    'real_hpp' => $totalPengeluaran,
                    'real_profit' => $realProfit,
                    'total_pembayaran_client' => $totalPembayaranClient,
                    'sisa_kewajiban_client' => $totalNilaiProyek - $totalPembayaranClient,
                    'sisa_kas' => $totalPembayaranClient - $totalPengeluaran,
                ];
            }
        }

        return view('laporan.index', compact('proyekList', 'laporan', 'detailTransaksi'));
    }


    public function index()
    {
        return view('laporan.index');
    }



    public function getDataLaporansemua(Request $request)
    {
        try {
            $start  = $request->get('start', 0);
            $length = $request->get('length', 10);
            $search = $request->get('search')['value'] ?? '';

            // mapping index kolom DataTables -> kolom di database
            $columns = [
                0 => 'id_proyek',
                1 => 'nama_proyek',
                2 => 'nilai_proyek',
                3 => 'estimasi_hpp',
                4 => 'target_hpp_total',
                5 => 'target_hpp_termin',
                6 => 'real_profit',
                7 => 'tanggal_deadline', 
            ];

            $orderColumnIndex = $request->input('order.0.column');
            $orderColumn = $columns[$orderColumnIndex] ?? 'id_proyek';
            $orderDir = $request->input('order.0.dir') ?? 'asc';

            $query = Proyek::query();

            // Search global
            if (!empty($search)) {
                $query->where('nama_proyek', 'like', "%{$search}%");
            }

            $totalRecords    = Proyek::count();
            $filteredRecords = $query->count();

            // apply sorting
            $query->orderBy($orderColumn, $orderDir);

            // apply limit & offset
            $proyekList = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($proyekList as $index => $proyek) {
                $totalAddendum = \DB::table('tb_addendum_proyek')
                    ->where('id_proyek', $proyek->id_proyek)
                    ->selectRaw('COALESCE(SUM(nilai_proyek_addendum), 0) as nilai_addendum,
                             COALESCE(SUM(estimasi_hpp_addendum), 0) as estimasi_hpp_addendum')
                    ->first();

                $nilaiProyek = $proyek->nilai_proyek;
                $adendum     = $totalAddendum->nilai_addendum;
                $estimasiPersen = $proyek->estimasi_hpp ?? 0;

                $estimasiHpp = ($nilaiProyek * $estimasiPersen / 100) + ($totalAddendum->estimasi_hpp_addendum ?? 0);
                $totalNilaiProyek = $nilaiProyek + $adendum;
                $estimasiProfit   = $totalNilaiProyek - $estimasiHpp;

                $totalPengeluaran = \DB::table('tb_pengeluaran_proyek')
                    ->where('id_proyek', $proyek->id_proyek)
                    ->where('status', 'sudah dibayar')
                    ->sum('jumlah');

                $realProfit = $totalNilaiProyek - $totalPengeluaran;

                $targetHppTotal = ($totalNilaiProyek * $estimasiPersen / 100) + ($totalAddendum->estimasi_hpp_addendum ?? 0);


                $totalTerminDibayar = \DB::table('tb_pemasukan_proyek as p')
                    ->join('tb_termin_proyek as t', 'p.id_termin', '=', 't.id_termin')
                    ->where('t.id_proyek', $proyek->id_proyek)
                    ->whereIn('t.status_pembayaran', ['sebagian', 'lunas'])
                    ->sum('p.jumlah');


                $targetHppTermin = $totalTerminDibayar * ($estimasiPersen / 100);

                $marginNilai = $totalNilaiProyek > 0 ? round(($realProfit / $totalNilaiProyek) * 100, 2) : 0;

                $data[] = [
                    'DT_RowIndex'        => $start + $index + 1,
                    'nama_proyek'        => $proyek->nama_proyek,
                    'total_nilai_proyek' => number_format($totalNilaiProyek, 0, ',', '.'),
                    'target_hpp_total'   => number_format($targetHppTotal, 0, ',', '.'),
                    'target_hpp_termin'  => number_format($targetHppTermin, 0, ',', '.'), 
                    'real_hpp'           => number_format($totalPengeluaran, 0, ',', '.'),
                    'estimasi_profit'    => number_format($estimasiProfit, 0, ',', '.'),
                    'real_profit'        => number_format($realProfit, 0, ',', '.'),
                    'margin_nilai'       => $marginNilai . '%',
                    'tanggal_deadline'   => \Carbon\Carbon::parse($proyek->tanggal_deadline)->format('d/m/Y'),
                    'aksi'               => '<button onclick="toggleDetail(' . $proyek->id_proyek . ')" class="px-2 py-1 bg-green-600 text-white rounded">Detail</button>',
                ];
            }

            return response()->json([
                'draw'            => intval($request->get('draw')),
                'recordsTotal'    => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data'            => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getDataLaporan(Request $request)
    {
        try {
            $start  = $request->get('start', 0);
            $length = $request->get('length', 10);
            $search = $request->get('search')['value'] ?? '';

            // mapping index kolom DataTables -> kolom di database
            $columns = [
                0 => 'id_proyek',
                1 => 'nama_proyek',
                2 => 'nilai_proyek',
                3 => 'estimasi_hpp',
                4 => 'target_hpp_total',
                5 => 'target_hpp_termin',
                6 => 'real_profit',
                7 => 'tanggal_deadline',
            ];

            $orderColumnIndex = $request->input('order.0.column');
            $orderColumn = $columns[$orderColumnIndex] ?? 'id_proyek';
            $orderDir = $request->input('order.0.dir') ?? 'asc';

            $query = Proyek::whereIn('status_proyek', ['process', 'progress']);

            // Search global
            if (!empty($search)) {
                $query->where('nama_proyek', 'like', "%{$search}%");
            }
            

            $totalRecords    = Proyek::count();
            $filteredRecords = $query->count();

            // apply sorting
            $query->orderBy($orderColumn, $orderDir);

            // apply limit & offset
            $proyekList = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($proyekList as $index => $proyek) {
                $totalAddendum = \DB::table('tb_addendum_proyek')
                    ->where('id_proyek', $proyek->id_proyek)
                    ->selectRaw('COALESCE(SUM(nilai_proyek_addendum), 0) as nilai_addendum,
                             COALESCE(SUM(estimasi_hpp_addendum), 0) as estimasi_hpp_addendum')
                    ->first();

                $nilaiProyek = $proyek->nilai_proyek;
                $adendum     = $totalAddendum->nilai_addendum;
                $estimasiPersen = $proyek->estimasi_hpp ?? 0;

                $estimasiHpp = ($nilaiProyek * $estimasiPersen / 100) + ($totalAddendum->estimasi_hpp_addendum ?? 0);
                $totalNilaiProyek = $nilaiProyek + $adendum;
                $estimasiProfit   = $totalNilaiProyek - $estimasiHpp;

                $totalPengeluaran = \DB::table('tb_pengeluaran_proyek')
                    ->where('id_proyek', $proyek->id_proyek)
                    ->where('status', 'sudah dibayar')
                    ->sum('jumlah');

                $realProfit = $totalNilaiProyek - $totalPengeluaran;

                $targetHppTotal = ($totalNilaiProyek * $estimasiPersen / 100) + ($totalAddendum->estimasi_hpp_addendum ?? 0);


                $totalTerminDibayar = \DB::table('tb_pemasukan_proyek as p')
                    ->join('tb_termin_proyek as t', 'p.id_termin', '=', 't.id_termin')
                    ->where('t.id_proyek', $proyek->id_proyek)
                    ->whereIn('t.status_pembayaran', ['sebagian', 'lunas'])
                    ->sum('p.jumlah');


                $targetHppTermin = $totalTerminDibayar * ($estimasiPersen / 100);

                $marginNilai = $totalNilaiProyek > 0 ? round(($realProfit / $totalNilaiProyek) * 100, 2) : 0;

                $data[] = [
                    'DT_RowIndex'        => $start + $index + 1,
                    'nama_proyek'        => $proyek->nama_proyek,
                    'total_nilai_proyek' => number_format($totalNilaiProyek, 0, ',', '.'),
                    'target_hpp_total'   => number_format($targetHppTotal, 0, ',', '.'),
                    'target_hpp_termin'  => number_format($targetHppTermin, 0, ',', '.'),
                    'real_hpp'           => number_format($totalPengeluaran, 0, ',', '.'),
                    'estimasi_profit'    => number_format($estimasiProfit, 0, ',', '.'),
                    'real_profit'        => number_format($realProfit, 0, ',', '.'),
                    'margin_nilai'       => $marginNilai . '%',
                    'tanggal_deadline'   => \Carbon\Carbon::parse($proyek->tanggal_deadline)->format('d/m/Y'),
                    'aksi'               => '<button onclick="toggleDetail(' . $proyek->id_proyek . ')" class="px-2 py-1 bg-green-600 text-white rounded">Detail</button>',
                ];
            }

            return response()->json([
                'draw'            => intval($request->get('draw')),
                'recordsTotal'    => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data'            => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getTransaksi($id)
    {
        try {
            $pemasukan = \DB::table('tb_pemasukan_proyek')
                ->where('id_proyek', $id)
                ->select('tanggal_pemasukan as tanggal', 'keterangan', 'jumlah')
                ->get()
                ->map(function ($row) {
                    return [
                        'tanggal' => $row->tanggal,
                        'keterangan' => $row->keterangan,
                        'jumlah' => (int) $row->jumlah,
                        'tipe' => 'pemasukan'
                    ];
                });

            $pengeluaran = \DB::table('tb_pengeluaran_proyek')
                ->where('id_proyek', $id)
                ->where('status', 'sudah dibayar')
                ->select('tanggal_pengeluaran as tanggal', 'keterangan', 'jumlah')
                ->get()
                ->map(function ($row) {
                    return [
                        'tanggal' => $row->tanggal,
                        'keterangan' => $row->keterangan,
                        'jumlah' => (int) $row->jumlah,
                        'tipe' => 'pengeluaran'
                    ];
                });


            $data = $pemasukan->merge($pengeluaran)->sortBy('tanggal')->values();

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function keuangan(Request $request)
    {
        // Ambil input dari user
        $tgl_mulai = $request->input('tgl_mulai') ? Carbon::parse($request->input('tgl_mulai'))->startOfDay() : now()->startOfMonth();
        $tgl_akhir = $request->input('tgl_akhir') ? Carbon::parse($request->input('tgl_akhir'))->endOfDay() : now()->endOfMonth();
        $periode = $request->input('periode', 'minggu'); // minggu / bulan
        $jenis = $request->input('jenis', 'semua');

        // Ambil data transaksi
        $pemasukan = PemasukanProyek::whereBetween('tanggal_pemasukan', [$tgl_mulai, $tgl_akhir])
            ->get()
            ->map(function ($item) {
                return (object)[
                    'tanggal' => $item->tanggal_pemasukan,
                    'jumlah' => $item->jumlah,
                    'deskripsi' => $item->keterangan,
                    'nama_proyek' => $item->nama_proyek ?? ($item->proyek->nama_proyek ?? '-'),
                    'tipe' => 'pemasukan',
                ];
            });

        $pengeluaran = PengeluaranProyek::whereBetween('tanggal_pengeluaran', [$tgl_mulai, $tgl_akhir])
            ->where('status', 'Sudah dibayar')
            ->get()
            ->map(function ($item) {
                return (object)[
                    'tanggal' => $item->tanggal_pengeluaran,
                    'jumlah' => $item->jumlah,
                    'deskripsi' => $item->keterangan,
                    'nama_proyek' => $item->nama_proyek ?? ($item->proyek->nama_proyek ?? '-'),
                    'tipe' => 'pengeluaran',
                ];
            });

        $transaksi = $pemasukan->merge($pengeluaran);

        // Filter jenis transaksi
        if ($jenis !== 'semua') {
            $transaksi = $transaksi->where('tipe', $jenis);
        }

        // Grouping berdasarkan periode
        $grouped = $transaksi->groupBy(function ($item) use ($periode) {
            $itemDate = Carbon::parse($item->tanggal);

            if ($periode === 'bulan') {
                // format: 2025-08
                return $itemDate->format('Y-m');
            } elseif ($periode === 'minggu') {
                // format: 2025-08-W2
                return $itemDate->format('Y-m') . '-W' . $itemDate->weekOfMonth;
            } else {
                // default: per hari
                return $itemDate->format('Y-m-d');
            }
        });

        $grouped = $grouped->sortKeys();

        return view('laporan.keuangan', compact(
            'tgl_mulai',
            'tgl_akhir',
            'periode',
            'jenis',
            'grouped'
        ));
    }



    public function keuangan2(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $periode = $request->input('periode', 'bulan'); // bulan/minggu/hari
        $jenis = $request->input('jenis', 'semua');

        // Logic yang lebih masuk akal:
        if ($periode === 'bulan') {
            // YEARLY VIEW: Lihat semua bulan dalam 1 tahun
            $startDate = Carbon::createFromDate($tahun, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($tahun, 12, 31)->endOfYear();
            $bulan = null; // tidak ada filter bulan spesifik

        } elseif ($periode === 'minggu') {
            // MONTHLY VIEW: Lihat minggu-minggu dalam 1 bulan
            $bulan = $request->input('bulan', date('n'));
            $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
        } else { // hari
            // MONTHLY VIEW: Lihat hari-hari dalam 1 bulan  
            $bulan = $request->input('bulan', date('n'));
            $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
        }

        // Ambil data transaksi
        $pemasukan = PemasukanProyek::whereBetween('tanggal_pemasukan', [$startDate, $endDate])
            ->get()
            ->map(function ($item) {
                return (object)[
                    'tanggal' => $item->tanggal_pemasukan,
                    'jumlah' => $item->jumlah,
                    'deskripsi' => $item->keterangan,
                    'nama_proyek' => $item->nama_proyek ?? ($item->proyek->nama_proyek ?? '-'),

                    'tipe' => 'pemasukan',
                ];
            });

        $pengeluaran = PengeluaranProyek::whereBetween('tanggal_pengeluaran', [$startDate, $endDate])
            ->get()
            ->map(function ($item) {
                return (object)[
                    'tanggal' => $item->tanggal_pengeluaran,
                    'jumlah' => $item->jumlah,
                    'deskripsi' => $item->keterangan,
                    'nama_proyek' => $item->nama_proyek ?? ($item->proyek->nama_proyek ?? '-'),
                    'tipe' => 'pengeluaran',
                ];
            });

        $transaksi = $pemasukan->merge($pengeluaran);

        // Filter jenis
        if ($jenis !== 'semua') {
            $transaksi = $transaksi->where('tipe', $jenis);
        }

        // Grouping berdasarkan periode
        $grouped = $transaksi->groupBy(function ($item) use ($periode) {
            $itemDate = Carbon::parse($item->tanggal);

            if ($periode === 'bulan') {
                // format: 2025-08
                return $itemDate->format('Y-m');
            } elseif ($periode === 'minggu') {
                // format: 2025-08-W2
                return $itemDate->format('Y-m') . '-W' . $itemDate->weekOfMonth;
            } else {
                // format: 2025-08-17
                return $itemDate->format('Y-m-d');
            }
        });


        $grouped = $grouped->sortKeys();

        return view('laporan.keuangan', compact(
            'bulan',
            'tahun',
            'periode',
            'jenis',
            'grouped'
        ));
    }
}
