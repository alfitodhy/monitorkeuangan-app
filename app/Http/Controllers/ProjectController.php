<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\TerminProyek;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use Carbon\Carbon;

class ProjectController extends Controller
{
    public function index()
    {
        $data = Proyek::where('is_active', 'Y')->latest()->get();
        return view('projects.index', compact('data'));
    }


    public function create()
    {
        return view('projects.create');
    }


    public function storelama(Request $request)
    {
        $request->merge([
            'nilai_proyek' => str_replace('.', '', $request->input('nilai_proyek')),
        ]);

        // Validasi input
        $validated = $request->validate([
            'kode_proyek' => 'required|unique:tb_proyek',
            'nama_proyek' => 'required|string',
            'nama_klien' => 'required|string',
            'nilai_proyek' => 'required|numeric',
            'estimasi_hpp' => 'nullable|string',
            'termin' => 'nullable|integer',
            'tipe_proyek' => 'required|string',
            'tanggal_start_proyek' => 'nullable|date',
            'tanggal_deadline' => 'nullable|date',
            'durasi_pengerjaan_bulan' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'attachment_file.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:5120',
        ]);

        // Bersihkan estimasi_hpp dari tanda %
        if (!empty($validated['estimasi_hpp'])) {
            $validated['estimasi_hpp'] = (int) str_replace('%', '', $validated['estimasi_hpp']);
        }

        // Format kapital setiap kata
        $validated['nama_klien'] = ucwords(strtolower(trim($validated['nama_klien'])));
        $validated['nama_proyek'] = ucwords(strtolower(trim($validated['nama_proyek'])));
        if (!empty($validated['keterangan'])) {
            $validated['keterangan'] = ucwords(strtolower(trim($validated['keterangan'])));
        }

        // Set default status_proyek
        $validated['status_proyek'] = 'process';

        // Hapus dulu attachment_file dari array validated supaya tidak disimpan langsung
        unset($validated['attachment_file']);

        // Buat record proyek dulu tanpa attachment_file
        $project = Proyek::create($validated);
        $idProyek = $project->id_proyek;
        $projectFolder = storage_path('app/public/uploads/proyek/pr_' . $idProyek);

        // Buat folder jika belum ada
        if (!File::exists($projectFolder)) {
            File::makeDirectory($projectFolder, 0755, true);
        }

        $fileNames = [];
        if ($request->hasFile('attachment_file')) {
            foreach ($request->file('attachment_file') as $file) {
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '.' . $file->getClientOriginalExtension();

                // Simpan file di storage/app/public/uploads/proyek/pr_(id_proyek)
                $file->storeAs('uploads/proyek/pr_' . $idProyek, $filename, 'public');

                $fileNames[] = $filename;
            }
        }

        // Update field attachment_file dengan JSON
        if (count($fileNames) > 0) {
            $project->attachment_file = json_encode($fileNames);
            $project->save();
        }

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil ditambahkan.');
    }


    public function store3(Request $request)
    {
        // Bersihkan format rupiah dari nilai_proyek
        $request->merge([
            'nilai_proyek' => preg_replace('/[^\d]/', '', $request->input('nilai_proyek', '')),
        ]);

        // Bersihkan format rupiah dari setiap termin jumlah
        if ($request->has('termins')) {
            $termins = $request->input('termins');
            foreach ($termins as $key => $termin) {
                if (isset($termin['jumlah'])) {
                    // Hapus semua karakter selain angka
                    $termins[$key]['jumlah'] = preg_replace('/[^\d]/', '', $termin['jumlah']);
                }
            }
            // Merge kembali data termins yang sudah dibersihkan
            $request->merge(['termins' => $termins]);
        }

        // Validasi input
        $validated = $request->validate([
            'kode_proyek' => 'required|unique:tb_proyek',
            'nama_proyek' => 'required|string',
            'nama_klien' => 'required|string',
            'nilai_proyek' => 'required|numeric',
            'estimasi_hpp' => 'nullable|string',
            'termin' => 'nullable|integer|min:1',
            'tipe_proyek' => 'required|string',
            'tipe_lainnya' => 'nullable|string',
            'tanggal_start_proyek' => 'nullable|date',
            'tanggal_deadline' => 'nullable|date',
            'durasi_pengerjaan_bulan' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'attachment_file.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:5120',
            'termins' => 'nullable|array',
            'termins.*.termin_ke' => 'required|integer|min:1',
            'termins.*.tanggal_jatuh_tempo' => 'required|date',
            'termins.*.jumlah' => 'required|numeric|min:0',
            'termins.*.keterangan' => 'nullable|string',
        ]);

        // Bersihkan estimasi_hpp dari tanda %
        if (!empty($validated['estimasi_hpp'])) {
            $validated['estimasi_hpp'] = (int) str_replace('%', '', $validated['estimasi_hpp']);
            if ($validated['estimasi_hpp'] < 0 || $validated['estimasi_hpp'] > 100) {
                return back()->withInput()->withErrors(['estimasi_hpp' => 'Estimasi HPP harus antara 0% dan 100%.']);
            }
        }

        // Validasi tipe "Lainnya"
        if ($validated['tipe_proyek'] === 'Lainnya' && empty($validated['tipe_lainnya'])) {
            return back()->withInput()->withErrors(['tipe_lainnya' => 'Silakan isi tipe proyek lainnya.']);
        }

        // Format kapital setiap kata
        $validated['nama_klien'] = ucwords(strtolower(trim($validated['nama_klien'])));
        $validated['nama_proyek'] = ucwords(strtolower(trim($validated['nama_proyek'])));
        if (!empty($validated['keterangan'])) {
            $validated['keterangan'] = ucwords(strtolower(trim($validated['keterangan'])));
        }

        // Validasi tanggal
        if (!empty($validated['tanggal_start_proyek']) && !empty($validated['tanggal_deadline'])) {
            if ($validated['tanggal_deadline'] < $validated['tanggal_start_proyek']) {
                return back()->withInput()->withErrors(['tanggal_deadline' => 'Tanggal deadline tidak boleh lebih awal dari tanggal mulai proyek.']);
            }
        }

        // Set default status_proyek
        $validated['status_proyek'] = 'process';

        // Hapus attachment_file dari array validated
        unset($validated['attachment_file']);

        // Buat record proyek
        $project = Proyek::create($validated);
        $idProyek = $project->id_proyek;
        $projectFolder = storage_path('app/public/uploads/proyek/pr_' . $idProyek);

        if (!File::exists($projectFolder)) {
            File::makeDirectory($projectFolder, 0755, true);
        }

        $fileNames = [];
        if ($request->hasFile('attachment_file')) {
            foreach ($request->file('attachment_file') as $file) {
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '.' . $file->getClientOriginalExtension();
                $file->storeAs('uploads/proyek/pr_' . $idProyek, $filename, 'public');
                $fileNames[] = $filename;
            }
        }

        if (count($fileNames) > 0) {
            $project->attachment_file = json_encode($fileNames);
            $project->save();
        }

        // Simpan termin ke tb_termin_proyek
        if (!empty($validated['termins'])) {
            $terminKeList = array_column($validated['termins'], 'termin_ke');
            if (count($terminKeList) !== count(array_unique($terminKeList))) {
                return back()->withInput()->withErrors(['termins' => 'Nomor termin tidak boleh duplikat.']);
            }

            $totalTermin = 0;
            foreach ($validated['termins'] as $termin) {
                // Validasi tanggal jatuh tempo
                if (!empty($validated['tanggal_start_proyek']) && $termin['tanggal_jatuh_tempo'] < $validated['tanggal_start_proyek']) {
                    return back()->withInput()->withErrors(['termins' => 'Tanggal jatuh tempo termin tidak boleh lebih awal dari tanggal mulai proyek.']);
                }
                $totalTermin += (int) $termin['jumlah'];
            }

            // Validasi total termin sama dengan nilai proyek
            if ($totalTermin != $validated['nilai_proyek']) {
                return back()->withInput()->withErrors(['termins' => "Total semua termin (" . number_format($totalTermin, 0, ',', '.') . ") harus sama dengan nilai proyek (" . number_format($validated['nilai_proyek'], 0, ',', '.') . ")."]);
            }

            // Simpan ke database
            foreach ($validated['termins'] as $termin) {
                TerminProyek::create([
                    'id_proyek' => $idProyek,
                    'termin_ke' => $termin['termin_ke'],
                    'tanggal_jatuh_tempo' => $termin['tanggal_jatuh_tempo'],
                    'jumlah' => (int) $termin['jumlah'],
                    'keterangan' => $termin['keterangan'] ?? null,
                ]);
            }
        }

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil ditambahkan.');
    }



    public function store(Request $request)
    {
        // Bersihkan format rupiah dari nilai_proyek
        $request->merge([
            'nilai_proyek' => preg_replace('/[^\d]/', '', $request->input('nilai_proyek', '')),
        ]);

        // Bersihkan format rupiah dari setiap termin jumlah
        if ($request->has('termins')) {
            $termins = $request->input('termins');
            foreach ($termins as $key => $termin) {
                if (isset($termin['jumlah'])) {
                    $termins[$key]['jumlah'] = preg_replace('/[^\d]/', '', $termin['jumlah']);
                }
            }
            $request->merge(['termins' => $termins]);
        }

        // Validasi input
        $validated = $request->validate([
            'kode_proyek' => 'required|string|unique:tb_proyek,kode_proyek',
            'nama_proyek' => 'required|string|unique:tb_proyek,nama_proyek',
            'nama_klien' => 'required|string',
            'nilai_proyek' => 'required|numeric',
            'estimasi_hpp' => 'nullable|integer|min:0|max:100', // langsung dicek batas 0 - 100
            'termin' => 'nullable|integer|min:1',
            'tipe_proyek' => 'required|string',
            'tipe_lainnya' => 'nullable|string',
            'tanggal_start_proyek' => 'nullable|date',
            'tanggal_deadline' => 'nullable|date|after_or_equal:tanggal_start_proyek', // tidak boleh lebih kecil
            'durasi_pengerjaan_bulan' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'attachment_file.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:5120',
            'termins' => 'nullable|array',
            'termins.*.termin_ke' => 'required|integer|min:1',
            'termins.*.tanggal_jatuh_tempo' => 'required|date',
            'termins.*.jumlah' => 'required|numeric|min:0',
            'termins.*.keterangan' => 'nullable|string',
        ]);

        // Format kapital setiap kata
        $validated['nama_klien'] = ucwords(strtolower(trim($validated['nama_klien'])));
        $validated['nama_proyek'] = ucwords(strtolower(trim($validated['nama_proyek'])));
        if (!empty($validated['keterangan'])) {
            $validated['keterangan'] = ucwords(strtolower(trim($validated['keterangan'])));
        }

        // Set default status_proyek
        $validated['status_proyek'] = 'process';

        // Hapus attachment_file dari array validated
        unset($validated['attachment_file']);

        // Tambahkan timestamp manual
        $now = Carbon::now();
        $validated['created_at'] = $now;
        $validated['updated_at'] = $now;

        // Buat record proyek
        $project = Proyek::create($validated);
        $idProyek = $project->id_proyek;
        $projectFolder = storage_path('app/public/uploads/proyek/pr_' . $idProyek);

        if (!File::exists($projectFolder)) {
            File::makeDirectory($projectFolder, 0755, true);
        }

        $fileNames = [];
        if ($request->hasFile('attachment_file')) {
            foreach ($request->file('attachment_file') as $file) {
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '.' . $file->getClientOriginalExtension();
                $file->storeAs('uploads/proyek/pr_' . $idProyek, $filename, 'public');
                $fileNames[] = $filename;
            }
        }

        if (count($fileNames) > 0) {
            $project->attachment_file = json_encode($fileNames);
            $project->save();
        }

        // Simpan termin ke tb_termin_proyek
        if (!empty($validated['termins'])) {
            $terminKeList = array_column($validated['termins'], 'termin_ke');
            if (count($terminKeList) !== count(array_unique($terminKeList))) {
                return back()->withInput()->withErrors(['termins' => 'Nomor termin tidak boleh duplikat.']);
            }

            $totalTermin = 0;
            foreach ($validated['termins'] as $termin) {
                if (!empty($validated['tanggal_start_proyek']) && $termin['tanggal_jatuh_tempo'] < $validated['tanggal_start_proyek']) {
                    return back()->withInput()->withErrors(['termins' => 'Tanggal jatuh tempo termin tidak boleh lebih awal dari tanggal mulai proyek.']);
                }
                $totalTermin += (int) $termin['jumlah'];
            }

            if ($totalTermin != $validated['nilai_proyek']) {
                return back()->withInput()->withErrors(['termins' => "Total jumlah termin (" . number_format($totalTermin, 0, ',', '.') . ") harus sama dengan nilai proyek (" . number_format($validated['nilai_proyek'], 0, ',', '.') . ")."]);
            }

            foreach ($validated['termins'] as $termin) {
                TerminProyek::create([
                    'id_proyek' => $idProyek,
                    'termin_ke' => $termin['termin_ke'],
                    'tanggal_jatuh_tempo' => $termin['tanggal_jatuh_tempo'],
                    'jumlah' => (int) $termin['jumlah'],
                    'keterangan' => $termin['keterangan'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil ditambahkan.');
    }



    public function show($id)
    {
        $project = Proyek::findOrFail($id);

        $termins = TerminProyek::where('id_proyek', $id)->get();

        return view('projects.show', compact('project', 'termins'));
    }


    public function edit($id_proyek)
    {
        // Cari proyek berdasarkan id, kalau tidak ditemukan 404 otomatis
        $project = Proyek::findOrFail($id_proyek);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Proyek $project)
    {
        $validated = $request->validate([
            'kode_proyek' => [
                'required',
                'string',
                Rule::unique('tb_proyek', 'kode_proyek')->ignore($project->id_proyek, 'id_proyek'),
            ],
            'nama_proyek' => [
                'required',
                'string',
                Rule::unique('tb_proyek', 'nama_proyek')->ignore($project->id_proyek, 'id_proyek'),
            ],
            'nama_klien' => 'required|string',
            'nilai_proyek' => 'required|string',
            'estimasi_hpp' => 'nullable|string',
            'termin' => 'nullable|integer',
            'tipe_proyek' => 'required|string',
            'tipe_lainnya' => 'nullable|string',
            'tanggal_start_proyek' => 'nullable|date',
            'tanggal_deadline' => 'nullable|date',
            'durasi_pengerjaan_bulan' => 'nullable|integer',
            'keterangan' => 'nullable|string',

            'attachment_file.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx,ppt,pptx|max:5120',
            'replace_file.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx,ppt,pptx|max:5120',
        ]);

        // ✅ Bersihkan angka & persen
        $validated['nilai_proyek'] = (int) str_replace('.', '', $validated['nilai_proyek']);
        $validated['estimasi_hpp'] = !empty($validated['estimasi_hpp'])
            ? (float) str_replace('%', '', $validated['estimasi_hpp'])
            : null;

        // ✅ Update data utama proyek
        $project->update([
            'kode_proyek' => $validated['kode_proyek'],
            'nama_proyek' => $validated['nama_proyek'],
            'nama_klien' => $validated['nama_klien'],
            'nilai_proyek' => $validated['nilai_proyek'],
            'estimasi_hpp' => $validated['estimasi_hpp'],
            'termin' => $validated['termin'] ?? null,
            'tipe_proyek' => $validated['tipe_proyek'],
            'tipe_lainnya' => $validated['tipe_lainnya'] ?? null,
            'tanggal_start_proyek' => $validated['tanggal_start_proyek'] ?? null,
            'tanggal_deadline' => $validated['tanggal_deadline'] ?? null,
            'durasi_pengerjaan_bulan' => $validated['durasi_pengerjaan_bulan'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        $storageFolder = 'uploads/proyek/pr_' . $project->id_proyek;

        $existingAttachments = $project->attachment_file
            ? json_decode($project->attachment_file, true)
            : [];

        // ✅ Handle penggantian file
        if ($request->hasFile('replace_file')) {
            foreach ($request->file('replace_file') as $oldFileName => $newFile) {
                if (in_array($oldFileName, $existingAttachments)) {
                    // Hapus file lama
                    Storage::disk('public')->delete($storageFolder . '/' . $oldFileName);

                    // Simpan file baru
                    $newFileName = time() . '_' . uniqid() . '.' . $newFile->getClientOriginalExtension();
                    $newFile->storeAs($storageFolder, $newFileName, 'public');

                    // Update nama file lama di array
                    $key = array_search($oldFileName, $existingAttachments);
                    if ($key !== false) {
                        $existingAttachments[$key] = $newFileName;
                    }
                }
            }
        }

        // ✅ Handle file attachment baru
        if ($request->hasFile('attachment_file')) {
            foreach ($request->file('attachment_file') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($storageFolder, $filename, 'public');
                $existingAttachments[] = $filename;
            }
        }

        // ✅ Update attachment file
        $project->update([
            'attachment_file' => json_encode($existingAttachments),
        ]);

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }


    public function destroy(Proyek $project)
    {
        // Kalau ada file attachment, hapus juga dari storage/public
        // if (!empty($project->attachment_file)) {
        //     $attachments = json_decode($project->attachment_file, true);
        //     foreach ($attachments as $file) {
        //         $path = public_path('uploads/proyek/' . $file);
        //         if (file_exists($path)) {
        //             unlink($path);
        //         }
        //     }
        // }

        // $project->delete();

        $project->is_active = 'N';
        $project->save();


        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dihapus.');
    }
}
