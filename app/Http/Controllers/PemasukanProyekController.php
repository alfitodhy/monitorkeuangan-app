<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemasukanProyek;
use App\Models\Proyek;
use App\Models\TerminProyek;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class PemasukanProyekController extends Controller
{


    public function index()
    {
        $pemasukan = DB::table('tb_proyek as pr')
            ->leftJoin('tb_pemasukan_proyek as p', 'p.id_proyek', '=', 'pr.id_proyek')
            ->where('pr.is_active', 'Y')
            ->select(
                'pr.id_proyek',
                'pr.nama_proyek',
                'pr.termin as total_termin',
                'pr.nilai_proyek',
                DB::raw('COALESCE(SUM(p.jumlah), 0) as total_dibayar'),
                DB::raw('(pr.nilai_proyek - COALESCE(SUM(p.jumlah), 0)) as sisa_bayar')
            )
            ->groupBy('pr.id_proyek', 'pr.nama_proyek', 'pr.termin', 'pr.nilai_proyek')
            ->get();

        return view('pemasukan.index', compact('pemasukan'));
    }



    public function create()
    {
        $proyek = Proyek::all();
        $proyek = Proyek::where('is_active', 'Y')->get();
    $proyek = Proyek::where('status_proyek', '!=', 'completed')->get();

        // $termin = TerminProyek::all();

        $metodePembayaran = PemasukanProyek::select('metode_pembayaran')
            ->distinct()
            ->pluck('metode_pembayaran');

        return view('pemasukan.create', compact('proyek', 'metodePembayaran'));


    }


    public function getTerminDetail2($id)
    {
        $proyek = Proyek::findOrFail($id);
        $jumlahTermin = $proyek->termin;
        $nilaiProyek = $proyek->nilai_proyek;

        $jumlahPemasukan = PemasukanProyek::where('id_proyek', $id)->count();
        $terminSekarang = $jumlahPemasukan + 1;

        $maksimalNominal = $nilaiProyek / $jumlahTermin;

        return response()->json([
            'termin_sekarang' => $terminSekarang,
            'maksimal_nominal' => $maksimalNominal,
            'nilai_proyek' => $nilaiProyek,
            'jumlah_termin' => $jumlahTermin,
        ]);
    }

    public function getTerminDetail($id)
{
    $proyek = Proyek::findOrFail($id);

    // Hitung nilai per termin
    $nilaiPerTermin = $proyek->nilai_proyek / $proyek->termin;

    // Ambil semua total pembayaran per termin dari database
    $totalPembayaranPerTermin = PemasukanProyek::where('id_proyek', $id)
        ->select('termin_ke', DB::raw('SUM(jumlah) as total_bayar'))
        ->groupBy('termin_ke')
        ->pluck('total_bayar', 'termin_ke')
        ->all();

    $terminSekarang = 1;
    $maksimalNominal = 0;
    
    // Loop dari termin pertama hingga terakhir untuk menemukan yang belum lunas
    for ($i = 1; $i <= $proyek->termin; $i++) {
        $totalBayarDiTerminIni = $totalPembayaranPerTermin[$i] ?? 0;
        
        // Jika total pembayaran untuk termin ini kurang dari nilai termin, 
        // maka ini adalah termin yang harus dibayar
        if ($totalBayarDiTerminIni < $nilaiPerTermin) {
            $terminSekarang = $i;
            $maksimalNominal = max(0, $nilaiPerTermin - $totalBayarDiTerminIni);
            break; // Hentikan loop karena termin saat ini sudah ditemukan
        }
    }
    
    // Jika loop selesai, berarti semua termin sudah lunas
    // Atur termin sekarang ke termin terakhir + 1 untuk menandakan proyek selesai
    if ($terminSekarang > $proyek->termin) {
        $terminSekarang = $proyek->termin + 1;
        $maksimalNominal = 0;
    }

    return response()->json([
        'termin_sekarang' => $terminSekarang,
        'maksimal_nominal' => $maksimalNominal,
    ]);
}   

    public function getTerminStatus($id, $termin)
    {
        $proyek = Proyek::findOrFail($id);
        $maksPerTermin = $proyek->nilai_proyek / $proyek->termin;

        $totalBayar = PemasukanProyek::where('id_proyek', $id)
            ->where('termin_ke', $termin)
            ->sum('jumlah');

        return response()->json([
            'lunas' => $totalBayar >= $maksPerTermin
        ]);
    }


    public function store(Request $request)
    {
        // Bersihkan input jumlah dari format ribuan dulu sebelum validasi
        $request->merge([
            'jumlah' => str_replace(['.', ','], '', $request->jumlah),
        ]);

        // Cari proyek
        $proyek = Proyek::findOrFail($request->id_proyek);

        $totalTermin = $proyek->termin;
        $nilaiPerTermin = $proyek->nilai_proyek / $totalTermin;

        // Cari termin yang belum lunas
        $terminBerjalan = PemasukanProyek::where('id_proyek', $proyek->id_proyek)
            ->select('termin_ke')
            ->groupBy('termin_ke')
            ->havingRaw('SUM(jumlah) < ?', [$nilaiPerTermin])
            ->orderBy('termin_ke', 'asc')
            ->first();

        if ($terminBerjalan) {
            // Masih ada termin berjalan
            $terminSekarang = $terminBerjalan->termin_ke;
            $sisaBayar = $nilaiPerTermin - PemasukanProyek::where('id_proyek', $proyek->id_proyek)
                ->where('termin_ke', $terminSekarang)
                ->sum('jumlah');
        } else {
            // Semua termin sebelumnya sudah lunas → termin baru
            $terminTerakhir = PemasukanProyek::where('id_proyek', $proyek->id_proyek)->max('termin_ke') ?? 0;
            $terminSekarang = $terminTerakhir + 1;
            $sisaBayar = $nilaiPerTermin;
        }

        // Validasi input (jumlah sudah dalam angka bersih)
        $validator = Validator::make($request->all(), [
            'id_proyek' => 'required|exists:tb_proyek,id_proyek',
            'tanggal_pemasukan' => 'required|date',
            'jumlah' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($sisaBayar) {
                    if ($value > $sisaBayar) {
                        $fail("Jumlah melebihi sisa pembayaran termin ini: " . number_format($sisaBayar, 0, ',', '.'));
                    }
                }
            ],
            'metode_pembayaran' => 'nullable|string|max:255',
            'attachment_file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Proses simpan file (jika ada)
      $path = null;

if ($request->hasFile('attachment_file')) {
    $file = $request->file('attachment_file');

    // Nama file unik
    $fileName = time() . '-' . Str::slug($proyek->nama_proyek) . '.' . $file->extension();

    // Path folder tujuan: uploads/pemasukan/pr_(id_proyek)
    $folderPath = 'uploads/pemasukan/pr_' . $proyek->id_proyek;

    // Simpan file di storage/app/public/uploads/pemasukan/pr_(id_proyek)
    $path = $file->storeAs($folderPath, $fileName, 'public');
}

        // Simpan data pemasukan
        PemasukanProyek::create([
            'id_proyek' => $proyek->id_proyek,
            'termin_ke' => $terminSekarang,
            'nama_klien' => $proyek->nama_klien,
            'tanggal_pemasukan' => $request->tanggal_pemasukan,
            'jumlah' => $request->jumlah, // sudah angka bersih
            'metode_pembayaran' => $request->metode_pembayaran,
            'attachment_file' => $path,
            'keterangan' => $request->keterangan
        ]);

        $jumlahTerminLunas = PemasukanProyek::where('id_proyek', $proyek->id_proyek)
            ->select('termin_ke', DB::raw('SUM(jumlah) as total_termin'))
            ->groupBy('termin_ke')
            ->having('total_termin', '>=', $nilaiPerTermin)
            ->count();

        if ($jumlahTerminLunas == 0) {
            $proyek->status_proyek = 'process';
        } elseif ($jumlahTerminLunas < $totalTermin) {
            $proyek->status_proyek = 'progress';
        } else {
            $proyek->status_proyek = 'completed';
        }

        $proyek->save();

        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil ditambahkan');
    }



    public function show($id_proyek)
    {
        $proyek = DB::table('tb_proyek as pr')
            ->leftJoin('tb_pemasukan_proyek as p', 'p.id_proyek', '=', 'pr.id_proyek')
            ->select(
                'pr.id_proyek',
                'pr.nama_proyek',
                'pr.termin as total_termin',
                'pr.nilai_proyek',
                DB::raw('COALESCE(SUM(p.jumlah), 0) as total_dibayar'),
                DB::raw('(pr.nilai_proyek - COALESCE(SUM(p.jumlah), 0)) as sisa_bayar')
            )
            ->where('pr.id_proyek', $id_proyek)
            ->groupBy('pr.id_proyek', 'pr.nama_proyek', 'pr.termin', 'pr.nilai_proyek')
            ->first();

        if (!$proyek) {
            abort(404, 'Data proyek tidak ditemukan.');
        }

        $nilai_per_termin = $proyek->nilai_proyek / $proyek->total_termin;

        $pemasukan_per_termin = DB::table('tb_pemasukan_proyek')
            ->select(DB::raw('CAST(termin_ke AS UNSIGNED) as termin_ke'), DB::raw('SUM(jumlah) as total_bayar'))
            ->where('id_proyek', $id_proyek)
            ->groupBy(DB::raw('CAST(termin_ke AS UNSIGNED)'))
            ->pluck('total_bayar', 'termin_ke');

  $metodePembayaran = PemasukanProyek::select('metode_pembayaran')
            ->distinct()
            ->pluck('metode_pembayaran');

        // Ini bagian penting
        $bukti_per_termin = PemasukanProyek::where('id_proyek', $id_proyek)
            ->whereNotNull('attachment_file')
            ->get()
            ->groupBy('termin_ke');

            

        return view('pemasukan.detail', compact(
            'proyek',
            'pemasukan_per_termin',
            'nilai_per_termin',
            'bukti_per_termin',
            'metodePembayaran'
            
        ));
    }



    public function lihatBukti($id, $termin)
    {
        // Ambil data proyek (optional cek exist)
        $proyek = Proyek::find($id);
        if (!$proyek) {
            abort(404, 'Proyek tidak ditemukan');
        }

        // Ambil semua pemasukan untuk termin tersebut yang punya file attachment
        // Ganti 'attachment_file' dengan nama kolom yang kamu pakai
        $buktipemasukan = PemasukanProyek::where('id_proyek', $id)
            ->where('termin_ke', $termin)
            ->whereNotNull('attachment_file')
            ->get();

        // Jika attachment disimpan di storage/app/public, gunakan Storage::url(...) di view
        return view('pemasukan.bukti', compact('proyek', 'termin', 'buktipemasukan'));
    }

    public function updateLunasi2(Request $request, $id_proyek, $termin_ke)
    {
        // Validasi input
        $request->validate([
            'id_pemasukan' => 'required|exists:tb_pemasukan_proyek,id_pemasukan',
            'jumlah_bayar' => 'required|numeric|min:1',
            // tambahkan validasi lain jika perlu
        ]);

        $pemasukan = PemasukanProyek::findOrFail($request->id_pemasukan);

        // Ambil nilai termin dari proyek untuk validasi batas pembayaran
        $proyek = Proyek::findOrFail($id_proyek);
        $nilaiPerTermin = $proyek->nilai_proyek / $proyek->total_termin;

        // Hitung sisa bayar termin (total bayar - yg mau diupdate)
        $totalBayarLain = PemasukanProyek::where('id_proyek', $id_proyek)
            ->where('termin_ke', $termin_ke)
            ->where('id_pemasukan', '<>', $pemasukan->id_pemasukan)
            ->sum('jumlah');

        $sisaBayar = $nilaiPerTermin - $totalBayarLain;

        if ($request->jumlah_bayar > $sisaBayar) {
            return back()->withErrors(['jumlah_bayar' => 'Jumlah melebihi sisa pembayaran termin ini: Rp ' . number_format($sisaBayar, 0, ',', '.')])->withInput();
        }

        // Update data pemasukan
        $pemasukan->jumlah = $request->jumlah_bayar;
        if ($request->filled('tanggal_pemasukan')) {
            $pemasukan->tanggal_pemasukan = $request->tanggal_pemasukan;
        }
        // Update field lain jika perlu
        $pemasukan->save();

        // Update status proyek seperti di store() kalau perlu
        $jumlahTerminLunas = PemasukanProyek::where('id_proyek', $id_proyek)
            ->select('termin_ke', DB::raw('SUM(jumlah) as total_termin'))
            ->groupBy('termin_ke')
            ->having('total_termin', '>=', $nilaiPerTermin)
            ->count();

        if ($jumlahTerminLunas == 0) {
            $proyek->status_proyek = 'process';
        } elseif ($jumlahTerminLunas < $proyek->termin) {
            $proyek->status_proyek = 'progress';
        } else {
            $proyek->status_proyek = 'completed';
        }
        $proyek->save();

        return redirect()->route('pemasukan.show', $id_proyek)->with('success', 'Pelunasan berhasil diperbarui.');
    }


    public function updateLunasi(Request $request, $id_proyek, $termin_ke)
{
    // Validasi input
    $request->validate([
        'jumlah_bayar'      => 'required|string',
        'metode_pembayaran' => 'required|string',
        'keterangan'        => 'nullable|string|max:255', // Tambahkan validasi untuk keterangan
        'attachment_file'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', 
    ]);

    // Bersihkan format Rupiah dari input jumlah_bayar
    $jumlahBayar = (int) preg_replace('/[^\d]/', '', $request->jumlah_bayar);
    
    $proyek = Proyek::findOrFail($id_proyek);

    if ($proyek->termin <= 0) {
        return back()->withErrors(['error' => 'Jumlah termin proyek harus lebih dari 0.']);
    }

    $nilaiPerTermin = $proyek->nilai_proyek / $proyek->termin;

    $totalBayarTerminSaatIni = PemasukanProyek::where('id_proyek', $id_proyek)
        ->where('termin_ke', $termin_ke)
        ->sum('jumlah');

    $sisaBayarReal = max(0, $nilaiPerTermin - $totalBayarTerminSaatIni);

    if ($jumlahBayar > $sisaBayarReal) {
        return back()->withErrors([
            'jumlah_bayar' => 'Jumlah melebihi sisa pembayaran termin ini: Rp ' . number_format($sisaBayarReal, 0, ',', '.')
        ])->withInput();
    }

    // Tangani unggah file jika ada
    $filePath = null;
    if ($request->hasFile('attachment_file')) {
        $file = $request->file('attachment_file');
        
        // Tentukan jalur penyimpanan dinamis di dalam public storage
        $path = 'uploads/pemasukan/pr_' . $id_proyek; // Mengganti 'pengeluaran' menjadi 'pemasukan'

        // Tentukan nama file yang unik
        $fileName = time() . '_' . $file->getClientOriginalName();
        
        // Simpan file ke direktori yang baru
        $file->storeAs('public/' . $path, $fileName);
        
        // Simpan jalur relatif ke database
        $filePath = $path . '/' . $fileName;
    }
    
    // Buat entri pemasukan baru dengan semua data
    PemasukanProyek::create([
        'id_proyek'         => $id_proyek,
        'termin_ke'         => $termin_ke,
        'jumlah'            => $jumlahBayar,
        'metode_pembayaran' => $request->metode_pembayaran,
        'nama_klien'        => $proyek->nama_klien, // Menyimpan nama klien dari data proyek
        'attachment_file'   => $filePath, // Menyimpan jalur file
        'tanggal_pemasukan' => now()->toDateString(),
                'keterangan'         => 'Pelunasan Termin ke-' . $termin_ke,
        'created_at'        => now()->setTimezone('Asia/Jakarta'),
    ]);

    // Update status proyek
    $jumlahTerminLunas = PemasukanProyek::where('id_proyek', $id_proyek)
        ->select('termin_ke', DB::raw('SUM(jumlah) as total_termin'))
        ->groupBy('termin_ke')
        ->having('total_termin', '>=', $nilaiPerTermin)
        ->count();

    if ($jumlahTerminLunas == $proyek->termin) {
        $proyek->status_proyek = 'completed';
    } else {
        $proyek->status_proyek = 'progress';
    }
    $proyek->save();

    return redirect()->route('pemasukan.show', $id_proyek)
        ->with('success', 'Pembayaran termin berhasil dicatat.');
}



}
