<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Pegawai;
use App\Rules\MatchOldPasswordPegawai;
use Session;

use Auth;

class PegawaiController extends Controller
{
    public function index(){
        $pegawai = "hello";

        return view('pegawai/home', [
            // 'detailmapel' => $detailmapel, 
            // 'totalkelas' => $totalkelas, 
            // 'totalsiswa' => $totalsiswa, 
            // 'totalmapel' => $totalmapel,
            // 'pengujian_cosine' => $pengujian_cosine,
            // 'pengujian_jaccard' => $pengujian_jaccard,
            'pegawai' => $pegawai, 
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
            ->where('a.kd_dokter', $username)->where('a.tgl_registrasi', $date)->where('a.status_lanjut', 'Ralan')
            ->orderby('a.jam_reg', 'desc')->get();
            
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
            $date = date("Y-m-d", strtotime("-30 day")); // date('Y-m-d'); //
            $enddate = date("Y-m-d");
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
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
            ->where(function ($query) use ($username) {
                $query->where('pr.nip', $username)
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
            // ->leftjoin('pemeriksaan_ralan as pr', 'a.no_rawat','=', 'pr.no_rawat')
            // ->leftjoin('penilaian_medis_ralan_bedah as prb', 'a.no_rawat','=', 'prb.no_rawat')
            // ->leftjoin('penilaian_medis_ralan_paru as prp', 'a.no_rawat','=', 'prp.no_rawat')
            // ->leftjoin('penilaian_medis_ralan_kandungan as prk', 'a.no_rawat','=', 'prk.no_rawat')
            // ->leftjoin('penilaian_medis_ralan_neurologi as prn', 'a.no_rawat','=', 'prn.no_rawat')
            // ->leftjoin('penilaian_medis_ralan_anak as pra', 'a.no_rawat','=', 'pra.no_rawat')
            // ->leftjoin('penilaian_medis_ralan_mata as prm', 'a.no_rawat','=', 'prm.no_rawat')
            // ->leftjoin('penilaian_medis_ralan_penyakit_dalam as prd', 'a.no_rawat','=', 'prd.no_rawat')
            // ->leftjoin('penilaian_medis_ralan as pru', 'a.no_rawat','=', 'pru.no_rawat')
            // ->leftjoin('penilaian_medis_igd as pmi', 'a.no_rawat','=', 'pmi.no_rawat')
            ->where('a.kd_dokter', $username)
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

        // $totalpasien = DB::table('pasien')->select('no_rkm_medis')->count();
        // $pasienbulan = DB::table('pasien')->select('no_rkm_medis')->where('tgl_daftar', 'like', '%'.$month.'%')->count();
        
        // $kdpoli = Session::get('getpoli');
        // $polibulan = DB::table('reg_periksa')->select('no_rawat')->where('kd_poli', $kdpoli)->where('stts', "!=", 'Belum')->where('tgl_registrasi', 'like', '%'.$month.'%')->count();

        // $polihari = DB::table('reg_periksa')->select('no_rawat')->where('kd_poli', $kdpoli)->where('tgl_registrasi', 'like', '%'.$date.'%')->count();

        // $pasienaktif = DB::table('reg_periksa as rp')->select('p.no_rkm_medis', DB::raw('count(*) as jumlah'), 'p.nm_pasien')->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')->where('kd_dokter', $username)->groupby('no_rkm_medis')->orderby('jumlah', 'desc')->take(10)->get();

        return view('admin/home', [
            // 'totalpasien' => $totalpasien, 
            // 'pasienbulan' => $pasienbulan, 
            // 'polibulan' => $polibulan, 
            // 'polihari' => $polihari, 

            'listdokter' => $listdokter, 
            'totalpolibulanperdokter' => $totalpolibulanperdokter, 
            'totalpolibulanperdokterinput' => $totalpolibulanperdokterinput, 
            'persentasepolibulanperdokter' => $persentasepolibulanperdokter, 
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
            $date = date("Y-m-d", strtotime("-30 day")); // date('Y-m-d'); //
            $enddate = date("Y-m-d");
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }

        $polibulanperdokterinput = [];
        $listsemuaperiksa = DB::table('reg_periksa as rp')->select('rp.no_rawat', 'rp.no_rkm_medis', 'rp.kd_poli', 'p.nm_poli',DB::raw('count(*) as jumlah'))
            ->leftjoin('poliklinik as p', 'p.kd_poli','=', 'rp.kd_poli')
            ->whereBetween('rp.tgl_registrasi', [$date, $enddate])
            ->groupBy('p.kd_poli')
            ->get();
        
        $listsudahperiksa = DB::table('reg_periksa as rp')->select('rp.no_rawat', 'rp.no_rkm_medis', 'rp.kd_poli', 'p.nm_poli', DB::raw('count(*) as jumlah'))
            ->leftjoin('poliklinik as p', 'p.kd_poli','=', 'rp.kd_poli')
            ->rightjoin('pemeriksaan_ralan as pr', 'rp.no_rawat','=', 'pr.no_rawat')
            ->whereBetween('rp.tgl_registrasi', [$date, $enddate])
            ->whereBetween('pr.tgl_perawatan', [$date, $enddate])
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

        return view('admin/persentasepoli', [
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
            $date = date("Y-m-d", strtotime("-30 day")); // date('Y-m-d'); //
            $enddate = date("Y-m-d");
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }

        // $date = '2023-06-15'; // date('Y-m-d'); //
        // $enddate = '2024-06-15'; // date('Y-m-d'); //
        $hargatotal = 0;

        $riwayatradiologi = DB::table('periksa_radiologi as pr')->select('pr.*', 'jpr.nm_perawatan')
            ->leftjoin('jns_perawatan_radiologi as jpr', 'jpr.kd_jenis_prw', '=', 'pr.kd_jenis_prw')
            ->whereBetween('pr.tgl_periksa', [$date, $enddate])
            ->orderBy('pr.no_rawat', 'DESC')
            ->get();
        
        $hasilrad = DB::table('hasil_radiologi')
            ->whereBetween('tgl_periksa', [$date, $enddate])
            ->orderBy('tgl_periksa', 'DESC')
            ->get();

        $totalbacaan= 0;
        foreach($hasilrad as $key => $value) {
            $string = preg_replace('/\s+/', '', $value->hasil);
            $string2 = substr_count($string, 'TS,Yth,');
            $totalbacaan += $string2;
        }

        foreach($riwayatradiologi as $key => $value) {
            $hargatotal += $value->biaya;
        }
        
        // dd($riwayatradiologi);

        return view('admin/riwayatradiologi', [
            'riwayatradiologi' => $riwayatradiologi, 
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
            'hargatotal' => $hargatotal, 
            'totalbacaan' => $totalbacaan, 
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
