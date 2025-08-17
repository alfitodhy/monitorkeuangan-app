<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // gunakan ini
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable // extend ke Authenticatable, bukan Model biasa
{
    use Notifiable;

    protected $table = 'tb_user';
    protected $primaryKey = 'id_user';
    public $timestamps = true;

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

    public function getRouteKeyName()
    {
        return 'id_user';
    }
}
