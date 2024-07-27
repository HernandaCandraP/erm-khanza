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
        $month = date('Y-m'); //'2023-06'; //
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

    public function admin(){
        $username = Session::get('username');
        $month = '2023-06'; // date('Y-m'); //
        $date = '2023-06-15'; // date('Y-m-d'); //

        $totalpasien = DB::table('pasien')->select('no_rkm_medis')->count();
        $pasienbulan = DB::table('pasien')->select('no_rkm_medis')->where('tgl_daftar', 'like', '%'.$month.'%')->count();
        
        $kdpoli = Session::get('getpoli');
        $polibulan = DB::table('reg_periksa')->select('no_rawat')->where('kd_poli', $kdpoli)->where('stts', "!=", 'Belum')->where('tgl_registrasi', 'like', '%'.$month.'%')->count();

        $polihari = DB::table('reg_periksa')->select('no_rawat')->where('kd_poli', $kdpoli)->where('tgl_registrasi', 'like', '%'.$date.'%')->count();

        // $pasienaktif = DB::table('reg_periksa as rp')->select('p.no_rkm_medis', DB::raw('count(*) as jumlah'), 'p.nm_pasien')->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')->where('kd_dokter', $username)->groupby('no_rkm_medis')->orderby('jumlah', 'desc')->take(10)->get();

        $pasienterakhir = DB::table('reg_periksa as a')->select('a.no_rawat', 'b.no_rkm_medis', 'b.nm_pasien', 'a.stts')
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')->where('a.tgl_registrasi', $date)->where('a.status_lanjut', 'Ralan')
            ->orderby('a.jam_reg', 'desc')->get();

        // dd($kdpoli);
        $pasienranap = DB::table('kamar_inap as ki')->where('ki.stts_pulang', '-')->select('ki.*', 'p.no_rkm_medis', 'p.nm_pasien', 'b.nm_bangsal')
            ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'ki.no_rawat')
            ->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->leftjoin('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
            ->leftjoin('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
            ->leftjoin('dpjp_ranap as dr', 'dr.no_rawat', '=', 'ki.no_rawat')
            ->get();

        return view('dokter/home', [
            'totalpasien' => $totalpasien, 
            'pasienbulan' => $pasienbulan, 
            'polibulan' => $polibulan, 
            'polihari' => $polihari, 
            'pasienranap' => $pasienranap, 
            // 'pasienaktif' => $pasienaktif, 
            'pasienterakhir' => $pasienterakhir, 
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
