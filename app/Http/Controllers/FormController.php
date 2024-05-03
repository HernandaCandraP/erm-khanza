<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;

class FormController extends Controller
{
    public function formsoap(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');


        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'a.tgl_registrasi')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        // dd($detailpasien);

        // get list semua dokter
        // $listdokter = DB::table('dokter')->where('status', '1')->get();

        if ($no_rawat != null && $request->tgl_perawatan != null && $request->jam_rawat != null) {
            $data = DB::table('pemeriksaan_ralan')->select('pemeriksaan_ralan.*', 'pegawai.nama')->where([['no_rawat', '=',$no_rawat], ['tgl_perawatan', '=',$request->tgl_perawatan], ['jam_rawat', '=',$request->jam_rawat]])->leftjoin('pegawai', 'pegawai.nik', 'pemeriksaan_ralan.nip')->first();
            $data->tgl_perawatan_lama = $request->tgl_perawatan;
            $data->jam_rawat_lama = $request->jam_rawat;
        }else{
            $data = DB::table('pemeriksaan_ralan')->first();
            foreach ($data as $key => $value) {
                $data->$key = "";
            }
            $data->nip = $kd_dokter;
            $data->nama = Auth::guard('pegawai')->user()->nama;
        }

        return view('form/soap', [
            'detailpasien' => $detailpasien,
            'data' => $data,
            'no_rawat' => $no_rawat
        ]);
    }

    public function rjawalmedisparu(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        if (DB::table('penilaian_medis_ralan_paru')->where('no_rawat', '=', $no_rawat)->exists()) {
            $penilaianralanparu = DB::table('penilaian_medis_ralan_paru')->select('penilaian_medis_ralan_paru.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'penilaian_medis_ralan_paru.kd_dokter')->first();
        }else{
            $penilaianralanparu = DB::table('penilaian_medis_ralan_paru')->first();
            foreach ($penilaianralanparu as $key => $value) {
                $penilaianralanparu->$key = "";
            }
            $penilaianralanparu->kd_dokter = $kd_dokter;
            $penilaianralanparu->nama = Auth::guard('pegawai')->user()->nama;
        }

        return view('form/rjawalmedisparu', [
            'detailpasien' => $detailpasien, 
            // 'listdokter' => $listdokter, 
            'penilaianralanparu' => $penilaianralanparu,
            'no_rawat' => $no_rawat
        ]);
    }

    public function rjpenanganandokter(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        $jnspenanganan = DB::table('jns_perawatan as a')->select('a.kd_jenis_prw', 'a.nm_perawatan')->where('status', '1')->where('total_byrdr', '>', '0')->orderby('a.nm_perawatan', 'ASC')->get();

        $getpenanganandr = DB::table('rawat_jl_dr as dr')->select('dr.*','p.nama', 'jns.nm_perawatan')
            ->leftjoin('pegawai as p', 'dr.kd_dokter','=', 'p.nik')
            ->leftjoin('jns_perawatan as jns', 'dr.kd_jenis_prw','=', 'jns.kd_jenis_prw')
            ->where('no_rawat', $no_rawat)->get();

        return view('form/rjpenanganandokter', [
            'detailpasien' => $detailpasien, 
            'jnspenanganan' => $jnspenanganan,
            'getpenanganandr' => $getpenanganandr,
            'no_rawat' => $no_rawat
        ]);
    }

    public function rjdiagnosa(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        $jnspenanganan = DB::table('jns_perawatan as a')->select('a.kd_jenis_prw', 'a.nm_perawatan')->where('status', '1')->where('total_byrdr', '>', '0')->orderby('a.nm_perawatan', 'ASC')->get();

        $diagnosapasien = DB::table('diagnosa_pasien as dp')->select('dp.*','p.nm_penyakit')
            ->leftjoin('penyakit as p', 'dp.kd_penyakit','=', 'p.kd_penyakit')
            ->where('dp.no_rawat', $no_rawat)->get();

        return view('form/rjdiagnosa', [
            'detailpasien' => $detailpasien, 
            'jnspenanganan' => $jnspenanganan,
            'diagnosapasien' => $diagnosapasien,
            'no_rawat' => $no_rawat
        ]);
    }

    public function rjresume(Request $request){
         // get session nama & kode poli
         $kd_dokter = Session::get('username');
         $nmpoli = Session::get('nmpoli');
 
         $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
         $no_rawat = $request->no_rawat;

        if (DB::table('resume_pasien')->where('no_rawat', '=', $no_rawat)->exists()) {
            $dataresume = DB::table('resume_pasien')->select('resume_pasien.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'resume_pasien.kd_dokter')->first();
        }else{
            $dataresume = DB::table('resume_pasien')->first();
            foreach ($dataresume as $key => $value) {
                $dataresume->$key = "";
            }
            $dataresume->kd_dokter = $kd_dokter;
            $dataresume->nama = Auth::guard('pegawai')->user()->nama;
        }

        //  dd($dataresume);
         return view('form/resume', [
             'detailpasien' => $detailpasien, 
             'dataresume' => $dataresume,
             'no_rawat' => $no_rawat
         ]);
    }

    public function permintaanlab(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();

        return view('form/permintaanlab', [
            'detailpasien' => $detailpasien, 
            'jnsperawatanlab' => $jnsperawatanlab,
            'no_rawat' => $no_rawat
        ]);
    }

    public function permintaanrad(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan')->where('status', '1')->get();

        return view('form/permintaanrad', [
            'detailpasien' => $detailpasien, 
            'jnsperawatanrad' => $jnsperawatanrad,
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
        }
        foreach ($request->all() as $i => $value) {
            // unset($request[$i]);
            if(!$request->filled($i)){
                $request->merge([$i => '-']);
            }
        }

        if($request->tabel == 'DataResumeMedisPasienRawatJalan'){
            $DataResumeMedisPasienRawatJalan = [
                'no_rawat' => $request->no_rawat,
                'kd_dokter' => $request->nip,
                'keluhan_utama' => $request->keluhan_utama, 
                'jalannya_penyakit' => $request->jalannya_penyakit,
                'pemeriksaan_penunjang' => $request->pemeriksaan_penunjang, 
                'hasil_laborat' => $request->hasil_laborat,
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
                'obat_pulang' => $request->obat_pulang
            ];
            return $this->func_create($request, $DataResumeMedisPasienRawatJalan, 'resume_pasien', 'form/resume');
        }
        elseif ($request->tabel == 'PenilaianAwalMedisRawatJalanParu') {
            $PenilaianAwalMedisRawatJalanParu = [
                'no_rawat' => $request->no_rawat,
                'kd_dokter' => $request->nip,
                'tanggal' => $request->tanggal,
                'anamnesis' => $request->anamnesis,
                'hubungan' => $request->hubungan,
                'keluhan_utama' => $request->keluhan_utama,
                'rpo' => $request->rpo,
                'rps' => $request->rps,
                'rpd' => $request->rpd,
                'alergi' => $request->alergi,
                'kesadaran' => $request->kesadaran,
                'td' => $request->td,
                'nadi' => $request->nadi,
                'suhu' => $request->suhu,
                'rr' => $request->rr,
                'status' => $request->status,
                'bb' => $request->bb,
                'nyeri' => $request->nyeri,
                'gcs' => $request->gcs,
                'kepala' => $request->kepala,
                'thoraks' => $request->thoraks,
                'abdomen' => $request->abdomen,
                'muskulos' => $request->muskulos,
                'lainnya' => $request->lainnya,
                'ket_lokalis' => $request->ket_lokalis,
                'lab' => $request->lab,
                'rad' => $request->rad,
                'pemeriksaan' => $request->pemeriksaan,
                'diagnosis' => $request->diagnosis,
                'diagnosis2' => $request->diagnosis2,
                'permasalahan' => $request->permasalahan,
                'terapi' => $request->terapi,
                'tindakan' => $request->tindakan,
                'edukasi' => $request->edukasi
            ];
            return $this->func_create($request, $PenilaianAwalMedisRawatJalanParu, 'penilaian_medis_ralan_paru', 'form/awalmedisparu');
        }
        elseif ($request->tabel == 'DataPemeriksaanRawatJalan') {
            $DataPemeriksaanRawatJalan = [
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
                'lingkar_perut' => $request->lingkar_perut,
                'alergi' => $request->alergi,
                'penilaian' => $request->penilaian,
                'rtl' => $request->rtl,
                'instruksi' => $request->instruksi,
                'evaluasi' => $request->evaluasi
            ];
            return $this->func_create($request, $DataPemeriksaanRawatJalan, 'pemeriksaan_ralan', 'form-soap');
        }
        elseif ($request->tabel == 'PermintaanLab') {
            $data = date('Ymd', strtotime($request->tgl_permintaan));
            $noorder = DB::table('permintaan_lab')->where('tgl_permintaan', $request->tgl_permintaan)->count();
            $noorderPK = "PK". $data . sprintf("%04s", $noorder+1);
            $pemeriksaanlab = [];
            $detailpermintaanlab = [];
            if($request->pemeriksaan){
                foreach ($request->pemeriksaan as $key => $value) {
                    list($a, $b) = explode('***', $value);
                    $dtpemeriksaanlab = ['noorder'=>$noorderPK, 'kd_jenis_prw'=> $a, 'stts_bayar' => 'Belum'];
                    array_push($pemeriksaanlab, $dtpemeriksaanlab);
                    if($b){
                        $dtdetailpermintaanlab = ['noorder'=>$noorderPK, 'kd_jenis_prw'=> $a, 'id_template' => $b ,'stts_bayar' => 'Belum'];
                        array_push($detailpermintaanlab, $dtdetailpermintaanlab);
                    }
                }
            }
            $permintaanLab = [
                'noorder' => $noorderPK,
                'no_rawat' => $request->no_rawat,
                'tgl_permintaan' => $request->tgl_permintaan,
                'jam_permintaan' => $request->jam_permintaan,
                'dokter_perujuk' => $request->nip,
                'status' => "ralan",
                'informasi_tambahan' => $request->informasi_tambahan,
                'diagnosa_klinis' => $request->diagnosa_klinis
            ];
            DB::table('permintaan_lab')->insert($permintaanLab);
            DB::table('permintaan_pemeriksaan_lab')->insert($pemeriksaanlab);
            DB::table('permintaan_detail_permintaan_lab')->insert($detailpermintaanlab);
            $request->session()->flash('success', 'Berhasil menyimpan permintaan laboratorium');
            return redirect()->back();
        }
        elseif ($request->tabel == 'PermintaanRadiologi') {
            $data = date('Ymd', strtotime($request->tgl_permintaan));
            // dd($data);
            $noorder = DB::table('permintaan_radiologi')->where('tgl_permintaan', $request->tgl_permintaan)->count();
            $noorderPR = "PR". $data . sprintf("%04s", $noorder+1);
            // dd($noorderPR);
            $pemeriksaanrad = [];
            if($request->pemeriksaan){
                foreach ($request->pemeriksaan as $key => $value) {
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
                'status' => "ralan",
                'informasi_tambahan' => $request->informasi_tambahan,
                'diagnosa_klinis' => $request->diagnosa_klinis
            ];
            DB::table('permintaan_radiologi')->insert($permintaanRad);
            DB::table('permintaan_pemeriksaan_radiologi')->insert($pemeriksaanrad);
            $request->session()->flash('success', 'Berhasil menyimpan permintaan Radiologi');
            return redirect()->back();
        }
        elseif ($request->tabel == 'PenangananDokter') {
            // dd($request->all());
            $reg_periksa = DB::table('reg_periksa')->where('no_rawat', $request->no_rawat)->first();
            if($reg_periksa->status_bayar == 'Sudah Bayar'){
                $request->session()->flash('error', 'Data billing sudah terferifikasi,  Silahkan hubungi bagian kasir / keuangan');
                return redirect()->back();
            }

            $penanganandokter = [];
            if($request->pemeriksaan){
                foreach ($request->pemeriksaan as $key => $value) {
                    $jnspenanganan = DB::table('jns_perawatan')->select('kd_jenis_prw', 'material', 'bhp', 'tarif_tindakandr', 'kso', 'menejemen', 'total_byrdr')->where('kd_jenis_prw', $value)->first();

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
                        'biaya_rawat'=> $jnspenanganan->total_byrdr,
                        'stts_bayar' => 'Belum'
                    ];
                    array_push($penanganandokter, $dtpenanganandokter);
                }
            }else{
                $request->session()->flash('error', 'Belum memilih penaganan dokter');
                return redirect()->back();
            }
            DB::table('rawat_jl_dr')->insert($penanganandokter);
            $request->session()->flash('success', 'Berhasil menyimpan penanganan dokter');
            return redirect()->back();
        }
    }
    
    private function func_create(Request $request, $data, $namatabel, $route){
        // dd($request->all());
        DB::beginTransaction();
        try{
            // dd($data->all());
            // if (DB::table($namatabel)->where('no_rawat', '=', $data['no_rawat'])->exists()) {
            //     DB::table($namatabel)->where('no_rawat','=', $data['no_rawat'])->update($data);
            // }else{
            //     DB::table($namatabel)->insert($data);
            // }
            
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
            // dd($e);
            DB::rollback(); 
            $request->session()->flash('error', 'Gagal'.$e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function listdata(Request $request){
        // dd($request->all());
        if($request->tabel == 'DataPemeriksaanRawatJalan'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('pemeriksaan_ralan as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.nip','=', 'd.nik')
                ->orderby('c.tgl_perawatan', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            return $this->func_listdata($request, 'form/listsoap', $data, 'Tidak Ada Riwayat Soap');
        }
        elseif($request->tabel == 'DataResumeMedisPasienRawatJalan'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('resume_pasien as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
                ->orderby('c.no_rawat', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            return $this->func_listdata($request, 'form/listresume', $data, 'Tidak Ada Riwayat Resume');
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanParu'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('penilaian_medis_ralan_paru as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
                ->orderby('c.no_rawat', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            // dd($data->get());
            return $this->func_listdata($request, 'form/listrjawalmedisparu', $data, 'Tidak Ada Riwayat Resume');
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
        if($request->tabel == 'DataPemeriksaanRawatJalan'){
            return $this->func_delete($request, 'pemeriksaan_ralan', "form-soap");
        }
        elseif($request->tabel == 'DataResumeMedisPasienRawatJalan'){
            return $this->func_delete($request, 'resume_pasien', "false");
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanParu'){
            return $this->func_delete($request, 'penilaian_medis_ralan_paru', "false");
        }
        elseif($request->tabel == 'PenangananDokter'){
            // return $this->func_delete($request, 'rawat_jl_dr', "form-penanganandokter");
            try{
                DB::table('rawat_jl_dr')->where([['no_rawat','=', $request->no_rawat], ['tgl_perawatan', '=',$request->tgl_perawatan_lama], ['jam_rawat', '=',$request->jam_rawat_lama], ['kd_jenis_prw', '=',$request->kd_jenis_prw]])->delete();

                $request->session()->flash('warning', 'Data Berhasil Dihapus');
                return redirect()->route('form-penanganandokter', ['no_rawat' => $request->no_rawat]);
            } catch (\Exception $e){
                $request->session()->flash('error', 'Data Gagal Dihapus');
                return redirect()->back();
            }
        }
        dd("dekarasi tabel belum ada");
    }

    private function func_delete(Request $request, $namatabel, $route)
    {

        // dd($request->all());
        if($request->niplama != Auth::guard('pegawai')->user()->nik){
            $request->session()->flash('error', "Data Tidak Dapat Dihapus, Dokter Penangung Jawab Tidak Sama");
            return redirect()->back();
        }

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
