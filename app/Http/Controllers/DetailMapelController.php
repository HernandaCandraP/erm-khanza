<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DetailMapel;
use App\MapelKelas;

class DetailMapelController extends Controller
{
    public function detailmapel(){
        $no = 1;
        
        $detailmapel = DetailMapel::join('mapel', 'detailmapel.id_mapel', '=', 'mapel.id_mapel')->select('detailmapel.*', 'mapel.nama_mapel')->get();
        return view('guru/detail_mapel', [
            'detailmapel' => $detailmapel, 
            'no' => $no
        ]);
    }
}
