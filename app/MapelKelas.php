<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class MapelKelas extends Authenticatable
{
    use Notifiable;

    protected $table = "mapelkelas";
    protected $primaryKey = "id_mapelkelas";
    
    protected $fillable = [
        'id_detail', 'id_kelas',
    ];
}
