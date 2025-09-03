<?php

namespace App\Http\Controllers;

use App\Models\AddendumProyek;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddendumController extends Controller
{
    /**
     * List semua addendum untuk 1 proyek.
     */
    public function index($idProyek)
    {
        $addendums = AddendumProyek::where('id_proyek', $idProyek)->get();

        return response()->json($addendums);
    }

    public function store2(Request $request, $id_proyek)
    {
        $request->validate([
            'nomor_addendum' => 'required|string|max:100',
            'tanggal_addendum' => 'required|date',
            'nilai_proyek_addendum' => 'required|numeric',
            'estimasi_hpp_addendum' => 'required|numeric',
            'tambahan_termin_addendum' => 'nullable|integer',
            'durasi_addendum' => 'nullable|integer',
            'deskripsi_perubahan' => 'nullable|string',
            'attachment_file.*' => 'nullable|file|max:2048', // max 2MB per file
        ]);

        $path = "uploads/proyek/pr_{$id_proyek}/addendum";
        $attachments = [];

        if ($request->hasFile('attachment_file')) {
            foreach ($request->file('attachment_file') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs("public/{$path}", $filename);
                $attachments[] = $filename;
            }
        }

        $addendum =   AddendumProyek::create([
            'id_proyek' => $id_proyek,
            'nomor_addendum' => $request->nomor_addendum,
            'tanggal_addendum' => $request->tanggal_addendum,
            'nilai_proyek_addendum' => $request->nilai_proyek_addendum,
            'estimasi_hpp_addendum' => $request->estimasi_hpp_addendum,
            'tambahan_termin_addendum' => $request->termin_addendum,
            'durasi_addendum' => $request->durasi_addendum,
            'deskripsi_perubahan' => $request->deskripsi_perubahan,
            'attachment_file' => $attachments, // otomatis ke JSON
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Addendum berhasil ditambahkan.',
            'data' => $addendum
        ]);
    }


    public function store(Request $request, $id_proyek)
    {
        $request->validate([
            'nomor_addendum' => 'required|string|max:100',
            'tanggal_addendum' => 'required|date',
            'nilai_proyek_addendum' => 'required|numeric',
            'estimasi_hpp_addendum' => 'required|numeric',
            'tambahan_termin_addendum' => 'nullable|integer',
            'durasi_addendum' => 'nullable|integer',
            'deskripsi_perubahan' => 'nullable|string',
            'attachment_file.*' => 'nullable|file|max:2048',
            'termins' => 'nullable|array',
            'termins.*.tanggal_jatuh_tempo' => 'nullable|date',
            'termins.*.jumlah' => 'nullable|numeric',
            'termins.*.keterangan' => 'nullable|string',
        ]);

        //  validasi total jumlah termin addendum harus sama dengan nilai proyek
        if ($request->has('termins')) {
            $totalJumlah = collect($request->termins)->sum(function ($termin) {
                return $termin['jumlah'] ?? 0;
            });

            if ($totalJumlah != $request->nilai_proyek_addendum) {
                return response()->json([
                    'success' => false,
                    'message' => "Total jumlah termin addendum ({$totalJumlah}) harus sama dengan nilai proyek addendum ({$request->nilai_proyek_addendum})."
                ], 422);
            }
        }

        $path = "uploads/proyek/pr_{$id_proyek}/addendum";
        $attachments = [];

        if ($request->hasFile('attachment_file')) {
            foreach ($request->file('attachment_file') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs("public/{$path}", $filename);
                $attachments[] = $filename;
            }
        }

        // simpan addendum
        $addendum = AddendumProyek::create([
            'id_proyek' => $id_proyek,
            'nomor_addendum' => $request->nomor_addendum,
            'tanggal_addendum' => $request->tanggal_addendum,
            'nilai_proyek_addendum' => $request->nilai_proyek_addendum,
            'estimasi_hpp_addendum' => $request->estimasi_hpp_addendum,
            'tambahan_termin_addendum' => $request->tambahan_termin_addendum,
            'durasi_addendum' => $request->durasi_addendum,
            'deskripsi_perubahan' => $request->deskripsi_perubahan,
            'attachment_file' => $attachments,
        ]);

        // ambil termin terakhir dari proyek ini
        $lastTermin = DB::table('tb_termin_proyek')
            ->where('id_proyek', $id_proyek)
            ->max('termin_ke');

        $startTermin = $lastTermin ? $lastTermin + 1 : 1;

        // simpan termin baru dari form
        if ($request->has('termins')) {
            $i = 0;
            foreach ($request->termins as $termin) {
                DB::table('tb_termin_proyek')->insert([
                    'id_proyek'   => $id_proyek,
                    'termin_ke'   => $startTermin + $i,
                    'tanggal_jatuh_tempo' => $termin['tanggal_jatuh_tempo'] ?? null,
                    'jumlah'      => $termin['jumlah'] ?? null,
                    'kategori_termin'  => "termin addendum",
                    'keterangan'  => $termin['keterangan'] ?? null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $i++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Addendum berhasil ditambahkan.',
            'data' => $addendum
        ]);
    }



    /**
     * Ambil detail 1 addendum.
     */
    public function show($idProyek, $idAddendum)
    {
        $addendum = AddendumProyek::where('id_proyek', $idProyek)
            ->where('id_addendum', $idAddendum)
            ->firstOrFail();

        return response()->json($addendum);
    }

    /**
     * Update addendum.
     */
    public function update(Request $request, $idProyek, $idAddendum)
    {
        $addendum = AddendumProyek::where('id_proyek', $idProyek)
            ->where('id_addendum', $idAddendum)
            ->firstOrFail();

        $validated = $request->validate([
            'nomor_addendum' => 'sometimes|required|string|max:100',
            'tanggal_addendum' => 'sometimes|required|date',
            'nilai_proyek_addendum' => 'sometimes|required|numeric',
            'estimasi_hpp_addendum' => 'nullable|numeric',
            'termin_addendum' => 'nullable|integer',
            'durasi_addendum' => 'nullable|integer',
            'deskripsi_perubahan' => 'nullable|string',
            'attachment_file' => 'nullable|array',
        ]);

        $addendum->update($validated);

        return response()->json([
            'message' => 'Addendum berhasil diperbarui',
            'addendum' => $addendum
        ]);
    }

    /**
     * Hapus addendum.
     */
    public function destroy($idProyek, $idAddendum)
    {
        $addendum = AddendumProyek::where('id_proyek', $idProyek)
            ->where('id_addendum', $idAddendum)
            ->firstOrFail();

        $addendum->delete();

        return response()->json([
            'success' => true,
            'message' => 'Addendum berhasil dihapus'
        ]);
    }
}
