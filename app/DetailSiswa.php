<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; 

class DetailSiswa extends Authenticatable
{
    use Notifiable;

    protected $table = "detailsiswa";
    protected $primaryKey = "id_detailsiswa";
    
    protected $fillable = [
        'id_detail', 'id_siswa', 'is_end',
    ];
}
