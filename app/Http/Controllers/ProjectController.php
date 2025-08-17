<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $data = Proyek::where('is_active', 'Y')->latest()->get();
        return view('Projects.index', compact('data'));
    }


    public function create()
    {
        return view('Projects.create');
    }


    public function store(Request $request)
    {
        // Hilangkan titik dari nilai_proyek
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




    public function show($id)
    {
        $project = Proyek::findOrFail($id);
        return view('projects.show', compact('project'));
    }

    public function edit($id)
    {
        // Cari proyek berdasarkan id, kalau tidak ditemukan 404 otomatis
        $project = Proyek::findOrFail($id);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Proyek $project)
    {
        $validated = $request->validate([
            'kode_proyek' => 'required|string|unique:tb_proyek,kode_proyek,' . $project->id_proyek . ',id_proyek',
            'nama_proyek' => 'required|string|unique:tb_proyek,nama_proyek,' . $project->id_proyek . ',id_proyek',
            // field lain tetap sama...
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


        // Bersihkan nilai proyek dan estimasi hpp
        $validated['nilai_proyek'] = (int) str_replace('.', '', $validated['nilai_proyek']);
        if (!empty($validated['estimasi_hpp'])) {
            $validated['estimasi_hpp'] = (float) str_replace('%', '', $validated['estimasi_hpp']);
        }

        // Update data utama proyek
        $project->update([
            'kode_proyek' => $validated['kode_proyek'],
            'nama_proyek' => $validated['nama_proyek'],
            'nama_klien' => $validated['nama_klien'],
            'nilai_proyek' => $validated['nilai_proyek'],
            'estimasi_hpp' => $validated['estimasi_hpp'] ?? null,
            'termin' => $validated['termin'] ?? null,
            'tipe_proyek' => $validated['tipe_proyek'],
            'tipe_lainnya' => $validated['tipe_lainnya'] ?? null,
            'tanggal_start_proyek' => $validated['tanggal_start_proyek'] ?? null,
            'tanggal_deadline' => $validated['tanggal_deadline'] ?? null,
            'durasi_pengerjaan_bulan' => $validated['durasi_pengerjaan_bulan'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        // Folder proyek khusus
       $storageFolder = 'uploads/proyek/pr_' . $project->id_proyek;

    // Ambil array lampiran lama
    $existingAttachments = $project->attachment_file ? json_decode($project->attachment_file, true) : [];

    // Handle file replace
    if ($request->has('replace_file')) {
        foreach ($request->file('replace_file') as $oldFileName => $newFile) {
            if (in_array($oldFileName, $existingAttachments)) {
                // Hapus file lama dari storage
                if (Storage::disk('public')->exists($storageFolder . '/' . $oldFileName)) {
                    Storage::disk('public')->delete($storageFolder . '/' . $oldFileName);
                }

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

    // Handle upload file baru (tambahan, bukan replace)
    if ($request->hasFile('attachment_file')) {
        foreach ($request->file('attachment_file') as $file) {
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($storageFolder, $filename, 'public');
            $existingAttachments[] = $filename;
        }
    }

    // Simpan ulang JSON lampiran
    $project->attachment_file = json_encode($existingAttachments);
    $project->save();

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
