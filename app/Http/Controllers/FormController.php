<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;

class FormController extends Controller
{
    public function menurajal(Request $request){
        $username = Session::get('username');
        $month = date('Y-m'); //'2023-06'; // 
        $date = date('Y-m-d'); //'2023-06-15'; // 
        
        $kdpoli = Session::get('getpoli');

        // $pasienaktif = DB::table('reg_periksa as rp')->select('p.no_rkm_medis', DB::raw('count(*) as jumlah'), 'p.nm_pasien')->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')->where('kd_dokter', $username)->groupby('no_rkm_medis')->orderby('jumlah', 'desc')->take(10)->get();

        $pasienterakhir = DB::table('reg_periksa as a')->select('a.no_rawat', 'b.no_rkm_medis', 'b.nm_pasien', 'a.stts', 'c.nm_dokter')
        ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
        ->leftjoin('dokter as c', 'a.kd_dokter', '=', 'c.kd_dokter')
        ->where('a.kd_dokter', $username)->where('a.tgl_registrasi', $date)->where('a.status_lanjut', 'Ralan')->orderby('a.jam_reg', 'desc')->get();

        $ceksoapdokter = DB::table('reg_periksa as a')->select('a.no_rawat', 'b.no_rkm_medis', 'b.nm_pasien', 'a.stts','pr.nip as petugasrs')->distinct()
        ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
        ->leftjoin('pemeriksaan_ralan as pr', 'a.no_rawat','=', 'pr.no_rawat')
        ->where('a.kd_dokter', $username)->where('a.tgl_registrasi', $date)->where('a.status_lanjut', 'Ralan')
        ->where('pr.nip', '=', $username)
        ->orderby('a.jam_reg', 'desc')->get();

        $awalmedisbedah = DB::table('reg_periksa as a')->select('a.no_rawat', 'b.no_rkm_medis', 'b.nm_pasien', 'a.stts','pr.kd_dokter as petugasrs')->distinct()
        ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
        ->leftjoin('penilaian_medis_ralan_bedah as pr', 'a.no_rawat','=', 'pr.no_rawat')
        ->where('a.kd_dokter', $username)->where('a.tgl_registrasi', $date)->where('a.status_lanjut', 'Ralan')
        ->where('pr.kd_dokter', '=', $username)
        ->orderby('a.jam_reg', 'desc')->get();

        foreach ($pasienterakhir as $key => $value) {
            $pasienterakhir[$key]->stts = "Belum";
            if($ceksoapdokter->isNotEmpty()){
                foreach ($ceksoapdokter as $keysoap => $soapsudah) {
                    if($value->no_rawat == $soapsudah->no_rawat){
                        $pasienterakhir[$key]->stts = "Sudah";
                    }
                }
            }
            if($awalmedisbedah->isNotEmpty()){
                foreach ($awalmedisbedah as $keysoap => $ambedah) {
                    if($value->no_rawat == $ambedah->no_rawat){
                        $pasienterakhir[$key]->stts = "Sudah";
                    }
                }
            }
        }

        $rujukaninternal = DB::table('reg_periksa as a')->select('a.no_rawat', 'b.no_rkm_medis', 'b.nm_pasien', 'a.stts', 'p.nm_poli')
        ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
        ->leftjoin('rujukan_internal_poli as int', 'int.no_rawat', '=', 'a.no_rawat')
        ->leftjoin('poliklinik as p', 'p.kd_poli', '=', 'a.kd_poli')
        ->where('int.kd_dokter', $username)->where('a.tgl_registrasi', $date)
        ->orderby('a.jam_reg', 'desc')->get();

        return view('form/list-rajal', [
            'rujukaninternal' => $rujukaninternal, 
            'pasienterakhir' => $pasienterakhir, 
        ]);
    }

    public function formsoap(Request $request){
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
            $data = DB::table('pemeriksaan_ralan')->select('pemeriksaan_ralan.*', 'pegawai.nama')->where([['no_rawat', '=',$no_rawat], ['tgl_perawatan', '=',$request->tgl_perawatan], ['jam_rawat', '=',$request->jam_rawat]])->leftjoin('pegawai', 'pegawai.nik', 'pemeriksaan_ralan.nip')->first();
            $data->tgl_perawatan_lama = $request->tgl_perawatan;
            $data->jam_rawat_lama = $request->jam_rawat;
        }else{
            $data = DB::table('pemeriksaan_ralan')->where('no_rawat', $request->no_rawat)->orderBy('tgl_perawatan', 'DESC')->orderBy('jam_rawat', 'DESC')->first();
        
            // ambil riwayat alergi
            $riwayatalergi = DB::table('reg_periksa as a')->select('a.no_rawat', 'pr.alergi')->distinct()
            ->join('pemeriksaan_ralan as pr', 'pr.no_rawat','=', 'a.no_rawat')
            ->where('a.no_rkm_medis', $detailpasien[0]->no_rkm_medis)
            ->where('pr.alergi', '!=', '')
            ->where('pr.alergi', '!=', '-')
            ->groupby('pr.alergi')
            ->limit(1)
            ->get();
            
            // cek apakah ada data di pemeriksaan ralan, jika tidak maka ambil data pemeriksaan lain
            if($data != null){
                foreach ($data as $key => $value) {
                    // ambil data ttv sebelumnya

                    // dokter yang meminta data soap diambil semua dari riwayat petugas
                    if($kd_dokter == '0058' || $kd_dokter == '0087' || $kd_dokter == 'dokteragus' || $kd_dokter == '0060'){
                        if($key == 'penilaian'){
                            $data->$key = "";
                        }else{
                            $data->$key = $value;
                        }
                    }else{
                        if($key == 'suhu_tubuh' || $key == 'tensi' || $key == 'nadi' || $key == 'respirasi' || $key == 'tinggi' || $key == 'berat' || $key == 'spo2' || $key == 'gcs' || $key == 'lingkar_perut' || $key == 'alergi' ){
                            $data->$key = $value;
                        }else{
                            $data->$key = "";
                        }
                    }
                }
            }else{
                $data = $data = DB::table('pemeriksaan_ralan')->first();
                foreach ($data as $key => $value) {
                    $data->$key = "";
                }
            }

            // tampilkan alergi di soap
            $alergi = '';
            if($riwayatalergi != null){
                foreach ($riwayatalergi as $key => $value) {
                    $alergi .= $value->alergi . ', ';
                }
                $data->alergi = $alergi;
            }

            $data->nip = $kd_dokter;
            $data->nama = Auth::guard('pegawai')->user()->nama;
        }

        $getsps = Session::get('nm_sps');
        $getimage = '';
        if($getsps == 'Bedah'){
            $getimage = 'image/LokalisOrtho.png';
        }elseif($getsps == 'Paru'){
            $getimage = 'image/LokalisParu.png';
        }elseif($getsps == 'Syaraf' || $getsps == 'Anak' || $getsps == 'Dalam'|| $getsps == 'Obsgyn'){
            $getimage = 'image/semua.png';
        }elseif($getsps == 'Mata' ){
            $getimage = 'image/mata.png';
        }elseif($getsps == 'Jantung' ){
            $getimage = 'image/jantung.jpg';
        }else{
            dd('gambar belum ada');
        }

        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'a.total_byr', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();
        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'a.total_byr')->where('status', '1')->get();
                
        // $jnspenyakit = DB::table('penyakit as p')->select('p.kd_penyakit', 'p.nm_penyakit')->orderby('kd_penyakit', 'ASC')->get();

        return view('form/soap', [
            'detailpasien' => $detailpasien,
            'data' => $data,
            'getimage' => $getimage,
            'resepdokter' => $resepdokter,
            // 'jnspenyakit' => $jnspenyakit,
            'jnsperawatanlab' => $jnsperawatanlab,
            'jnsperawatanrad' => $jnsperawatanrad,
            'no_rawat' => $no_rawat
        ]);
    }

    public function rincianbiaya(Request $request){
        //biaya radiologi
        $biayarad = DB::table('periksa_radiologi as pr')->select('pr.*', 'jpr.nm_perawatan', DB::raw('count(*) as total'))
            ->leftjoin('jns_perawatan_radiologi as jpr', 'jpr.kd_jenis_prw', '=', 'pr.kd_jenis_prw')
            ->where('pr.no_rawat', $request->no_rawat)
            ->orderBy('jpr.nm_perawatan', 'DESC')
            ->groupBy('pr.kd_jenis_prw') 
            ->get();
        
        // Bacaan Rad
        $bacaanrad = DB::table('hasil_radiologi')->where('no_rawat', $request->no_rawat)->orderBy('tgl_periksa', 'DESC')->get();
        
        $getgambar = DB::table('gambar_radiologi')->where('no_rawat', $request->no_rawat)->orderBy('tgl_periksa', 'DESC')->get();

        $gambarrad = [];
        if($getgambar->isNotEmpty()){
            foreach ($getgambar as $key => $value) {
                $gambarrad[] = str_replace("simrs-rsba", "", env('APP_URL')) .'budiasih/radiologi/'. $value->lokasi_gambar;
            }
        }

        // biaya lab
        $biayalab = DB::table('periksa_lab as pl')->select('pl.*', 'jpl.nm_perawatan', DB::raw('count(*) as total'))
            ->leftjoin('jns_perawatan_lab as jpl', 'jpl.kd_jenis_prw', '=', 'pl.kd_jenis_prw')
            ->where('pl.no_rawat', $request->no_rawat)
            ->orderBy('jpl.nm_perawatan', 'DESC')
            ->groupBy('pl.kd_jenis_prw') 
            ->get();
        
        // bacaan lab
        $bacaanlab =  DB::table('detail_periksa_lab as detail')->select('detail.*', 'jpl.nm_perawatan', 'b.pemeriksaan', 'b.satuan')
            ->leftjoin('jns_perawatan_lab as jpl', 'jpl.kd_jenis_prw', '=', 'detail.kd_jenis_prw')
            ->leftjoin('template_laboratorium as b', 'b.id_template', '=', 'detail.id_template')
            ->orderBy('jpl.nm_perawatan', 'DESC')
            ->where('no_rawat', $request->no_rawat)
            ->get();

        // biaya obat
        $biayaobat = DB::table('detail_pemberian_obat as dpo')->select('dpo.*', 'db.nama_brng')
            ->leftjoin('databarang as db', 'db.kode_brng', '=', 'dpo.kode_brng')
            ->where('dpo.no_rawat', $request->no_rawat)
            ->orderBy('db.nama_brng', 'DESC')
            ->get();
        
        // biaya penanganan dokter
        $biayapenanganandr = DB::table('rawat_jl_dr as dr')->select('dr.*', 'jns.nm_perawatan', DB::raw('count(*) as total'))
            ->leftjoin('jns_perawatan as jns', 'dr.kd_jenis_prw','=', 'jns.kd_jenis_prw')
            ->where('dr.no_rawat',  $request->no_rawat)
            ->orderBy('jns.nm_perawatan', 'DESC')
            ->groupBy('jns.kd_jenis_prw') 
            ->get();
        
        // biaya penanganan perawat
        $biayapenangananpr = DB::table('rawat_jl_pr as pr')->select('pr.*', 'jns.nm_perawatan', DB::raw('count(*) as total'))
            ->leftjoin('jns_perawatan as jns', 'pr.kd_jenis_prw','=', 'jns.kd_jenis_prw')
            ->where('pr.no_rawat',  $request->no_rawat)
            ->orderBy('jns.nm_perawatan', 'DESC')
            ->groupBy('jns.kd_jenis_prw') 
            ->get();

        // biaya administrasi
        $detailpasien = DB::table('reg_periksa as a')->select('a.biaya_reg','b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        // dd($request->all());
        $hargatotal = 0;
        foreach($biayarad as $key => $value) {
            // print($value->nm_perawatan . "|| biaya : ". $value->biaya . "|| Total : ". $value->total ."|| Harga Total : ". $value->total * $value->biaya. "<br>");
            $hargatotal += $value->total * $value->biaya;
        }

        foreach($biayalab as $key => $value) {
            // print($value->nm_perawatan . "|| biaya : ". $value->biaya . "|| Total : ". $value->total ."|| Harga Total : ". $value->total * $value->biaya. "<br>");
            $hargatotal += $value->total * $value->biaya;
        }

        foreach($biayaobat as $key => $value) {
            // print($value->nama_brng . "|| harga : ". $value->biaya_obat ."|| Jumlah : ". $value->jml ."|| Harga Total : ". $value->total. "<br>");
            $hargatotal += $value->total;
        }

        foreach($biayapenanganandr as $key => $value) {
            // print($value->nm_perawatan . "|| harga : ". $value->biaya_rawat ."|| Jumlah : ". $value->total ."|| Harga Total : ". $value->total * $value->biaya_rawat. "<br>");
            $hargatotal += $value->total * $value->biaya_rawat;
        }

        foreach($biayapenangananpr as $key => $value) {
            // print($value->nm_perawatan . "|| harga : ". $value->biaya_rawat ."|| Jumlah : ". $value->total ."|| Harga Total : ". $value->total * $value->biaya_rawat. "<br>");
            $hargatotal += $value->total * $value->biaya_rawat;
        }

        $hargatotal += $detailpasien[0]->biaya_reg;
        
        // dd($bacaanlab, $hargatotal);

        return view('form/rincianbiaya', [
            'detailpasien' => $detailpasien,
            'biayarad' => $biayarad,
            'biayalab' => $biayalab,
            'biayaobat' => $biayaobat,
            'biayapenanganandr' => $biayapenanganandr,
            'biayapenangananpr' => $biayapenangananpr,
            'hargatotal' => $hargatotal,
            'bacaanrad' => $bacaanrad,
            'bacaanlab' => $bacaanlab,
            'gambarrad' => $gambarrad,
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

            // ambil data ttv dari keperawatan umum
            $ambilttv = DB::table('penilaian_awal_keperawatan_ralan')->where('no_rawat', $request->no_rawat)->orderBy('tanggal', 'DESC')->first();
            if($ambilttv != null){
                $penilaianralanparu->suhu = $ambilttv->suhu;
                $penilaianralanparu->td = $ambilttv->td;
                $penilaianralanparu->nadi = $ambilttv->nadi;
                $penilaianralanparu->rr = $ambilttv->rr;
                $penilaianralanparu->bb = $ambilttv->bb;
                $penilaianralanparu->gcs = $ambilttv->gcs;
            }

            // dd($penilaianralanparu, $ambilttv);
        }

        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'a.total_byr', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();
        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'a.total_byr')->where('status', '1')->get();
        $resepdokter = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.ralan', 'db.letak_barang','jenis.nama', 'db.kode_sat', 'gudangbarang.stok')
        ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
        ->join('gudangbarang', 'gudangbarang.kode_brng','=', 'db.kode_brng')
        ->where('db.status', '=', '1')->where('gudangbarang.stok', '>', '0')
        ->groupby('gudangbarang.kode_brng')->orderby('db.nama_brng')->get();

        return view('form/rjawalmedisparu', [
            'detailpasien' => $detailpasien, 
            // 'listdokter' => $listdokter, 
            'jnsperawatanlab' => $jnsperawatanlab,
            'jnsperawatanrad' => $jnsperawatanrad,
            'resepdokter' => $resepdokter,
            'penilaianralanparu' => $penilaianralanparu,
            'no_rawat' => $no_rawat
        ]);
    }

    public function rjawalmedisbedah (Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        if (DB::table('penilaian_medis_ralan_bedah')->where('no_rawat', '=', $no_rawat)->exists()) {
            $penilaianralanbedah = DB::table('penilaian_medis_ralan_bedah')->select('penilaian_medis_ralan_bedah.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'penilaian_medis_ralan_bedah.kd_dokter')->first();
        }else{
            $penilaianralanbedah = DB::table('penilaian_medis_ralan_bedah')->first();
            foreach ($penilaianralanbedah as $key => $value) {
                $penilaianralanbedah->$key = "";
            }
            $penilaianralanbedah->kd_dokter = $kd_dokter;
            $penilaianralanbedah->nama = Auth::guard('pegawai')->user()->nama;

            // ambil data ttv dari keperawatan umum
            $ambilttv = DB::table('penilaian_awal_keperawatan_ralan')->where('no_rawat', $request->no_rawat)->orderBy('tanggal', 'DESC')->first();
            if($ambilttv != null){
                $penilaianralanbedah->suhu = $ambilttv->suhu;
                $penilaianralanbedah->td = $ambilttv->td;
                $penilaianralanbedah->nadi = $ambilttv->nadi;
                $penilaianralanbedah->rr = $ambilttv->rr;
                $penilaianralanbedah->bb = $ambilttv->bb;
                $penilaianralanbedah->gcs = $ambilttv->gcs;
            }
        }
        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();
        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan')->where('status', '1')->get();
        $resepdokter = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.ralan', 'db.letak_barang','jenis.nama', 'db.kode_sat', 'gudangbarang.stok')
        ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
        ->join('gudangbarang', 'gudangbarang.kode_brng','=', 'db.kode_brng')
        ->where('db.status', '=', '1')->where('gudangbarang.stok', '>', '0')
        ->groupby('gudangbarang.kode_brng')->orderby('db.nama_brng')->get();

        return view('form/rjawalmedisbedah', [
            'detailpasien' => $detailpasien, 
            // 'listdokter' => $listdokter, 
            'jnsperawatanlab' => $jnsperawatanlab,
            'jnsperawatanrad' => $jnsperawatanrad,
            'resepdokter' => $resepdokter,
            'penilaianralanbedah' => $penilaianralanbedah,
            'no_rawat' => $no_rawat
        ]);
    }

    public function rjawalmedisobsgyn (Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        if (DB::table('penilaian_medis_ralan_kandungan')->where('no_rawat', '=', $no_rawat)->exists()) {
            $penilaianralanobsgyn = DB::table('penilaian_medis_ralan_kandungan')->select('penilaian_medis_ralan_kandungan.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'penilaian_medis_ralan_kandungan.kd_dokter')->first();
        }else{
            $penilaianralanobsgyn = DB::table('penilaian_medis_ralan_kandungan')->first();
            foreach ($penilaianralanobsgyn as $key => $value) {
                $penilaianralanobsgyn->$key = "";
            }
            $penilaianralanobsgyn->kd_dokter = $kd_dokter;
            $penilaianralanobsgyn->nama = Auth::guard('pegawai')->user()->nama;

            // ambil data ttv dari keperawatan kebidanan
            $ambilttv = DB::table('penilaian_awal_keperawatan_kebidanan')->where('no_rawat', $request->no_rawat)->orderBy('tanggal', 'DESC')->first();
            if($ambilttv != null){
                $penilaianralanobsgyn->td = $ambilttv->td;
                $penilaianralanobsgyn->nadi = $ambilttv->nadi;
                $penilaianralanobsgyn->rr = $ambilttv->rr;
                $penilaianralanobsgyn->suhu = $ambilttv->suhu;
                $penilaianralanobsgyn->gcs = $ambilttv->gcs;
                $penilaianralanobsgyn->bb = $ambilttv->bb;
                $penilaianralanobsgyn->tb = $ambilttv->tb;
                $penilaianralanobsgyn->tfu = $ambilttv->tfu;
                $penilaianralanobsgyn->tbj = $ambilttv->tbj;
                $penilaianralanobsgyn->his = $ambilttv->his;
                $penilaianralanobsgyn->djj = $ambilttv->bjj;
            }
        }     
        
        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();
        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan')->where('status', '1')->get();
        $resepdokter = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.ralan', 'db.letak_barang','jenis.nama', 'db.kode_sat', 'gudangbarang.stok')
            ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
            ->join('gudangbarang', 'gudangbarang.kode_brng','=', 'db.kode_brng')
            ->where('db.status', '=', '1')->where('gudangbarang.stok', '>', '0')
            ->groupby('gudangbarang.kode_brng')->orderby('db.nama_brng')->get();

        return view('form/rjawalmedisobsgyn', [
            'detailpasien' => $detailpasien, 
            // 'listdokter' => $listdokter, 
            'jnsperawatanlab' => $jnsperawatanlab,
            'jnsperawatanrad' => $jnsperawatanrad,
            'resepdokter' => $resepdokter,
            'penilaianralanobsgyn' => $penilaianralanobsgyn,
            'no_rawat' => $no_rawat
        ]);
    }

    public function rjawalmedissyaraf (Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        if (DB::table('penilaian_medis_ralan_neurologi')->where('no_rawat', '=', $no_rawat)->exists()) {
            $penilaianralansyaraf = DB::table('penilaian_medis_ralan_neurologi')->select('penilaian_medis_ralan_neurologi.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'penilaian_medis_ralan_neurologi.kd_dokter')->first();
        }else{
            $penilaianralansyaraf = DB::table('penilaian_medis_ralan_neurologi')->first();
            foreach ($penilaianralansyaraf as $key => $value) {
                $penilaianralansyaraf->$key = "";
            }
            $penilaianralansyaraf->kd_dokter = $kd_dokter;
            $penilaianralansyaraf->nama = Auth::guard('pegawai')->user()->nama;

            // ambil data ttv dari keperawatan umum
            $ambilttv = DB::table('penilaian_awal_keperawatan_ralan')->where('no_rawat', $request->no_rawat)->orderBy('tanggal', 'DESC')->first();
            if($ambilttv != null){
                $penilaianralansyaraf->suhu = $ambilttv->suhu;
                $penilaianralansyaraf->td = $ambilttv->td;
                $penilaianralansyaraf->nadi = $ambilttv->nadi;
                $penilaianralansyaraf->rr = $ambilttv->rr;
                $penilaianralansyaraf->bb = $ambilttv->bb;
                $penilaianralansyaraf->gcs = $ambilttv->gcs;
            }
        }
        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();
        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan')->where('status', '1')->get();
        $resepdokter = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.ralan', 'db.letak_barang','jenis.nama', 'db.kode_sat', 'gudangbarang.stok')
        ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
        ->join('gudangbarang', 'gudangbarang.kode_brng','=', 'db.kode_brng')
        ->where('db.status', '=', '1')->where('gudangbarang.stok', '>', '0')
        ->groupby('gudangbarang.kode_brng')->orderby('db.nama_brng')->get();
        
        return view('form/rjawalmedissyaraf', [
            'detailpasien' => $detailpasien, 
            // 'listdokter' => $listdokter, 
            'jnsperawatanlab' => $jnsperawatanlab,
            'jnsperawatanrad' => $jnsperawatanrad,
            'resepdokter' => $resepdokter,
            'penilaianralansyaraf' => $penilaianralansyaraf,
            'no_rawat' => $no_rawat
        ]);
    }

    public function rjawalmedisanak (Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        if (DB::table('penilaian_medis_ralan_anak')->where('no_rawat', '=', $no_rawat)->exists()) {
            $penilaianralananak = DB::table('penilaian_medis_ralan_anak')->select('penilaian_medis_ralan_anak.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'penilaian_medis_ralan_anak.kd_dokter')->first();
        }else{
            $penilaianralananak = DB::table('penilaian_medis_ralan_anak')->first();
            foreach ($penilaianralananak as $key => $value) {
                $penilaianralananak->$key = "";
            }
            $penilaianralananak->kd_dokter = $kd_dokter;
            $penilaianralananak->nama = Auth::guard('pegawai')->user()->nama;

            // ambil data ttv dari keperawatan anak
            $ambilttv = DB::table('penilaian_awal_keperawatan_ralan_bayi')->where('no_rawat', $request->no_rawat)->orderBy('tanggal', 'DESC')->first();
            if($ambilttv != null){
                $penilaianralananak->suhu = $ambilttv->suhu;
                $penilaianralananak->td = $ambilttv->td;
                $penilaianralananak->nadi = $ambilttv->nadi;
                $penilaianralananak->rr = $ambilttv->rr;
                $penilaianralananak->bb = $ambilttv->bb;
                $penilaianralananak->tb = $ambilttv->tb;
                $penilaianralananak->gcs = $ambilttv->gcs;
            }
            // dd($ambilttv, $penilaianralananak);
        }

        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();
        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan')->where('status', '1')->get();
        $resepdokter = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.ralan', 'db.letak_barang','jenis.nama', 'db.kode_sat', 'gudangbarang.stok')
            ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
            ->join('gudangbarang', 'gudangbarang.kode_brng','=', 'db.kode_brng')
            ->where('db.status', '=', '1')->where('gudangbarang.stok', '>', '0')
            ->groupby('gudangbarang.kode_brng')->orderby('db.nama_brng')->get();
            
        return view('form/rjawalmedisanak', [
            'detailpasien' => $detailpasien, 
            // 'listdokter' => $listdokter, 
            'jnsperawatanlab' => $jnsperawatanlab,
            'jnsperawatanrad' => $jnsperawatanrad,
            'resepdokter' => $resepdokter,
            'penilaianralananak' => $penilaianralananak,
            'no_rawat' => $no_rawat
        ]);
    }

    public function rjawalmedismata (Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        if (DB::table('penilaian_medis_ralan_mata')->where('no_rawat', '=', $no_rawat)->exists()) {
            $penilaianralanmata = DB::table('penilaian_medis_ralan_mata')->select('penilaian_medis_ralan_mata.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'penilaian_medis_ralan_mata.kd_dokter')->first();
        }else{
            $penilaianralanmata = DB::table('penilaian_medis_ralan_mata')->first();
            foreach ($penilaianralanmata as $key => $value) {
                $penilaianralanmata->$key = "";
            }
            $penilaianralanmata->kd_dokter = $kd_dokter;
            $penilaianralanmata->nama = Auth::guard('pegawai')->user()->nama;

            // ambil data ttv dari keperawatan umum
            $ambilttv = DB::table('penilaian_awal_keperawatan_ralan')->where('no_rawat', $request->no_rawat)->orderBy('tanggal', 'DESC')->first();
            if($ambilttv != null){
                $penilaianralanmata->suhu = $ambilttv->suhu;
                $penilaianralanmata->td = $ambilttv->td;
                $penilaianralanmata->nadi = $ambilttv->nadi;
                $penilaianralanmata->rr = $ambilttv->rr;
                $penilaianralanmata->bb = $ambilttv->bb;
            }
            // dd($ambilttv, $penilaianralanmata);
        }

        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();
        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan')->where('status', '1')->get();
        $resepdokter = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.ralan', 'db.letak_barang','jenis.nama', 'db.kode_sat', 'gudangbarang.stok')
        ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
        ->join('gudangbarang', 'gudangbarang.kode_brng','=', 'db.kode_brng')
        ->where('db.status', '=', '1')->where('gudangbarang.stok', '>', '0')
        ->groupby('gudangbarang.kode_brng')->orderby('db.nama_brng')->get();

        return view('form/rjawalmedismata', [
            'detailpasien' => $detailpasien, 
            // 'listdokter' => $listdokter, 
            'jnsperawatanlab' => $jnsperawatanlab,
            'jnsperawatanrad' => $jnsperawatanrad,
            'resepdokter' => $resepdokter,
            'penilaianralanmata' => $penilaianralanmata,
            'no_rawat' => $no_rawat
        ]);
    }

    public function rjawalmedisdalam (Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        if (DB::table('penilaian_medis_ralan_penyakit_dalam')->where('no_rawat', '=', $no_rawat)->exists()) {
            $penilaianralandalam = DB::table('penilaian_medis_ralan_penyakit_dalam')->select('penilaian_medis_ralan_penyakit_dalam.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'penilaian_medis_ralan_penyakit_dalam.kd_dokter')->first();
        }else{
            $penilaianralandalam = DB::table('penilaian_medis_ralan_penyakit_dalam')->first();
            foreach ($penilaianralandalam as $key => $value) {
                $penilaianralandalam->$key = "";
            }
            $penilaianralandalam->kd_dokter = $kd_dokter;
            $penilaianralandalam->nama = Auth::guard('pegawai')->user()->nama;

            // ambil data ttv dari keperawatan umum
            $ambilttv = DB::table('penilaian_awal_keperawatan_ralan')->where('no_rawat', $request->no_rawat)->orderBy('tanggal', 'DESC')->first();
            if($ambilttv != null){
                $penilaianralandalam->suhu = $ambilttv->suhu;
                $penilaianralandalam->td = $ambilttv->td;
                $penilaianralandalam->nadi = $ambilttv->nadi;
                $penilaianralandalam->rr = $ambilttv->rr;
                $penilaianralandalam->bb = $ambilttv->bb;
                $penilaianralandalam->gcs = $ambilttv->gcs;
            }
            // dd($ambilttv, $penilaianralandalam);
        }

        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();
        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan')->where('status', '1')->get();
        $resepdokter = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.ralan', 'db.letak_barang','jenis.nama', 'db.kode_sat', 'gudangbarang.stok')
        ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
        ->join('gudangbarang', 'gudangbarang.kode_brng','=', 'db.kode_brng')
        ->where('db.status', '=', '1')->where('gudangbarang.stok', '>', '0')
        ->groupby('gudangbarang.kode_brng')->orderby('db.nama_brng')->get();

        return view('form/rjawalmedisdalam', [
            'detailpasien' => $detailpasien, 
            // 'listdokter' => $listdokter, 
            'jnsperawatanlab' => $jnsperawatanlab,
            'jnsperawatanrad' => $jnsperawatanrad,
            'resepdokter' => $resepdokter,
            'penilaianralandalam' => $penilaianralandalam,
            'no_rawat' => $no_rawat
        ]);
    }

    public function rjawalmedisumum (Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        if (DB::table('penilaian_medis_ralan')->where('no_rawat', '=', $no_rawat)->exists()) {
            $penilaianralanumum = DB::table('penilaian_medis_ralan')->select('penilaian_medis_ralan.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'penilaian_medis_ralan.kd_dokter')->first();
        }else{
            $penilaianralanumum = DB::table('penilaian_medis_ralan')->first();
            foreach ($penilaianralanumum as $key => $value) {
                $penilaianralanumum->$key = "";
            }
            $penilaianralanumum->kd_dokter = $kd_dokter;
            $penilaianralanumum->nama = Auth::guard('pegawai')->user()->nama;

            // ambil data ttv dari keperawatan umum
            $ambilttv = DB::table('penilaian_awal_keperawatan_ralan')->where('no_rawat', $request->no_rawat)->orderBy('tanggal', 'DESC')->first();
            if($ambilttv != null){
                $penilaianralanumum->suhu = $ambilttv->suhu;
                $penilaianralanumum->td = $ambilttv->td;
                $penilaianralanumum->nadi = $ambilttv->nadi;
                $penilaianralanumum->rr = $ambilttv->rr;
                $penilaianralanumum->bb = $ambilttv->bb;
                $penilaianralanumum->gcs = $ambilttv->gcs;
            }
        }

        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();
        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan')->where('status', '1')->get();
        $resepdokter = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.ralan', 'db.letak_barang','jenis.nama', 'db.kode_sat', 'gudangbarang.stok')
            ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
            ->join('gudangbarang', 'gudangbarang.kode_brng','=', 'db.kode_brng')
            ->where('db.status', '=', '1')->where('gudangbarang.stok', '>', '0')
            ->groupby('gudangbarang.kode_brng')->orderby('db.nama_brng')->get();

        return view('form/rjawalmedisumum', [
            'detailpasien' => $detailpasien, 
            // 'listdokter' => $listdokter, 
            'jnsperawatanlab' => $jnsperawatanlab,
            'jnsperawatanrad' => $jnsperawatanrad,
            'resepdokter' => $resepdokter,
            'nmpoli' => $nmpoli,
            'penilaianralanumum' => $penilaianralanumum,
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

        $cekriwayatresume = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
            ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
            ->join('resume_pasien as c', 'a.no_rawat','=', 'c.no_rawat')
            ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
            ->where('a.no_rawat', $request->no_rawat)->count();

        if (DB::table('resume_pasien')->where('no_rawat', '=', $no_rawat)->exists()) {
            $dataresume = DB::table('resume_pasien')->select('resume_pasien.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'resume_pasien.kd_dokter')->first();
        }else{
            $dataresume = DB::table('resume_pasien')->first();
            foreach ($dataresume as $key => $value) {
                $dataresume->$key = "";
            }
            $dataresume->kd_dokter = $kd_dokter;
            $dataresume->nama = Auth::guard('pegawai')->user()->nama;

            $keluhanutama = DB::table('pemeriksaan_ralan')->select('keluhan', 'pemeriksaan', 'penilaian')
                ->where('no_rawat', $request->no_rawat)
                ->where('nip', $kd_dokter)
                ->orderby('jam_rawat', 'DESC')->first();

            $penunjanglab = DB::table('permintaan_lab as pl')->select('jpl.nm_perawatan')
                ->join('permintaan_pemeriksaan_lab as ppl', 'pl.noorder','=', 'ppl.noorder')
                ->join('jns_perawatan_lab as jpl', 'jpl.kd_jenis_prw','=', 'ppl.kd_jenis_prw')
                ->where('pl.no_rawat', $request->no_rawat)
                ->get();

            $penunjangrad = DB::table('permintaan_radiologi as pr')->select('jpr.nm_perawatan')
                ->join('permintaan_pemeriksaan_radiologi as ppr', 'pr.noorder','=', 'ppr.noorder')
                ->join('jns_perawatan_radiologi as jpr', 'jpr.kd_jenis_prw','=', 'ppr.kd_jenis_prw')
                ->where('pr.no_rawat', $request->no_rawat)
                ->get();
            
            // cek diagnosa pasien yang telah dimasukkan
            $cekdiagnosa = DB::table('diagnosa_pasien as dp')->select('dp.*', 'p.nm_penyakit')
                ->join('penyakit as p', 'p.kd_penyakit','=', 'dp.kd_penyakit')
                ->where('dp.no_rawat', $request->no_rawat)->where('dp.status', 'Ralan')->orderby('dp.prioritas', 'ASC')->get();

            // cek prosedur pasien yang telah dimasukkan
            $cekprosedur = DB::table('prosedur_pasien as pp')->select('pp.*', 'i.deskripsi_panjang')
                ->join('icd9 as i', 'i.kode','=', 'pp.kode')
                ->where('pp.no_rawat', $request->no_rawat)->where('pp.status', 'Ralan')->orderby('pp.prioritas', 'ASC')->get();

            $listpenunjanglab= "";
            if($penunjanglab->isNotEmpty()){
                $listpenunjanglab .= "Penunjang Laboratorium : ";
                foreach ($penunjanglab as $key => $value) {
                    $listpenunjanglab .= $value->nm_perawatan . ", ";
                }
            }

            $listpenunjangrad= "";
            if($penunjangrad->isNotEmpty()){
                // cek jika penunjang lab ada isi maka jalankan next line
                if($listpenunjanglab != null){
                    $listpenunjangrad .= "\r\n";
                }
                $listpenunjangrad .= "Penunjang Radiologi : ";
                foreach ($penunjangrad as $key => $value) {
                    $listpenunjangrad .= $value->nm_perawatan . ", ";
                }
            }
            $dataresume->pemeriksaan_penunjang = $listpenunjanglab . $listpenunjangrad;
            $dataresume->keluhan_utama = $keluhanutama->keluhan ?? '';
            $dataresume->jalannya_penyakit = $keluhanutama->pemeriksaan ?? '';

            // cek diagnosa 
            if($cekdiagnosa->isNotEmpty()){
                if (isset($cekdiagnosa[0])) {
                    $dataresume->diagnosa_utama = $cekdiagnosa[0]->nm_penyakit;
                    $dataresume->kd_diagnosa_utama = $cekdiagnosa[0]->kd_penyakit;
                }
                if (isset($cekdiagnosa[1])) {
                    $dataresume->diagnosa_sekunder = $cekdiagnosa[1]->nm_penyakit;
                    $dataresume->kd_diagnosa_sekunder = $cekdiagnosa[1]->kd_penyakit;
                }
                if (isset($cekdiagnosa[2])) {
                    $dataresume->diagnosa_sekunder2 = $cekdiagnosa[2]->nm_penyakit;
                    $dataresume->kd_diagnosa_sekunder2 = $cekdiagnosa[2]->kd_penyakit;
                }
            }else{
                if($keluhanutama->penilaian ?? ''){
                    $diagnosa = explode("\n", $keluhanutama->penilaian);
                    $dataresume->diagnosa_utama = $diagnosa[0] ?? "";
                    $dataresume->diagnosa_sekunder = $diagnosa[1] ?? "";
                    $dataresume->diagnosa_sekunder2 = $diagnosa[2] ?? "";
                    $dataresume->diagnosa_sekunder3 = $diagnosa[3] ?? "";
                    $dataresume->diagnosa_sekunder4 = $diagnosa[4] ?? "";
                }
            }

            // cek prosedur
            if($cekprosedur->isNotEmpty()){
                if (isset($cekprosedur[0])) {
                    $dataresume->prosedur_utama = $cekprosedur[0]->deskripsi_panjang;
                    $dataresume->kd_prosedur_utama = $cekprosedur[0]->kode;
                }
            }
        }

        //  dd($dataresume);
         return view('form/resume', [
             'detailpasien' => $detailpasien, 
             'dataresume' => $dataresume,
             'no_rawat' => $no_rawat,
             'cekriwayatresume' => $cekriwayatresume
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

    public function rjresep(Request $request){
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

        // dd($listresep);

            // dd($resepdokter);
        // select databarang.kode_brng, databarang.nama_brng, databarang.letak_barang,jenis.nama, databarang.kode_sat, sum(gudangbarang.stok) as stok 
        //               from databarang 
		// 									inner join jenis on databarang.kdjns=jenis.kdjns 
		// 									inner join gudangbarang on databarang.kode_brng=gudangbarang.kode_brng 
        //               where databarang.status='1' and gudangbarang.stok>0 group by gudangbarang.kode_brng order by databarang.nama_brng							
											

        return view('form/resepdokter', [
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
            $permintaan = $this->permintan($request);
            $request->terapi .= $permintaan['terapi'];
            $request->lab .= $permintaan['lab'];
            $request->rad .= $permintaan['rad'];
            // dd($request->all());
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
        elseif ($request->tabel == 'PenilaianAwalMedisRawatJalanBedah') {
            $permintaan = $this->permintan($request);
            $request->terapi .= $permintaan['terapi'];
            $request->lab .= $permintaan['lab'];
            $request->rad .= $permintaan['rad'];

            $PenilaianAwalMedisRawatJalanBedah = [
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
                'ekstremitas' => $request->ekstremitas,
                'genetalia' => $request->genetalia,
                'columna' => $request->columna,
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
            return $this->func_create($request, $PenilaianAwalMedisRawatJalanBedah, 'penilaian_medis_ralan_bedah', 'form/awalmedisbedah');
        }
        elseif ($request->tabel == 'PenilaianAwalMedisRawatJalanSyaraf') {
            $permintaan = $this->permintan($request);
            $request->terapi .= $permintaan['terapi'];
            $request->lab .= $permintaan['lab'];
            $request->rad .= $permintaan['rad'];
            
            $PenilaianAwalMedisRawatJalanSyaraf = [
                'no_rawat' => $request->no_rawat,
                'kd_dokter' => $request->nip,
                'tanggal' => $request->tanggal,
                'anamnesis'=> $request->anamnesis,
                'hubungan' => $request->hubungan,
                'keluhan_utama' => $request->keluhan_utama,
                'rpo' => $request->rpo,
                'rps' => $request->rps,
                'rpd' => $request->rpd,
                'alergi' => $request->alergi,
                'kesadaran' => $request->kesadaran,
                'status' => $request->status,
                'td' => $request->td,
                'nadi' => $request->nadi,
                'suhu' => $request->suhu,
                'rr' => $request->rr,
                'bb' => $request->bb,
                'nyeri' => $request->nyeri,
                'gcs' => $request->gcs,
                'kepala' => $request->kepala,
                'keterangan_kepala' => $request->keterangan_kepala,
                'thoraks' => $request->thoraks,
                'keterangan_thoraks' => $request->keterangan_thoraks,
                'abdomen' => $request->abdomen,
                'keterangan_abdomen' => $request->keterangan_abdomen,
                'ekstremitas' => $request->ekstremitas,
                'keterangan_ekstremitas' => $request->keterangan_ekstremitas,
                'columna' => $request->columna,
                'keterangan_columna' => $request->keterangan_columna,
                'muskulos' => $request->muskulos,
                'keterangan_muskulos' => $request->keterangan_muskulos,
                'lainnya' => $request->lainnya,
                'lab' => $request->lab,
                'rad' => $request->rad,
                'penunjanglain' => $request->penunjanglain,
                'diagnosis' => $request->diagnosis,
                'diagnosis2' => $request->diagnosis2,
                'permasalahan' => $request->permasalahan,
                'terapi' => $request->terapi,
                'tindakan' => $request->tindakan,
                'edukasi' => $request->edukasi
            ];
            // dd($request->all(), $PenilaianAwalMedisRawatJalanSyaraf);
            return $this->func_create($request, $PenilaianAwalMedisRawatJalanSyaraf, 'penilaian_medis_ralan_neurologi', 'form/awalmedissyaraf');
        }
        elseif ($request->tabel == 'PenilaianAwalMedisRawatJalanObsgyn') {
            $permintaan = $this->permintan($request);
            $request->tata = $permintaan['terapi'];
            $request->penunjang .= $permintaan['lab'];

            $PenilaianAwalMedisRawatJalanObsgyn = [
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
                'konsul'=> $request->konsul
            ];
            return $this->func_create($request, $PenilaianAwalMedisRawatJalanObsgyn, 'penilaian_medis_ralan_kandungan', 'form/awalmedisobsgyn');
        }
        elseif ($request->tabel == 'PenilaianAwalMedisRawatJalanAnak') {
            $permintaan = $this->permintan($request);
            $request->tata = $permintaan['terapi'];
            $request->penunjang .= $permintaan['lab'] . ", ";
            $request->penunjang .= $permintaan['rad'];
            
            $PenilaianAwalMedisRawatJalanAnak = [
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
                'abdomen'=> $request->abdomen,
                'genital'=> $request->genital,
                'ekstremitas'=> $request->ekstremitas,
                'kulit'=> $request->kulit,
                'ket_fisik'=> $request->ket_fisik,
                'ket_lokalis'=> $request->ket_lokalis,
                'penunjang'=> $request->penunjang,
                'diagnosis'=> $request->diagnosis,
                'tata'=> $request->tata,
                'konsul'=> $request->konsul
            ];
            // dd($request->all(), $PenilaianAwalMedisRawatJalanAnak);
            return $this->func_create($request, $PenilaianAwalMedisRawatJalanAnak, 'penilaian_medis_ralan_anak', 'form/awalmedisanak');
        }
        elseif ($request->tabel == 'PenilaianAwalMedisRawatJalanMata') {
            $permintaan = $this->permintan($request);
            $request->terapi .= $permintaan['terapi'];
            $request->lab .= $permintaan['lab'];
            $request->rad .= $permintaan['rad'];

            $PenilaianAwalMedisRawatJalanMata = [
                'no_rawat' => $request->no_rawat,
                'kd_dokter' => $request->nip,
                'tanggal' => $request->tanggal,
                'anamnesis'=> $request->anamnesis,
                'hubungan'=> $request->hubungan,
                'keluhan_utama'=> $request->keluhan_utama,
                'rps'=> $request->rps,
                'rpd'=> $request->rpd,
                'rpo'=> $request->rpo,
                'alergi' => $request->alergi,
                'td' => $request->td,
                'bb' => $request->bb,
                'suhu' => $request->suhu,
                'nadi' => $request->nadi,
                'rr' => $request->rr,
                'nyeri' => $request->nyeri,
                'status' => $request->status,
                'visuskanan' => $request->visuskanan,
                'visuskiri' => $request->visuskiri,
                'cckanan' => $request->cckanan,
                'cckiri' => $request->cckiri,
                'palkanan' => $request->palkanan,
                'palkiri' => $request->palkiri,
                'conkanan' => $request->conkanan,
                'conkiri' => $request->conkiri,
                'corneakanan' => $request->corneakanan,
                'corneakiri' => $request->corneakiri,
                'coakanan' => $request->coakanan,
                'coakiri' => $request->coakiri,
                'pupilkanan' => $request->pupilkanan,
                'pupilkiri' => $request->pupilkiri,
                'lensakanan' => $request->lensakanan,
                'lensakiri' => $request->lensakiri,
                'funduskanan' => $request->funduskanan,
                'funduskiri' => $request->funduskiri,
                'papilkanan' => $request->papilkanan,
                'papilkiri' => $request->papilkiri,
                'retinakanan' => $request->retinakanan,
                'retinakiri' => $request->retinakiri,
                'makulakanan' => $request->makulakanan,
                'makulakiri' => $request->makulakiri,
                'tiokanan' => $request->tiokanan,
                'tiokiri' => $request->tiokiri,
                'mbokanan' => $request->mbokanan,
                'mbokiri' => $request->mbokiri,
                'lab' => $request->lab,
                'rad' => $request->rad,
                'penunjang' => $request->penunjang,
                'tes' => $request->tes,
                'pemeriksaan' => $request->pemeriksaan,
                'diagnosis' => $request->diagnosis,
                'diagnosisbdg' => $request->diagnosisbdg,
                'permasalahan' => $request->permasalahan,
                'terapi' => $request->terapi,
                'tindakan' => $request->tindakan,
                'edukasi' => $request->edukasi
            ];
            // dd($request->all(), $PenilaianAwalMedisRawatJalanMata);
            return $this->func_create($request, $PenilaianAwalMedisRawatJalanMata, 'penilaian_medis_ralan_mata', 'form/awalmedismata');
        }
        elseif ($request->tabel == 'PenilaianAwalMedisRawatJalanDalam') {
            // dd($request->all());
            $permintaan = $this->permintan($request);
            $request->terapi .= $permintaan['terapi'];
            $request->lab .= $permintaan['lab'];
            $request->rad .= $permintaan['rad'];

            $PenilaianAwalMedisRawatJalanDalam = [
                'no_rawat' => $request->no_rawat,
                'kd_dokter' => $request->nip,
                'tanggal' => $request->tanggal,
                'anamnesis'=> $request->anamnesis,
                'hubungan' => $request->hubungan,
                'keluhan_utama' => $request->keluhan_utama,
                'rpo' => $request->rpo,
                'rps' => $request->rps,
                'rpd' => $request->rpd,
                'alergi' => $request->alergi,
                'status' => $request->status,
                'td' => $request->td,
                'nadi' => $request->nadi,
                'suhu' => $request->suhu,
                'rr' => $request->rr,
                'bb' => $request->bb,
                'nyeri' => $request->nyeri,
                'gcs' => $request->gcs,
                'kondisi' => $request->kondisi,
                'kepala' => $request->kepala,
                'keterangan_kepala' => $request->keterangan_kepala,
                'thoraks' => $request->thoraks,
                'keterangan_thorak' => $request->keterangan_thorak,
                'abdomen' => $request->abdomen,
                'keterangan_abdomen' => $request->keterangan_abdomen,
                'ekstremitas' => $request->ekstremitas,
                'keterangan_ekstremitas' => $request->keterangan_ekstremitas,
                'lainnya' => $request->lainnya,
                'lab' => $request->lab,
                'rad' => $request->rad,
                'penunjanglain' => $request->penunjanglain,
                'diagnosis' => $request->diagnosis,
                'diagnosis2' => $request->diagnosis2,
                'permasalahan' => $request->permasalahan,
                'terapi' => $request->terapi,
                'tindakan' => $request->tindakan,
                'edukasi' => $request->edukasi
            ];
            // dd($request->all(), $PenilaianAwalMedisRawatJalanDalam);
            return $this->func_create($request, $PenilaianAwalMedisRawatJalanDalam, 'penilaian_medis_ralan_penyakit_dalam', 'form/awalmedisdalam');
        }
        elseif ($request->tabel == 'PenilaianAwalMedisRawatJalanUmum') {
            $permintaan = $this->permintan($request);
            $request->tata = $permintaan['terapi'];
            $request->penunjang .= $permintaan['lab'] . ", ";
            $request->penunjang .= $permintaan['rad'];

            $PenilaianAwalMedisRawatJalanUmum = [
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
                'gigi'=> $request->gigi,
                'tht'=> $request->tht,
                'thoraks'=> $request->thoraks,
                'abdomen'=> $request->abdomen,
                'genital'=> $request->genital,
                'ekstremitas'=> $request->ekstremitas,
                'kulit'=> $request->kulit,
                'ket_fisik'=> $request->ket_fisik,
                'ket_lokalis'=> $request->ket_lokalis,
                'penunjang'=> $request->penunjang,
                'diagnosis'=> $request->diagnosis,
                'tata'=> $request->tata,
                'konsulrujuk'=> $request->konsulrujuk
            ];
            // dd($request->all(), $PenilaianAwalMedisRawatJalanUmum);
            return $this->func_create($request, $PenilaianAwalMedisRawatJalanUmum, 'penilaian_medis_ralan', 'form/awalmedisumum');
        }
        elseif ($request->tabel == 'DataPemeriksaanRawatJalan') {
            // jika diagnosa terisi, cek no rawat sebelumnya apakah sudah ada diagnosa yang sama dengan diagnosa no rawat baru
            $status_penyakit = "Baru";
            $duplicatediagnosa = [];
            if($request->diagnosa_utama != null){
                $cekdiagnosa = DB::table('diagnosa_pasien')->where('no_rawat', $request->no_rawat)->where('status', 'Ralan')->orderby('prioritas', 'ASC')->get();

                // jika cekdiagnosa kosong maka set prioritas menjadi 0
                if($cekdiagnosa->isEmpty()){
                    $cekdiagnosa[] = new \stdClass();
                    $cekdiagnosa[0]->prioritas = "0";
                }else{
                    foreach ($cekdiagnosa as $no => $kode) {
                        foreach ($request->diagnosa_utama as $key => $value) {
                            if($kode->kd_penyakit == $value){
                                $duplicatediagnosa[] = $key;
                            }
                        }
                    }
                }
                // menghapus diagnosa yang sama
                $diagnosafilter = array_diff_key($request->diagnosa_utama, $duplicatediagnosa);
                $diagnosafilter = array_values($diagnosafilter);
                
                // perulangan untuk menyimpan multiple select diagnosa
                $isipenilaian = "";
                foreach ($diagnosafilter as $key => $value) {
                    $diagnosa = [
                        'no_rawat' => $request->no_rawat,
                        'kd_penyakit' => $value,
                        'status' => 'Ralan',
                        'prioritas' => $cekdiagnosa[0]->prioritas + $key + 1,
                        'status_penyakit' => 'Baru',
                    ];

                    DB::table('diagnosa_pasien')->insert($diagnosa);
                    $isipenilaian .= $value . "\n";
                }

                if($request->penilaian != '-'){
                    $request->penilaian .= "\r\n";
                }
                $request->penilaian .= $isipenilaian ;
            }

            if($request->jumlah != null || $request->resep){
                $request->tabel = 'ResepDokter';
                $request->jam_peresepan = $request->jam_rawat;
                $request->tgl_peresepan =  $request->tgl_perawatan2;
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
                $request->tabel = 'PermintaanRadiologi';
                $request->tgl_permintaan = $request->tgl_perawatan2;
                $request->jam_permintaan = $request->jam_rawat;
                $this->create($request);
            }
            if($request->pemeriksaanlab != null){
                $request->tabel = 'PermintaanLab';
                $request->tgl_permintaan = $request->tgl_perawatan2;
                $request->jam_permintaan = $request->jam_rawat;
                $this->create($request);
            }
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
            // dd($request->all());
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
                'status' => "ralan",
                'informasi_tambahan' => $request->informasi_tambahanlab,
                'diagnosa_klinis' => $request->diagnosa_klinislab
            ];
            DB::table('permintaan_lab')->insert($permintaanLab);
            DB::table('permintaan_pemeriksaan_lab')->insert($pemeriksaanlab);
            DB::table('permintaan_detail_permintaan_lab')->insert($detailpermintaanlab);
            $request->session()->flash('success', 'Berhasil menyimpan permintaan laboratorium');
            return redirect()->back();
        }
        elseif ($request->tabel == 'PermintaanRadiologi') {
            // dd($request->all());
            $data = date('Ymd', strtotime($request->tgl_permintaan));
            // dd($data);
            $noorder = DB::table('permintaan_radiologi')->where('tgl_permintaan', $request->tgl_permintaan)->orderby('noorder', 'DESC');
            if($noorder->count() == 0){
                $noorderPR = "PR". $data . sprintf("%04s", 1);
            }else{
                $lastOrder = substr($noorder->first()->noorder, -4) +1;
                $noorderPR = "PR". $data . sprintf("%04s", $lastOrder);
            }
            // dd($noorderPR);
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
                'status' => "ralan",
                'informasi_tambahan' => $request->informasi_tambahanrad,
                'diagnosa_klinis' => $request->diagnosa_klinisrad
            ];
            DB::table('permintaan_radiologi')->insert($permintaanRad);
            DB::table('permintaan_pemeriksaan_radiologi')->insert($pemeriksaanrad);
            $request->session()->flash('success', 'Berhasil menyimpan permintaan Radiologi');
            return redirect()->back();
        }
        elseif ($request->tabel == 'ResepDokter') {
            $reg_periksa = DB::table('reg_periksa')->where('no_rawat', $request->no_rawat)->first();
            if($reg_periksa->status_bayar == 'Sudah Bayar'){
                $request->session()->flash('error', 'Data billing sudah terferifikasi,  Silahkan hubungi bagian kasir / keuangan');
                return redirect()->back();
            }

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
                'status' => 'ralan',
                'tgl_penyerahan' => '0000-00-00',
                'jam_penyerahan' => '00:00:00',
            ];
            DB::table('resep_obat')->insert($resep_obat);
            DB::table('resep_dokter')->insert($resepdokter);
            $request->session()->flash('success', 'Berhasil menyimpan Resep');
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
        else{
            dd("kosong");
        }
    }

    private function permintan(Request $request){
        $uniquelab = $request->lab;
        $getdate = date('Y-m-d');
        $gettime = date('H:i:s');

        if($request->jumlah != null || $request->resep){
            $request->tabel = 'ResepDokter';
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
            $request->tabel = 'PermintaanRadiologi';
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
            $request->tabel = 'PermintaanLab';
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
            $request->session()->flash('error', 'Gagal Simpan, Sudah Ada Data Sebelumnya');
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
        if($request->tabel == 'DataKontrolRawatJalan'){   
            $listnorawat = DB::table('reg_periksa')->select('no_rawat')
            ->where('no_rkm_medis', $request->no_rkm_medis)
            ->orderby('tgl_registrasi', 'DESC')->limit(20)->get();
        
            $arraylistnorawat = [];
            foreach ($listnorawat as $key => $value) {
                array_push($arraylistnorawat, $value->no_rawat);
            }         
            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('pemeriksaan_ralan as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.nip','=', 'd.nik')
                ->orderby('a.no_rawat', 'DESC')
                ->whereIn('a.no_rawat', $arraylistnorawat);

            return $this->func_listdata($request, 'form/listkontrol', $data, 'Tidak Ada Riwayat Soap');
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
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanBedah'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('penilaian_medis_ralan_bedah as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
                ->orderby('c.no_rawat', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            // dd($data->get());
            return $this->func_listdata($request, 'form/listrjawalmedisbedah', $data, 'Tidak Ada Riwayat Resume');
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanObsgyn'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('penilaian_medis_ralan_kandungan as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
                ->orderby('c.no_rawat', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            // dd($data->get());
            return $this->func_listdata($request, 'form/listrjawalmedisobsgyn', $data, 'Tidak Ada Riwayat Resume');
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanSyaraf'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('penilaian_medis_ralan_neurologi as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
                ->orderby('c.no_rawat', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            // dd($data->get());
            return $this->func_listdata($request, 'form/listrjawalmedissyaraf', $data, 'Tidak Ada Riwayat Resume');
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanAnak'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('penilaian_medis_ralan_anak as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
                ->orderby('c.no_rawat', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            // dd($data->get());
            return $this->func_listdata($request, 'form/listrjawalmedisanak', $data, 'Tidak Ada Riwayat Resume');
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanMata'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('penilaian_medis_ralan_mata as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
                ->orderby('c.no_rawat', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            // dd($data->get());
            return $this->func_listdata($request, 'form/listrjawalmedismata', $data, 'Tidak Ada Riwayat Resume');
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanDalam'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('penilaian_medis_ralan_penyakit_dalam as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
                ->orderby('c.no_rawat', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            return $this->func_listdata($request, 'form/listrjawalmedisdalam', $data, 'Tidak Ada Riwayat Resume');
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanUmum'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('penilaian_medis_ralan as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
                ->orderby('c.no_rawat', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            return $this->func_listdata($request, 'form/listrjawalmedisumum', $data, 'Tidak Ada Riwayat Resume');
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
        if($request->tabel == 'DataPemeriksaanRawatJalan'){
            return $this->func_delete($request, 'pemeriksaan_ralan', "form-soap");
        }
        elseif($request->tabel == 'DataResumeMedisPasienRawatJalan'){
            return $this->func_delete($request, 'resume_pasien', "false");
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanParu'){
            return $this->func_delete($request, 'penilaian_medis_ralan_paru', "false");
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanBedah'){
            return $this->func_delete($request, 'penilaian_medis_ralan_bedah', "false");
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanObsgyn'){
            return $this->func_delete($request, 'penilaian_medis_ralan_kandungan', "false");
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanSyaraf'){
            return $this->func_delete($request, 'penilaian_medis_ralan_neurologi', "false");
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanAnak'){
            return $this->func_delete($request, 'penilaian_medis_ralan_anak', "false");
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanMata'){
            return $this->func_delete($request, 'penilaian_medis_ralan_mata', "false");
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanDalam'){
            return $this->func_delete($request, 'penilaian_medis_ralan_penyakit_dalam', "false");
        }
        elseif($request->tabel == 'PenilaianAwalMedisRawatJalanUmum'){
            return $this->func_delete($request, 'penilaian_medis_ralan', "false");
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
