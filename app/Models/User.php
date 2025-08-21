<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'tb_user';
    protected $primaryKey = 'id_user';
    public $timestamps = true;

    public $incrementing = true; // penting biar sessions keisi
    protected $keyType = 'int';  // integer PK

    protected $fillable = [
        'nama_lengkap',
        'username',
        'password',
        'email',
        'no_telp',
        'foto_profil',
        'role',
        'status_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
