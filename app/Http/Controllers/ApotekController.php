<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Pegawai;
use App\Rules\MatchOldPasswordPegawai;
use Session;

use Auth;

class ApotekController extends Controller
{
    public function index(){
        $pegawai = "hello";

        return view('pegawai/home', [
            // 'detailmapel' => $detailmapel, 
            // 'totalkelas' => $totalkelas, 
            // 'totalsiswa' => $totalsiswa, 
            // 'totalmapel' => $totalmapel,
            // 'pengujian_cosine' => $pengujian_cosine,
            // 'pengujian_jaccard' => $pengujian_jaccard,
            'pegawai' => $pegawai, 
        ]);
    }

    public function apotek(){
        $pasienranap = DB::table('kamar_inap as ki')->where('ki.stts_pulang', '-')->select('ki.*', 'p.no_rkm_medis', 'p.nm_pasien', 'b.nm_bangsal', 'rg.no_rawat2', 'p2.no_rkm_medis as no_rkm_medis2', 'p2.nm_pasien as nm_pasien2')
            ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'ki.no_rawat')
            ->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->leftjoin('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
            ->leftjoin('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
            ->leftjoin('dpjp_ranap as dr', 'dr.no_rawat', '=', 'ki.no_rawat')
            ->leftjoin('ranap_gabung as rg', 'rg.no_rawat', '=', 'ki.no_rawat')
            ->leftjoin('reg_periksa as rp2', 'rp2.no_rawat', '=', 'rg.no_rawat2')
            ->leftjoin('pasien as p2', 'p2.no_rkm_medis', '=', 'rp2.no_rkm_medis')
            ->get();
        
        return view('apotek/home', [
            'pasienranap' => $pasienranap, 
        ]);
    }
}
