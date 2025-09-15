<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\TerminProyek;
use App\Models\AddendumProyek;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

use Carbon\Carbon;

class ProjectController extends Controller
{
    public function index()
    {
        $proyek = Proyek::where('is_active', 'Y')->latest()->get();
        return view('projects.index', compact('proyek'));
    }

    public function datatable(Request $request)
    {
        $query = Proyek::query();

        // Search custom
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('kode_proyek', 'like', "%{$search}%")
                    ->orWhere('nama_proyek', 'like', "%{$search}%")
                    ->orWhere('nama_klien', 'like', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addIndexColumn() // DT_RowIndex
            ->addColumn('aksi', function ($row) {
                $btn  = '<a href="' . route('projects.show', $row->id_proyek) . '" class="btn btn-xs btn-primary mr-1">Detail</a>';
                if ($row->status_proyek === 'process') {
                    $btn .= '<a href="' . route('projects.edit', $row->id_proyek) . '" class="btn btn-xs btn-warning mr-1">Edit</a>';
                }
                if ($row->status_proyek === 'progress') {
                    $btn .= '<button type="button" onclick="openAddendumModal(' . $row->id_proyek . ', \'' . $row->nama_proyek . '\')" class="btn btn-xs btn-secondary mr-1">Addendum</button>';
                }
                $btn .= '<form action="' . route('projects.destroy', $row->id_proyek) . '" method="POST" class="inline">@csrf @method("DELETE")<button type="submit" class="btn btn-xs btn-error">Hapus</button></form>';
                return $btn;
            })
            ->editColumn('nilai_proyek', function ($row) {
                return 'Rp ' . number_format($row->nilai_proyek, 0, ',', '.');
            })
            ->editColumn('estimasi_hpp', function ($row) {
                return $row->estimasi_hpp !== null ? number_format($row->estimasi_hpp, 0, ',', '.') . ' %' : '-';
            })
            ->editColumn('tipe_proyek', function ($row) {
                return '<span class="badge badge-info">' . $row->tipe_proyek . '</span>';
            })
            ->editColumn('status_proyek', function ($row) {
                $statusColors = [
                    'process' => 'badge badge-outline',
                    'progress' => 'badge badge-warning',
                    'completed' => 'badge badge-success',
                    'canceled' => 'badge badge-error'
                ];
                $colorClass = $statusColors[$row->status_proyek] ?? 'badge';
                return '<span class="' . $colorClass . '">' . ucfirst($row->status_proyek ?: '-') . '</span>';
            })
            ->rawColumns(['aksi', 'tipe_proyek', 'status_proyek'])
            ->make(true);
    }

    public function create()
    {
        return view('projects.create');
    }





    public function store(Request $request)
    {
        $request->merge([
            'nilai_proyek' => preg_replace('/[^\d]/', '', $request->input('nilai_proyek', '')),
            'estimasi_hpp' => preg_replace('/[^\d]/', '', $request->input('estimasi_hpp', '')),
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
            'estimasi_hpp' => 'required|numeric|min:0|max:100',
            'termin' => 'nullable|integer|min:1',
            'tipe_proyek' => 'required|string',
            'tipe_lainnya' => 'nullable|string',
            'tanggal_start_proyek' => 'nullable|date',
            'tanggal_deadline' => 'nullable|date|after_or_equal:tanggal_start_proyek',
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
                    'keterangan' => $termin['keterangan'] ?? '',
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
        $project = Proyek::findOrFail($id_proyek);

        $termins = TerminProyek::where('id_proyek', $id_proyek)->get();

        return view('projects.edit', compact('project', 'termins'));
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

        // Bersihkan angka & persen
        $validated['nilai_proyek'] = (int) str_replace('.', '', $validated['nilai_proyek']);
        $validated['estimasi_hpp'] = !empty($validated['estimasi_hpp'])
            ? (float) str_replace('%', '', $validated['estimasi_hpp'])
            : null;

        // 🔹 Validasi total termin
        if ($request->has('termins')) {
            $totalTermin = 0;
            foreach ($request->termins as $terminData) {
                $jumlah = isset($terminData['jumlah'])
                    ? (int) preg_replace('/[^0-9]/', '', $terminData['jumlah'])
                    : 0;
                $totalTermin += $jumlah;
            }

            if ($totalTermin !== $validated['nilai_proyek']) {
                return back()
                    ->withInput()
                    ->withErrors(['termins' => 'Total jumlah termin harus sama dengan Nilai Proyek (Rp ' . number_format($validated['nilai_proyek'], 0, ',', '.') . ').']);
            }
        }

        // 🔹 Update data proyek
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
            'keterangan' => $validated['keterangan'] ?? '',
        ]);

        // 🔹 Handle Attachment (tetap sama seperti punyamu)
        $storageFolder = 'uploads/proyek/pr_' . $project->id_proyek;
        $existingAttachments = $project->attachment_file
            ? json_decode($project->attachment_file, true)
            : [];

        if ($request->hasFile('replace_file')) {
            foreach ($request->file('replace_file') as $oldFileName => $newFile) {
                if (in_array($oldFileName, $existingAttachments)) {
                    Storage::disk('public')->delete($storageFolder . '/' . $oldFileName);

                    $newFileName = time() . '_' . uniqid() . '.' . $newFile->getClientOriginalExtension();
                    $newFile->storeAs($storageFolder, $newFileName, 'public');

                    $key = array_search($oldFileName, $existingAttachments);
                    if ($key !== false) {
                        $existingAttachments[$key] = $newFileName;
                    }
                }
            }
        }

        if ($request->hasFile('attachment_file')) {
            foreach ($request->file('attachment_file') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($storageFolder, $filename, 'public');
                $existingAttachments[] = $filename;
            }
        }

        $project->update([
            'attachment_file' => json_encode($existingAttachments),
        ]);

        // 🔹 Handle Termin Proyek
        if ($request->has('termins')) {
            foreach ($request->termins as $terminData) {
                $jumlah = isset($terminData['jumlah'])
                    ? (int) preg_replace('/[^0-9]/', '', $terminData['jumlah'])
                    : 0;

                if (!empty($terminData['id_termin'])) {
                    $termin = TerminProyek::find($terminData['id_termin']);
                    if ($termin) {
                        $termin->update([
                            'termin_ke' => $terminData['termin_ke'],
                            'jumlah' => $jumlah,
                            'tanggal_jatuh_tempo' => $terminData['tanggal_jatuh_tempo'] ?? null,
                            'keterangan' => $terminData['keterangan'] ?? null,
                        ]);
                    }
                } else {
                    TerminProyek::create([
                        'id_proyek' => $project->id_proyek,
                        'termin_ke' => $terminData['termin_ke'],
                        'jumlah' => $jumlah,
                        'tanggal_jatuh_tempo' => $terminData['tanggal_jatuh_tempo'] ?? null,
                        'keterangan' => $terminData['keterangan'] ?? null,
                    ]);
                }
            }
        }

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
