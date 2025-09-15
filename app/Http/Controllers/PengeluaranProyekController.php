<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranProyek;

use App\Models\Proyek;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class PengeluaranProyekController extends Controller
{
    /**
     * List semua pengeluaran
     */
    public function index()
    {
        return view('pengeluaran.index');
    }

    // DataTables AJAX


    public function getData(Request $request)
    {
        try {
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $search = $request->get('search')['value'] ?? '';

            $query = PengeluaranProyek::query();

            //  Filter berdasarkan status
            if ($request->has('status_filter') && !empty($request->status_filter)) {
                $statusFilter = $request->status_filter;

                if (is_array($statusFilter)) {
                    $query->whereIn('status', $statusFilter);
                } elseif (str_contains($statusFilter, ',')) {
                    $query->whereIn('status', explode(',', $statusFilter));
                } else {
                    $query->where('status', $statusFilter);
                }
            }

            //  Custom search filter
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_proyek', 'like', "%{$search}%")
                        ->orWhere('nama_vendor', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%")
                        ->orWhere('jumlah', 'like', "%{$search}%");
                });
            }

            //  Sorting (urutan kolom sesuai tabel DataTables lu)
            $columns = ['nama_proyek', 'nama_vendor', 'tanggal_pengeluaran', 'jumlah', 'status'];
            if ($request->has('order')) {
                foreach ($request->get('order') as $order) {
                    $columnIdx = intval($order['column']) - 1; // -1 karena DT_RowIndex
                    $dir = $order['dir'] === 'desc' ? 'desc' : 'asc';
                    if ($columnIdx >= 0 && isset($columns[$columnIdx])) {
                        $query->orderBy($columns[$columnIdx], $dir);
                    }
                }
            } else {
                $query->orderBy('tanggal_pengeluaran', 'desc'); // default
            }

            //  Hitung total
            $totalRecords = PengeluaranProyek::count();
            $filteredRecords = $query->count();

            // Ambil data
            $items = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($items as $index => $item) {
                $statusClass = match ($item->status) {
                    'Pengajuan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200',
                    'Sedang diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200',
                    'Sudah dibayar' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200',
                    'Ditolak' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200',
                    'Cancel' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200',
                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
                };

                $data[] = [
                    'DT_RowIndex' => $start + $index + 1,
                    'nama_proyek' => $item->nama_proyek ?? '-',
                    'nama_vendor' => $item->nama_vendor ?? '-',
                    'tanggal' => \Carbon\Carbon::parse($item->tanggal_pengeluaran)->format('d/m/Y'),
                    'jumlah' => $item->jumlah,
                    'status' => '<span class="px-2 py-1 text-xs font-medium rounded ' . $statusClass . '">' .
                        ucfirst($item->status ?: 'Pengajuan') . '</span>',
                    'aksi' => view('pengeluaran.partials.actions', compact('item'))->render()
                ];
            }

            return response()->json([
                'draw' => intval($request->get('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function create()
    {
        $proyek = Proyek::orderBy('nama_proyek', 'asc')->get();

        $jenisVendor = Vendor::select('jenis_vendor')
            ->distinct()
            ->orderBy('jenis_vendor', 'asc')
            ->pluck('jenis_vendor');

        return view('pengeluaran.create', compact('proyek', 'jenisVendor'));
    }


    public function getVendorByJenis($jenis)
    {
        $vendors = Vendor::where('jenis_vendor', $jenis)
            ->select('id_vendor as id', 'nama_vendor', 'rekening')
            ->orderBy('nama_vendor', 'asc')
            ->get()
            ->map(function ($v) {
                $v->rekening = json_decode($v->rekening, true);
                return $v;
            });

        return response()->json($vendors);
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

            // Simpan ke storage/app/public/uploads/pengeluaran/pr_(id)/
            $filePathNota = $file->storeAs($path, $fileName, 'public');
        }

        // Upload file bukti transfer (kalau approve)
        $filePathBukti = null;
        if ($request->hasFile('file_buktitf')) {
            $file = $request->file('file_buktitf');
            $fileName = time() . '_buktitf_' . $file->getClientOriginalName();
            $path = 'uploads/pengeluaran/pr_' . $request->id_proyek;

            $filePathBukti = $file->storeAs($path, $fileName, 'public');
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
            'file_buktitf' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $pengeluaran = PengeluaranProyek::findOrFail($id);

        $folder = "uploads/pengeluaran/pr_{$pengeluaran->id_proyek}";
        $path = $request->file('file_buktitf')->store($folder, 'public');

        $pengeluaran->update([
            'file_buktitf' => $path,
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
        return view('pengeluaran.edit', compact('pengeluaran', 'proyek', 'jenisVendor', 'vendor'));
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


    public function cancel($id_pengeluaran)
    {
        $pengeluaran = PengeluaranProyek::where('id_pengeluaran', $id_pengeluaran)->firstOrFail();

        if ($pengeluaran->status === 'Pengajuan') {
            $pengeluaran->status = 'Cancel';
            $pengeluaran->save();
        }

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil dibatalkan.');
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
