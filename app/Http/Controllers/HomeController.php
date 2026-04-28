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

    public function mutasi(Request $request)
    {
        $username = Session::get('username');
        $month = date('Y-m'); // '2026-03'; // 
        $date = date('Y-m-d'); //'2026-03-09'; // 
          

        $antrean = DB::table('reg_periksa as a')->select('a.no_rawat', 'b.no_rkm_medis', 'b.nm_pasien', 'mb.status', 'mb.dikirim', 'mb.diterima','pr.jam_rawat', 'pr.tgl_perawatan', 'ro.tgl_perawatan as tgl_kirim_obat', 'ro.tgl_penyerahan as tgl_serah_obat','ro.jam', 'ro.jam_penyerahan', 'ro.jam_peresepan')
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->leftjoin('mutasi_berkas as mb', 'mb.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('pemeriksaan_ralan as pr', 'pr.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('resep_obat as ro', 'ro.no_rawat', '=','pr.no_rawat')
            ->where('a.tgl_registrasi', $date)
            ->whereNotIn('a.kd_poli', ['IGDK'])
            ->where('mb.dikirim', '!=', '0000-00-00 00:00:00')
            ->orderby('a.jam_reg', 'ASC')->get();

        // dd($antrean);
        foreach ($antrean as $key => $value) {
            if ($value->diterima <= $value->dikirim || $value->diterima == '0000-00-00 00:00:00'){
                $request->merge(['status' => 'taskid4', 
                    'no_rawat' => $value->no_rawat, 
                    'jam_awal' => $value->diterima, 
                    'jam_pembanding' => $value->dikirim
                ]);
                $this->updateantrean($request);
            }
            else if ($value->jam_rawat == null ){
                // dd( $value->no_rawat, $value->jam_rawat, $value->dikirim ,'jam rawat null', $antrean);
                
                // tidak menjalankan fungsi karena pemeriksaan soap masih kosong
            }
            else if($value->tgl_perawatan." ". $value->jam_rawat <= $value->diterima){
                $request->merge(['status' => "taskid5",
                    'no_rawat' => $value->no_rawat, 
                    'jam_awal' => $value->tgl_perawatan." ". $value->jam_rawat, 
                    'jam_pembanding' => $value->diterima
                ]);
                $this->updateantrean($request);
            }
            else if($value->tgl_kirim_obat." ". $value->jam <= $value->tgl_perawatan." ". $value->jam_rawat && $value->jam != '00:00:00'){
                $request->merge(['status' => "taskid6",
                    'no_rawat' => $value->no_rawat, 
                    'jam_awal' => $value->tgl_kirim_obat." ". $value->jam, 
                    'jam_pembanding' => $value->tgl_perawatan." ". $value->jam_rawat
                ]);
                $this->updateantrean($request);
            }
            else if($value->tgl_serah_obat." ". $value->jam_penyerahan <= $value->tgl_kirim_obat." ". $value->jam && $value->jam_penyerahan != '00:00:00'){
                $request->merge(['status' => "taskid7",
                    'no_rawat' => $value->no_rawat, 
                    'jam_awal' => $value->tgl_serah_obat." ". $value->jam_penyerahan, 
                    'jam_pembanding' => $value->tgl_kirim_obat." ". $value->jam
                ]);
                $this->updateantrean($request);
            }
        }
        // dd($antrean);


        return view('petugas/antrean-edit', [
            'antrean' => $antrean, 
        ]);
        // return redirect()->back();
        // return view('home');
    }
    
    public function updateantrean(Request $request)
    {
        $jarakwaktuakhir = 720; //12mnt
        $jarakwaktuawal = 360; //6mnt
        // $getdatetime = date('Y-m-d H:i:s');
        
        // $inttime = strtotime($getdatetime);
        // $inttime = strtotime('2012-03-27 18:47:00');

        // $asli = date("Y-m-d H:i:s",$inttime);
        // $stringtime = date("Y-m-d H:i:s",($inttime - $jarakwaktuakhir)); 
        // $awal = date("Y-m-d H:i:s",($inttime - $jarakwaktuawal));
        // $random= mt_rand(($inttime - $jarakwaktuakhir),($inttime - $jarakwaktuawal));
        // $stringrandom = date("Y-m-d H:i:s",$random);

        // $random2= mt_rand(($random - $jarakwaktuakhir),($random - $jarakwaktuawal));
        // $stringrandom2 = date("Y-m-d H:i:s",$random2);

        $inttime = strtotime($request->jam_pembanding);
        $random= mt_rand(($inttime + $jarakwaktuawal),($inttime + $jarakwaktuakhir));
        $stringrandom = date("Y-m-d H:i:s",$random);
        $jamakhir = date("H:i:s",$random);
        $tglakhir = date("Y-m-d",$random);

        // ambil jam dan tanggal awal
        $jamawal= date("H:i:s", strtotime($request->jam_awal));
        $tglawal= date("Y-m-d", strtotime($request->jam_awal));

        if($request->status == 'taskid4'){
            // dd("task id 4");
            DB::table('mutasi_berkas')->where('no_rawat', $request->no_rawat)->update(['diterima' => $stringrandom, 'status'=>'Sudah Diterima']);
        }elseif($request->status == 'taskid5'){
            // dd("task id 5");
            DB::table('pemeriksaan_ralan')->where([['no_rawat','=', $request->no_rawat], ['tgl_perawatan', '=',$tglawal], ['jam_rawat', '=',$jamawal]])->update(['tgl_perawatan' => $tglakhir, 'jam_rawat' => $jamakhir]);
        }elseif($request->status == 'taskid6'){
            // dd("task id 6");
            DB::table('resep_obat')->where([['no_rawat','=', $request->no_rawat], ['tgl_perawatan', '=',$tglawal], ['jam', '=',$jamawal]])->update(['jam' => $jamakhir]);
            DB::table('aturan_pakai')->where([['no_rawat','=', $request->no_rawat], ['tgl_perawatan', '=',$tglawal], ['jam', '=',$jamawal]])->update(['jam' => $jamakhir]);
            DB::table('detail_pemberian_obat')->where([['no_rawat','=', $request->no_rawat], ['tgl_perawatan', '=',$tglawal], ['jam', '=',$jamawal]])->update(['jam' => $jamakhir]);
        }elseif($request->status == 'taskid7'){
            // dd("task id 7");
            DB::table('resep_obat')->where([['no_rawat','=', $request->no_rawat], ['tgl_perawatan', '=',$tglawal], ['jam_penyerahan', '=',$jamawal]])->update(['jam_penyerahan' => $jamakhir]);
        }
        // return redirect()->back();
    }
}
