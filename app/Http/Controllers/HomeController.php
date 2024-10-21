<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function mutasi()
    {
        $username = Session::get('username');
        $month = date('Y-m'); //'2023-06'; // 
        $date = date('Y-m-d'); //'2023-06-15'; // 
          

        $antrean = DB::table('reg_periksa as a')->select('a.no_rawat', 'b.no_rkm_medis', 'b.nm_pasien', 'mb.status', 'mb.dikirim', 'mb.diterima','pr.jam_rawat', 'pr.tgl_perawatan', 'ro.jam', 'ro.jam_penyerahan', 'ro.jam_peresepan')
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->leftjoin('mutasi_berkas as mb', 'mb.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('pemeriksaan_ralan as pr', 'pr.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('resep_obat as ro', 'ro.no_rawat', '=','pr.no_rawat')
            ->where('a.tgl_registrasi', $date)
            ->where('mb.dikirim', '!=', '0000-00-00 00:00:00')
            ->orderby('a.jam_reg', 'ASC')->get();

        // dd($pasienranap);


        return view('petugas/antrean-edit', [
            'antrean' => $antrean, 
        ]);
        // return redirect()->back();
        // return view('home');
    }
    
    public function updateantrean(Request $request)
    {
        $jarakwaktuakhir = 1000; //20mnt
        $jarakwaktuawal = 480; //8mnt
        $getdatetime = date('Y-m-d H:i:s');
        
        // $inttime = strtotime($getdatetime);
        // $inttime = strtotime('2012-03-27 18:47:00');

        // $asli = date("Y-m-d H:i:s",$inttime);
        // $stringtime = date("Y-m-d H:i:s",($inttime - $jarakwaktuakhir)); 
        // $awal = date("Y-m-d H:i:s",($inttime - $jarakwaktuawal));
        // $random= mt_rand(($inttime - $jarakwaktuakhir),($inttime - $jarakwaktuawal));
        // $stringrandom = date("Y-m-d H:i:s",$random);

        // $random2= mt_rand(($random - $jarakwaktuakhir),($random - $jarakwaktuawal));
        // $stringrandom2 = date("Y-m-d H:i:s",$random2);

        $antrean = DB::table('mutasi_berkas')->where('no_rawat', $request->no_rawat)->first();
        
        $waktuantreanawal = $antrean->dikirim;
        $inttime = strtotime($waktuantreanawal);
        $random= mt_rand(($inttime + $jarakwaktuawal),($inttime + $jarakwaktuakhir));
        $stringrandom = date("Y-m-d H:i:s",$random);

        $waktuantreanawal4 = $antrean->diterima;
        $inttime4 = strtotime($waktuantreanawal4);
        $random4= mt_rand(($inttime4 + $jarakwaktuawal),($inttime4 + $jarakwaktuakhir));
        $stringrandom4 = date("Y-m-d H:i:s",$random);

        // dd($waktuantreanawal);
        // dd($request->all());
        // dd($asli, $awal, $stringtime, $stringrandom);

        $tanggalawal = $request->tgl_perawatan;
        $jamawal = $request->jam_rawat;
        $tanggal = date('Y-m-d', $random4);
        $jam = date('H:i:s', $random4);

        // dd($jamawal, $tanggalawal, $jam, $tanggal);

        if($request->status == 'taskid4'){
            // dd("task id 4");
            DB::table('mutasi_berkas')->where('no_rawat', $request->no_rawat)->update(['diterima' => $stringrandom, 'status'=>'Sudah Diterima']);
        }elseif($request->status == 'taskid5'){
            // dd("task id 5");
            DB::table('pemeriksaan_ralan')->where([['no_rawat','=', $request->no_rawat], ['tgl_perawatan', '=',$tanggalawal], ['jam_rawat', '=',$jamawal]])->update(['tgl_perawatan' => $tanggal, 'jam_rawat' => $jam]);
            // dd($hasil);
        }
        return redirect()->back();


// select pr.no_rawat, pr.jam_rawat, ro.jam, ro.jam_penyerahan, ro.jam_peresepan from resep_obat ro
// join pemeriksaan_ralan pr on pr.no_rawat = ro.no_rawat where ro.no_rawat LIKE '2024/09/19/%' AND ro.jam < pr.jam_rawat limit 60;
    }
}
