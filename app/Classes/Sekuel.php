<?php

namespace App\Classes;
use DB;

class Sekuel{
    public function getAlergi($no_rawat){
        // tampilkan alergi di soap
        $alergi = '';
        // ambil riwayat alergi
        $riwayatalergi = DB::table('alergi_pasien as a')->select('r.no_rkm_medis', 'a.note')
            ->leftjoin('reg_periksa as r', 'r.no_rawat','=', 'a.no_rawat')
            ->where('r.no_rawat', $no_rawat)->get();
        if($riwayatalergi != null){
            foreach ($riwayatalergi as $key => $value) {
                $alergi .= $value->note;
            }
        }
        return $alergi;
    }
}

?>