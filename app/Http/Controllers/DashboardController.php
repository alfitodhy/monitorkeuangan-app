<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengeluaranProyek;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Rekap jumlah pengajuan status selesai (bulan ini)
        $rekapBulanIni = PengeluaranProyek::where('user_created', 'kepalaops')
            ->where('status', 'sudah dibayar')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Total pengajuan aktif (semua, user_created = kepalaops)
        $totalPengeluaranKepalaops = PengeluaranProyek::where('user_created', 'kepalaops')
            ->count();

        // Total sedang diproses (semua)
        $totalSedangDiproses = PengeluaranProyek::where('status', 'sedang diproses')
            ->count();

        // Total sudah dibayar (hari ini)
        $totalSudahDibayarHariIni = PengeluaranProyek::where('status', 'sudah dibayar')
            ->whereDate('created_at', Carbon::today())
            ->count();

        return view('dashboard', compact(
            'rekapBulanIni',
            'totalPengeluaranKepalaops',
            'totalSedangDiproses',
            'totalSudahDibayarHariIni'
        ));
    }
}
