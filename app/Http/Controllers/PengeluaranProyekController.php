<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranProyek;

use App\Models\Proyek;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PengeluaranProyekController extends Controller
{
    /**
     * List semua pengeluaran
     */
    public function index()
    {
        $pengeluaran = PengeluaranProyek::latest()->get();
        return view('pengeluaran.index', compact('pengeluaran'));
    }


    public function create()
    {
        $proyek = Proyek::all();
        $jenisVendor = Vendor::select('jenis_vendor')->distinct()->pluck('jenis_vendor');
        return view('pengeluaran.create', compact('proyek', 'jenisVendor'));
    }

    public function getVendorByJenis($jenis)
    {
        $vendors = Vendor::where('jenis_vendor', $jenis)
            ->select('id_vendor as id', 'nama_vendor', 'rekening')
            ->get()
            ->map(function ($v) {
                $v->rekening = json_decode($v->rekening, true);
                return $v;
            });

        return response()->json($vendors);

    }




    public function storelama(Request $request)
    {
        // Validasi data yang masuk
        $request->validate([
            'id_proyek' => 'required|integer',
            'id_vendor' => 'required|integer',
            'nama_proyek' => 'required|string',
            'tanggal_pengeluaran' => 'required|date',
            'jumlah' => 'required|integer',
            'keterangan' => 'nullable|string',
            'file_nota' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2MB
        ]);

        // Ambil data vendor untuk mendapatkan nama vendor
        $vendor = Vendor::findOrFail($request->id_vendor);
        $namaVendor = $vendor->nama_vendor;

        // Tangani input rekening vendor
        $rekening = $request->rekening;
        if ($rekening === 'lainnya') {
            $request->validate([
                'atas_nama' => 'required|string',
                'nama_bank' => 'required|string',
                'no_rekening' => 'required|string',
            ]);

            $rekening_baru = [
                'nama_bank' => $request->nama_bank,
                'no_rekening' => $request->no_rekening,
                'atas_nama' => $request->atas_nama,
            ];

            $daftar_rekening = json_decode($vendor->rekening, true) ?? [];
            $daftar_rekening[] = $rekening_baru;
            $vendor->rekening = json_encode($daftar_rekening);
            $vendor->save();

            $rekening = "{$request->nama_bank} - {$request->no_rekening} a/n {$request->atas_nama}";
        }

        // Tangani upload file bukti
        $filePath = null;
        if ($request->hasFile('file_nota')) {
            $file = $request->file('file_nota');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = 'uploads/pengeluaran/pr_' . $request->id_proyek;

            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }

            $file->move(public_path($path), $fileName);
            $filePath = $path . '/' . $fileName;
        }

        // Simpan pengeluaran ke database
        PengeluaranProyek::create([
            'id_proyek' => $request->id_proyek,
            'nama_proyek' => $request->nama_proyek,
            'id_vendor' => $request->id_vendor,
            'nama_vendor' => $namaVendor,
            'rekening' => $rekening,
            'status' => 'Pengajuan',
            'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'file_nota' => $filePath,
            'user_created' => Auth::user()->username // atau session('username')
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran proyek berhasil ditambahkan!');
    }


    public function store(Request $request)
    {
        // Validasi dasar
        $rules = [
            'id_proyek' => 'required|integer',
            'id_vendor' => 'required|integer',
            'nama_proyek' => 'required|string',
            'tanggal_pengeluaran' => 'required|date',
            'jumlah' => 'required|integer',
            'keterangan' => 'nullable|string',
            'file_nota' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2MB
            'status' => 'nullable|string|in:Pengajuan,Approve', // status baru
        ];

        // Kalau pilih Approve, wajib upload file bukti tf
        if ($request->status === 'Approve') {
            $rules['file_buktitf'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        $request->validate($rules);

        // Ambil vendor
        $vendor = Vendor::findOrFail($request->id_vendor);
        $namaVendor = $vendor->nama_vendor;

        // Tangani rekening
        $rekening = $request->rekening;
        if ($rekening === 'lainnya') {
            $request->validate([
                'atas_nama' => 'required|string',
                'nama_bank' => 'required|string',
                'no_rekening' => 'required|string',
            ]);

            $rekening_baru = [
                'nama_bank' => $request->nama_bank,
                'no_rekening' => $request->no_rekening,
                'atas_nama' => $request->atas_nama,
            ];

            $daftar_rekening = json_decode($vendor->rekening, true) ?? [];
            $daftar_rekening[] = $rekening_baru;
            $vendor->rekening = json_encode($daftar_rekening);
            $vendor->save();

            $rekening = "{$request->nama_bank} - {$request->no_rekening} a/n {$request->atas_nama}";
        }

        // Upload file nota
        $filePathNota = null;
        if ($request->hasFile('file_nota')) {
            $file = $request->file('file_nota');
            $fileName = time() . '_nota_' . $file->getClientOriginalName();
            $path = 'uploads/pengeluaran/pr_' . $request->id_proyek;

            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }

            $file->move(public_path($path), $fileName);
            $filePathNota = $path . '/' . $fileName;
        }

        // Upload file bukti transfer (kalau approve)
        $filePathBukti = null;
        if ($request->hasFile('file_buktitf')) {
            $file = $request->file('file_buktitf');
            $fileName = time() . '_buktitf_' . $file->getClientOriginalName();
            $path = 'uploads/pengeluaran/pr_' . $request->id_proyek;

            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }

            $file->move(public_path($path), $fileName);
            $filePathBukti = $path . '/' . $fileName;
        }

        // Tentukan status
        $status = $request->status === 'Approve' ? 'Sudah dibayar' : 'Pengajuan';

        // Simpan data
        PengeluaranProyek::create([
            'id_proyek' => $request->id_proyek,
            'nama_proyek' => $request->nama_proyek,
            'id_vendor' => $request->id_vendor,
            'nama_vendor' => $namaVendor,
            'rekening' => $rekening,
            'status' => $status,
            'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'file_nota' => $filePathNota,
            'file_buktitf' => $filePathBukti,
            'user_created' => Auth::user()->username,
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran proyek berhasil ditambahkan!');
    }



    public function prosesPengeluaranjson(Request $request, $id_pengeluaran)
    {
        $pengeluaran = PengeluaranProyek::findOrFail($id_pengeluaran);

        // Validasi dropdown
        $request->validate([
            'aksi' => 'required|in:proses,approve',
        ]);

        if ($request->aksi === 'proses') {
            // Kalau pilih proses dulu
            $pengeluaran->status = 'Sedang diproses';
            $pengeluaran->save();

            return back()->with('success', 'Pengeluaran berhasil diproses!');
        }

        if ($request->aksi === 'approve') {
            // Kalau langsung approve, wajib upload file
            $request->validate([
                'file_buktitf' => 'required|array',
                'file_buktitf.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            $paths = [];
            $folder = "uploads/pengeluaran/pr_{$pengeluaran->id_proyek}";

            foreach ($request->file('file_buktitf') as $file) {
                $paths[] = $file->store($folder, 'public');
            }

            // Simpan status + path file
            $pengeluaran->status = 'Sudah dibayar';
            $pengeluaran->file_buktitf = json_encode($paths); // simpan sebagai json array
            $pengeluaran->save();

            return back()->with('success', 'Pengeluaran berhasil di-approve!');
        }
    }

    public function prosesPengeluaran(Request $request, $id_pengeluaran)
    {
        $pengeluaran = PengeluaranProyek::findOrFail($id_pengeluaran);

        // Validasi dropdown dengan pesan error custom
        $request->validate([
            'aksi' => 'required|in:proses,approve',
        ], [
            'aksi.required' => 'Pilih aksi terlebih dahulu.',
            'aksi.in' => 'Aksi yang dipilih tidak valid.',
        ]);

        if ($request->aksi === 'proses') {
            // Kalau pilih proses dulu
            $pengeluaran->status = 'Sedang diproses';
            $pengeluaran->save();

            return back()->with('success', 'Pengeluaran berhasil diproses!');
        }

        if ($request->aksi === 'approve') {
            // Kalau langsung approve, wajib upload file
            $request->validate([
                'file_buktitf' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ], [
                'file_buktitf.required' => 'File bukti transfer wajib diupload.',
                'file_buktitf.file' => 'File bukti transfer tidak valid.',
                'file_buktitf.mimes' => 'Format file harus jpg, jpeg, png, atau pdf.',
                'file_buktitf.max' => 'Ukuran file maksimal 2MB.',
            ]);

            $folder = "uploads/pengeluaran/pr_{$pengeluaran->id_proyek}";
            $path = $request->file('file_buktitf')->store($folder, 'public');

            // Simpan status + path file
            $pengeluaran->status = 'Sudah dibayar';
            $pengeluaran->file_buktitf = $path; // langsung string, bukan json
            $pengeluaran->save();

            return back()->with('success', 'Pengeluaran berhasil di-approve!');
        }
    }




    public function approve(Request $request, $id)
    {
        $request->validate([
            'file_buktitf' => 'required|array',
            'file_buktitf.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $pengeluaran = PengeluaranProyek::findOrFail($id);

        $paths = [];
        $folder = "uploads/pengeluaran/pr_{$pengeluaran->id_proyek}";

        foreach ($request->file('file_buktitf') as $file) {
            // Simpan file di storage/app/public/uploads/pengeluaran/pr_{id_proyek}/
            $paths[] = $file->store($folder, 'public');
        }

        $pengeluaran->update([
            'file_buktitf' => $paths,
            'status' => 'Sudah dibayar'
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran disetujui');
    }

    // Reject Pengeluaran
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_bod' => 'required|string|max:500'
        ]);

        $pengeluaran = PengeluaranProyek::findOrFail($id);

        $pengeluaran->update([
            'catatan_bod' => $request->catatan_bod,
            'status' => 'Ditolak'
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran ditolak');
    }


    /**
     * Lihat detail pengeluaran
     */
    public function show($id)
    {
        $pengeluaran = PengeluaranProyek::findOrFail($id);
        return view('pengeluaran.show', compact('pengeluaran'));
    }

    /**
     * Form edit pengeluaran
     */
    public function edit($id)
    {
        $proyek = Proyek::all();
        $vendor = Vendor::all();
        $jenisVendor = Vendor::select('jenis_vendor')->distinct()->pluck('jenis_vendor');
        $pengeluaran = PengeluaranProyek::findOrFail($id);
        return view('pengeluaran.edit', compact('pengeluaran','proyek','jenisVendor','vendor'));
    }

    /**
     * Update pengeluaran
     */
    public function update(Request $request, $id)
    {
        $pengeluaran = PengeluaranProyek::findOrFail($id);

        $validated = $request->validate([
            'id_proyek' => 'required|integer',
            'id_vendor' => 'required|integer',
            'nama_vendor' => 'nullable|string|max:150',
            'rekening' => 'nullable|array',
            'tanggal_pengeluaran' => 'required|date',
            'kategori_pengeluaran' => 'required|string|max:100',
            'jumlah' => 'required|numeric',
            'metode_pembayaran' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            'file_nota.*' => 'nullable|file|max:2048',
            'file_buktitf.*' => 'nullable|file|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        // Ambil data lama
        $fileNotaPaths = $pengeluaran->file_nota ?? [];
        $fileBuktiPaths = $pengeluaran->file_buktitf ?? [];

        // Upload file nota baru
        if ($request->hasFile('file_nota')) {
            foreach ($request->file('file_nota') as $file) {
                $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/pengeluaran/nota'), $filename);
                $fileNotaPaths[] = 'uploads/pengeluaran/nota/' . $filename;
            }
        }

        // Upload file bukti transfer baru
        if ($request->hasFile('file_buktitf')) {
            foreach ($request->file('file_buktitf') as $file) {
                $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/pengeluaran/bukti'), $filename);
                $fileBuktiPaths[] = 'uploads/pengeluaran/bukti/' . $filename;
            }
        }

        // Update data
        $pengeluaran->update([
            'id_proyek' => $validated['id_proyek'],
            'id_vendor' => $validated['id_vendor'],
            'nama_vendor' => $validated['nama_vendor'] ?? null,
            'rekening' => $validated['rekening'] ?? [],
            'tanggal_pengeluaran' => $validated['tanggal_pengeluaran'],
            'kategori_pengeluaran' => $validated['kategori_pengeluaran'],
            'jumlah' => $validated['jumlah'],
            'metode_pembayaran' => $validated['metode_pembayaran'] ?? null,
            'status' => $validated['status'] ?? '',
            'file_nota' => $fileNotaPaths,
            'file_buktitf' => $fileBuktiPaths,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran proyek berhasil diperbarui.');
    }

    /**
     * Hapus pengeluaran
     */
    public function destroy($id)
    {
        $pengeluaran = PengeluaranProyek::findOrFail($id);
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran proyek berhasil dihapus.');
    }
}
