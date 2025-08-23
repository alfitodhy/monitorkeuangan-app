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



    public function index(Request $request)
    {
        $proyekList = Proyek::all(); // untuk dropdown
        $laporan = null;


        if ($request->id_proyek) {
            $proyek = Proyek::find($request->id_proyek);


            if ($proyek) {
                // nilai proyek & adendum
                $nilaiProyek = $proyek->nilai_proyek;
                $adendum = $proyek->adendum ?? 0;


                // estimasi HPP (persen) -> konversi ke nominal
                $estimasiPersen = $proyek->estimasi_hpp ?? 0;
                $estimasiHpp = ($nilaiProyek + $adendum) * $estimasiPersen / 100;


                // estimasi profit (nominal)
                $estimasiProfit = ($nilaiProyek + $adendum) - $estimasiHpp;


                // total pemasukan dari client
                $totalPembayaranClient = \DB::table('tb_pemasukan_proyek')
                    ->where('id_proyek', $proyek->id_proyek)
                    ->sum('jumlah');


                // total pengeluaran aktual
                $totalPengeluaran = \DB::table('tb_pengeluaran_proyek')
                    ->where('id_proyek', $proyek->id_proyek)
                    ->sum('jumlah');


                // real profit = nilai proyek + adendum - pengeluaran aktual
                $realProfit = ($nilaiProyek + $adendum) - $totalPengeluaran;


                // persentase margin
                $marginHpp = $estimasiHpp > 0 ? round(($estimasiProfit / $estimasiHpp) * 100, 2) : 0;
                $marginNilai = ($nilaiProyek + $adendum) > 0 ? round(($estimasiProfit / ($nilaiProyek + $adendum)) * 100, 2) : 0;


                $laporan = [
                    'nama_proyek' => $proyek->nama_proyek,
                    'nilai_proyek' => $nilaiProyek,
                    'adendum' => $adendum,
                    'estimasi_hpp' => $estimasiHpp,
                    'estimasi_profit' => $estimasiProfit,
                    'margin_hpp' => $marginHpp,
                    'margin_nilai' => $marginNilai,
                    'real_hpp' => $totalPengeluaran,
                    'real_profit' => $realProfit,
                    'total_pembayaran_client' => $totalPembayaranClient,
                    'sisa_kewajiban_client' => ($nilaiProyek + $adendum) - $totalPembayaranClient,
                    'sisa_kas' => $totalPembayaranClient - $totalPengeluaran,
                ];
            }
        }


        return view('laporan.index', compact('proyekList', 'laporan'));
    }


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



    public function keuangan(Request $request)
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
