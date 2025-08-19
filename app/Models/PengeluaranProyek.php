<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranProyek extends Model
{
    protected $table = 'tb_pengeluaran_proyek';
    protected $primaryKey = 'id_pengeluaran';
    public $timestamps = true;

    protected $fillable = [
        'id_proyek',
        'nama_proyek',
        'id_vendor',
        'nama_vendor',
        'rekening',
        'tanggal_pengeluaran',
        'jumlah',
        'status',
        'file_nota',
        'file_buktitf',
        'keterangan',
        'catatan_bod',
        'user_created',
    ];

    protected $casts = [
        'rekening' => 'array',
        'file_nota' => 'array',
        // 'file_buktitf' => 'array',
    ];
}
