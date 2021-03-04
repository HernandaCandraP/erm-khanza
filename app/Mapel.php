<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mapel extends Authenticatable
{
    use Notifiable;

    protected $table = "mapel";
    protected $primaryKey = "id_mapel";
    
    protected $fillable = [
        'nama_mapel', 'keterangan',
    ];

    public function getdetailmapel(){
        return $this->hasMany(
            'App\DetailMapel',
            'id_mapel'
        );
    }
}
