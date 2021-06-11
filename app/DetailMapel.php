<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; 

class DetailMapel extends Authenticatable
{
    use Notifiable;

    protected $table = "detailmapel";
    protected $primaryKey = "id_detail";
    
    protected $fillable = [
        'id_mapel', 'keterangan', 'waktu_mulai', 'waktu_selesai', 'waktu_test',
    ];

    public function kelas(){
        return $this->belongsToMany(
            'App\Kelas',
            'mapelkelas',
            'id_detail' , 'id_kelas'
        )->orderBy('nama_kelas');
    }
}
