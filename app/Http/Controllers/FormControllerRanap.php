<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Classes\Sekuel;
use Session;
use Carbon\Carbon;
use Auth;

class FormControllerRanap extends Controller
{
    public function menuranap (Request $request){
        $pasienranap = DB::table('kamar_inap as ki')->select('ki.*', 'p.no_rkm_medis', 'p.nm_pasien', 'b.nm_bangsal', 'rg.no_rawat2', 'p2.no_rkm_medis as no_rkm_medis2', 'p2.nm_pasien as nm_pasien2')
            ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'ki.no_rawat')
            ->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->leftjoin('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
            ->leftjoin('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
            ->leftjoin('dpjp_ranap as dr', 'dr.no_rawat', '=', 'ki.no_rawat')
            ->leftjoin('ranap_gabung as rg', 'rg.no_rawat', '=', 'ki.no_rawat')
            ->leftjoin('reg_periksa as rp2', 'rp2.no_rawat', '=', 'rg.no_rawat2')
            ->leftjoin('pasien as p2', 'p2.no_rkm_medis', '=', 'rp2.no_rkm_medis');

        if($request->tanggal != null){
            $date = date("Y-m-d", strtotime(substr($request->tanggal, 0, 10))); // date('Y-m-d'); //
            $enddate = date("Y-m-d", strtotime(substr($request->tanggal, -10)));
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
            $pasienranap = $pasienranap->whereBetween('ki.tgl_masuk', [$date, $enddate]);
        }else{            
            $date = date('Y-m-d');
            $enddate = date('Y-m-d');
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
            $pasienranap = $pasienranap->where('ki.stts_pulang', '-');
        }

        if(Session::get('kd_sps') == '-'){
            $pasienranap = $pasienranap->get();
        }else{
            $pasienranap = $pasienranap->where('dr.kd_dokter', Session::get('username'))->get();
        }
        
        return view('formranap/list-ranap', [
            'pasienranap' => $pasienranap,
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
        ]);
    }

    public function riresume(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');

        $detailpasien = DB::table('kamar_inap as ki')->select('ki.*', 'p.no_rkm_medis', 'p.nm_pasien', 'b.nm_bangsal', 'k.kd_kamar', 'rp.no_rawat', 'p.umur')
        ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'ki.no_rawat')
        ->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
        ->leftjoin('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
        ->leftjoin('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
        ->leftjoin('dpjp_ranap as dr', 'dr.no_rawat', '=', 'ki.no_rawat')
        ->where('ki.no_rawat', $request->no_rawat )
        // ->where('dr.kd_dokter',  Session::get('username'))
        ->get();
        // dd($detailpasien);
        // $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        $cekriwayatresume = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
            ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
            ->join('resume_pasien_ranap as c', 'a.no_rawat','=', 'c.no_rawat')
            ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
            ->where('a.no_rawat', $request->no_rawat)->count();

       if (DB::table('resume_pasien_ranap')->where('no_rawat', '=', $no_rawat)->exists()) {
           $dataresume = DB::table('resume_pasien_ranap')->select('resume_pasien_ranap.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'resume_pasien_ranap.kd_dokter')->first();
           $dataresume->kontrol = Carbon::parse($dataresume->kontrol)->format('Y-m-d');
       }else{
           $dataresume = DB::table('resume_pasien_ranap')->first();
           foreach ($dataresume as $key => $value) {
               $dataresume->$key = "";
           }
           $dataresume->kd_dokter = $kd_dokter;
           $dataresume->kontrol = date('Y-m-d');
           $dataresume->nama = Auth::guard('pegawai')->user()->nama;

           $keluhanutama = DB::table('pemeriksaan_ranap')->select('keluhan', 'pemeriksaan', 'suhu_tubuh', 'tensi', 'nadi', 'respirasi', 'alergi', 'penilaian')
                ->where('no_rawat', $request->no_rawat)
                ->where('nip', $kd_dokter)
                ->orderby('tgl_perawatan', 'DESC')->orderby('jam_rawat', 'DESC')->first();

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
            
            $tindakandokter = DB::table('rawat_inap_dr as ri')->select('ri.tgl_perawatan', 'ri.jam_rawat', 'jpi.nm_perawatan')
                ->join('jns_perawatan_inap as jpi', 'jpi.kd_jenis_prw','=', 'ri.kd_jenis_prw')
                ->where('ri.no_rawat', $request->no_rawat)
                ->get();

            // cek diagnosa pasien yang telah dimasukkan
            $cekdiagnosa = DB::table('diagnosa_pasien as dp')->select('dp.*', 'p.nm_penyakit')
                ->join('penyakit as p', 'p.kd_penyakit','=', 'dp.kd_penyakit')
                ->where('dp.no_rawat', $request->no_rawat)->where('dp.status', 'Ranap')->orderby('dp.prioritas', 'ASC')->get();

            // cek prosedur pasien yang telah dimasukkan
            $cekprosedur = DB::table('prosedur_pasien as pp')->select('pp.*', 'i.deskripsi_panjang')
                ->join('icd9 as i', 'i.kode','=', 'pp.kode')
                ->where('pp.no_rawat', $request->no_rawat)->where('pp.status', 'Ranap')->orderby('pp.prioritas', 'ASC')->get();

            // cek resep pulang
            $cekreseppulang = DB::table('permintaan_resep_pulang as rp')->select('dpr.*', 'db.nama_brng')
                ->leftjoin('detail_permintaan_resep_pulang as dpr', 'dpr.no_permintaan','=', 'rp.no_permintaan')
                ->leftjoin('databarang as db', 'db.kode_brng', '=', 'dpr.kode_brng')
                ->where('rp.no_rawat', $request->no_rawat)->get();

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

            if($keluhanutama != null){
                $dataresume->keluhan_utama = $keluhanutama->keluhan ?? '';
                $dataresume->pemeriksaan_fisik = $keluhanutama->pemeriksaan ?? '';
                $dataresume->ket_keadaan = "suhu: " . $keluhanutama->suhu_tubuh . "°C, tensi: " . $keluhanutama->tensi . "mmHg, nadi: " . $keluhanutama->nadi . "x/menit, RR: " . $keluhanutama->respirasi . "/menit";
                $dataresume->alergi = $keluhanutama->alergi;
            }

            $riwayattindakan= "";
            if($tindakandokter->isNotEmpty()){
                foreach ($tindakandokter as $key => $value) {
                    $riwayattindakan .= $value->tgl_perawatan ." ". $value->jam_rawat ." ". $value->nm_perawatan . "\r\n";
                }
                $dataresume->tindakan_dan_operasi = $riwayattindakan;
            }

            $dataresume->edukasi = "Keluarga pasien, nama: \r\nHubungan dengan pasien: \r\nTidak dapat memberi edukasi kepada pasien atau keluarga karena: ";

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
                    $dataresume->diagnosa_sekunder = $cekdiagnosa[2]->nm_penyakit;
                    $dataresume->kd_diagnosa_sekunder = $cekdiagnosa[2]->kd_penyakit;
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

            // cek apakah adara resep pulang
            $reseppulang = "";
            if ($cekreseppulang->isNotEmpty()) {
                foreach ($cekreseppulang as $key => $value) {
                    $reseppulang .= $value->nama_brng . " jumlah: " . $value->jml . " (" . $value->dosis . ")\r\n";
                }
                $dataresume->obat_pulang = $reseppulang;
            }
        }
        // dd($dataresume);
        $obatterakhir = DB::table('resep_obat as ro')
            ->orderby('ro.no_resep', 'DESC')
            ->where('ro.status', 'ranap')
            ->where('ro.no_rawat', $request->no_rawat)->first();

        $listobatterakhir = [];
        $listResep = "";
        if($obatterakhir){
            $listobatterakhir = DB::table('resep_dokter as rd')
                ->select('rd.aturan_pakai', 'db.nama_brng' , 'db.kode_brng')
                ->leftjoin('databarang as db', 'db.kode_brng', '=', 'rd.kode_brng')
                ->orderby('rd.kode_brng', 'DESC')
                ->where('rd.no_resep', $obatterakhir->no_resep)->get();      
            
            if($listobatterakhir->isNotEmpty()){
                foreach ($listobatterakhir as $key => $value) {
                    $namaObat = DB::table('databarang')->select('nama_brng')->where('kode_brng', $request->reseppulang[$key])->first();
                    $listResep .= $value->nama_brng . " " . $value->aturan_pakai . "\r\n";
                }
            }
        }    

        return view('formranap/resume', [
            'detailpasien' => $detailpasien, 
            'dataresume' => $dataresume,
            'cekriwayatresume' => $cekriwayatresume,
            'no_rawat' => $no_rawat,
            'listResep' => $listResep,
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
        $biayapenanganandr = DB::table('rawat_inap_dr as dr')->select('dr.*', 'jns.nm_perawatan', DB::raw('count(*) as total'))
            ->leftjoin('jns_perawatan_inap as jns', 'dr.kd_jenis_prw','=', 'jns.kd_jenis_prw')
            ->where('dr.no_rawat',  $request->no_rawat)
            ->orderBy('jns.nm_perawatan', 'DESC')
            ->groupBy('jns.kd_jenis_prw') 
            ->get();
        
        // biaya penanganan perawat
        $biayapenangananpr = DB::table('rawat_inap_pr as pr')->select('pr.*', 'jns.nm_perawatan', DB::raw('count(*) as total'))
            ->leftjoin('jns_perawatan_inap as jns', 'pr.kd_jenis_prw','=', 'jns.kd_jenis_prw')
            ->where('pr.no_rawat',  $request->no_rawat)
            ->orderBy('jns.nm_perawatan', 'DESC')
            ->groupBy('jns.kd_jenis_prw') 
            ->get();

        // biaya administrasi
        $detailpasien = DB::table('reg_periksa as a')->select('a.biaya_reg','b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'ki.ttl_biaya','bsl.nm_bangsal')
            ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
            ->join('kamar_inap as ki', 'ki.no_rawat','=', 'a.no_rawat')
            ->join('kamar as k', 'k.kd_kamar','=', 'ki.kd_kamar')
            ->leftjoin('bangsal as bsl', 'bsl.kd_bangsal', '=', 'k.kd_bangsal')
            ->where('a.no_rawat', $request->no_rawat)->get();
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

        $hargatotal += $detailpasien[0]->ttl_biaya;
        
        // dd($bacaanlab, $hargatotal);

        return view('formranap/rincianbiaya', [
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
            if(Session::get('kd_sps') == '-'){ // dr umum
                $data = DB::table('pemeriksaan_ranap')->where('no_rawat', $request->no_rawat)->orderBy('tgl_perawatan', 'DESC')->orderBy('jam_rawat', 'DESC')->first();

                // cek apakah ada data di pemeriksaan ralan, jika tidak maka ambil data pemeriksaan lain
                if($data != null){
                    foreach ($data as $key => $value) {
                        $data->$key = $value;
                    }
                }else{
                    $data = $data = DB::table('pemeriksaan_ranap')->first();
                    foreach ($data as $key => $value) {
                        $data->$key = "";
                    }
                }
            }else{ // dr spesialis
                $data = DB::table('pemeriksaan_ranap')
                    ->select('pemeriksaan_ranap.*')
                    ->join('dokter', 'dokter.kd_dokter','=', 'pemeriksaan_ranap.nip')
                    ->where('no_rawat', $request->no_rawat)
                    ->orderBy('tgl_perawatan', 'DESC')
                    ->orderBy('jam_rawat', 'DESC')->first();
                            
                // cek apakah ada data di pemeriksaan ralan, jika tidak maka ambil data pemeriksaan lain
                if($data != null){
                    foreach ($data as $key => $value) {
                        $data->$key = $value;
                    }
                }else{
                    $data = $data = DB::table('pemeriksaan_ranap')->first();
                    foreach ($data as $key => $value) {
                        $data->$key = "";
                    }
                }
            }

            // ambil riwayat alergi
            $sekuel = new Sekuel();
            $data->alergi = $sekuel->getAlergi($request->no_rawat);

            $data->nip = $kd_dokter;
            $data->nama = Auth::guard('pegawai')->user()->nama;
        }

        // ambil obat terakhir
        $listnorawat = DB::table('reg_periksa')->select('no_rawat')
        ->where('no_rkm_medis', $detailpasien[0]->no_rkm_medis)
        ->orderby('tgl_registrasi', 'DESC')->limit(10)->get();
    
        $arraylistnorawat = [];
        foreach ($listnorawat as $key => $value) {
            array_push($arraylistnorawat, $value->no_rawat);
        }         
        
        // ambil seluruh riwayat resep
        $datalistnorawatlengkap = DB::table('resep_obat as ro')->select('ro.no_resep', 'ro.no_rawat', 'ro.status', 'rd.*', 'db.nama_brng', 'db.ralan', 'gb.stok')
            ->join('resep_dokter as rd', 'rd.no_resep','=', 'ro.no_resep')
            ->leftjoin('databarang as db', 'db.kode_brng', '=', 'rd.kode_brng')
            ->leftjoin('gudangbarang as gb', 'gb.kode_brng','=', 'rd.kode_brng')
            ->where('gb.stok', '>', '0')
            ->orderby('ro.no_resep', 'DESC')
            ->where('ro.status', 'ranap')
            ->whereIn('ro.no_rawat', $arraylistnorawat)->get();

        // ambil total obat per resep untuk menghitung colspan
        $datalistnorawatperresep = DB::table('resep_obat as ro')->select('ro.no_resep', DB::raw('count(*) as total'))
            ->join('resep_dokter as rd', 'rd.no_resep','=', 'ro.no_resep')
            ->orderby('ro.no_resep', 'DESC')
            ->groupby('ro.no_resep')
            ->where('ro.status', 'ranap')
            ->whereIn('ro.no_rawat', $arraylistnorawat)->get();

        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'a.total_byr', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();
        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'a.total_byr')->where('status', '1')->get();
        $getimage = 'image/semua.png';
        
        $jnspenyakit = DB::table('penyakit as p')->select('p.kd_penyakit', 'p.nm_penyakit')->orderby('kd_penyakit', 'ASC')->get();


        $sekuel = new Sekuel();
        list($riwayatlab, $riwayatdelatillab) = $sekuel->riwayatlab($request->no_rawat);
        list($pasienrad, $hasilrad, $getgambar, $grouprad) = $sekuel->riwayatrad($request->no_rawat);
        list($riwayatekg) = $sekuel->riwayatekg($request->no_rawat);

        return view('formranap/formri-soap', [
            'detailpasien' => $detailpasien,
            'data' => $data,
            'resepdokter' => $resepdokter,
            'jnsperawatanlab' => $jnsperawatanlab,
            'jnsperawatanrad' => $jnsperawatanrad,
            'jnspenyakit' => $jnspenyakit,
            'getimage' => $getimage,
            'no_rawat' => $no_rawat,
            'datalistnorawatperresep' => $datalistnorawatperresep,
            'datalistnorawatlengkap' => $datalistnorawatlengkap,
            'riwayatlab' => $riwayatlab,
            'riwayatdelatillab' => $riwayatdelatillab,
            'pasienrad' => $pasienrad,
            'getgambar' => $getgambar,
            'hasilrad' => $hasilrad,
            'grouprad' => $grouprad,
            'riwayatekg' => $riwayatekg,
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

        $resepdokter = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.ralan', 'db.letak_barang','jenis.nama', 'db.kode_sat', 'gudangbarang.stok')
            ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
            ->join('gudangbarang', 'gudangbarang.kode_brng','=', 'db.kode_brng')
            ->where('db.status', '=', '1')->where('gudangbarang.stok', '>', '0')
            ->groupby('gudangbarang.kode_brng')->orderby('db.nama_brng')->get();

        $adaisipengisianmedisumum = true;
        if (DB::table('penilaian_medis_ranap')->where('no_rawat', '=', $no_rawat)->exists()) {
            $medisumum = DB::table('penilaian_medis_ranap')->select('penilaian_medis_ranap.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'penilaian_medis_ranap.kd_dokter')->first();
        }else{
            $adaisipengisianmedisumum = false;
            $medisumum = DB::table('penilaian_medis_ranap')->first();
            // foreach ($medisumum as $key => $value) {
            //     $medisumum->$key = "";
            // }
            $medisumum->kd_dokter = $kd_dokter;
            $medisumum->nama = Auth::guard('pegawai')->user()->nama;

            $ambilttv = DB::table('penilaian_medis_igd')->where('no_rawat', $request->no_rawat)->orderBy('tanggal', 'DESC')->first();
// dd($ambilttv,$medisumum);
            if($ambilttv != null){
                foreach ($medisumum as $key => $value) {
                    $medisumum->$key = $ambilttv->$key ?? 'Normal';
                }
            }else{
                foreach ($medisumum as $key => $value) {
                    $medisumum->$key = "";
                }
            }
            $medisumum->kd_dokter = $kd_dokter;
            $medisumum->nama = Auth::guard('pegawai')->user()->nama;
            $medisumum->edukasi = $medisumum->penunjang = '-';
            // dd($ambilttv,$medisumum);
            // dd($medisumum,$ambilttv);
        }

        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'a.total_byr', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();
        $jnsperawatanrad = DB::table('jns_perawatan_radiologi as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'a.total_byr')->where('status', '1')->get();

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
            $getimage = 'image/semua.png';
        }
        
        // Ambil riwayat laborat dan radiologi
        $sekuel = new Sekuel();
        list($riwayatlab, $riwayatdelatillab) = $sekuel->riwayatlab($request->no_rawat);
        list($pasienrad, $hasilrad, $getgambar, $grouprad) = $sekuel->riwayatrad($request->no_rawat);

        // dd($pasienrad, $hasilrad, $getgambar, $grouprad, $riwayatlabcoba, $riwayatlab);
        return view('formranap/formri-medisumum', [
            'detailpasien' => $detailpasien,
            'medisumum' => $medisumum,
            'jnsperawatanlab' => $jnsperawatanlab,
            'jnsperawatanrad' => $jnsperawatanrad,
            'resepdokter' => $resepdokter,
            'getimage' => $getimage,
            'no_rawat' => $no_rawat,
            'riwayatlab' => $riwayatlab,
            'riwayatdelatillab' => $riwayatdelatillab,
            'pasienrad' => $pasienrad,
            'getgambar' => $getgambar,
            'hasilrad' => $hasilrad,
            'grouprad' => $grouprad,
            'adaisipengisianmedisumum' => $adaisipengisianmedisumum,
        ]);
    }

    public function rimediskebidanan(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');
        $ambilttv = DB::table('penilaian_awal_keperawatan_kebidanan_ranap')->where('no_rawat', $request->no_rawat)->orderBy('tanggal', 'DESC')->first();
        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        $resepdokter = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.ralan', 'db.letak_barang','jenis.nama', 'db.kode_sat', 'gudangbarang.stok')
            ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
            ->join('gudangbarang', 'gudangbarang.kode_brng','=', 'db.kode_brng')
            ->where('db.status', '=', '1')->where('gudangbarang.stok', '>', '0')
            ->groupby('gudangbarang.kode_brng')->orderby('db.nama_brng')->get();

        if (DB::table('penilaian_medis_ranap_kandungan')->where('no_rawat', '=', $no_rawat)->exists()) {
            $mediskebidanan = DB::table('penilaian_medis_ranap_kandungan')->select('penilaian_medis_ranap_kandungan.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'penilaian_medis_ranap_kandungan.kd_dokter')->first();
        }else{
            $mediskebidanan = DB::table('penilaian_medis_ranap_kandungan')->first();
            foreach ($mediskebidanan as $key => $value) {
                $mediskebidanan->$key = "";
            }
            $mediskebidanan->kd_dokter = $kd_dokter;
            $mediskebidanan->nama = Auth::guard('pegawai')->user()->nama;

            // $ambilttv = DB::table('penilaian_awal_keperawatan_kebidanan_ranap')->where('no_rawat', $request->no_rawat)->orderBy('tanggal', 'DESC')->first();
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

        $jnsperawatanlab = DB::table('jns_perawatan_lab as a')->select('a.kd_jenis_prw', 'a.nm_perawatan', 'b.Pemeriksaan', 'b.id_template')->leftjoin('template_laboratorium as b', 'b.kd_jenis_prw', '=', 'a.kd_jenis_prw')->where('status', '1')->get();

        return view('formranap/formri-mediskebidanan', [
            'detailpasien' => $detailpasien, 
            'mediskebidanan' => $mediskebidanan,
            'resepdokter' => $resepdokter,
            'jnsperawatanlab' => $jnsperawatanlab,
            'no_rawat' => $no_rawat,
            'ambilttv' => $ambilttv
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

    public function ekg(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');
        $cekriwayatekg = 0;

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        if (DB::table('hasil_pemeriksaan_ekg')->where('no_rawat', '=', $no_rawat)->exists()) {
            $cekriwayatekg = 1;
            $pem_ekg = DB::table('hasil_pemeriksaan_ekg')->select('hasil_pemeriksaan_ekg.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'hasil_pemeriksaan_ekg.kd_dokter')->first();
        }else{
            $pem_ekg = DB::table('hasil_pemeriksaan_ekg')->first();
            foreach ($pem_ekg as $key => $value) {
                $pem_ekg->$key = "";
            }
            $pem_ekg->kd_dokter = $kd_dokter;
            $pem_ekg->nama = Auth::guard('pegawai')->user()->nama;
        }
        return view('formranap/pemeriksaanekg', [
            'detailpasien' => $detailpasien, 
            'pem_ekg' => $pem_ekg,
            'cekriwayatekg' => $cekriwayatekg,
            'no_rawat' => $no_rawat
        ]);
    }

    public function edukasi(Request $request){
        // get session nama & kode poli
        $kd_dokter = Session::get('username');
        $nmpoli = Session::get('nmpoli');
        $cekriwayatedukasi = 0;

        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        $no_rawat = $request->no_rawat;

        // if (DB::table('pelaksanaan_informasi_edukasi')->where('no_rawat', '=', $no_rawat)->exists()) {
        //     $cekriwayatedukasi = 1;
        //     $edukasi = DB::table('pelaksanaan_informasi_edukasi')->select('pelaksanaan_informasi_edukasi.*', 'pegawai.nama')->where('no_rawat', '=',$no_rawat)->leftjoin('pegawai', 'pegawai.nik', 'pelaksanaan_informasi_edukasi.nik')->first();
        // }else{
            $edukasi = DB::table('pelaksanaan_informasi_edukasi')->first();
            foreach ($edukasi as $key => $value) {
                $edukasi->$key = "";
            }
            $edukasi->kd_dokter = $kd_dokter;
            $edukasi->nama = Auth::guard('pegawai')->user()->nama;
        // }

        $list_pelaksanaan_edu = DB::table('pelaksanaan_informasi_edukasi')
            ->select('pelaksanaan_informasi_edukasi.*','pegawai.nama')
            ->where('pelaksanaan_informasi_edukasi.no_rawat', '=', $no_rawat)
            ->leftjoin('pegawai', 'pegawai.nik', 'pelaksanaan_informasi_edukasi.nik')
            ->orderby('pelaksanaan_informasi_edukasi.tanggal', 'DESC')->get();

        // dd($edukasi);
        return view('form/edukasi', [
            'detailpasien' => $detailpasien, 
            'edukasi' => $edukasi,
            'cekriwayatedukasi' => $cekriwayatedukasi,
            'list_pelaksanaan_edu' => $list_pelaksanaan_edu,
            'no_rawat' => $no_rawat
        ]);
    }

    public function edukasihapus(Request $request){
        try{
            DB::table('pelaksanaan_informasi_edukasi')
             ->where('no_rawat',$request->no_rawat)
             ->where('tanggal',$request->tanggal)
             ->delete();
             $request->session()->flash('warning', 'Data Berhasil Dihapus');
        } catch (\Exception $e){
            $request->session()->flash('error', 'Data Gagal Dihapus');
        }
        return redirect()->back();
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
            // jika diagnosa terisi, cek no rawat sebelumnya apakah sudah ada diagnosa yang sama dengan diagnosa no rawat baru
            $status_penyakit = "Baru";
            $duplicatediagnosa = [];
            if($request->diagnosa_utama != null){
                $cekdiagnosa = DB::table('diagnosa_pasien')->where('no_rawat', $request->no_rawat)->where('status', 'Ranap')->orderby('prioritas', 'ASC')->get();

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
                        'status' => 'Ranap',
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
                $request->tabel = 'ResepDokterRawatInap';
                $request->jam_peresepan = date('H:i:s');
                $request->tgl_peresepan =  date('Y-m-d');
                $this->create($request);

                if($request->rtl != '-'){
                    $request->rtl .= "\r\n";
                }
                
                // Menambahkan obat ke plan
                $listResep = " \r\n";
                foreach ($request->jumlah as $key => $value) {
                    $namaObat = DB::table('databarang')->select('nama_brng')->where('kode_brng', $request->resep[$key])->first();
                    if (!str_contains($namaObat->nama_brng, "Handsco") && !str_contains($namaObat->nama_brng, "Spuit")) {
                        $listResep .= $namaObat->nama_brng . " " . $request->aturan_pakai[$key] . "\r\n";
                    }
                }
                $request->rtl = $request->rtl . $listResep;

            }elseif($request->jumlahpulang != null || $request->reseppulang){
                $request->tabel = 'ResepPulangDokterRawatInap';
                $request->jam_peresepan = date('H:i:s');
                $request->tgl_peresepan =  date('Y-m-d');
                $this->create($request);

                if($request->rtl != '-'){
                    $request->rtl .= "\r\n";
                }
                
                // Menambahkan obat ke plan
                $listResep = " Resep Pulang: \r\n";
                foreach ($request->jumlahpulang as $key => $value) {
                    $namaObat = DB::table('databarang')->select('nama_brng')->where('kode_brng', $request->reseppulang[$key])->first();
                    $listResep .= $namaObat->nama_brng . " " . $request->aturan_pakai_pulang[$key] . "\r\n";
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
            $request->tata = $request->tata. " ". $permintaan['terapi'];
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
            $this->simpanawalmediskesoap($request);
            return $this->func_create($request, $PenilaianAwalMedisRawatInap, 'penilaian_medis_ranap', 'formri-medisumum');
        }
        elseif ($request->tabel == 'PenilaianAwalMedisKebidananRawatInap') {
            $permintaan = $this->permintan($request);
            $request->tata = $permintaan['terapi'];
            $request->lab = $permintaan['lab'];

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
            $noorder = DB::table('resep_obat')->where('no_resep',  'like', '%' . $data . '%')->orderby('no_resep', 'DESC');

            if($noorder->count() == 0){
                $noorderResep = $data . sprintf("%04s", 1);
            }else{
                $noorderResep = $noorder->first()->no_resep+1;
            }
            // dd($noorderResep);
            $resepdokter = [];
            if($request->jumlah){
                foreach ($request->jumlah as $key => $value) {
                    // jika aturan pakai kosong, ubah menjadi '-'
                    if($request->aturan_pakai[$key] == null)
                    {
                        $dtresepdokter = ['no_resep'=>$noorderResep, 'kode_brng'=> $request->resep[$key], 'jml' => $request->jumlah[$key], 'aturan_pakai' => '-'];
                    }else{
                        $dtresepdokter = ['no_resep'=>$noorderResep, 'kode_brng'=> $request->resep[$key], 'jml' => $request->jumlah[$key], 'aturan_pakai' => $request->aturan_pakai[$key]];
                    }
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
        elseif ($request->tabel == 'ResepPulangDokterRawatInap') {
            $data = date('Ymd', strtotime($request->tgl_peresepan));
            // dd($data);
            $noorder = DB::table('permintaan_resep_pulang')->where('no_permintaan',  'like', '%' . $data . '%')->orderby('no_permintaan', 'DESC');

            if($noorder->count() == 0){
                $noorderResepPulang = 'RP'. $data . sprintf("%04s", 1);
            }else{
                $justnoorder = substr($noorder->first()->no_permintaan, 2);
                $noorderResepPulang = $justnoorder+1;
                $noorderResepPulang = 'RP'.$noorderResepPulang;
            }
            $resepdokterpulang = [];
            if($request->jumlahpulang){
                foreach ($request->jumlahpulang as $key => $value) {
                    // jika aturan pakai kosong, ubah menjadi '-'
                    if($request->aturan_pakai_pulang[$key] == null)
                    {
                        $dtresepdokterpulang = ['no_permintaan'=>$noorderResepPulang, 'kode_brng'=> $request->reseppulang[$key], 'jml' => $request->jumlahpulang[$key], 'dosis' => '-'];
                    }else{
                        $dtresepdokterpulang = ['no_permintaan'=>$noorderResepPulang, 'kode_brng'=> $request->reseppulang[$key], 'jml' => $request->jumlahpulang[$key], 'dosis' => $request->aturan_pakai_pulang[$key]];
                    }
                    array_push($resepdokterpulang, $dtresepdokterpulang);
                }
            }

            $resep_obat = [
                'no_permintaan' => $noorderResepPulang,
                'tgl_permintaan' => $request->tgl_peresepan,
                'jam' => $request->jam_peresepan,
                'no_rawat' => $request->no_rawat,
                'kd_dokter' => $request->nip,
                'status' => 'Belum',
                'tgl_validasi' => '0000-00-00',
                'jam_validasi' => '00:00:00',
            ];
            DB::table('permintaan_resep_pulang')->insert($resep_obat);
            DB::table('detail_permintaan_resep_pulang')->insert($resepdokterpulang);
            $request->session()->flash('success', 'Berhasil menyimpan Resep');
            return redirect()->back();
        }
        elseif ($request->tabel == 'HasilPemeriksaanEKG') {
            $HasilPemeriksaanEKG = [
                'no_rawat' => $request->no_rawat,
                'tanggal' => $request->tanggal,
                'kd_dokter' => $request->nip,
                'diagnosa_klinis' => $request->diagnosa_klinis,
                'kiriman_dari' => $request->kiriman_dari,
                'irama' => $request->irama,
                'laju_jantung' => $request->laju_jantung,
                'gelombangp' => $request->gelombangp,
                'intervalpr' => $request->intervalpr,
                'axis' => $request->axis,
                'kompleksqrs' => $request->kompleksqrs,
                'segmenst' => $request->segmenst,
                'gelombangt' => $request->gelombangt,
                'kesimpulan' => $request->kesimpulan
            ];
            return $this->func_create($request, $HasilPemeriksaanEKG, 'hasil_pemeriksaan_ekg', 'ekgranap');
        }
        else{
            dd("nama tabel kosong");
        }
    }

    private function permintan(Request $request){
        $uniquelab = $request->lab;
        $getdate = date('Y-m-d');
        $gettime = date('H:i:s');

        $request->request->add(['tgl_peresepan' => $getdate]);
        $request->request->add(['jam_peresepan' => $gettime]);
        $request->request->add(['tgl_permintaan' => $getdate]);
        $request->request->add(['jam_permintaan' => $gettime]);

        if($request->jumlah != null || $request->resep){
            $request->tabel = 'ResepDokterRawatInap';
            // $request->jam_peresepan = $gettime;
            // $request->tgl_peresepan =  $getdate;
            $this->create($request);
            
            // Menambahkan obat ke terapi
            $listResep = " \r\n";
            foreach ($request->jumlah as $key => $value) {
                $namaObat = DB::table('databarang')->select('nama_brng')->where('kode_brng', $request->resep[$key])->first();
                $listResep .= $namaObat->nama_brng . " " . $request->aturan_pakai[$key] . "\r\n";
            }
            $request->terapi = $request->terapi . $listResep;
        }
        if($request->pemeriksaanrad != null){
            $request->tabel = 'PermintaanRadiologiRawatInap';
            // $request->tgl_permintaan = $getdate;
            // $request->jam_permintaan = $gettime;
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
            // $request->tgl_permintaan = $getdate;
            // $request->jam_permintaan = $gettime;
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
            // dd($e);
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
        elseif($request->tabel == 'PemeriksaanEKG'){            
            $data = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur', 'c.*', 'd.nama')
                ->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')
                ->join('hasil_pemeriksaan_ekg as c', 'a.no_rawat','=', 'c.no_rawat')
                ->leftjoin('pegawai as d', 'c.kd_dokter','=', 'd.nik')
                ->orderby('c.no_rawat', 'DESC')
                ->where('a.no_rawat', $request->no_rawat);
            return $this->func_listdata($request, 'formranap/listpemeriksaanekg', $data, 'Tidak Ada EKG');
        }
        else{
            dd("kosong");
        }
    }


    public function simpanawalmediskesoap(Request $request)
    {
        // dd($request->all());
        DB::table('pemeriksaan_ranap')->insert([
            'tgl_perawatan' => $request->tanggal,
            'jam_rawat' => $request->jam_permintaan,
            'no_rawat' => $request->no_rawat,
            'nip' => $request->nip,
            'keluhan' => $request->keluhan_utama,
            'pemeriksaan' => $request->rps .", ". $request->ket_lokalis ?? $request->lainnya,
            'suhu_tubuh' => $request->suhu,
            'tensi' => $request->td,
            'berat' => $request->bb,
            'tinggi' => $request->tb,
            'respirasi' => $request->rr,
            'nadi' => $request->nadi,
            'spo2' => $request->spo ?? '-',
            'gcs' => $request->gcs,
            'kesadaran' => $request->kesadaran ?? '-',
            'alergi' => $request->alergi,
            'penilaian' => $request->diagnosis,
            'rtl' => $request->tata ?? $request->terapi,
            'instruksi' => $request->edukasi ?? $request->konsul ?? '-',
            'evaluasi' => '-'
        ]);
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
        elseif($request->tabel == 'HasilPemeriksaanEKG'){
            return $this->func_delete($request, 'hasil_pemeriksaan_ekg', "false");
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