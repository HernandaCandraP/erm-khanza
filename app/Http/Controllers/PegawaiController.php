<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Pegawai;
use App\Rules\MatchOldPasswordPegawai;
use Session;
use Carbon\Carbon;

use Auth;

class PegawaiController extends Controller
{
    public function index(){
//         $nik = Auth::guard('pegawai')->user()->nik;
//         $getid = DB::table('pegawai')->select('id')->where('nik', $nik)->first();
//         $presensimasuk = DB::table('temporary_presensi')->where('id', $getid->id)->get();
//         $ldate = date('H:i:s');
// dd($ldate);
        return view('pegawai/webcam', [
            // 'detailmapel' => $detailmapel, 
            // 'totalkelas' => $totalkelas, 
            // 'totalsiswa' => $totalsiswa, 
            // 'totalmapel' => $totalmapel,
            // 'pengujian_cosine' => $pengujian_cosine,
            // 'pengujian_jaccard' => $pengujian_jaccard,
            // 'pegawai' => $pegawai, 
        ]);
    }

    public function dokter(){
        $username = Session::get('username');
        $month = date('Y-m'); // '2023-06'; // 
        $date = date('Y-m-d'); //'2023-06-15'; // 

        $totalpasien = DB::table('pasien')->select('no_rkm_medis')->count();
        $pasienbulan = DB::table('pasien')->select('no_rkm_medis')->where('tgl_daftar', 'like', '%'.$month.'%')->count();
        
        $kdpoli = Session::get('getpoli');
        $polibulan = DB::table('reg_periksa')->select('no_rawat')->where('kd_poli', $kdpoli)->where('stts', "!=", 'Belum')->where('tgl_registrasi', 'like', '%'.$month.'%')->count();

        $polihari = DB::table('reg_periksa')->select('no_rawat')->where('kd_poli', $kdpoli)->where('tgl_registrasi', 'like', '%'.$date.'%')->count();

        // $pasienaktif = DB::table('reg_periksa as rp')->select('p.no_rkm_medis', DB::raw('count(*) as jumlah'), 'p.nm_pasien')->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')->where('kd_dokter', $username)->groupby('no_rkm_medis')->orderby('jumlah', 'desc')->take(10)->get();

        $pasienterakhir = DB::table('reg_periksa as a')->select('a.no_rawat', 'b.no_rkm_medis', 'b.nm_pasien', 'a.stts')
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->where('a.kd_dokter', $username)
            ->where('a.kd_poli','!=', 'U0013')
            ->where('a.tgl_registrasi', $date)->where('a.status_lanjut', 'Ralan')
            ->orderby('a.jam_reg', 'desc')->get();
            
        $ceksoapdokter = DB::table('reg_periksa as a')->select('a.no_rawat', 'b.no_rkm_medis', 'b.nm_pasien', 'a.stts','pr.nip as petugasrs')->distinct()
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->leftjoin('pemeriksaan_ralan as pr', 'a.no_rawat','=', 'pr.no_rawat')
            ->where('a.kd_dokter', $username)
            ->where('a.kd_poli','!=', 'U0013')->where('a.tgl_registrasi', $date)->where('a.status_lanjut', 'Ralan')
            ->where('pr.nip', '=', $username)
            ->orderby('a.jam_reg', 'desc')->get();

        $awalmedisbedah = DB::table('reg_periksa as a')->select('a.no_rawat', 'b.no_rkm_medis', 'b.nm_pasien', 'a.stts','pr.kd_dokter as petugasrs')->distinct()
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->leftjoin('penilaian_medis_ralan_bedah as pr', 'a.no_rawat','=', 'pr.no_rawat')
            ->where('a.kd_dokter', $username)->where('a.tgl_registrasi', $date)->where('a.status_lanjut', 'Ralan')
            ->where('pr.kd_dokter', '=', $username)
            ->orderby('a.jam_reg', 'desc')->get();

        $awalmedisdalam = DB::table('reg_periksa as a')->select('a.no_rawat', 'b.no_rkm_medis', 'b.nm_pasien', 'a.stts','pr.kd_dokter as petugasrs')->distinct()
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->leftjoin('penilaian_medis_ralan_penyakit_dalam as pr', 'a.no_rawat','=', 'pr.no_rawat')
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
            if($awalmedisdalam->isNotEmpty()){
                foreach ($awalmedisdalam as $keysoap => $amdalam) {
                    if($value->no_rawat == $amdalam->no_rawat){
                        $pasienterakhir[$key]->stts = "Sudah";
                    }
                }
            }
        }

        $rujukaninternal = DB::table('reg_periksa as a')->select('a.no_rawat', 'b.no_rkm_medis', 'b.nm_pasien', 'a.stts')
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->leftjoin('rujukan_internal_poli as int', 'int.no_rawat', '=', 'a.no_rawat')
            ->where('int.kd_dokter', $username)->where('a.tgl_registrasi', $date)
            ->orderby('a.jam_reg', 'desc')->get();

        $pasienranap = DB::table('kamar_inap as ki')->where('ki.stts_pulang', '-')->select('ki.*', 'p.no_rkm_medis', 'p.nm_pasien', 'b.nm_bangsal', 'rg.no_rawat2', 'p2.no_rkm_medis as no_rkm_medis2', 'p2.nm_pasien as nm_pasien2')
            ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'ki.no_rawat')
            ->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->leftjoin('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
            ->leftjoin('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
            ->leftjoin('dpjp_ranap as dr', 'dr.no_rawat', '=', 'ki.no_rawat')
            ->leftjoin('ranap_gabung as rg', 'rg.no_rawat', '=', 'ki.no_rawat')
            ->leftjoin('reg_periksa as rp2', 'rp2.no_rawat', '=', 'rg.no_rawat2')
            ->leftjoin('pasien as p2', 'p2.no_rkm_medis', '=', 'rp2.no_rkm_medis')
            ->where('dr.kd_dokter', $username)
            ->get();

            // 2024-07-23 08:54:58.0	Admin Utama 	insert into ranap_gabung values('2024/01/18/000005','2024/01/18/000083)

        // dd($pasienranap);
        
        return view('dokter/home', [
            'totalpasien' => $totalpasien, 
            'pasienbulan' => $pasienbulan, 
            'polibulan' => $polibulan, 
            'polihari' => $polihari, 
            'pasienranap' => $pasienranap, 
            'rujukaninternal' => $rujukaninternal, 
            // 'pasienaktif' => $pasienaktif, 
            'pasienterakhir' => $pasienterakhir, 
        ]);
    }

    public function admin(Request $request){
        if($request->tanggal != null){
            $date = date("Y-m-d", strtotime(substr($request->tanggal, 0, 10))); // date('Y-m-d'); //
            $enddate = date("Y-m-d", strtotime(substr($request->tanggal, -10)));
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }else{
            $start = new Carbon('first day of this month');
            $end = new Carbon('last day of this month');
            
            $date = $start->format("Y-m-d");
            $enddate = $end->format("Y-m-d");
            $tanggalawal = $start->formatLocalized('%d %B %Y');
            $tanggalakhir = $end->formatLocalized('%d %B %Y');
        }

        $persentasepengisian = [];
        $polibulanperdokterinput = [];
        $polibulanperdokter= [];
        $listdokter = DB::table('dokter as d')->select('d.nm_dokter', 'd.kd_dokter', 's.nm_sps')
            ->leftjoin('spesialis as s', 's.kd_sps','=', 'd.kd_sps')
            ->where('d.status', '1')->whereNotIn('d.kd_dokter', ['0105','0103','0082', '0110', 'dr.Lie', 'D0000037','0052', '0102'])->orderby('d.kd_sps', 'DESC')->orderby('d.nm_dokter', 'DESC')->get();
        // dd($listdokter);
        foreach ($listdokter as $key => $value) {
            $username = $value->kd_dokter;
            $polibulanperdokterinput[] = DB::table('reg_periksa as a')->select('a.*', DB::raw('count(*) as jumlah'))
            ->leftjoin('pemeriksaan_ralan as pr', 'a.no_rawat','=', 'pr.no_rawat')
            ->leftjoin('penilaian_medis_ralan_bedah as prb', 'a.no_rawat','=', 'prb.no_rawat')
            ->leftjoin('penilaian_medis_ralan_paru as prp', 'a.no_rawat','=', 'prp.no_rawat')
            ->leftjoin('penilaian_medis_ralan_kandungan as prk', 'a.no_rawat','=', 'prk.no_rawat')
            ->leftjoin('penilaian_medis_ralan_neurologi as prn', 'a.no_rawat','=', 'prn.no_rawat')
            ->leftjoin('penilaian_medis_ralan_anak as pra', 'a.no_rawat','=', 'pra.no_rawat')
            ->leftjoin('penilaian_medis_ralan_mata as prm', 'a.no_rawat','=', 'prm.no_rawat')
            ->leftjoin('penilaian_medis_ralan_penyakit_dalam as prd', 'a.no_rawat','=', 'prd.no_rawat')
            ->leftjoin('penilaian_medis_ralan as pru', 'a.no_rawat','=', 'pru.no_rawat')
            ->leftjoin('penilaian_medis_igd as pmi', 'a.no_rawat','=', 'pmi.no_rawat')
            ->where('a.kd_dokter', $username)
            ->where('a.status_lanjut', 'Ralan')
            ->where('a.stts', '!=' , 'Batal')
            ->where(function ($query) use ($username) {
                $query->orwhere('pr.nip', $username)
                    ->orWhere('prb.kd_dokter', $username)
                    ->orWhere('prp.kd_dokter', $username)
                    ->orWhere('prk.kd_dokter', $username)
                    ->orWhere('prn.kd_dokter', $username)
                    ->orWhere('pra.kd_dokter', $username)
                    ->orWhere('prm.kd_dokter', $username)
                    ->orWhere('prd.kd_dokter', $username)
                    ->orWhere('pru.kd_dokter', $username)
                    ->orWhere('pmi.kd_dokter', $username);
                })
            // ->where('a.tgl_registrasi', 'like', '%'.$month.'%')
            ->whereBetween('a.tgl_registrasi', [$date, $enddate])
            ->groupBy('a.no_rawat') 
            ->get();

            $polibulanperdokter[] = DB::table('reg_periksa as a')->select('a.*', DB::raw('count(*) as jumlah'))
            ->where('a.kd_dokter', $username)
            ->where('a.status_lanjut', 'Ralan')
            ->where('a.stts', '!=' , 'Batal')
            // ->where('a.tgl_registrasi', 'like', '%'.$month.'%')
            ->whereBetween('a.tgl_registrasi', [$date, $enddate])
            ->groupBy('a.no_rawat') 
            ->get();
        }
        $totalpolibulanperdokterinput = [];
        $totalpolibulanperdokter = [];
        $persentasepolibulanperdokter = [];
        foreach ($polibulanperdokterinput as $key => $value) {
            $totalinput = $polibulanperdokterinput[$key]->count();
            $totalsemua = $polibulanperdokter[$key]->count();
            $totalpolibulanperdokterinput[$key] = $totalinput;
            $totalpolibulanperdokter[$key] = $totalsemua;
            if($totalsemua != 0){
                $persentasepolibulanperdokter[$key] = round(($totalinput / $totalsemua) * 100, 1);
            }else{
                $persentasepolibulanperdokter[$key] = 0;
            }
        }

        return view('admin/home', [
            'listdokter' => $listdokter, 
            'totalpolibulanperdokter' => $totalpolibulanperdokter, 
            'totalpolibulanperdokterinput' => $totalpolibulanperdokterinput, 
            'persentasepolibulanperdokter' => $persentasepolibulanperdokter, 
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
        ]);
    }

    public function penilaianperawat(Request $request){
        if($request->tanggal != null){
            $date = date("Y-m-d", strtotime(substr($request->tanggal, 0, 10))); // date('Y-m-d'); //
            $enddate = date("Y-m-d", strtotime(substr($request->tanggal, -10)));
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }else{
            $start = new Carbon('first day of this month');
            $end = new Carbon('last day of this month');
            
            $date = $start->format("Y-m-d");
            $enddate = $end->format("Y-m-d");
            $tanggalawal = $start->formatLocalized('%d %B %Y');
            $tanggalakhir = $end->formatLocalized('%d %B %Y');
        }

        $persentasepengisian = [];
        $polibulanperperawatinput = [];
        $polibulanperperawat= [];

        // ambil list perawat beserta poli
        $listperawat = [
                '02.02.09.139' => ['U0003', 'U0005'], //rista bedah dalam
                '02.02.12.207' => ['U0017'], //ratna jantung
                '02.03.18.266' => ['U0004', 'U0003'], //gustin anak & bedah
                '02.02.20.310' => ['U0006', 'U0007'], //ima mata & obgyn
                '02.02.00.024' => ['U0008'], //lilik syaraf
                '058' => ['IGDK'], //susiana igd
                '289' => ['IGDK'], //lilis igd
                '408' => ['IGDK'], //bachtiar igd
                '308' => ['IGDK'], //okakan igd
                '02.02.21.335' => ['IGDK'], //alfi igd
                '176' => ['IGDK'], //muslimah igd
                '02.02.00.022' => ['IGDK'], //hadi igd
                '347' => ['IGDK'], //kusmiran igd
                '053' => ['IGDK'], //nurkolis igd
            ];

        foreach ($listperawat as $nip => $poli) {
            $namaperawat[$nip] = DB::table('petugas as p')->select('p.nama', 'p.nip')
            ->where('p.nip', $nip)
            ->first();

            // dd($namaperawat);

            $polibulanperperawatinput[$nip] = DB::table('reg_periksa as a')->select('a.*', DB::raw('count(*) as jumlah'))
            ->leftjoin('pemeriksaan_ralan as pr', 'a.no_rawat','=', 'pr.no_rawat')
            ->leftjoin('penilaian_awal_keperawatan_igd as pak', 'a.no_rawat','=', 'pak.no_rawat')
            ->whereIn('a.kd_poli', $poli)
            // ->where('a.status_lanjut', 'Ralan')
            ->where(function ($query) use ($nip) {
                $query->orwhere('pr.nip', $nip)
                    ->orWhere('pak.nip', $nip);
                })
            ->whereBetween('a.tgl_registrasi', [$date, $enddate])
            ->groupBy('a.no_rawat') 
            ->get();

            $polibulanperperawat[$nip] = DB::table('reg_periksa as a')->select('a.*', DB::raw('count(*) as jumlah'))
            ->whereIn('a.kd_poli', $poli)
            // ->where('a.status_lanjut', 'Ralan')
            ->whereBetween('a.tgl_registrasi', [$date, $enddate])
            ->groupBy('a.no_rawat') 
            ->get();
        }

        $totalpolibulanperperawatinput = [];
        $totalpolibulanperperawat = [];
        $persentasepolibulanperperawat = [];
        foreach ($polibulanperperawatinput as $key => $value) {
            $totalinput = $polibulanperperawatinput[$key]->count();
            $totalsemua = $polibulanperperawat[$key]->count();
            $totalpolibulanperperawatinput[$key] = $totalinput;
            $totalpolibulanperperawat[$key] = $totalsemua;
            if($totalsemua != 0){
                $persentasepolibulanperperawat[$key] = round(($totalinput / $totalsemua) * 100, 1);
            }else{
                $persentasepolibulanperperawat[$key] = 0;
            }
        }

        return view('admin/penilaianperawat', [
            'namaperawat' => $namaperawat, 
            'listperawat' => $listperawat, 
            'totalpolibulanperperawat' => $totalpolibulanperperawat, 
            'totalpolibulanperperawatinput' => $totalpolibulanperperawatinput, 
            'persentasepolibulanperperawat' => $persentasepolibulanperperawat, 
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
        ]);
    }

    public function persentasepoli(Request $request){
        if($request->tanggal != null){
            $date = date("Y-m-d", strtotime(substr($request->tanggal, 0, 10))); // date('Y-m-d'); //
            $enddate = date("Y-m-d", strtotime(substr($request->tanggal, -10)));
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }else{
            $start = new Carbon('first day of this month');
            $end = new Carbon('last day of this month');
            
            $date = $start->format("Y-m-d");
            $enddate = $end->format("Y-m-d");
            $tanggalawal = $start->formatLocalized('%d %B %Y');
            $tanggalakhir = $end->formatLocalized('%d %B %Y');
        }

        $listsemuaperiksa = DB::table('reg_periksa as rp')->select('rp.no_rawat', 'rp.no_rkm_medis', 'rp.kd_poli', 'p.nm_poli',DB::raw('count(*) as jumlah'))
            ->leftjoin('poliklinik as p', 'p.kd_poli','=', 'rp.kd_poli')
            ->whereBetween('rp.tgl_registrasi', [$date, $enddate])
            ->where('rp.stts', '!=', 'batal')
            ->groupBy('p.kd_poli')
            ->get();
        
        $listsudahperiksa = DB::table('reg_periksa as rp')->select('rp.no_rawat', 'rp.no_rkm_medis', 'rp.kd_poli', 'p.nm_poli', DB::raw('count(*) as jumlah'))
            ->leftjoin('poliklinik as p', 'p.kd_poli','=', 'rp.kd_poli')
            ->leftjoin('pemeriksaan_ralan as pr', 'rp.no_rawat','=', 'pr.no_rawat')
            ->leftjoin('penilaian_awal_keperawatan_igd as igd', 'rp.no_rawat','=', 'igd.no_rawat')
            ->whereBetween('rp.tgl_registrasi', [$date, $enddate])
            ->where(function ($query) use ($date, $enddate) {
                $query->orWhereBetween('pr.tgl_perawatan', [$date, $enddate])
                    ->orWhereBetween('igd.tanggal', [$date, $enddate]);
                })
            ->groupBy('rp.no_rawat') 
            ->where('rp.stts', '!=', 'batal')
            ->distinct()
            ->get();

        $listpoli = DB::table('poliklinik')->orderby('nm_poli', 'DESC')->where('status', '1')->whereNotIn('kd_poli', ['U0011','-', 'U0010', 'U0012', 'U0013', 'U0007'])->get();

        $kodepoli = [];
        foreach ($listpoli as $keypoli => $valuepoli) {
            $total = 0;
            $totalsemua = 0;
            // ambil pemeriksaan yang sudah diperiksa
            foreach ($listsudahperiksa as $key => $sudah) {
                if ($valuepoli->kd_poli == $sudah->kd_poli) {
                    $total++;
                }
            }
            // ambil total semua pemeriksaan
            foreach ($listsemuaperiksa as $key => $semua) {
                if ($valuepoli->kd_poli == $semua->kd_poli) {
                    $totalsemua = $semua->jumlah;
                }
            }
            // print($total. '<br>');
            $kodepoli[$keypoli] = ['sudah' => $total, 'semua' => $totalsemua, 'nm_poli' => $valuepoli->nm_poli];
        }

        $persentaseinputpoli = [];  
        foreach ($kodepoli as $key => $value) {
            if($value['semua'] != 0){
                $persentaseinputpoli[$key] = ['hasil' =>round(($value['sudah'] / $value['semua']) * 100, 1),
                                             'sudah' =>$value['sudah'], 
                                             'semua' =>$value['semua'],
                                             'nm_poli' =>$value['nm_poli']];
            }else{
                $persentaseinputpoli[$key] = ['hasil' =>0, 
                                             'sudah' =>$value['sudah'], 
                                             'semua' =>$value['semua'],
                                             'nm_poli' =>$value['nm_poli']];
            }
        }

        // persentase pengisian laboratorium
        $permintaanlab = DB::table('permintaan_lab')
            ->whereBetween('tgl_permintaan', [$date, $enddate])->count();
        $labsudahdiperiksa = DB::table('permintaan_lab')
            ->where('tgl_hasil', '!=', '0000-00-00')
            ->whereBetween('tgl_permintaan', [$date, $enddate])->count();
        if($labsudahdiperiksa <= 0){
            $persentaselab = 0;
        }else{
            $persentaselab = round(($labsudahdiperiksa / $permintaanlab) * 100, 1);
        }
        $persentaseinputpoli[] = ['hasil' => $persentaselab, 
                                'sudah' =>$labsudahdiperiksa, 
                                'semua' =>$permintaanlab,
                                'nm_poli' => 'Laboratorium'];


        // Persentase pengisian obat
        $semuapermintaanobat = DB::table('resep_obat')
            ->whereBetween('tgl_peresepan', [$date, $enddate])->count();
        $validasiobat = DB::table('resep_obat')
            ->where('tgl_perawatan', '!=', '0000-00-00')
            ->whereBetween('tgl_peresepan', [$date, $enddate])->count();
        if($validasiobat <= 0){
            $persentaseobat= 0;
        }else{
            $persentaseobat= round(($validasiobat / $semuapermintaanobat) * 100, 1);
        }
        $persentaseinputpoli[] = ['hasil' => $persentaseobat, 
                                'sudah' =>$validasiobat, 
                                'semua' =>$semuapermintaanobat,
                                'nm_poli' => 'Farmasi'];

        // Ranap Per Kelas
        $listsudahperiksaranap = DB::table('reg_periksa as rp')->select('ki.*','k.kelas','rp.no_rawat', 'rp.no_rkm_medis', DB::raw('count(*) as jumlah'))
            ->leftjoin('pemeriksaan_ranap as pr', 'rp.no_rawat','=', 'pr.no_rawat')
            ->leftjoin('kamar_inap as ki', 'ki.no_rawat', '=', 'rp.no_rawat')
            ->leftjoin('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
            ->whereBetween('rp.tgl_registrasi', [$date, $enddate])
            ->where('rp.status_lanjut', 'Ranap')
            // ->where('pr.tgl_perawatan', [$date, $enddate])
            ->whereNotNull('ki.tgl_keluar')
            ->groupBy('rp.no_rawat') 
            ->where('rp.stts', '!=', 'batal')
            ->distinct()
            ->get();
        
        $lamavip = $totalpemeriksaanvip = $lama1 = $totalpemeriksaan1 = $lama2 = $totalpemeriksaan2 = $lama3 = $totalpemeriksaan3 = 0;
        foreach ($listsudahperiksaranap as $key => $value) {
            if($value->kelas == 'Kelas VIP'){
                $lamavip += $value->lama;
                $totalpemeriksaanvip += $value->jumlah;
            }elseif($value->kelas == 'Kelas 1'){
                $lama1 += $value->lama;
                $totalpemeriksaan1 += $value->jumlah;
            }elseif($value->kelas == 'Kelas 2'){
                $lama2 += $value->lama;
                $totalpemeriksaan2 += $value->jumlah;
            }elseif($value->kelas == 'Kelas 3'){
                $lama3 += $value->lama;
                $totalpemeriksaan3 += $value->jumlah;
            }
        }

        $persentaseinputranap[0] = ['hasil' => $lamavip > 0 ? round(($totalpemeriksaanvip / ($lamavip *3)) * 100, 1) : 0,
                                    'lamaperawatan' =>$lamavip, 
                                    'totalsoap' =>$totalpemeriksaanvip,
                                    'nm_poli' =>"VIP"];

        $persentaseinputranap[1] = ['hasil' =>$lama1 > 0 ? round(($totalpemeriksaan1 / ($lama1 *3)) * 100, 1) : 0,
                                    'lamaperawatan' =>$lama1, 
                                    'totalsoap' =>$totalpemeriksaan1,
                                    'nm_poli' =>"Kelas 1"];

        $persentaseinputranap[2] = ['hasil' =>$lama2 > 0 ? round(($totalpemeriksaan2 / ($lama2 *3)) * 100, 1) : 0,
                                    'lamaperawatan' =>$lama2, 
                                    'totalsoap' =>$totalpemeriksaan2,
                                    'nm_poli' =>"Kelas 2"];

        $persentaseinputranap[3] = ['hasil' =>$lama3 > 0 ? round(($totalpemeriksaan3 / ($lama3 *3)) * 100, 1) : 0,
                                    'lamaperawatan' =>$lama3, 
                                    'totalsoap' =>$totalpemeriksaan3,
                                    'nm_poli' =>"Kelas 3"];

        return view('admin/persentasepoli', [
            'persentaseinputpoli' => $persentaseinputpoli, 
            'persentaseinputranap' => $persentaseinputranap, 
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
        ]);
    }

    public function persentaseranap(Request $request){
        if($request->tanggal != null){
            $date = date("Y-m-d", strtotime(substr($request->tanggal, 0, 10))); // date('Y-m-d'); //
            $enddate = date("Y-m-d", strtotime(substr($request->tanggal, -10)));
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }else{
            $start = new Carbon('first day of this month');
            $end = new Carbon('last day of this month');
            
            $date = $start->format("Y-m-d");
            $enddate = $end->format("Y-m-d");
            $tanggalawal = $start->formatLocalized('%d %B %Y');
            $tanggalakhir = $end->formatLocalized('%d %B %Y');
        }

            $date = '2024-01-15'; // date('Y-m-d'); //
        $enddate = '2024-01-30'; // date('Y-m-d'); //

        // $listRI = DB::table('kamar_inap as ki')->select('ki.*', 'p.no_rkm_medis', 'p.nm_pasien', 'b.kd_bangsal', 'b.nm_bangsal', 'k.kd_kamar', 'rp.no_rawat', 'p.umur', DB::raw("count(*) as jumlah"))
        //     ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'ki.no_rawat')
        //     ->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
        //     ->leftjoin('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
        //     ->leftjoin('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
        //     ->leftjoin('pemeriksaan_ranap as pr', 'pr.no_rawat', '=', 'ki.no_rawat')
        //     ->where('ki.stts_pulang', '!=' ,'-')
        //     ->orderBy('ki.tgl_keluar', 'DESC')
        //     ->groupBy('ki.no_rawat')
        //     ->whereBetween('ki.tgl_masuk', [$date, $enddate])
        //     ->where('ki.no_rawat', '2024/01/18/000005' )
        //     // ->limit(10)
        //     ->get();

        // $listRI = DB::table('reg_periksa as rp')->select('ki.*', 'p.no_rkm_medis', 'p.nm_pasien', 'b.kd_bangsal', 'b.nm_bangsal', 'k.kd_kamar', 'rp.no_rawat', 'p.umur', DB::raw("count(*) as jumlah"))
        //     ->distinct()
        //     ->leftjoin('kamar_inap as ki', 'rp.no_rawat', '=', 'ki.no_rawat')
        //     ->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
        //     ->leftjoin('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
        //     ->leftjoin('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
        //     ->leftjoin('pemeriksaan_ranap as pr', 'pr.no_rawat', '=', 'ki.no_rawat')
        //     ->where('ki.stts_pulang', '!=' ,'-')
        //     ->orderBy('ki.tgl_keluar', 'DESC')
        //     ->groupBy('ki.no_rawat', 'ki.kd_kamar')
        //     ->whereBetween('ki.tgl_masuk', [$date, $enddate])
        //     ->whereBetween('pr.tgl_perawatan', [$date, $enddate])
        //     ->where('ki.no_rawat', '2024/01/18/000005' )
        //     // ->limit(10)
        //     ->get();

        $listRI = DB::table('reg_periksa as rp')
            ->select('p.no_rkm_medis', 'p.nm_pasien', DB::raw("ki.*, count(*) as jumlah"))
            ->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->leftjoin('pemeriksaan_ranap as pr', 'pr.no_rawat', '=', 'rp.no_rawat')
            ->leftjoin('kamar_inap as ki', 'rp.no_rawat', '=', 'ki.no_rawat')
            ->orderBy('rp.no_rawat', 'DESC')
            ->whereBetween('ki.tgl_masuk', [$date, $enddate])
            ->whereBetween('pr.tgl_perawatan', [$date, $enddate])
            // ->where('rp.no_rawat', '2024/01/18/000005' )
            ->groupBy('ki.kd_kamar')->distinct()
            ->get();
        // dd($listRI);

        $listkamar = ['R001B', 'R00B1', 'R002', 'R003', 'R003', 'R004', 'R005', 'R006'];

        foreach ($listkamar as $valueKamar) {
            $totalpasien = 0;
            $totalperawatan = 0;
            $namabangsal = '';
            $lama = 0;
            foreach ($listRI as $key => $valueRI) {
                if($valueRI->kd_bangsal == $valueKamar){
                    $totalpasien++;
                    $totalperawatan += $valueRI->jumlah;
                    $namabangsal = $valueRI->nm_bangsal;
                    $lama += $valueRI->lama;
                }
            };
            // perhari harunya ada 3x pemeriksaan, maka jumlah hari dikalikan 3
            // setelah itu dibandingkan dengan jumlah pemeriksaan yang sudah diinputkan
            $pemeriksaan = $lama * 3;
            if($totalperawatan <= 0){
                $pemeriksaan = 0;
            }else{
                $pemeriksaan = ($totalperawatan / $pemeriksaan) * 100;
            }
            $kamar[$valueKamar] = ['nm_bangsal' => $namabangsal, 'totalpasien' => $totalpasien, 'totalperawatan' => $totalperawatan, 'lama' => $lama, 'hasil' => $pemeriksaan];
        }



        $listsemuaperiksa = DB::table('reg_periksa as rp')->select('rp.no_rawat', 'rp.no_rkm_medis', 'rp.kd_poli', 'p.nm_poli',DB::raw('count(*) as jumlah'))
            ->leftjoin('poliklinik as p', 'p.kd_poli','=', 'rp.kd_poli')
            ->whereBetween('rp.tgl_registrasi', [$date, $enddate])
            ->groupBy('p.kd_poli')
            ->get();        
        
        $listsudahperiksa = DB::table('reg_periksa as rp')->select('rp.no_rawat', 'rp.no_rkm_medis', 'rp.kd_poli', 'p.nm_poli', DB::raw('count(*) as jumlah'))
            ->leftjoin('poliklinik as p', 'p.kd_poli','=', 'rp.kd_poli')
            ->leftjoin('pemeriksaan_ralan as pr', 'rp.no_rawat','=', 'pr.no_rawat')
            ->leftjoin('penilaian_awal_keperawatan_igd as igd', 'rp.no_rawat','=', 'igd.no_rawat')
            ->whereBetween('rp.tgl_registrasi', [$date, $enddate])
            ->where(function ($query) use ($date, $enddate) {
                $query->orWhereBetween('pr.tgl_perawatan', [$date, $enddate])
                    ->orWhereBetween('igd.tanggal', [$date, $enddate]);
                })
            ->groupBy('rp.no_rawat') 
            ->distinct()
            ->get();

        $listpoli = DB::table('poliklinik')->orderby('nm_poli', 'DESC')->where('status', '1')->whereNotIn('kd_poli', ['U0011','-', 'U0010', 'U0012'])->get();

        $kodepoli = [];
        foreach ($listpoli as $keypoli => $valuepoli) {
            $total = 0;
            $totalsemua = 0;
            // ambil pemeriksaan yang sudah diperiksa
            foreach ($listsudahperiksa as $key => $sudah) {
                if ($valuepoli->kd_poli == $sudah->kd_poli) {
                    $total++;
                }
            }
            // ambil total semua pemeriksaan
            foreach ($listsemuaperiksa as $key => $semua) {
                if ($valuepoli->kd_poli == $semua->kd_poli) {
                    $totalsemua = $semua->jumlah;
                }
            }
            // print($total. '<br>');
            $kodepoli[$keypoli] = ['sudah' => $total, 'semua' => $totalsemua, 'nm_poli' => $valuepoli->nm_poli];
        }

        $persentaseinputpoli = [];  
        foreach ($kodepoli as $key => $value) {
            if($value['semua'] != 0){
                $persentaseinputpoli[$key] = ['hasil' =>round(($value['sudah'] / $value['semua']) * 100, 1),
                                             'sudah' =>$value['sudah'], 
                                             'semua' =>$value['semua'],
                                             'nm_poli' =>$value['nm_poli']];
            }else{
                $persentaseinputpoli[$key] = ['hasil' =>0, 
                                             'sudah' =>$value['sudah'], 
                                             'semua' =>$value['semua'],
                                             'nm_poli' =>$value['nm_poli']];
            }
        }

        return view('admin/persentaseranap', [
            'persentaseinputpoli' => $persentaseinputpoli, 
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
        ]);
    }

    function riwayatradiologi(Request $request){
        if($request->tanggal != null){
            $date = date("Y-m-d", strtotime(substr($request->tanggal, 0, 10))); // date('Y-m-d'); //
            $enddate = date("Y-m-d", strtotime(substr($request->tanggal, -10)));
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }else{
            $start = new Carbon('first day of this month');
            $end = new Carbon('last day of this month');
            
            $date = $start->format("Y-m-d");
            $enddate = $end->format("Y-m-d");
            $tanggalawal = $start->formatLocalized('%d %B %Y');
            $tanggalakhir = $end->formatLocalized('%d %B %Y');
        }

        // $date = '2023-06-15'; // date('Y-m-d'); //
        // $enddate = '2024-06-15'; // date('Y-m-d'); //
        $hargatotal = 0;

        // //total permintaan
        // $riwayatradiologi = DB::table('periksa_radiologi as pr')->select('pr.*', 'jpr.nm_perawatan')
        //     ->leftjoin('jns_perawatan_radiologi as jpr', 'jpr.kd_jenis_prw', '=', 'pr.kd_jenis_prw')
        //     ->whereBetween('pr.tgl_periksa', [$date, $enddate])
        //     ->orderBy('pr.no_rawat', 'DESC')
        //     ->get();

        // //total pasien menghitung pasien bpjs atau umum
        // $hasilrad = DB::table('hasil_radiologi as hr')->select('hr.*', 'rp.kd_pj')
        //     ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'hr.no_rawat')
        //     ->whereBetween('hr.tgl_periksa', [$date, $enddate])
        //     ->orderBy('hr.tgl_periksa', 'DESC')
        //     ->get();

        //total pemeriksaan
        $riwayatradiologi = DB::table('periksa_radiologi as pr')->select('pr.*', 'jpr.nm_perawatan')
            ->leftjoin('jns_perawatan_radiologi as jpr', 'jpr.kd_jenis_prw', '=', 'pr.kd_jenis_prw')
            ->whereBetween('pr.tgl_periksa', [$date, $enddate])
            ->orderBy('pr.no_rawat', 'DESC')
            ->get();

        $riwayatradiologigroupnorawat = DB::table('periksa_radiologi as pr')->select('pn.png_jawab', 'hr.hasil','pr.tgl_periksa', 'p.no_rkm_medis', 'p.nm_pasien', 'rp.kd_pj','pr.no_rawat', 'pr.jam', DB::raw('count(*) as jumlah'))->distinct()
            ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'pr.no_rawat')
            ->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->leftjoin('penjab as pn', 'pn.kd_pj', '=', 'rp.kd_pj')
            ->leftjoin('hasil_radiologi as hr', function($join)
                {
                    $join->on('pr.no_rawat', '=',  'hr.no_rawat');
                    $join->on('pr.tgl_periksa','=', 'hr.tgl_periksa');
                    $join->on('pr.jam','=', 'hr.jam');
                })
            ->whereBetween('pr.tgl_periksa', [$date, $enddate])
            ->groupBY('pr.tgl_periksa','pr.no_rawat', 'pr.jam')
            ->orderBy('pr.tgl_periksa', 'DESC')
            ->get();

        
        // hitung text yang mengandung yth
        $cekperiksa = [];
        $gambarrad = [];
        foreach ($riwayatradiologigroupnorawat as $key => $value) {
            $hasilrad = DB::table('hasil_radiologi')->where([['no_rawat', $value->no_rawat], ['tgl_periksa', $value->tgl_periksa], ['jam', $value->jam]])->first();

            $hasilrad = preg_replace('/\s+/', '', $hasilrad->hasil ?? null);
            $hasilrad = strtolower($hasilrad);
            $total = substr_count($hasilrad, 'yth');
            $cekperiksa[] = $total;

            // ambil gambar
            $getgambar = DB::table('gambar_radiologi')
            ->where([['no_rawat', $value->no_rawat], ['tgl_periksa', $value->tgl_periksa], ['jam', $value->jam]])
            ->orderBy('tgl_periksa', 'DESC')
            ->get();

            if($getgambar->isNotEmpty()){
                foreach ($getgambar as $keygambar => $valuegambar) {
                    $gambarrad[$key][$keygambar] = str_replace("simrs-rsba", "", env('APP_URL')) .'budiasih/radiologi/'. $valuegambar->lokasi_gambar;
                }
            }else{
                $gambarrad[$key][0] = null;
            }
        }

        //total pasien menghitung pasien bpjs atau umum
        $hasilrad = DB::table('hasil_radiologi as hr')->select('hr.*', 'rp.kd_pj')
            ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'hr.no_rawat')
            ->whereBetween('hr.tgl_periksa', [$date, $enddate])
            ->orderBy('hr.tgl_periksa', 'DESC')
            ->get();

        // dd($riwayatradiologi, $riwayatradiologigroupnorawat, $gambarrad, $cekperiksa);

        $totalbacaanBPJS= 0;
        $totalbacaanUmum= 0;
        foreach($hasilrad as $key => $value) {
            $string = preg_replace('/\s+/', '', $value->hasil);
            $string = strtolower($string);
            $string2 = substr_count($string, 'yth');
            if($value->kd_pj == "BPJ"){
                $totalbacaanBPJS += $string2;
            }elseif($value->kd_pj == "A02"){
                $totalbacaanUmum += $string2;
            }
        }

        foreach($riwayatradiologi as $key => $value) {
            $hargatotal += $value->biaya;
        }
        
        $totalPermintaan = DB::table('periksa_radiologi as pr')
            ->selectRaw('COUNT(CASE WHEN rp.kd_pj = "BPJ" THEN 1 END) AS BPJS')
            ->selectRaw('COUNT(CASE WHEN rp.kd_pj = "A02" THEN 1 END) AS Umum')
            ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'pr.no_rawat')
            ->leftjoin('jns_perawatan_radiologi as jpr', 'jpr.kd_jenis_prw', '=', 'pr.kd_jenis_prw')
            ->whereBetween('pr.tgl_periksa', [$date, $enddate])
            ->get();
        
        // UMUM
        if($totalbacaanUmum >= 1){
            $persetaseUmum = round(($totalbacaanUmum / $totalPermintaan[0]->Umum) * 100, 1);
            $persetaseUmum = ($persetaseUmum >= 100) ? 100 : $persetaseUmum;
        }else{
            $persetaseUmum = 0;
        }
        $bpjs[] = ['hasil' => $persetaseUmum, 
                'bacaan' =>$totalbacaanUmum, 
                'permintaan' =>$totalPermintaan[0]->Umum,
                'jenis' => 'UMUM'];
        
        // BPJS
        if($totalbacaanBPJS >= 1){
            $persetaseBPJS = round(($totalbacaanBPJS / $totalPermintaan[0]->BPJS) * 100, 1);
            $persetaseBPJS = ($persetaseBPJS >= 100) ? 100 : $persetaseBPJS;
        }else{
            $persetaseBPJS = 0;
        }
        $bpjs[] = ['hasil' => $persetaseBPJS, 
                'bacaan' =>$totalbacaanBPJS, 
                'permintaan' =>$totalPermintaan[0]->BPJS,
                'jenis' => 'BPJS'];

        $bpjs[] = ['hasil' => ($persetaseBPJS + $persetaseUmum) / 2, 
                'bacaan' => $totalbacaanBPJS + $totalbacaanUmum, 
                'permintaan' =>$totalPermintaan[0]->BPJS + $totalPermintaan[0]->Umum,
                'jenis' => 'TOTAL'];

        return view('admin/riwayatradiologi', [
            'riwayatradiologi' => $riwayatradiologi, 
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
            'hargatotal' => $hargatotal, 
            'bpjs' => $bpjs, 
            'riwayatradiologigroupnorawat' => $riwayatradiologigroupnorawat, 
            'cekperiksa' => $cekperiksa, 
            'gambarrad' => $gambarrad
        ]);
    }

    function pemesananobat(Request $request){
        // $date = date("Y-m-d", strtotime("-3 MONTH")); // date('Y-m-d'); //
        $date = '2024-11-01'; 
        $enddate = date("Y-m-d");

        // ambil data obat
        $obat = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.letak_barang','jenis.nama', 'db.dasar', 'db.h_beli', 'db.ralan')
            ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
            ->where('db.status', '=', '1')->orderby('db.nama_brng')->get();
        $pemesanan = [];
        $hargaobat = [];

        if($request->kode_brng){
            $pemesanan = DB::table('detailpesan as dp')->select(
                    'dp.*', 'ds.kode_suplier', 'ds.nama_suplier', 'p.tgl_faktur', 'p.ppn', 'dp.total', 'dp.jumlah2',
                    DB::raw("
                    (CASE WHEN p.ppn = 0 THEN dp.total WHEN p.ppn > 0 THEN (dp.total)+((dp.total*11)/100) END) AS hargappn,
                    (CASE WHEN p.ppn = 0 THEN dp.total / dp.jumlah2 WHEN p.ppn > 0 THEN (dp.total / dp.jumlah2)+(((dp.total / dp.jumlah2)*11)/100) END) AS pcs,
                    (CASE WHEN p.ppn = 0 THEN (dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.3) WHEN p.ppn > 0 THEN (dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.11) + ((dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.11))*0.3 END) AS hargajual,
                                        (CASE WHEN p.ppn = 0 THEN (dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.391) WHEN p.ppn > 0 THEN (dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.11) + ((dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.11))*0.391 END) AS hargajuallagi")
                )
                ->leftjoin('pemesanan as p', 'p.no_faktur', '=', 'dp.no_faktur')
                ->leftjoin('datasuplier as ds', 'ds.kode_suplier', '=', 'p.kode_suplier')
                ->where('kode_brng', "?")
                ->orderBy('p.tgl_faktur', 'DESC')
                ->setBindings([$request->kode_brng])
                ->whereBetween('p.tgl_faktur', [$date, $enddate])
                ->get()
                ->sortByDesc('pcs');

            $hargaobat = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.letak_barang', 'db.dasar', 'db.h_beli', 'db.ralan')
                ->where('db.kode_brng', $request->kode_brng)->first();
        }

        return view('admin/pemesananobat', [
            'obat' => $obat, 
            'pemesanan' => $pemesanan, 
            'hargaobat' => $hargaobat,
        ]);
    }

    function selisihobat(Request $request){
        // $date = date("Y-m-d", strtotime("-1 year")); 
        // $date = date("Y-m-d", strtotime("-4 MONTH")); 
        $date = '2024-11-01'; 
        $enddate = date("Y-m-d");
        
        // ambil data obat
        $obat = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.letak_barang','jenis.nama', 'db.dasar', 'db.h_beli', 'db.ralan')
            ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
            ->where('db.status', '=', '1')->orderby('db.nama_brng')->get();

        $selisihharagaobat = [];
        // foreach ($obat as $key => $value) {
        //     // dd($value->kode_brng);
        //     $getharga = DB::table('detailpesan as dp')->select(
        //         'db.nama_brng', 'db.dasar', 'db.h_beli', 'db.ralan', 'dp.*', 'ds.kode_suplier', 'ds.nama_suplier', 'p.tgl_faktur', 'p.ppn', 'dp.total', 'dp.jumlah2',
        //         DB::raw("
        //             (CASE WHEN p.ppn = 0 THEN dp.total WHEN p.ppn > 0 THEN (dp.total)+((dp.total*11)/100) END) AS hargappn,
        //             (CASE WHEN p.ppn = 0 THEN dp.total / dp.jumlah2 WHEN p.ppn > 0 THEN (dp.total / dp.jumlah2)+(((dp.total / dp.jumlah2)*11)/100) END) AS pcs,
        //             (CASE WHEN p.ppn = 0 THEN (dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.3) WHEN p.ppn > 0 THEN (dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.11) + ((dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.11))*0.3 END) AS hargajual")
        //     )
        //     ->leftjoin('pemesanan as p', 'p.no_faktur', '=', 'dp.no_faktur')
        //     ->leftjoin('datasuplier as ds', 'ds.kode_suplier', '=', 'p.kode_suplier')
        //     ->leftjoin('databarang as db', 'db.kode_brng', '=', 'dp.kode_brng')
        //     ->where('dp.kode_brng', "?")
        //     ->orderBy('p.tgl_faktur', 'DESC')
        //     // ->setBindings([$value->kode_brng])
        //     ->setBindings(['TABB0000414'])
        //     ->first();
            
        //     $selisihharagaobat[] = $getharga;
        // }

        foreach ($obat as $key => $value) {
            // dd($value->kode_brng);
            $getharga = DB::table('detailpesan as dp')->select(
                'db.nama_brng', 'db.dasar', 'db.h_beli', 'db.ralan', 'dp.*', 'ds.kode_suplier', 'ds.nama_suplier', 'p.tgl_faktur', 'p.ppn', 'dp.total', 'dp.jumlah2',
                DB::raw("
                    (CASE WHEN p.ppn = 0 THEN dp.total WHEN p.ppn > 0 THEN (dp.total)+((dp.total*11)/100) END) AS hargappn,
                    (CASE WHEN p.ppn = 0 THEN dp.total / dp.jumlah2 WHEN p.ppn > 0 THEN (dp.total / dp.jumlah2)+(((dp.total / dp.jumlah2)*11)/100) END) AS pcs,
                    (CASE WHEN p.ppn = 0 THEN (dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.3) WHEN p.ppn > 0 THEN (dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.11) + ((dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.11))*0.3 END) AS hargajual,
                    (CASE WHEN p.ppn = 0 THEN (dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.391) WHEN p.ppn > 0 THEN (dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.11) + ((dp.total / dp.jumlah2)+((dp.total / dp.jumlah2)*0.11))*0.391 END) AS hargajuallagi")
            )
            ->leftjoin('pemesanan as p', 'p.no_faktur', '=', 'dp.no_faktur')
            ->leftjoin('datasuplier as ds', 'ds.kode_suplier', '=', 'p.kode_suplier')
            ->leftjoin('databarang as db', 'db.kode_brng', '=', 'dp.kode_brng')
            ->where('dp.kode_brng', "?")
            ->orderBy('p.tgl_faktur', 'DESC')
            ->setBindings([$value->kode_brng])
            // ->setBindings(['ALK000001'])
            ->whereBetween('p.tgl_faktur', [$date, $enddate])
            ->take(6)
            ->get()
            ->sortByDesc('pcs');

            // ambil harga teringgi dari 6 bulan terakhir
            if($getharga){
                foreach ($getharga as $hargaobat) {
                    $selisihharagaobat[] = $hargaobat;

                    // update harga
                    if($request->aksi == 'UpdateHarga'){
                        $request->merge(['kode_brng' => $hargaobat->kode_brng, 'hargajual' => $hargaobat->hargajual]);
                        $this->updatehargaobat($request);
                    }
                    break;
                }
            }
        }

        // dd($selisihharagaobat[481]);
        
        return view('admin/selisihobat', [
            'selisihharagaobat' => $selisihharagaobat,
        ]);
    }

    function updatehargaobat(Request $request){
        $hargajual = number_format($request->hargajual , 0, ',', '');

        $validate = $request->validate([
            'kode_brng' => ['required', 'max:50'],
            'hargajual' => ['required'],
        ]);
// dd($hargajual);
        $data = DB::table('databarang')->where('kode_brng','=', $request->kode_brng)
            ->update([
                'ralan' => $hargajual,
                'kelas1' => $hargajual,
                'kelas2' => $hargajual,
                'kelas3' => $hargajual,
                'utama' => $hargajual,
                'vip' => $hargajual,
                'vvip' => $hargajual,
                'beliluar' => $hargajual,
                'jualbebas' => $hargajual,
                'karyawan' => $hargajual,
            ]);

        $request->session()->flash('success', 'Obat: ' .$request->kode_brng. ' Berhasil Diupdate');
        return redirect()->back();
    }

    public function biayarajal(Request $request){

        if($request->tanggal != null){
            $date = date("Y-m-d", strtotime(substr($request->tanggal, 0, 10))); // date('Y-m-d'); //
            $enddate = date("Y-m-d", strtotime(substr($request->tanggal, -10)));
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }else{
            $start = new Carbon('first day of this month');
            $end = new Carbon('last day of this month');
            
            $date = $start->format("Y-m-d");
            $enddate = $end->format("Y-m-d");
            $tanggalawal = $start->formatLocalized('%d %B %Y');
            $tanggalakhir = $end->formatLocalized('%d %B %Y');
        }
        
        $detailpasien = DB::table('reg_periksa as c')
            ->select('a.besar_bayar','b.nm_pasien', 'b.no_rkm_medis', 'b.umur', 'c.kd_pj', 'c.no_rawat', 'poli.nm_poli', 'p.totalpiutang',
                DB::raw('(CASE WHEN c.kd_pj = "A02" THEN "UMUM" ELSE "BPJS" END) AS pj')
            )
            ->leftjoin('poliklinik as poli', 'poli.kd_poli','=', 'c.kd_poli')
            ->leftjoin('detail_nota_jalan as a', 'a.no_rawat','=', 'c.no_rawat')
            ->leftjoin('pasien as b', 'b.no_rkm_medis','=', 'c.no_rkm_medis')
            ->leftjoin('detail_piutang_pasien as p', 'p.no_rawat','=', 'c.no_rawat')
            ->whereBetween('c.tgl_registrasi', [$date, $enddate])
            ->where('c.status_lanjut', 'Ralan')
            ->get();

        $detailpasienranap = DB::table('reg_periksa as c')
            ->select('d.besar_bayar','b.nm_pasien', 'b.no_rkm_medis', 'b.umur', 'c.kd_pj', 'c.no_rawat', 'p.totalpiutang', 'k.kelas', 'bsl.nm_bangsal', 'ki.diagnosa_awal', 'ki.diagnosa_akhir',
                DB::raw('(CASE WHEN c.kd_pj = "A02" THEN "UMUM" ELSE "BPJS" END) AS pj')
            )
            ->leftjoin('detail_nota_inap as d', 'd.no_rawat','=', 'c.no_rawat')
            ->leftjoin('pasien as b', 'b.no_rkm_medis','=', 'c.no_rkm_medis')
            ->leftjoin('detail_piutang_pasien as p', 'p.no_rawat','=', 'c.no_rawat')
            ->leftjoin('kamar_inap as ki', 'ki.no_rawat', '=', 'c.no_rawat')
            ->leftjoin('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
            ->leftjoin('bangsal as bsl', 'bsl.kd_bangsal', '=', 'k.kd_bangsal')
            ->whereBetween('ki.tgl_keluar', [$date, $enddate])
            ->where('c.status_lanjut', 'Ranap')
            ->get();

        // dd($detailpasienranap);
        return view('admin/biayarajal', [
            'detailpasien' => $detailpasien,
            'detailpasienranap' => $detailpasienranap,
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
        ]);
    }

    function ambil_icd(Request $request){
        // dd($request->search);
        $search = $request->search;
        // ini hanya mencari kata nama, sekarang kita tambahkan pakai pekerjaan oke
        $sql = DB::table('penyakit as p')->select('p.kd_penyakit', 'p.nm_penyakit')
            ->where('nm_penyakit', 'like', '%'.$search.'%')
            ->orwhere('kd_penyakit', 'like', '%'.$search.'%')
            ->orderby('kd_penyakit', 'ASC')->limit(10)->get();
        
        if ($sql->isNotEmpty()) {
            $list = array();
            foreach ($sql as $key => $value) {
                $gabungan = $value->kd_penyakit." - ".$value->nm_penyakit;
                $list[$key]['id'] = $value->kd_penyakit;
                $list[$key]['text'] = $gabungan;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    function cekstokobat(Request $request){
        $stokobat = DB::table('databarang as db')->select( 'db.nama_brng', 'gb.stok')
        ->leftjoin('gudangbarang as gb', 'gb.kode_brng','=', 'db.kode_brng')
        ->where('db.kode_brng', $request->id)
        ->where('db.status', '=', '1')
        ->where('gb.stok', '>', '0')
        ->first();

        $msg ="";
        if($stokobat != null){
            if($stokobat->stok < $request->jumlah ){
                $msg = 'Stok ' .$stokobat->nama_brng. ' Sisa ' .$stokobat->stok;
            }
        }
        return response()->json(array('msg'=> $msg, 'idinput'=>$request->idinput, 'stok'=>$stokobat->stok), 200);
    }

    public function tutupbilling(Request $request){
        // dd($request->all());
        if($request->nik != null && $request->detail != null){
            DB::table('user as u')->where('u.id_user',DB::raw("AES_ENCRYPT($request->nik, 'nur')") )->update(array('hapus_nota_salah'=>$request->detail ));
        }

        $user = DB::table('user as u')->select("p.nama", "u.hapus_nota_salah", \DB::raw("AES_DECRYPT(u.id_user, 'nur') as id_user") )
            ->join('pegawai as p', function ($join) {
                    $join->on('p.nik', '=', DB::raw("AES_DECRYPT(u.id_user, 'nur')"));
                })
            ->orderby('u.hapus_nota_salah', 'asc')
            ->get();
        // dd($user);

        return view('pegawai/tutupbilling', ['user' => $user]);
    }

    public function hargaobat(){
        $dasar = $beli = $jual = 0;
        $hargaobat = DB::table('databarang as db')->select('db.kode_brng', 'db.nama_brng', 'db.h_beli', 'db.dasar', 'db.ralan', 'db.letak_barang','jenis.nama', 'db.kode_sat', 'gudangbarang.stok', 
            DB::raw('db.h_beli * gudangbarang.stok as ttl_beli'), DB::raw('db.ralan * gudangbarang.stok as ttl_jual'),  DB::raw('db.dasar * gudangbarang.stok as ttl_dasar'))
            ->join('jenis', 'jenis.kdjns','=', 'db.kdjns')
            ->join('gudangbarang', 'gudangbarang.kode_brng','=', 'db.kode_brng')
            ->where('db.status', '=', '1')->where('gudangbarang.kd_bangsal', '=', 'AP')
            ->groupby('gudangbarang.kode_brng')->orderby('db.kode_brng')->get();

        return view('pegawai/hargaobat', [
            'hargaobat' => $hargaobat,
            'dasar' => $dasar,
            'jual' => $jual,
            'beli' => $beli
        ]);
    }

    public function tampilguru(){
        $guru = Guru::orderBy('nip')->get();
        return view('admin/guru', ['guru' => $guru]);
    }

    public function profile(){
        $pegawai = Auth::guard('pegawai')->user();
        return view('admin/profile_detail', ['pegawai' => $pegawai]);
    }

    public function gantipassword(Request $request){
        $validate = $request->validate([
            'password_lama' => ['required', 'min:3', 'max:20', new MatchOldPasswordPegawai],
            'password_baru' => ['required' , 'min:3', 'max:20'],
            'ulangi_password_baru' => ['required', 'min:3', 'same:password_baru', 'max:20'],
        ]);

        // proses ubah password
        $data = Pegawai::find(auth()->guard('pegawai')->user()->id);
        $data->password = bcrypt($request->password_baru);
        $data->save();

        $request->session()->flash('success', 'Password Berhasil Diganti');
        return redirect()->back();
    }

    public function create(Request $request)
    {
        $validate = $request->validate([
            'nip' => ['required', 'unique:guru,nip', 'max:22'],
            'nama_guru' => ['required', 'max:50'],
            'jenis_kelamin' => ['required'],
            'alamat' => ['max:100'],
            'no_telp' => ['max:13'],
            'level' => ['required']
        ]);

        $data = new Guru;

        $data->nip = $request->nip;
        $data->nama_guru = $request->nama_guru;
        $data->jenis_kelamin = $request->jenis_kelamin;
        $data->alamat = $request->alamat;
        $data->no_telp = $request->no_telp;
        $data->level = $request->level;
        $data->username = $request->nip;
        $data->password = bcrypt($request->nip);
        
        $data->save();
        $request->session()->flash('success', 'Berhasil Menambahkan Guru Baru: '.$request->nama_guru);
        return redirect()->route('homeadmin/guru');
    }

    public function edit($id)
    {
        $guru = Guru::find($id);

        return view('admin/guru_edit', ['guru'=>$guru]);
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'nip' => ['required', 'unique:guru,nip,'.$id.',id_guru', 'max:22'],
            'nama_guru' => ['required', 'max:50'],
            'jenis_kelamin' => ['required'],
            'username' => ['required', 'max:50'],
            'alamat' => ['max:100'],
            'no_telp' => ['max:13'],
            'level' => ['required']
        ]);

        $data = Guru::find($id);

        $data->nip = $request->nip;
        $data->nama_guru = $request->nama_guru;
        $data->jenis_kelamin = $request->jenis_kelamin;
        $data->alamat = $request->alamat;
        $data->no_telp = $request->no_telp;
        $data->level = $request->level;
        $data->username = $request->username;

        $data->save();
        $request->session()->flash('success', 'Guru: ' .$data->nama_guru. ' Berhasil Diedit');
        return redirect()->route('homeadmin/guru');
    }

    public function reset(Request $request, $id)
    {
        $data = Guru::find($id);
        $data->password = bcrypt($data->nip);
        
        $data->save();
        $request->session()->flash('success', 'Berhasil Reset Password untuk Guru ' .$data->nama_guru);
        return redirect()->back();
    }

    public function delete(Request $request, $id)
    {
        $data = Guru::find($id);
        // cek apakah ada relasi ke tabel lain
        try{
            $data->delete();
            $request->session()->flash('warning', 'Guru: ' .$data->nama_guru. ' Berhasil Dihapus');
            return redirect()->back();
        } catch (\Exception $e){
            $request->session()->flash('error', 'Guru: ' .$data->nama_guru. ' Tidak Dapat Dihapus');
            return redirect()->back();
        }
    }
}
