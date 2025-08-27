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
            ->where('pr.is_active', 'Y')
            ->select(
                'pr.id_proyek',
                'pr.nama_proyek',
                'pr.nilai_proyek',
                'pr.termin',
                // total tagihan dari tb_termin_proyek
                DB::raw('(SELECT COALESCE(SUM(t.jumlah), 0) 
                      FROM tb_termin_proyek t 
                      WHERE t.id_proyek = pr.id_proyek) as total_tagihan'),
                // total dibayar dari tb_pemasukan_proyek
                DB::raw('(SELECT COALESCE(SUM(p.jumlah), 0) 
                      FROM tb_pemasukan_proyek p 
                      WHERE p.id_proyek = pr.id_proyek) as total_dibayar')
            )
            ->get()
            ->map(function ($row) {
                $row->sisa_bayar = $row->nilai_proyek - $row->total_dibayar;
                return $row;
            });

        return view('pemasukan.index', compact('pemasukan'));
    }





    public function create()
    {
        $proyek = Proyek::where('is_active', 'Y')
            ->where('status_proyek', '!=', 'completed')
            ->get();

        $metodePembayaran = PemasukanProyek::select('metode_pembayaran')
            ->distinct()
            ->pluck('metode_pembayaran');

        return view('pemasukan.create', compact('proyek', 'metodePembayaran'));
    }




    public function getDetail($idProyek)
    {
        try {

            $proyek = Proyek::find($idProyek);
            if (!$proyek) {
                return response()->json(['error' => 'Proyek tidak ditemukan'], 404);
            }

            // 🔎 Cek apakah ada termin yang masih "sebagian"
            $terminSebagian = TerminProyek::where('id_proyek', $idProyek)
                ->where('status_pembayaran', 'sebagian')
                ->orderBy('termin_ke', 'asc')
                ->first();

            if ($terminSebagian) {
                return response()->json([
                    'id_termin'        => null,
                    'nama_klien'       => $proyek->nama_klien,
                    'termin_sekarang'  => $terminSebagian->termin_ke,
                    'maksimal_nominal' => 0,
                    'termin_lunas'     => false,
                    'harus_lunasi_termin_sebelumnya' => true,
                    'message' => "Harap lunasi dulu termin ke-{$terminSebagian->termin_ke} sebelum melanjutkan pembayaran termin berikutnya."
                ]);
            }

            // 🔎 Ambil termin pertama yang belum lunas (status belum dibayar)
            $termin = TerminProyek::where('id_proyek', $idProyek)
                ->where('status_pembayaran', 'belum dibayar')
                ->orderBy('termin_ke', 'asc')
                ->first();

            // ✅ Kalau semua termin sudah lunas
            if (!$termin) {
                return response()->json([
                    'id_termin'        => null,
                    'nama_klien'       => $proyek->nama_klien,
                    'termin_sekarang'  => null,
                    'maksimal_nominal' => 0,
                    'termin_lunas'     => true,
                    'harus_lunasi_termin_sebelumnya' => false,
                    'message' => 'Semua termin sudah lunas'
                ]);
            }

            // ✅ Jika aman → boleh input pemasukan untuk termin sekarang
            return response()->json([
                'id_termin'        => $termin->id_termin,
                'nama_klien'       => $proyek->nama_klien,
                'termin_sekarang'  => $termin->termin_ke,
                'maksimal_nominal' => (int) $termin->jumlah,
                'termin_lunas'     => false,
                'harus_lunasi_termin_sebelumnya' => false,
                'message' => "Silakan input pembayaran untuk termin ke-{$termin->termin_ke}"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan sistem',
                'details' => $e->getMessage()
            ], 500);
        }
    }




    public function getTerminsByProyek($id_proyek)
    {
        $termins = TerminProyek::where('id_proyek', $id_proyek)
            ->orderBy('termin_ke', 'asc')
            ->get();

        return response()->json($termins);
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
        $request->validate([
            'id_proyek' => 'required|exists:tb_proyek,id_proyek',
            'id_termin' => 'required|exists:tb_termin_proyek,id_termin',
            'jumlah' => 'required|numeric|min:1',

            'tanggal_pemasukan' => 'required|date',
            'attachment_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $terminSekarang = DB::table('tb_termin_proyek')
            ->where('id_termin', $request->id_termin)
            ->first();

        if (!$terminSekarang) {
            return back()->withErrors(['Termin tidak ditemukan.']);
        }

        $termin1 = DB::table('tb_termin_proyek')
            ->where('id_proyek', $terminSekarang->id_proyek)
            ->where('termin_ke', 1)
            ->first();

        if ($termin1) {
            $sudahDibayarTermin1 = DB::table('tb_pemasukan_proyek')
                ->where('id_termin', $termin1->id_termin)
                ->sum('jumlah');

            $sisaTermin1 = $termin1->jumlah - $sudahDibayarTermin1;

            if ($sisaTermin1 > 0 && $terminSekarang->termin_ke > 1) {
                return back()->withErrors([
                    "Termin ke-1 belum lunas. Harus dilunasi dulu sebelum mengisi termin ke-{$terminSekarang->termin_ke}."
                ])->withInput();
            }
        }

        $filePath = null;
        if ($request->hasFile('attachment_file')) {
            $file = $request->file('attachment_file');
            $folder = "uploads/pemasukan/pr_{$request->id_proyek}";
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($folder, $filename, 'public');

            // simpan JSON valid (array of string)
            $filePath = json_encode([$folder . '/' . $filename]);
        }


        $proyek = DB::table('tb_proyek')->where('id_proyek', $request->id_proyek)->first();
        $namaKlien = $proyek ? $proyek->nama_klien : null;

        DB::table('tb_pemasukan_proyek')->insert([
            'id_proyek' => $request->id_proyek,
            'id_termin' => $request->id_termin,
            'termin_ke' => $terminSekarang->termin_ke,
            'nama_klien' => $namaKlien,
            'jumlah' => $request->jumlah,
            'tanggal_pemasukan' => $request->tanggal_pemasukan,
            'metode_pembayaran' => $request->metode_pembayaran,
            'attachment_file' => $filePath, 
            'keterangan' => $request->keterangan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $totalDibayar = DB::table('tb_pemasukan_proyek')
            ->where('id_termin', $request->id_termin)
            ->sum('jumlah');

        $statusPembayaran = 'sebagian';
        $tanggalLunas = null;

        if ($totalDibayar >= $terminSekarang->jumlah) {
            $statusPembayaran = 'lunas';
            $tanggalLunas = now();
        }


        DB::table('tb_termin_proyek')
            ->where('id_termin', $request->id_termin)
            ->update([
                'status_pembayaran' => $statusPembayaran,
                'tanggal_lunas' => $tanggalLunas,
                'updated_at' => now(),
            ]);

        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil ditambahkan!');
    }




    public function show($id_proyek)
    {
        // Ambil proyek
        $proyek = DB::table('tb_proyek as pr')
            ->select(
                'pr.id_proyek',
                'pr.nama_proyek',
                'pr.termin as total_termin',
                'pr.nilai_proyek'
            )
            ->where('pr.id_proyek', $id_proyek)
            ->first();

        if (!$proyek) {
            abort(404, 'Data proyek tidak ditemukan.');
        }

        // Ambil data termin dari tabel tb_termin_proyek + progress bayar
        $termins = DB::table('tb_termin_proyek as t')
            ->leftJoin('tb_pemasukan_proyek as p', 't.id_termin', '=', 'p.id_termin')
            ->select(
                't.id_termin',
                't.termin_ke',
                't.jumlah as nilai_termin',
                't.status_pembayaran',
                DB::raw('COALESCE(SUM(p.jumlah), 0) as total_bayar'),
                DB::raw('(t.jumlah - COALESCE(SUM(p.jumlah), 0)) as sisa_bayar')
            )
            ->where('t.id_proyek', $id_proyek)
            ->groupBy('t.id_termin', 't.termin_ke', 't.jumlah', 't.status_pembayaran')
            ->orderBy('t.termin_ke')
            ->get();

        // ✅ Total sudah dibayar langsung dari tb_pemasukan_proyek
        $total_dibayar = DB::table('tb_pemasukan_proyek')
            ->where('id_proyek', $id_proyek)
            ->sum('jumlah');

        // ✅ Hitung sisa bayar dari nilai_proyek - total_dibayar
        $sisa_bayar = $proyek->nilai_proyek - $total_dibayar;

        // Ambil metode pembayaran unik
        $metodePembayaran = PemasukanProyek::select('metode_pembayaran')
            ->distinct()
            ->pluck('metode_pembayaran');

        // Bukti pembayaran per termin
        $bukti_per_termin = PemasukanProyek::where('id_proyek', $id_proyek)
            ->whereNotNull('attachment_file')
            ->get()
            ->groupBy('id_termin'); // lebih aman pakai id_termin

        return view('pemasukan.detail', compact(
            'proyek',
            'termins',
            'total_dibayar',
            'sisa_bayar',
            'bukti_per_termin',
            'metodePembayaran'
        ));
    }




    public function showlama($id_proyek)
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

        $nilaiProyek = $proyek->nilai_proyek;
        $totalTermin = $proyek->termin;

        // Hitung nilai per termin (dibulatkan ke bawah)
        $nilaiPerTermin = floor($nilaiProyek / $totalTermin);

        // Hitung sisa proyek (agar total pas)
        $sisaPembulatan = $nilaiProyek - ($nilaiPerTermin * $totalTermin);

        // Kalau termin terakhir → tambahkan sisa pembulatan
        if ($termin_ke == $totalTermin) {
            $nilaiPerTermin += $sisaPembulatan;
        }

        // Hitung total bayar yang sudah masuk di termin ini
        $totalBayarTerminSaatIni = PemasukanProyek::where('id_proyek', $id_proyek)
            ->where('termin_ke', $termin_ke)
            ->sum('jumlah');

        // Hitung sisa real
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

        // Hitung jumlah termin yang sudah lunas
        $jumlahTerminLunas = 0;
        for ($i = 1; $i <= $totalTermin; $i++) {
            $nilaiTermin = floor($nilaiProyek / $totalTermin);

            // Kalau termin terakhir, tambahin sisa pembulatan
            if ($i == $totalTermin) {
                $nilaiTermin += $sisaPembulatan;
            }

            $totalBayar = PemasukanProyek::where('id_proyek', $id_proyek)
                ->where('termin_ke', $i)
                ->sum('jumlah');

            if ($totalBayar >= $nilaiTermin) {
                $jumlahTerminLunas++;
            }
        }

        // Update status proyek
        if ($jumlahTerminLunas == $totalTermin) {
            $proyek->status_proyek = 'completed';
        } else {
            $proyek->status_proyek = 'progress';
        }
        $proyek->save();

        return redirect()->route('pemasukan.show', $id_proyek)
            ->with('success', 'Pembayaran termin berhasil dicatat.');
    }



    public function updateLunasi(Request $request, $id_proyek, $termin_ke)
    {
        $request->validate([
            'jumlah_bayar'      => 'required|string',
            'metode_pembayaran' => 'required|string',
            'keterangan'        => 'nullable|string|max:255',
            'attachment_file'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $jumlahBayar = (int) preg_replace('/[^\d]/', '', $request->jumlah_bayar);

        $proyek = Proyek::findOrFail($id_proyek);

        // Ambil data termin dari tb_termin
        $termin = TerminProyek::where('id_proyek', $id_proyek)
            ->where('termin_ke', $termin_ke)
            ->firstOrFail();

        $nilaiTermin = $termin->jumlah;

        // Hitung total pembayaran yang sudah masuk untuk termin ini
        $totalBayarTerminSaatIni = PemasukanProyek::where('id_proyek', $id_proyek)
            ->where('termin_ke', $termin_ke)
            ->sum('jumlah');

        $sisaBayarReal = max(0, $nilaiTermin - $totalBayarTerminSaatIni);

        if ($sisaBayarReal <= 0) {
            return back()->withErrors([
                'jumlah_bayar' => 'Termin ini sudah lunas.'
            ]);
        }

        if ($jumlahBayar > $sisaBayarReal) {
            return back()->withErrors([
                'jumlah_bayar' => 'Jumlah melebihi sisa pembayaran termin ini: Rp ' . number_format($sisaBayarReal, 0, ',', '.')
            ])->withInput();
        }

        // Tangani unggah file jika ada
        $filePath = null;
        if ($request->hasFile('attachment_file')) {
            $file = $request->file('attachment_file');
            $path = 'uploads/pemasukan/pr_' . $id_proyek;
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/' . $path, $fileName);
            $filePath = $path . '/' . $fileName;
        }

        // Simpan pembayaran baru
        PemasukanProyek::create([
            'id_proyek'         => $id_proyek,
            'termin_ke'         => $termin_ke,
            'jumlah'            => $jumlahBayar,
            'metode_pembayaran' => $request->metode_pembayaran,
            'nama_klien'        => $proyek->nama_klien,
            'attachment_file'   => $filePath,
            'tanggal_pemasukan' => now()->toDateString(),
            'keterangan'        => $request->keterangan ?? 'Pelunasan Termin ke-' . $termin_ke,
            'id_termin'         => $termin->id_termin,
            'created_at'        => now()->setTimezone('Asia/Jakarta'),
        ]);

        // Update status termin jika lunas
        $totalBayarTermin = PemasukanProyek::where('id_proyek', $id_proyek)
            ->where('termin_ke', $termin_ke)
            ->sum('jumlah');

        if ($totalBayarTermin >= $nilaiTermin) {
            $termin->status_pembayaran = 'lunas';
            $termin->tanggal_lunas = now(); // ✅ isi tanggal lunas
        } else {
            $termin->status_pembayaran = 'sebagian';
            $termin->tanggal_lunas = null;
        }
        $termin->save();

        // Update status proyek jika semua termin lunas
        $jumlahTerminLunas = TerminProyek::where('id_proyek', $id_proyek)
            ->where('status_pembayaran', 'lunas')
            ->count();

        $totalTermin = TerminProyek::where('id_proyek', $id_proyek)->count();

        $proyek->status_proyek = ($jumlahTerminLunas == $totalTermin) ? 'completed' : 'progress';
        $proyek->save();

        return redirect()->route('pemasukan.show', $id_proyek)
            ->with('success', 'Pembayaran termin berhasil dicatat.');
    }
}
