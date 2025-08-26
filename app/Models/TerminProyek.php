<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TerminProyek extends Model
{
    protected $table = 'tb_termin_proyek';
    protected $primaryKey = 'id_termin';
    protected $fillable = [
        'id_proyek',
        'termin_ke',
        'tanggal_jatuh_tempo',
        'jumlah',
        'status_pembayaran',
        'tanggal_lunas',
        'keterangan',
    ];
    public $timestamps = false;
}
