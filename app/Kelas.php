<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Kelas extends Authenticatable
{
    use Notifiable;

    protected $table = "kelas";
    protected $primaryKey = "id_kelas";
    
    protected $fillable = [
        'nama_kelas', 'keterangan',
    ];
}
