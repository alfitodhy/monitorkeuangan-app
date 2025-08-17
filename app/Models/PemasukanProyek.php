<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemasukanProyek extends Model
{
    protected $table = 'tb_pemasukan_proyek';
    protected $primaryKey = 'id_pemasukan';
    public $timestamps = true;

    protected $fillable = [
        'id_proyek',
        'id_termin',
        'nama_klien',
        'tanggal_pemasukan',
        'jumlah',
        'metode_pembayaran',
        'termin_ke',
        'attachment_file',
        'keterangan',
    ];

     protected $casts = [
        'attachment_file' => 'array',
    ];



    // Relasi ke proyek
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek');
    }

    // Relasi ke termin
//     public function termin()
//     {
//         return $this->belongsTo(TerminProyek::class, 'id_termin');
//     }
// 


}
