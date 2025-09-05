<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('vendors.index');
    }


    public function datatable(Request $request)
    {
        try {
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $search = $request->get('search')['value'] ?? '';

            $query = Vendor::query();

            // Search
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_vendor', 'like', "%{$search}%")
                        ->orWhere('kode_vendor', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Sorting
            $columns = ['kode_vendor', 'nama_vendor', 'alamat', 'no_telp', 'email']; // hanya kolom database

            if ($request->has('order')) {
                foreach ($request->get('order') as $order) {
                    $columnIdx = intval($order['column']) - 1; // kurangi 1 karena DT_RowIndex
                    $dir = $order['dir'] === 'desc' ? 'desc' : 'asc';
                    if ($columnIdx >= 0 && isset($columns[$columnIdx])) {
                        $query->orderBy($columns[$columnIdx], $dir);
                    }
                }
            } else {
                $query->orderBy('nama_vendor', 'asc'); // default
            }


            $totalRecords = Vendor::count();
            $filteredRecords = $query->count();

            $vendors = $query->offset($start)
                ->limit($length)
                ->get();

            $data = [];
            foreach ($vendors as $index => $vendor) {
                $data[] = [
                    'DT_RowIndex' => $start + $index + 1,
                    'kode_vendor' => $vendor->kode_vendor,
                    'nama_vendor' => $vendor->nama_vendor,
                    'alamat' => $vendor->alamat,
                    'no_telp' => $vendor->no_telp,
                    'email' => $vendor->email,
                    'aksi' => view('vendors.partials.actions', compact('vendor'))->render()
                ];
            }

            return response()->json([
                'draw' => intval($request->get('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function getByJenis($jenis)
    {
        return Vendor::where('jenis_vendor', $jenis)->get(['id_vendor', 'nama_vendor']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $spesialisasi_vendor = Vendor::pluck('spesialisasi')->unique()->toArray();

        $rekening_vendors = Vendor::pluck('rekening')->toArray();
        $nama_bank = [];
        foreach ($rekening_vendors as $rekening_list) {
            // Mendekode JSON menjadi array PHP
            $rekening_list = json_decode($rekening_list, true);
            if (is_array($rekening_list)) {
                foreach ($rekening_list as $rekening) {
                    if (isset($rekening['nama_bank']) && !in_array($rekening['nama_bank'], $nama_bank)) {
                        $nama_bank[] = $rekening['nama_bank'];
                    }
                }
            }
        }

        return view('vendors.create', [
            'spesialisasi_vendor' => $spesialisasi_vendor,
            'nama_bank' => array_unique($nama_bank) // Pastikan unik

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_vendor' => 'nullable|string|max:50|unique:tb_vendor,kode_vendor',
            'nama_vendor' => 'required|string|max:150|unique:tb_vendor,nama_vendor',
            'jenis_vendor' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'spesialisasi' => 'nullable|string|max:150',
            'keterangan' => 'nullable|string',
            'rekening' => 'nullable|array',
            'rekening.*.atas_nama' => 'nullable|string|max:150',
            'rekening.*.nama_bank' => 'nullable|string|max:100',
            'rekening.*.no_rekening' => 'nullable|string|max:100',
        ]);

        // Generate kode vendor otomatis kalau kosong
        $kode_vendor = $request->kode_vendor ?? 'VND-' . str_pad(Vendor::count() + 1, 4, '0', STR_PAD_LEFT);

        // Format kapital di awal kata
        $nama_vendor = ucwords(strtolower($request->nama_vendor));
        $jenis_vendor = ucwords(strtolower($request->jenis_vendor));
        $alamat = ucwords(strtolower($request->alamat ?? ''));
        $spesialisasi = ucwords(strtolower($request->spesialisasi ?? ''));
        $keterangan = ucwords(strtolower($request->keterangan ?? ''));

        // Format kapital untuk rekening
        $rekeningFormatted = [];
        if (!empty($request->rekening)) {
            foreach ($request->rekening as $rek) {
                if (empty($rek['atas_nama']) && empty($rek['nama_bank']) && empty($rek['no_rekening'])) {
                    continue;
                }

                // Nama bank: kalau sudah ALL CAPS, biarin
                $namaBank = $rek['nama_bank'] ?? '';
                if ($namaBank && !ctype_upper(str_replace(' ', '', $namaBank))) {
                    $namaBank = ucwords(strtolower($namaBank));
                }

                $rekeningFormatted[] = [
                    'atas_nama' => ucwords(strtolower($rek['atas_nama'] ?? '')),
                    'nama_bank' => $namaBank,
                    'no_rekening' => $rek['no_rekening'] ?? '',
                ];
            }
        }


        // Simpan data
        Vendor::create([
            'kode_vendor' => $kode_vendor,
            'nama_vendor' => $nama_vendor,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'alamat' => $alamat,
            'jenis_vendor' => $jenis_vendor,
            'spesialisasi' => $spesialisasi,
            'rekening' => json_encode($rekeningFormatted),
            'keterangan' => $keterangan,
        ]);

        return redirect()->route('vendors.index')->with('success', 'Vendor berhasil ditambahkan');
    }




    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        return view('vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        // Mengambil data spesialisasi dan bank yang unik dari database
        $spesialisasi_vendor = Vendor::pluck('spesialisasi')->unique()->toArray();

        $rekening_vendors = Vendor::pluck('rekening')->toArray();
        $nama_bank = [];
        foreach ($rekening_vendors as $rekening_list) {
            $rekening_list = json_decode($rekening_list, true);
            if (is_array($rekening_list)) {
                foreach ($rekening_list as $rekening) {
                    if (isset($rekening['nama_bank']) && !in_array($rekening['nama_bank'], $nama_bank)) {
                        $nama_bank[] = $rekening['nama_bank'];
                    }
                }
            }
        }

        return view('vendors.edit', compact('vendor', 'spesialisasi_vendor', 'nama_bank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        // Lakukan validasi data di sini
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            // Tambahkan aturan validasi lainnya
            'rekening.*.atas_nama' => 'nullable|string|max:255',
            'rekening.*.nama_bank' => 'nullable|string|max:255',
            'rekening.*.no_rekening' => 'nullable|string|max:255',
        ]);

        // Update data vendor
        $vendor->nama_vendor = $request->nama_vendor;
        $vendor->no_telp = $request->no_telp;
        $vendor->email = $request->email;
        $vendor->jenis_vendor = $request->jenis_vendor;
        $vendor->spesialisasi = $request->spesialisasi;
        $vendor->alamat = $request->alamat;
        $vendor->keterangan = $request->keterangan;

        // Simpan data rekening sebagai JSON
        $vendor->rekening = json_encode(array_values($request->rekening ?? []));

        $vendor->save();

        return redirect()->route('vendors.index')->with('success', 'Vendor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Vendor berhasil dihapus.');
    }
}
