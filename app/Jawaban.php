<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Jawaban extends Authenticatable
{
    use Notifiable;

    protected $table = "jawaban";
    protected $primaryKey = "id_jawaban";
    
    protected $fillable = [
        'id_siswa','id_soal', 'jawaban_siswa', 'stemming_jawaban_siswa', 'nilai_cosine', 'nilai_jaccard'
    ];
}
