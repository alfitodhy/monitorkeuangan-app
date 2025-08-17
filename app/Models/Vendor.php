<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'tb_vendor';
    protected $primaryKey = 'id_vendor';

    protected $fillable = [
        'kode_vendor',
        'nama_vendor',
        'no_telp',
        'email',
        'alamat',
        'jenis_vendor',
        'spesialisasi',
        'rekening',
        'keterangan',
    ];

    protected $casts = [
        'rekening' => 'array', // Laravel otomatis decode/encode JSON
    ];
    
}
