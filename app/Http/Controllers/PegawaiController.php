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
        $month = date('Y-m');
        $date = date('Y-m-d');
        $totalpasien = DB::table('pasien')->select('no_rkm_medis')->count();
        $pasienbulan = DB::table('pasien')->select('no_rkm_medis')->where('tgl_daftar', 'like', '%'.$month.'%')->count();
        
        $kdpoli = Session::get('getpoli');
        $polibulan = DB::table('reg_periksa')->select('no_rawat')->where('kd_poli', $kdpoli)->where('stts', "!=", 'Belum')->where('tgl_registrasi', 'like', '%'.$month.'%')->count();

        $polihari = DB::table('reg_periksa')->select('no_rawat')->where('kd_poli', $kdpoli)->where('tgl_registrasi', 'like', '%'.$date.'%')->count();

        return view('dokter/home', [
            'totalpasien' => $totalpasien, 
            'pasienbulan' => $pasienbulan, 
            'polibulan' => $polibulan, 
            'polihari' => $polihari, 
        ]);
    }

    public function admin(){
        $pegawai = "admin";
        // $totalguru = Guru::count();
        // $totalsiswa = Siswa::count();
        // $totalkelas = Kelas::count();
        // $totalmapel = Mapel::count();

        // $pengujian = new Pengujian();
        // $pengujian_cosine = $pengujian->pengujian_cosine($data_cosine);
        // $pengujian_jaccard = $pengujian->pengujian_jaccard($data_jaccard);

        return view('admin/home', [
            // 'detailmapel' => $detailmapel, 
            // 'totalkelas' => $totalkelas, 
            // 'totalsiswa' => $totalsiswa, 
            // 'totalmapel' => $totalmapel,
            // 'pengujian_cosine' => $pengujian_cosine,
            // 'pengujian_jaccard' => $pengujian_jaccard,
            'pegawai' => $pegawai, 
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
