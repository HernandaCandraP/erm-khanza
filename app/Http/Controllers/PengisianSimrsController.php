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

    public function ermrawatinap(Request $request){
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
            ->select('a.*', 'p.nm_poli', 'b.nm_pasien', 'b.no_rkm_medis', 'c.nm_dokter', 
            'pak.no_rawat as pak', 'pmu.no_rawat as pmu', 'rjd.no_rawat as rjd', 'rja.no_rawat as rja', 'rjl.no_rawat as rjl', 'rjn.no_rawat as rjn',
            'rjg.no_rawat as rjg', 'rjp.no_rawat as rjp', 'pptl.no_rawat as pptl', 'ckh.no_rawat as ckh', 'ckk.no_rawat as ckk', 'ppa.no_rawat as ppa',
            'ppd.no_rawat as ppd', 'cor.no_rawat as cor', 'ckr.no_rawat as ckr', 'ccg.no_rawat as ccg', 'mrt.no_rawat as mrt', 'hpe.no_rawat as hpe',
            'pun.no_rawat as pun', 'cp.no_rawat as cp', 'tpa.no_rawat as tpa', 'pp.no_rawat as pp', 'rpr.no_rawat as rpr', 'pptn.no_rawat as pptn',
            'pkk.no_rawat as pkk', 'mrk.no_rawat as mrk', 'co.no_rawat as co', 'cop.no_rawat as cop'
            )
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->leftjoin('dokter as c', 'a.kd_dokter', '=', 'c.kd_dokter')
            ->leftjoin('poliklinik as p', 'a.kd_poli', '=', 'p.kd_poli')

            ->leftjoin('penilaian_awal_keperawatan_ranap as pak','pak.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('penilaian_medis_ranap as pmu', 'pmu.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('penilaian_lanjutan_resiko_jatuh_dewasa as rjd', 'rjd.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('penilaian_lanjutan_resiko_jatuh_anak as rja', 'rja.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('penilaian_lanjutan_resiko_jatuh_lansia as rjl', 'rjl.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('penilaian_risiko_jatuh_neonatus as rjn', 'rjn.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('penilaian_lanjutan_resiko_jatuh_geriatri as rjg', 'rjg.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('penilaian_lanjutan_resiko_jatuh_psikiatri as rjp', 'rjp.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('penilaian_pasien_terminal as pptl', 'pptl.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('checklist_kriteria_masuk_hcu as ckh', 'ckh.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('checklist_kriteria_keluar_hcu as ckk', 'ckk.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('pemantauan_pews_anak as ppa', 'ppa.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('pemantauan_pews_dewasa as ppd', 'ppd.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('catatan_observasi_ranap as cor', 'cor.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('catatan_keperawatan_ranap as ckr', 'ckr.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('catatan_cek_gds as ccg', 'ccg.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('monitoring_reaksi_tranfusi as mrt', 'mrt.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('hasil_pemeriksaan_ekg as hpe', 'hpe.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('penilaian_ulang_nyeri as pun', 'pun.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('catatan_persalinan as cp', 'cp.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('transfer_pasien_antar_ruang as tpa', 'tpa.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('perencanaan_pemulangan as pp', 'pp.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('resume_pasien_ranap as rpr', 'rpr.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('persetujuan_penolakan_tindakan as pptn', 'pptn.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('penilaian_awal_keperawatan_kebidanan_ranap as pkk', 'pkk.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('penilaian_medis_ranap_kandungan as mrk', 'mrk.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('catatan_observasi_ranap_kebidanan as co', 'co.no_rawat', '=', 'a.no_rawat')
            ->leftJoin('catatan_observasi_ranap_postpartum as cop', 'cop.no_rawat', '=', 'a.no_rawat')
            ->groupBy('a.no_rawat')
            ->whereBetween('a.tgl_registrasi', [$date, $enddate])
            ->where('status_lanjut', 'Ranap')
            ->where('a.kd_poli', 'IGDK')
            ->get();

        return view('erm/pengisian-rawatinap', [
            'listpasien' => $listpasien, 
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
        ]);
    }

    
    public function ermrawatjalan(Request $request){
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
            ->select('a.*', 'p.nm_poli', 'b.nm_pasien', 'b.no_rkm_medis', 'c.nm_dokter', 'soap.no_rawat as soap', 'resume.no_rawat as resume'
            )
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->leftjoin('dokter as c', 'a.kd_dokter', '=', 'c.kd_dokter')
            ->leftjoin('poliklinik as p', 'a.kd_poli', '=', 'p.kd_poli')

            ->leftjoin('pemeriksaan_ralan as soap','soap.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('resume_pasien as resume','resume.no_rawat', '=', 'a.no_rawat')
            ->groupBy('a.no_rawat')
            ->whereBetween('a.tgl_registrasi', [$date, $enddate])
            ->where('a.status_lanjut', 'Ralan')
            ->get();

        return view('erm/pengisian-rawatjalan', [
            'listpasien' => $listpasien, 
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
        ]);
    }

     public function ermfarmasi(Request $request){
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
            ->select('a.*', 'p.nm_poli', 'b.nm_pasien', 'b.no_rkm_medis', 'c.nm_dokter', 'kf.no_rawat as kf', 'ro.no_rawat as ro'
            )
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->leftjoin('dokter as c', 'a.kd_dokter', '=', 'c.kd_dokter')
            ->leftjoin('poliklinik as p', 'a.kd_poli', '=', 'p.kd_poli')

            ->leftjoin('konseling_farmasi as kf','kf.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('rekonsiliasi_obat as ro','ro.no_rawat', '=', 'a.no_rawat')
            ->groupBy('a.no_rawat')
            ->whereBetween('a.tgl_registrasi', [$date, $enddate])
            ->where('a.kd_poli', 'IGDK')
            ->get();

        return view('erm/pengisian-farmasi', [
            'listpasien' => $listpasien, 
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
        ]);
    }


    public function ermgizi(Request $request){
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
            ->select('a.*', 'p.nm_poli', 'b.nm_pasien', 'b.no_rkm_medis', 'c.nm_dokter', 'snd.no_rawat as snd', 'snl.no_rawat as snl',
            'sna.no_rawat as sna', 'sg.no_rawat as sg', 'ag.no_rawat as ag', 'mag.no_rawat as mag', 'cag.no_rawat as cag'
            )
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->leftjoin('dokter as c', 'a.kd_dokter', '=', 'c.kd_dokter')
            ->leftjoin('poliklinik as p', 'a.kd_poli', '=', 'p.kd_poli')

            ->leftjoin('skrining_nutrisi_dewasa as snd','snd.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('skrining_nutrisi_lansia as snl','snl.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('skrining_nutrisi_anak as sna','sna.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('skrining_gizi as sg','sg.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('asuhan_gizi as ag','ag.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('monitoring_asuhan_gizi as mag','mag.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('catatan_adime_gizi as cag','cag.no_rawat', '=', 'a.no_rawat')
            ->groupBy('a.no_rawat')
            ->whereBetween('a.tgl_registrasi', [$date, $enddate])
            ->where('status_lanjut', 'Ranap')
            ->where('a.kd_poli', 'IGDK')
            ->get();

        return view('erm/pengisian-gizi', [
            'listpasien' => $listpasien, 
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
        ]);
    }

    public function ermoperasi(Request $request){
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
            ->select('a.*', 'p.nm_poli', 'b.nm_pasien', 'b.no_rkm_medis', 'c.nm_dokter', 'cpo.no_rawat as cpo', 'ssa.no_rawat as ssa', 'tsi.no_rawat as tsi',
            'ssml.no_rawat as ssml', 'clpo.no_rawat as clpo', 'ppo.no_rawat as ppo', 'ppa.no_rawat as ppa'
            )
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->leftjoin('dokter as c', 'a.kd_dokter', '=', 'c.kd_dokter')
            ->leftjoin('poliklinik as p', 'a.kd_poli', '=', 'p.kd_poli')

            ->leftjoin('checklist_pre_operasi as cpo','cpo.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('signin_sebelum_anestesi as ssa','ssa.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('timeout_sebelum_insisi as tsi','tsi.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('signout_sebelum_menutup_luka as ssml','ssml.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('checklist_post_operasi as clpo','clpo.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('penilaian_pre_operasi as ppo','ppo.no_rawat', '=', 'a.no_rawat')
            ->leftjoin('penilaian_pre_anestesi as ppa','ppa.no_rawat', '=', 'a.no_rawat')
            ->groupBy('a.no_rawat')
            ->whereBetween('a.tgl_registrasi', [$date, $enddate])
            ->where('a.kd_poli', 'IGDK')
            ->get();

        return view('erm/pengisian-operasi', [
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
        $folderPath = "../file/";

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
