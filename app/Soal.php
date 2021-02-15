<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Soal extends Authenticatable
{
    use Notifiable;

    protected $table = "soal";
    protected $primaryKey = "id_soal";
    
    protected $fillable = [
        'id_detail','soal', 'jawaban', 'stemming', 'id_guru'
    ];
}
