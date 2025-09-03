<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddendumProyek extends Model
{
    use HasFactory;

    protected $table = 'tb_addendum_proyek';
    protected $primaryKey = 'id_addendum';

    protected $fillable = [
        'id_proyek',
        'nomor_addendum',
        'tanggal_addendum',
        'nilai_proyek_addendum',
        'estimasi_hpp_addendum',
        'tambahan_termin_addendum',
        'tambahan_termin_addendum',
        'durasi_addendum',
        'deskripsi_perubahan',
        'attachment_file',
    ];

    protected $casts = [
        'attachment_file' => 'array',
        'nilai_proyek_addendum' => 'string', // bigint jadi string biar aman
        'estimasi_hpp_addendum' => 'string', // bigint juga
    ];


    // Relasi ke proyek
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }
}
