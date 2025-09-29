<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use HasFactory;

    protected $table = 'tb_proyek';

    protected $primaryKey = 'id_proyek';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'kode_proyek',
        'nama_proyek',
        'nama_klien',
        'attachment_file',
        'status_proyek',
        'nilai_proyek',
        'termin',
        'estimasi_hpp',
        'tipe_proyek',
        'tanggal_start_proyek',
        'tanggal_deadline',
        'durasi_pengerjaan_bulan',
        'lokasi_proyek',
        'keterangan',
    ];
}
