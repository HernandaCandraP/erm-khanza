<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengisianSimrsController extends Controller
{
    public function ermigd(Request $request){
        if($request->tanggal != null){
            $date = date("Y-m-d", strtotime(substr($request->tanggal, 0, 10))); // date('Y-m-d'); //
            $enddate = date("Y-m-d", strtotime(substr($request->tanggal, -10)));
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }else{
            $start = new Carbon('first day of this month');
            $end = new Carbon('last day of this month');
            
            $date = date('Y-m-d');
            $enddate = date('Y-m-d');
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }

        $listpasien = DB::table('reg_periksa as a')
            ->select('a.*', 'p.nm_poli', 'b.nm_pasien', 'b.no_rkm_medis', 'c.nm_dokter', 'dti.no_rawat as triase', 'paki.no_rawat as awalkeperawatanigd', 
            'pmi.no_rawat as awalmedisigd', 'coi.no_rawat as observasiigd', 'ppa.no_rawat as pews', 'ppd.no_rawat as ews', 'ppn.no_rawat as ewsneonatus',
            'hpe.no_rawat as ekg', 'ppt.no_rawat as persetujuanpenolakan', 'jatuhdewasa.no_rawat as jatuhdewasa', 'jatuhanak.no_rawat as jatuhanak',
            'terminal.no_rawat as terminal', 'resep.no_rawat as resep', 'transfer.no_rawat as transfer', 'resume.no_rawat as resume')
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->leftjoin('dokter as c', 'a.kd_dokter', '=', 'c.kd_dokter')
            ->leftjoin('poliklinik as p', 'a.kd_poli', '=', 'p.kd_poli')
            ->leftjoin('data_triase_igd as dti', 'dti.no_rawat','=', 'a.no_rawat')
            ->leftjoin('penilaian_awal_keperawatan_igd as paki', 'paki.no_rawat','=', 'a.no_rawat')
            ->leftjoin('penilaian_medis_igd as pmi', 'pmi.no_rawat','=', 'a.no_rawat')
            ->leftjoin('catatan_observasi_igd as coi', 'coi.no_rawat','=', 'a.no_rawat')
            ->leftjoin('pemantauan_pews_anak as ppa', 'ppa.no_rawat','=', 'a.no_rawat')
            ->leftjoin('pemantauan_pews_dewasa as ppd', 'ppd.no_rawat','=', 'a.no_rawat')
            ->leftjoin('pemantauan_ews_neonatus as ppn', 'ppn.no_rawat','=', 'a.no_rawat')
            ->leftjoin('hasil_pemeriksaan_ekg as hpe', 'hpe.no_rawat','=', 'a.no_rawat')
            ->leftjoin('persetujuan_penolakan_tindakan as ppt', 'ppt.no_rawat','=', 'a.no_rawat')
            ->leftjoin('penilaian_lanjutan_resiko_jatuh_dewasa as jatuhdewasa', 'jatuhdewasa.no_rawat','=', 'a.no_rawat')
            ->leftjoin('penilaian_lanjutan_resiko_jatuh_anak as jatuhanak', 'jatuhanak.no_rawat','=', 'a.no_rawat')
            // lanjutan neonatus dll belum
            ->leftjoin('penilaian_pasien_terminal as terminal', 'terminal.no_rawat','=', 'a.no_rawat')            
            ->leftjoin('resep_obat as resep', 'resep.no_rawat','=', 'a.no_rawat')       
            ->leftjoin('transfer_pasien_antar_ruang as transfer', 'transfer.no_rawat','=', 'a.no_rawat')            
            ->leftjoin('resume_pasien as resume', 'resume.no_rawat','=', 'a.no_rawat')   
            ->groupBy('a.no_rawat')
            ->whereBetween('a.tgl_registrasi', [$date, $enddate])
            ->where('a.kd_poli', 'IGDK')
            ->get();

        return view('erm/pengisian-igd', [
            'listpasien' => $listpasien, 
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
        ]);
    }

    public function berkasdigital(Request $request){
        // dd($request->all());
        if($request->no_rkm_medis != null){
            $pasien = DB::table('reg_periksa as a')
                ->select('a.no_rawat', 'a.almt_pj', 'b.no_rkm_medis', 'b.nm_pasien', 'b.tgl_lahir')
                ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
                ->where('b.no_rkm_medis', $request->no_rkm_medis)
                ->orderby('a.no_rawat', 'DESC')
                ->first();

            if($pasien == null){
                $request->session()->flash('error', 'NO RM tidak Ditemukan');
                return redirect()->back()->withInput();
            }
            
            $listberkas = DB::table('berkas_digital_perawatan as berkas')
                ->select('berkas.*', 'master.nama')
                ->leftjoin('master_berkas_digital as master', 'berkas.kode', '=', 'master.kode')
                ->leftjoin('reg_periksa as r', 'r.no_rawat', '=', 'berkas.no_rawat')
                ->where('r.no_rkm_medis', $request->no_rkm_medis)
                ->orderby('r.no_rawat', 'DESC')->get();
            // dd($listberkas);
        }else{
            $pasien = [];
            $listberkas = [];
        }

        $masterberkas = DB::table('master_berkas_digital as master')->orderby('master.nama', 'ASC')->get();

        return view('erm/berkasdigital', [
            'pasien' => $pasien, 
            'masterberkas' => $masterberkas, 
            'listberkas' => $listberkas, 
        ]);
    }

    public function berkasdigitalsimpan(Request $request){
        $validate = $request->validate([
            'kode' => ['required'],
            'no_rawat' => ['required'],
            'no_rkm_medis' => ['required'],
            'image' => ['mimes:jpeg,png,pdf','max:2048', 'required'],
        ]);

        $file = $request->file('image');
        // timestamp.namafile
        $nama_file = date('Y-m-d')."_".$file->getClientOriginalName();
        $folderPath = "file/";
        // fungsi simpan file
        $file->move($folderPath,$nama_file);

        // simpan ke database
        $uploadfile = [
            'no_rawat' => $request->no_rawat,
            'kode' => $request->kode,
            'lokasi_file' => $nama_file,
        ];
        // DB::table('berkas_digital_perawatan')->insert($uploadfile);
        DB::table('berkas_digital_perawatan')->updateOrInsert(['no_rawat' => $request->no_rawat, 'kode' => $request->kode, 'lokasi_file' => $nama_file], $uploadfile);
        // dd($request->all(), $file, $nama_file);

        $pasien = DB::table('reg_periksa as a')
                ->select('a.no_rawat', 'a.almt_pj', 'b.no_rkm_medis', 'b.nm_pasien', 'b.tgl_lahir')
                ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
                ->where('a.no_rawat', $request->no_rawat)
                ->orderby('a.no_rawat', 'DESC')
                ->first();

        $masterberkas = DB::table('master_berkas_digital as master')->orderby('master.nama', 'ASC')->get();

        $listberkas = DB::table('berkas_digital_perawatan as berkas')
            ->select('berkas.*', 'master.nama')
            ->leftjoin('master_berkas_digital as master', 'berkas.kode', '=', 'master.kode')
            ->leftjoin('reg_periksa as r', 'r.no_rawat', '=', 'berkas.no_rawat')
            ->where('r.no_rkm_medis', $request->no_rkm_medis)
            ->orderby('r.no_rawat', 'DESC')->get();

            // dd($pasien, $masterberkas, $listberkas);
        return view('erm/berkasdigital', [
            'pasien' => $pasien, 
            'masterberkas' => $masterberkas, 
            'listberkas' => $listberkas, 
        ]);
    }

}
