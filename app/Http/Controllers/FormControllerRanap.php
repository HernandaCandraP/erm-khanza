<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;

class FormControllerRanap extends Controller
{
    public function menuranap (Request $request){
        $pasienranap = DB::table('kamar_inap as ki')->where('ki.stts_pulang', '-')->select('ki.*', 'p.no_rkm_medis', 'p.nm_pasien', 'b.nm_bangsal')
            ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'ki.no_rawat')
            ->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->leftjoin('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
            ->leftjoin('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
            ->leftjoin('dpjp_ranap as dr', 'dr.no_rawat', '=', 'ki.no_rawat')
            ->where('dr.kd_dokter',  Session::get('username'))
            ->get();

        
        // dd($pasienranap);
        
        // $username = Session::get('username');
        $month = date('Y-m'); //'2023-06'; //
        $pasienbulan = DB::table('kamar_inap')->select('no_rawat')->where('tgl_masuk', 'like', '%'.$month.'%')->count();
        
        return view('formranap/list-ranap', [
            'pasienranap' => $pasienranap,
            'pasienbulan' => $pasienbulan
        ]);
    }

    public function riresume(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('kamar_inap as ki')->where('ki.stts_pulang', '-')->select('ki.*', 'p.no_rkm_medis', 'p.nm_pasien', 'b.nm_bangsal', 'k.kd_kamar', 'rp.no_rawat', 'p.umur')
        ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'ki.no_rawat')
        ->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
        ->leftjoin('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
        ->leftjoin('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
        ->leftjoin('dpjp_ranap as dr', 'dr.no_rawat', '=', 'ki.no_rawat')
        ->where('ki.no_rawat', $request->no_rawat )
        ->where('dr.kd_dokter',  Session::get('username'))
        ->get();
        // dd($detailpasien);
        // $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

       if (DB::table('resume_pasien_ranap')->where('no_rawat', '=', $no_rawat)->exists()) {
           $dataresume = DB::table('resume_pasien_ranap')->select('resume_pasien_ranap.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'resume_pasien_ranap.kd_dokter')->first();
       }else{
           $dataresume = DB::table('resume_pasien_ranap')->first();
           foreach ($dataresume as $key => $value) {
               $dataresume->$key = "";
           }
           $dataresume->kd_dokter = $kd_dokter;
           $dataresume->nama = Auth::guard('pegawai')->user()->nama;
       }

       //  dd($dataresume);
        return view('formranap/resume', [
            'detailpasien' => $detailpasien, 
            'dataresume' => $dataresume,
            'no_rawat' => $no_rawat
        ]);
   }

    public function risoap (Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');


        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'a.tgl_registrasi')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        $resepdokter = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.ralan', 'db.letak_barang','jenis.nama', 'db.kode_sat', 'gudangbarang.stok')
            ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
            ->join('gudangbarang', 'gudangbarang.kode_brng','=', 'db.kode_brng')
            ->where('db.status', '=', '1')->where('gudangbarang.stok', '>', '0')
            ->groupby('gudangbarang.kode_brng')->orderby('db.nama_brng')->get();
        // dd($detailpasien);

        // get list semua dokter
        // $listdokter = DB::table('dokter')->where('status', '1')->get();

        if ($no_rawat != null && $request->tgl_perawatan != null && $request->jam_rawat != null) {
            $data = DB::table('pemeriksaan_ranap')->select('pemeriksaan_ranap.*', 'pegawai.nama')->where([['no_rawat', '=',$no_rawat], ['tgl_perawatan', '=',$request->tgl_perawatan], ['jam_rawat', '=',$request->jam_rawat]])->leftjoin('pegawai', 'pegawai.nik', 'pemeriksaan_ranap.nip')->first();
            $data->tgl_perawatan_lama = $request->tgl_perawatan;
            $data->jam_rawat_lama = $request->jam_rawat;
        }else{
            $data = DB::table('pemeriksaan_ranap')->where('no_rawat', $request->no_rawat)->orderBy('tgl_perawatan', 'DESC')->orderBy('jam_rawat', 'DESC')->first();
            // cek apakah ada data di pemeriksaan ralan, jika tidak maka ambil data pemeriksaan lain
            if($data != null){
                foreach ($data as $key => $value) {
                    // ambil data ttv sebelumnya
                    if($key == 'suhu_tubuh' || $key == 'tensi' || $key == 'nadi' || $key == 'respirasi' || $key == 'tinggi' || $key == 'berat' || $key == 'spo2' || $key == 'gcs' ){
                        $data->$key = $value;
                    }else{
                        $data->$key = "";
                    }
                }
            }else{
                $data = $data = DB::table('pemeriksaan_ranap')->first();
                foreach ($data as $key => $value) {
                    $data->$key = "";
                }
            }
            $data->nip = $kd_dokter;
            $data->nama = Auth::guard('pegawai')->user()->nama;
        }

        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();
        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan')->where('status', '1')->get();
        $getimage = 'image/semua.png';

        return view('formranap/formri-soap', [
            'detailpasien' => $detailpasien,
            'data' => $data,
            'resepdokter' => $resepdokter,
            'jnsperawatanlab' => $jnsperawatanlab,
            'jnsperawatanrad' => $jnsperawatanrad,
            'getimage' => $getimage,
            'no_rawat' => $no_rawat
        ]);
    }

    public function ripenanganandokter(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        $getkelas = DB::table('kamar_inap as ki')->select('k.kelas')->join('kamar as k', 'k.kd_kamar','=', 'ki.kd_kamar')->where('no_rawat', $no_rawat)->first();

        $jnspenanganan = DB::table('jns_perawatan_inap as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'a.kelas', 'a.total_byrdr')
            ->where([['status', '1'], ['total_byrdr', '>', '0'], ['kelas', $getkelas->kelas]])
            ->orwhere([['status', '1'], ['total_byrdr', '>', '0'], ['kelas', '-']])
            ->orderby('a.nm_perawatan', 'ASC')->get();
        
        $getpenanganandr = DB::table('rawat_inap_dr as dr')->select('dr.*','p.nama', 'jns.nm_perawatan')
            ->leftjoin('pegawai as p', 'dr.kd_dokter','=', 'p.nik')
            ->leftjoin('jns_perawatan_inap as jns', 'dr.kd_jenis_prw','=', 'jns.kd_jenis_prw')
            ->where('no_rawat', $no_rawat)->get();

        return view('formranap/formri-penanganandokter', [
            'detailpasien' => $detailpasien, 
            'jnspenanganan' => $jnspenanganan,
            'getpenanganandr' => $getpenanganandr,
            'no_rawat' => $no_rawat
        ]);
    }

    public function rimedisumum(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        if (DB::table('penilaian_medis_ranap')->where('no_rawat', '=', $no_rawat)->exists()) {
            $medisumum = DB::table('penilaian_medis_ranap')->select('penilaian_medis_ranap.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'penilaian_medis_ranap.kd_dokter')->first();
        }else{
            $medisumum = DB::table('penilaian_medis_ranap')->first();
            foreach ($medisumum as $key => $value) {
                $medisumum->$key = "";
            }
            $medisumum->kd_dokter = $kd_dokter;
            $medisumum->nama = Auth::guard('pegawai')->user()->nama;

            $ambilttv = DB::table('penilaian_awal_keperawatan_ranap')->where('no_rawat', $request->no_rawat)->orderBy('tanggal', 'DESC')->first();
            // dd($ambilttv);
            if($ambilttv != null){
                $medisumum->suhu = $ambilttv->pemeriksaan_suhu;
                $medisumum->td = $ambilttv->pemeriksaan_td;
                $medisumum->nadi = $ambilttv->pemeriksaan_nadi;
                $medisumum->rr = $ambilttv->pemeriksaan_rr;
                $medisumum->tb = $ambilttv->pemeriksaan_tb;
                $medisumum->bb = $ambilttv->pemeriksaan_bb;
                $medisumum->spo = $ambilttv->pemeriksaan_spo2;
                $medisumum->gcs = $ambilttv->pemeriksaan_gcs;
            }
            // dd($medisumum,$ambilttv);
        }

        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();
        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan')->where('status', '1')->get();
        // $resepdokter = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.ralan', 'db.letak_barang','jenis.nama', 'db.kode_sat', 'gudangbarang.stok')
        // ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
        // ->join('gudangbarang', 'gudangbarang.kode_brng','=', 'db.kode_brng')
        // ->where('db.status', '=', '1')->where('gudangbarang.stok', '>', '0')
        // ->groupby('gudangbarang.kode_brng')->orderby('db.nama_brng')->get();

        return view('formranap/formri-medisumum', [
            'detailpasien' => $detailpasien,
            'medisumum' => $medisumum,
            'jnsperawatanlab' => $jnsperawatanlab,
            'jnsperawatanrad' => $jnsperawatanrad,
            // 'resepdokter' => $resepdokter,
            'no_rawat' => $no_rawat
        ]);
    }

    public function rimediskebidanan(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        if (DB::table('penilaian_medis_ranap_kandungan')->where('no_rawat', '=', $no_rawat)->exists()) {
            $mediskebidanan = DB::table('penilaian_medis_ranap_kandungan')->select('penilaian_medis_ranap_kandungan.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'penilaian_medis_ranap_kandungan.kd_dokter')->first();
        }else{
            $mediskebidanan = DB::table('penilaian_medis_ranap_kandungan')->first();
            foreach ($mediskebidanan as $key => $value) {
                $mediskebidanan->$key = "";
            }
            $mediskebidanan->kd_dokter = $kd_dokter;
            $mediskebidanan->nama = Auth::guard('pegawai')->user()->nama;

            $ambilttv = DB::table('penilaian_awal_keperawatan_kebidanan_ranap')->where('no_rawat', $request->no_rawat)->orderBy('tanggal', 'DESC')->first();
            if($ambilttv != null){
                $mediskebidanan->suhu = $ambilttv->pemeriksaan_kebidanan_suhu;
                $mediskebidanan->td = $ambilttv->pemeriksaan_kebidanan_td;
                $mediskebidanan->nadi = $ambilttv->pemeriksaan_kebidanan_nadi;
                $mediskebidanan->rr = $ambilttv->pemeriksaan_kebidanan_rr;
                $mediskebidanan->tb = $ambilttv->pemeriksaan_kebidanan_tb;
                $mediskebidanan->bb = $ambilttv->pemeriksaan_kebidanan_bb;
                $mediskebidanan->spo = $ambilttv->pemeriksaan_kebidanan_spo2;
                $mediskebidanan->gcs = $ambilttv->pemeriksaan_kebidanan_gcs; 
                $mediskebidanan->tfu = $ambilttv->pemeriksaan_kebidanan_tfu; 
                $mediskebidanan->tbj = $ambilttv->pemeriksaan_kebidanan_tbj; 
                $mediskebidanan->his = $ambilttv->pemeriksaan_kebidanan_his; 
                $mediskebidanan->djj = $ambilttv->pemeriksaan_kebidanan_djj; 
            }
            // dd($mediskebidanan,$ambilttv);
        }

        return view('formranap/formri-mediskebidanan', [
            'detailpasien' => $detailpasien, 
            'mediskebidanan' => $mediskebidanan,
            'no_rawat' => $no_rawat
        ]);
    }

    public function ripermintaanlab(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();

        return view('formranap/formri-permintaanlab', [
            'detailpasien' => $detailpasien, 
            'jnsperawatanlab' => $jnsperawatanlab,
            'no_rawat' => $no_rawat
        ]);
    }

    public function ripermintaanrad(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan')->where('status', '1')->get();

        return view('formranap/formri-permintaanrad', [
            'detailpasien' => $detailpasien, 
            'jnsperawatanrad' => $jnsperawatanrad,
            'no_rawat' => $no_rawat
        ]);
    }

    public function riresep(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        $resepdokter = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.ralan', 'db.letak_barang','jenis.nama', 'db.kode_sat', 'gudangbarang.stok')
            ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
            ->join('gudangbarang', 'gudangbarang.kode_brng','=', 'db.kode_brng')
            ->where('db.status', '=', '1')->where('gudangbarang.stok', '>', '0')
            ->groupby('gudangbarang.kode_brng')->orderby('db.nama_brng')->get();

        $listresep = DB::table('resep_obat as ro')
            ->select('ro.kd_dokter', 'p.nama', 'ro.no_rawat', 'rd.*', 'db.nama_brng')
            ->leftjoin('pegawai as p', 'p.nik', '=', 'ro.kd_dokter')
            ->join('resep_dokter as rd', 'rd.no_resep', '=', 'ro.no_resep')
            ->join('databarang as db', 'db.kode_brng', '=', 'rd.kode_brng')
            ->where('no_rawat', $no_rawat)->orderby('rd.no_resep', 'DESC')->get();

        return view('formranap/formri-resepdokter', [
            'detailpasien' => $detailpasien, 
            'resepdokter' => $resepdokter,
            'listresep' => $listresep,
            'no_rawat' => $no_rawat
        ]);
    }

    public function create(Request $request)
    {      
        // dd($request->all());
        if($request->niplama != $request->nip && $request->action == 'update'){
            $request->session()->flash('error', "Data Tidak Dapat Disimpan, Dokter Penangung Jawab Tidak Sama");
            return redirect()->back();
        }elseif($request->niplama != $request->nip && $request->action == 'update_with_time'){
            $request->session()->flash('error', "Data Tidak Dapat Disimpan, Dokter Penangung Jawab Tidak Sama");
            return redirect()->back();
        }elseif($request->action == 'hapus'){
            return $this->delete($request);
        }
        
        foreach ($request->all() as $i => $value) {
            // unset($request[$i]);
            if(!$request->filled($i)){
                $request->merge([$i => '-']);
            }
        }

        if ($request->tabel == 'DataPemeriksaanRawatInap') {
            if($request->jumlah != null || $request->resep){
                $request->tabel = 'ResepDokterRawatInap';
                $request->jam_peresepan = date('H:i:s');
                $request->tgl_peresepan =  date('Y-m-d');
                $this->create($request);
                
                // Menambahkan obat ke plan
                $listResep = " Resep : \r\n";
                foreach ($request->jumlah as $key => $value) {
                    $namaObat = DB::table('databarang')->select('nama_brng')->where('kode_brng', $request->resep[$key])->first();
                    $listResep .= $namaObat->nama_brng . " Jumlah " . $request->jumlah[$key] . " Aturan Pakai " . $request->aturan_pakai[$key] . "\r\n";
                }
                $request->rtl = $request->rtl . $listResep;
            }
            if($request->pemeriksaanrad != null){
                $request->tabel = 'PermintaanRadiologiRawatInap';
                $request->tgl_permintaan = date('Y-m-d');
                $request->jam_permintaan = date('H:i:s');
                $this->create($request);
            }
            if($request->pemeriksaanlab != null){
                $request->tabel = 'PermintaanLabRawatInap';
                $request->tgl_permintaan = date('Y-m-d');
                $request->jam_permintaan = date('H:i:s');
                $this->create($request);
            }
            $DataPemeriksaanRawatInap = [
                'tgl_perawatan' => $request->tgl_perawatan,
                'jam_rawat' => $request->jam_rawat,
                'no_rawat' => $request->no_rawat,
                'nip' => $request->nip,
                'keluhan' => $request->keluhan,
                'pemeriksaan' => $request->pemeriksaan,
                'suhu_tubuh' => $request->suhu_tubuh,
                'tensi' => $request->tensi,
                'berat' => $request->berat,
                'tinggi' => $request->tinggi,
                'respirasi' => $request->respirasi,
                'nadi' => $request->nadi,
                'spo2' => $request->spo2,
                'gcs' => $request->gcs,
                'kesadaran' => $request->kesadaran,
                'alergi' => $request->alergi,
                'penilaian' => $request->penilaian,
                'rtl' => $request->rtl,
                'instruksi' => $request->instruksi,
                'evaluasi' => $request->evaluasi
            ];
            return $this->func_create($request, $DataPemeriksaanRawatInap, 'pemeriksaan_ranap', 'formri-soap');
        }
        elseif($request->tabel == 'DataResumeMedisPasienRawatInap'){
            $DataResumeMedisPasienRawatInap = [
                'no_rawat' => $request->no_rawat,
                'kd_dokter' => $request->nip,
                'diagnosa_awal' => $request->diagnosa_awal,
                'alasan' => $request->alasan,
                'keluhan_utama' => $request->keluhan_utama,
                'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
                'jalannya_penyakit' => $request->jalannya_penyakit,
                'pemeriksaan_penunjang' => $request->pemeriksaan_penunjang,
                'hasil_laborat' => $request->hasil_laborat,
                'tindakan_dan_operasi' => $request->tindakan_dan_operasi,
                'obat_di_rs' => $request->obat_di_rs,
                'diagnosa_utama' => $request->diagnosa_utama,
                'kd_diagnosa_utama' => $request->kd_diagnosa_utama,
                'diagnosa_sekunder' => $request->diagnosa_sekunder,
                'kd_diagnosa_sekunder' => $request->kd_diagnosa_sekunder,
                'diagnosa_sekunder2' => $request->diagnosa_sekunder2,
                'kd_diagnosa_sekunder2' => $request->kd_diagnosa_sekunder2,
                'diagnosa_sekunder3' => $request->diagnosa_sekunder3,
                'kd_diagnosa_sekunder3' => $request->kd_diagnosa_sekunder3,
                'diagnosa_sekunder4' => $request->diagnosa_sekunder4,
                'kd_diagnosa_sekunder4' => $request->kd_diagnosa_sekunder4,
                'prosedur_utama' => $request->prosedur_utama,
                'kd_prosedur_utama' => $request->kd_prosedur_utama,
                'prosedur_sekunder' => $request->prosedur_sekunder,
                'kd_prosedur_sekunder' => $request->kd_prosedur_sekunder,
                'prosedur_sekunder2' => $request->prosedur_sekunder2,
                'kd_prosedur_sekunder2' => $request->kd_prosedur_sekunder2,
                'prosedur_sekunder3' => $request->prosedur_sekunder3,
                'kd_prosedur_sekunder3' => $request->kd_prosedur_sekunder3,
                'alergi' => $request->alergi,
                'diet' => $request->diet,
                'lab_belum' => $request->lab_belum,
                'edukasi' => $request->edukasi,
                'ket_keadaan' => $request->ket_keadaan,
                'cara_keluar' => $request->cara_keluar,
                'ket_keluar' => $request->ket_keluar,
                'dilanjutkan' => $request->dilanjutkan,
                'ket_dilanjutkan' => $request->ket_dilanjutkan,
                'kontrol' => $request->kontrol,
                'obat_pulang' => $request->obat_pulang,
            ];
            return $this->func_create($request, $DataResumeMedisPasienRawatInap, 'resume_pasien_ranap', 'formri-resume');
        }
        elseif ($request->tabel == 'PenangananDokterRawatInap') {
            // dd($request->all());
            $reg_periksa = DB::table('reg_periksa')->where('no_rawat', $request->no_rawat)->first();
            if($reg_periksa->status_bayar == 'Sudah Bayar'){
                $request->session()->flash('error', 'Data billing sudah terferifikasi,  Silahkan hubungi bagian kasir / keuangan');
                return redirect()->back();
            }

            $penanganandokter = [];
            if($request->pemeriksaan){
                foreach ($request->pemeriksaan as $key => $value) {
                    $jnspenanganan = DB::table('jns_perawatan_inap')->select('kd_jenis_prw', 'material', 'bhp', 'tarif_tindakandr', 'kso', 'menejemen', 'total_byrdr')->where('kd_jenis_prw', $value)->first();

                    $dtpenanganandokter = [
                        'no_rawat'=> $request->no_rawat,
                        'kd_jenis_prw'=> $value,
                        'kd_dokter'=> $request->nip,
                        'tgl_perawatan'=> $request->tgl_perawatan,
                        'jam_rawat'=> $request->jam_rawat,
                        'material'=> $jnspenanganan->material,
                        'bhp'=> $jnspenanganan->bhp,
                        'tarif_tindakandr'=> $jnspenanganan->tarif_tindakandr,
                        'kso'=> $jnspenanganan->kso,
                        'menejemen'=> $jnspenanganan->menejemen,
                        'biaya_rawat'=> $jnspenanganan->total_byrdr
                    ];
                    array_push($penanganandokter, $dtpenanganandokter);
                }
            }else{
                $request->session()->flash('error', 'Belum memilih penaganan dokter');
                return redirect()->back();
            }
            DB::table('rawat_inap_dr')->insert($penanganandokter);
            $request->session()->flash('success', 'Berhasil menyimpan penanganan dokter');
            return redirect()->back();
        }
        elseif ($request->tabel == 'PenilaianAwalMedisRawatInap') {
            $permintaan = $this->permintan($request);
            $request->terapi = $permintaan['terapi'];
            $request->lab = $permintaan['lab'];
            $request->rad = $permintaan['rad'];

            $PenilaianAwalMedisRawatInap = [
                'no_rawat' => $request->no_rawat,
                'kd_dokter' => $request->nip,
                'tanggal' => $request->tanggal,
                'anamnesis'=> $request->anamnesis,
                'hubungan'=> $request->hubungan,
                'keluhan_utama'=> $request->keluhan_utama,
                'rps'=> $request->rps,
                'rpd'=> $request->rpd,
                'rpk'=> $request->rpk,
                'rpo'=> $request->rpo,
                'alergi'=> $request->alergi,
                'keadaan'=> $request->keadaan,
                'gcs'=> $request->gcs,
                'kesadaran'=> $request->kesadaran,
                'td'=> $request->td,
                'nadi'=> $request->nadi,
                'rr'=> $request->rr,
                'suhu'=> $request->suhu,
                'spo'=> $request->spo,
                'bb'=> $request->bb,
                'tb'=> $request->tb,
                'kepala'=> $request->kepala,
                'mata'=> $request->mata,
                'gigi'=> $request->gigi,
                'tht'=> $request->tht,
                'thoraks'=> $request->thoraks,
                'jantung'=> $request->jantung,
                'paru'=> $request->paru,
                'abdomen'=> $request->abdomen,
                'genital'=> $request->genital,
                'ekstremitas'=> $request->ekstremitas,
                'kulit'=> $request->kulit,
                'ket_fisik'=> $request->ket_fisik,
                'ket_lokalis'=> $request->ket_lokalis,
                'lab'=> $request->lab,
                'rad'=> $request->rad,
                'penunjang'=> $request->penunjang,
                'diagnosis'=> $request->diagnosis,
                'tata'=> $request->tata,
                'edukasi'=> $request->edukasi
            ];
            return $this->func_create($request, $PenilaianAwalMedisRawatInap, 'penilaian_medis_ranap', 'formri-medisumum');
        }
        elseif ($request->tabel == 'PenilaianAwalMedisKebidananRawatInap') {
            $permintaan = $this->permintan($request);
            $request->terapi = $permintaan['terapi'];
            $request->lab = $permintaan['lab'];
            $request->rad = $permintaan['rad'];

            $PenilaianAwalMedisKebidananRawatInap = [
                'no_rawat' => $request->no_rawat,
                'kd_dokter' => $request->nip,
                'tanggal' => $request->tanggal,
                'anamnesis'=> $request->anamnesis,
                'hubungan'=> $request->hubungan,
                'keluhan_utama'=> $request->keluhan_utama,
                'rps'=> $request->rps,
                'rpd'=> $request->rpd,
                'rpk'=> $request->rpk,
                'rpo'=> $request->rpo,
                'alergi'=> $request->alergi,
                'keadaan'=> $request->keadaan,
                'gcs'=> $request->gcs,
                'kesadaran'=> $request->kesadaran,
                'td'=> $request->td,
                'nadi'=> $request->nadi,
                'rr'=> $request->rr,
                'suhu'=> $request->suhu,
                'spo'=> $request->spo,
                'bb'=> $request->bb,
                'tb'=> $request->tb,
                'kepala'=> $request->kepala,
                'mata'=> $request->mata,
                'gigi'=> $request->gigi,
                'tht'=> $request->tht,
                'thoraks'=> $request->thoraks,
                'jantung'=> $request->jantung,
                'paru'=> $request->paru,
                'abdomen'=> $request->abdomen,
                'genital'=> $request->genital,
                'ekstremitas'=> $request->ekstremitas,
                'kulit'=> $request->kulit,
                'ket_fisik'=> $request->ket_fisik,
                'tfu'=> $request->tfu,
                'tbj'=> $request->tbj,
                'his'=> $request->his,
                'kontraksi'=> $request->kontraksi,
                'kulit'=> $request->kulit,
                'djj'=> $request->djj,
                'inspeksi'=> $request->inspeksi,
                'inspekulo'=> $request->inspekulo,
                'vt'=> $request->vt,
                'rt'=> $request->rt,
                'ultra'=> $request->ultra,
                'kardio'=> $request->kardio,
                'lab'=> $request->lab,
                'diagnosis'=> $request->diagnosis,
                'tata'=> $request->tata,
                'edukasi'=> $request->edukasi
            ];
            return $this->func_create($request, $PenilaianAwalMedisKebidananRawatInap, 'penilaian_medis_ranap_kandungan', 'formri-mediskebidanan');
        }
        elseif ($request->tabel == 'PermintaanLabRawatInap') {
            $data = date('Ymd', strtotime($request->tgl_permintaan));
            $noorder = DB::table('permintaan_lab')->where('tgl_permintaan', $request->tgl_permintaan)->orderby('noorder', 'DESC');
            if($noorder->count() == 0){
                $noorderPK = "PK". $data . sprintf("%04s", 1);
            }else{
                $lastOrder = substr($noorder->first()->noorder, -4) +1;
                $noorderPK = "PK". $data . sprintf("%04s", $lastOrder);
            }
            $pemeriksaanlab = [];
            $detailpermintaanlab = [];
            $kd_jenis_prw = [];
            if($request->pemeriksaanlab){
                foreach ($request->pemeriksaanlab as $key => $value) {
                    list($a, $b) = explode('***', $value);
                    array_push($kd_jenis_prw, $a);
                    if($b){
                        $dtdetailpermintaanlab = ['noorder'=>$noorderPK, 'kd_jenis_prw'=> $a, 'id_template' => $b ,'stts_bayar' => 'Belum'];
                        array_push($detailpermintaanlab, $dtdetailpermintaanlab);
                    }
                }

                // mengambil kode unik untuk dimasukkan ke pemeriksaan lab
                $uniquepemeriksaanlab = array_unique($kd_jenis_prw);
                foreach ($uniquepemeriksaanlab as $key => $value) {
                    $dtpemeriksaanlab = ['noorder'=>$noorderPK, 'kd_jenis_prw'=> $value, 'stts_bayar' => 'Belum'];
                    array_push($pemeriksaanlab, $dtpemeriksaanlab);
                }
            }
            $permintaanLab = [
                'noorder' => $noorderPK,
                'no_rawat' => $request->no_rawat,
                'tgl_permintaan' => $request->tgl_permintaan,
                'jam_permintaan' => $request->jam_permintaan,
                'dokter_perujuk' => $request->nip,
                'status' => "ranap",
                'informasi_tambahan' => $request->informasi_tambahanlab,
                'diagnosa_klinis' => $request->diagnosa_klinislab
            ];
            DB::table('permintaan_lab')->insert($permintaanLab);
            DB::table('permintaan_pemeriksaan_lab')->insert($pemeriksaanlab);
            DB::table('permintaan_detail_permintaan_lab')->insert($detailpermintaanlab);
            $request->session()->flash('success', 'Berhasil menyimpan permintaan laboratorium');
            return redirect()->back();
        }
        elseif ($request->tabel == 'PermintaanRadiologiRawatInap') {
            $data = date('Ymd', strtotime($request->tgl_permintaan));
            // dd($data);
            $noorder = DB::table('permintaan_radiologi')->where('tgl_permintaan', $request->tgl_permintaan)->count();
            $noorder = DB::table('permintaan_radiologi')->where('tgl_permintaan', $request->tgl_permintaan)->orderby('noorder', 'DESC');
            if($noorder->count() == 0){
                $noorderPR = "PR". $data . sprintf("%04s", 1);
            }else{
                $lastOrder = substr($noorder->first()->noorder, -4) +1;
                $noorderPR = "PR". $data . sprintf("%04s", $lastOrder);
            }
            $pemeriksaanrad = [];
            if($request->pemeriksaanrad){
                foreach ($request->pemeriksaanrad as $key => $value) {
                    $dtpemeriksaanrad = ['noorder'=>$noorderPR, 'kd_jenis_prw'=> $value, 'stts_bayar' => 'Belum'];
                    array_push($pemeriksaanrad, $dtpemeriksaanrad);
                }
            }
            $permintaanRad = [
                'noorder' => $noorderPR,
                'no_rawat' => $request->no_rawat,
                'tgl_permintaan' => $request->tgl_permintaan,
                'jam_permintaan' => $request->jam_permintaan,
                'dokter_perujuk' => $request->nip,
                'status' => "ranap",
                'informasi_tambahan' => $request->informasi_tambahanrad,
                'diagnosa_klinis' => $request->diagnosa_klinisrad
            ];
            DB::table('permintaan_radiologi')->insert($permintaanRad);
            DB::table('permintaan_pemeriksaan_radiologi')->insert($pemeriksaanrad);
            $request->session()->flash('success', 'Berhasil menyimpan permintaan Radiologi');
            return redirect()->back();
        }
        elseif ($request->tabel == 'ResepDokterRawatInap') {
            $data = date('Ymd', strtotime($request->tgl_peresepan));
            // dd($data);
            $noorder = DB::table('resep_obat')->where('tgl_peresepan', $request->tgl_peresepan)->orderby('no_resep', 'DESC');

            if($noorder->count() == 0){
                $noorderResep = $data . sprintf("%04s", 1);
            }else{
                $noorderResep = $noorder->first()->no_resep+1;
            }
            // dd($noorderResep);
            $resepdokter = [];
            if($request->jumlah){
                foreach ($request->jumlah as $key => $value) {
                    $dtresepdokter = ['no_resep'=>$noorderResep, 'kode_brng'=> $request->resep[$key], 'jml' => $request->jumlah[$key], 'aturan_pakai' => $request->aturan_pakai[$key]];
                    array_push($resepdokter, $dtresepdokter);
                }
            }

            $resep_obat = [
                'no_resep' => $noorderResep,
                'tgl_perawatan' => '0000-00-00',
                'jam' => '00:00:00',
                'no_rawat' => $request->no_rawat,
                'kd_dokter' => $request->nip,
                'tgl_peresepan' => $request->tgl_peresepan,
                'jam_peresepan' => $request->jam_peresepan,
                'status' => 'ranap',
                'tgl_penyerahan' => '0000-00-00',
                'jam_penyerahan' => '00:00:00',
            ];
            DB::table('resep_obat')->insert($resep_obat);
            DB::table('resep_dokter')->insert($resepdokter);
            $request->session()->flash('success', 'Berhasil menyimpan Resep');
            return redirect()->back();
        }
        else{
            dd("nama tabel kosong");
        }
    }

    private function permintan(Request $request){
        $uniquelab = $request->lab;
        $getdate = date('Y-m-d');
        $gettime = date('H:i:s');

        if($request->jumlah != null || $request->resep){
            $request->tabel = 'ResepDokterRawatInap';
            $request->jam_peresepan = $gettime;
            $request->tgl_peresepan =  $getdate;
            $this->create($request);
            
            // Menambahkan obat ke terapi
            $listResep = " Resep : \r\n";
            foreach ($request->jumlah as $key => $value) {
                $namaObat = DB::table('databarang')->select('nama_brng')->where('kode_brng', $request->resep[$key])->first();
                $listResep .= $namaObat->nama_brng . " Jumlah " . $request->jumlah[$key] . " Aturan Pakai " . $request->aturan_pakai[$key] . "\r\n";
            }
            $request->terapi = $request->terapi . $listResep;
        }
        if($request->pemeriksaanrad != null){
            $request->tabel = 'PermintaanRadiologiRawatInap';
            $request->tgl_permintaan = $getdate;
            $request->jam_permintaan = $gettime;
            $this->create($request);

            // Menambahkan obat ke rad
            $listRad = " ";
            foreach ($request->pemeriksaanrad as $key => $value) {
                $namaRad = DB::table('jns_perawatan_radiologi')->select('nm_perawatan')->where('kd_jenis_prw', $request->pemeriksaanrad[$key])->first();
                $listRad .= $namaRad->nm_perawatan . ", ";
            }
            $request->rad = $request->rad . $listRad;
        }
        if($request->pemeriksaanlab != null){
            $request->tabel = 'PermintaanLabRawatInap';
            $request->tgl_permintaan = $getdate;
            $request->jam_permintaan = $gettime;
            $this->create($request);

            // Menambahkan obat ke lab
            $listLab = [$request->lab];
            $uniquelab = [];
            foreach ($request->pemeriksaanlab as $key => $value) {
                list($a, $b) = explode('***', $value);
                $namaLab = DB::table('jns_perawatan_lab')->select('nm_perawatan')->where('kd_jenis_prw', $a)->first();
                array_push($listLab, $namaLab->nm_perawatan);
            }
            $uniquelab = array_unique($listLab);
            $uniquelab = implode(", ",$uniquelab);
        }
        return['terapi'=> $request->terapi, 'lab'=>$uniquelab, 'rad'=>$request->rad];
    }
    
    private function func_create(Request $request, $data, $namatabel, $route){
        // dd($request->all());
        DB::beginTransaction();
        try{            
            if($request->action == 'simpan'){
                DB::table($namatabel)->insert($data);
            }    
            elseif($request->action == 'update'){
                $dataupdate = DB::table($namatabel)->where('no_rawat','=', $data['no_rawat']);
                if($dataupdate->first() != null){
                    $dataupdate->update($data);
                }else{
                    $request->session()->flash('error', 'Belum ada data yang diupdate, silahkan simpan data');
                    return redirect()->back()->withInput();
                }
            }
            elseif($request->action == 'update_with_time'){
                if($request->tgl_perawatan_lama != "-" && $request->jam_rawat_lama !="-"){
                    $dataupdate = DB::table($namatabel)->where([['no_rawat','=', $data['no_rawat']], ['tgl_perawatan', '=',$request->tgl_perawatan_lama], ['jam_rawat', '=',$request->jam_rawat_lama]]);
                    if($dataupdate->first() != null){
                        $dataupdate->update($data);
                    }else{
                        $request->session()->flash('error', 'Belum ada data yang diupdate, silahkan simpan data');
                        return redirect()->back()->withInput();
                    }
                }else{
                    $request->session()->flash('error', 'Silahkan pilih data untuk diupdate');
                    return redirect()->back()->withInput();
                }
            }

            DB::commit();
            $request->session()->flash('success', 'Berhasil menyimpan data');
            return redirect()->route($route, ['no_rawat' => $request->no_rawat]);

        }catch(\Exception $e){
            dd($e);
            DB::rollback(); 
            $request->session()->flash('error', 'Gagal Simpan, Sudah Ada Data Sebelumnya');
            return redirect()->back()->withInput();
        }
    }

    public function listdata(Request $request){
        // dd($request->all());
        if($request->tabel == 'DataPemeriksaanRawatInap'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('pemeriksaan_ranap as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.nip','=', 'd.nik')
                ->orderby('c.tgl_perawatan', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            return $this->func_listdata($request, 'formranap/ranaplistsoap', $data, 'Tidak Ada Riwayat Soap');
        }
        elseif($request->tabel == 'DataResumeMedisPasienRawatInap'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('resume_pasien_ranap as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
                ->orderby('c.no_rawat', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            return $this->func_listdata($request, 'formranap/listresume', $data, 'Tidak Ada Riwayat Resume');
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatInap'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('penilaian_medis_ranap as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
                ->orderby('c.no_rawat', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            // dd($data->get());
            return $this->func_listdata($request, 'formranap/list-medisumum', $data, 'Tidak Ada Riwayat Resume');
        }
        elseif($request->tabel == 'PenilaianAwalMedisKebidananRawatInap'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('penilaian_medis_ranap_kandungan as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
                ->orderby('c.no_rawat', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            // dd($data->get());
            return $this->func_listdata($request, 'formranap/list-medisumumkebidanan', $data, 'Tidak Ada Riwayat Resume');
        }
        else{
            dd("kosong");
        }
    }

    private function func_listdata(Request $request, $redirect, $data, $pesan)
    {
        $listdata = $data->get();
        $dataawal = $data->first();

        if($dataawal == null){
            $request->session()->flash('error', $pesan);
            return redirect()->back();
        }else{
            return view($redirect, [
                'data' => $listdata,
                'detailpasien' => $dataawal
            ]);
        }
    }

    public function delete(Request $request)
    {
        if($request->niplama != Auth::guard('pegawai')->user()->nik){
            $request->session()->flash('error', "Data Tidak Dapat Dihapus, Dokter Penangung Jawab Tidak Sama");
            return redirect()->back();
        }

        if($request->tabel == 'DataPemeriksaanRawatInap'){
            return $this->func_delete($request, 'pemeriksaan_ranap', "formri-soap");
        }
        elseif($request->tabel == 'DataResumeMedisPasienRawatInap'){
            return $this->func_delete($request, 'resume_pasien_ranap', "false");
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatInap'){
            return $this->func_delete($request, 'penilaian_medis_ranap', "false");
        }
        elseif($request->tabel == 'PenilaianAwalMedisKebidananRawatInap'){
            return $this->func_delete($request, 'penilaian_medis_ranap_kandungan', "false");
        }
        elseif($request->tabel == 'PenangananDokterRawatInap'){
            // return $this->func_delete($request, 'rawat_jl_dr', "form-penanganandokter");
            try{
                DB::table('rawat_inap_dr')->where([['no_rawat','=', $request->no_rawat], ['tgl_perawatan', '=',$request->tgl_perawatan_lama], ['jam_rawat', '=',$request->jam_rawat_lama], ['kd_jenis_prw', '=',$request->kd_jenis_prw]])->delete();

                $request->session()->flash('warning', 'Data Berhasil Dihapus');
                return redirect()->route('formri-penanganandokter', ['no_rawat' => $request->no_rawat]);
            } catch (\Exception $e){
                $request->session()->flash('error', 'Data Gagal Dihapus');
                return redirect()->back();
            }
        }
        dd("dekarasi tabel belum ada");
    }

    private function func_delete(Request $request, $namatabel, $route)
    {
        // menghapus data dengan filter jam rawat atau tidak
        if($route != "false"){
            try{
                DB::table($namatabel)->where([['no_rawat','=', $request->no_rawat], ['tgl_perawatan', '=',$request->tgl_perawatan_lama], ['jam_rawat', '=',$request->jam_rawat_lama]])->delete();

                $request->session()->flash('warning', 'Data Berhasil Dihapus');
                return redirect()->route($route, ['no_rawat' => $request->no_rawat]);
            } catch (\Exception $e){
                $request->session()->flash('error', 'Data Gagal Dihapus');
                return redirect()->back();
            }
        }else{
            try{
                DB::table($namatabel)->where('no_rawat',$request->no_rawat)->delete();
                $request->session()->flash('warning', 'Data Berhasil Dihapus');
                return redirect()->back();
            } catch (\Exception $e){
                $request->session()->flash('error', 'Data Gagal Dihapus');
                return redirect()->back();
            }
        }
    }
}