<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BarcodeController extends Controller
{
    public function barcode(){
        
        $dokter = DB::table('dokter')->where('status', '1')->orderby('nm_dokter')->get();
        $poliklinik = DB::table('poliklinik')->where('status', '1')->get();

        return view('barcode/barcode', [
            'dokter' => $dokter, 
            'poliklinik' => $poliklinik, 
        ]);
    }

    function getpasien(Request $request){
        $detailpasien = DB::table('pasien as p')
        ->select('p.no_rkm_medis', 'p.nm_pasien', 'p.jk', 'p.tmp_lahir', 'p.tgl_lahir', 'p.alamat', 'p.pekerjaan', 'p.stts_nikah', 'p.agama', 'p.no_tlp', 'p.umur')
        ->where('p.no_rkm_medis', $request->norm)
        ->first();

        $riwayatperiksa = DB::table('reg_periksa as rp')
        ->select('rp.no_rawat', 'rp.tgl_registrasi', 'd.nm_dokter', 'p.nm_poli', 'rp.status_lanjut')
        ->leftjoin('dokter as d', 'd.kd_dokter', '=', 'rp.kd_dokter')
        ->leftjoin('poliklinik as p', 'p.kd_poli', '=', 'rp.kd_poli')
        ->where('rp.no_rkm_medis', $request->norm)
        ->get();
        
        // 122315	NY. ISTIQAMAH	3503054610690001	P	TRENGGALEK	1969-10-06	MURATUN/MARJI	RT/RW 014/004	-	PEDAGANG	JANDA	ISLAM	2020-07-11	085232627823	54 Th 11 Bl 19 Hr	SMP	ANAK	BAMBANG SUCIPTO	BPJ	0000665368323	1446	507	237	PETANI	RT/RW 014/004	GONDANG	TUGU	TRENGGALEK	-	5	11	1	-	-	267	JAWA TIMUR

        // dd($detailpasien);


        return response()->json(array('detailpasien'=> $detailpasien), 200);
    }

    function riwayatperiksa(Request $request){
        $riwayatperiksa = DB::table('reg_periksa as rp')
        ->select('rp.no_rawat', 'rp.tgl_registrasi', 'd.nm_dokter', 'p.nm_poli', 'rp.status_lanjut')
        ->leftjoin('dokter as d', 'd.kd_dokter', '=', 'rp.kd_dokter')
        ->leftjoin('poliklinik as p', 'p.kd_poli', '=', 'rp.kd_poli')
        ->where('rp.no_rkm_medis', $request->norm)
        ->orderby('rp.no_rawat', 'DESC')
        ->take(5)->get();

        return response()->json(array('riwayatperiksa'=> $riwayatperiksa), 200);
    }

    function simpanregistrasi(Request $request){
        // dd($request->all());
        $time = Carbon::now()->format('H:i:s');
        // ambil no rawat terakhir
        $newDate = date("Y/m/d", strtotime($request->tgl_registrasi));
        $norawatterakhir = DB::table('reg_periksa as rp')->select('rp.no_rawat')->where('no_rawat', 'like', '%' . $newDate . '%')
            ->orderby('rp.no_rawat', 'DESC')
            ->first();

        $pasien =  DB::table('pasien as p')->where('no_rkm_medis', $request->no_rkm_medis)->first();

        if($norawatterakhir){
            $norawatselanjutnya = substr($norawatterakhir->no_rawat, -6) + 1;
            $no_rawat = $newDate .'/'. sprintf("%06d", $norawatselanjutnya);
        }else{
            $no_rawat = $newDate .'/'. sprintf("%06d", 1);
        }
        
        $noreg = DB::table('reg_periksa as rp')->select('rp.no_reg')
        ->orderby('rp.no_rawat', 'DESC')
        ->where('rp.kd_poli', $request->poliklinik)
        ->first();

        $umur = Carbon::parse($pasien->tgl_lahir)->diff(Carbon::now())->format('%y');
        $regperiksa = [
            'no_reg' => $noreg->no_reg,
            'no_rawat' => $no_rawat,
            'tgl_registrasi' => $request->tgl_registrasi,
            'jam_reg' => $time,
            'kd_dokter' => $request->dokter,
            'no_rkm_medis' => $request->no_rkm_medis,
            'kd_poli' => $request->poliklinik,
            'p_jawab' => $pasien->namakeluarga,
            'almt_pj' => $pasien->alamatpj,
            'hubunganpj' => $pasien->keluarga,
            'biaya_reg' => 80000,
            'stts' => 'Belum',
            'stts_daftar' => 'Lama',
            'status_lanjut' => 'Ralan',
            'kd_pj' => $pasien->kd_pj,
            'umurdaftar' => $umur,
            'sttsumur' => 'Th',
            'status_bayar' => 'Belum Bayar',
            'status_poli' => 'Lama'
        ];
        // dd($regperiksa);
        DB::table('reg_periksa')->insert($regperiksa);

        $request->session()->flash('success', 'Berhasil simpan registrasi pasien');
        return redirect()->back();;
    }

    function cetakkartu(Request $request){
        $detailpasien = DB::table('pasien as p')
        ->select('p.no_rkm_medis', 'p.nm_pasien', 'p.jk', 'p.tmp_lahir', 'p.tgl_lahir', 'p.alamat', 'p.pekerjaan', 'p.stts_nikah', 'p.agama', 'p.no_tlp', 'p.umur')
        ->where('p.no_rkm_medis', $request->norm)
        ->first();
        
        // 122315	NY. ISTIQAMAH	3503054610690001	P	TRENGGALEK	1969-10-06	MURATUN/MARJI	RT/RW 014/004	-	PEDAGANG	JANDA	ISLAM	2020-07-11	085232627823	54 Th 11 Bl 19 Hr	SMP	ANAK	BAMBANG SUCIPTO	BPJ	0000665368323	1446	507	237	PETANI	RT/RW 014/004	GONDANG	TUGU	TRENGGALEK	-	5	11	1	-	-	267	JAWA TIMUR

        // dd($detailpasien);

        return view('barcode/cetakkartu', [
            // 'presensi' => $presensi, 
            // 'rekappresensi' => $rekappresensi, 
        ]);
    }
}