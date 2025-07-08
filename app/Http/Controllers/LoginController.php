<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use Hash;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::guard('pegawai')->check() != null){
            if(Auth::guard('pegawai')->user()->level == "Petugas"){
                return redirect('pegawai');
            }else if(Auth::guard('pegawai')->user()->level == "Dokter"){
                return redirect('dokter');
            }else if(Auth::guard('pegawai')->user()->level == "Admin"){
                return redirect('admin');
            }
        }
        return view('login');
    }

    public function kelolalogin()
    {
        $datalogin = DB::table('login_rsba')->orderby("nik", "ASC")->get();
        $pegawai = DB::table('pegawai')->orderby("nama", "ASC")
            ->where('stts_aktif', 'AKTIF')
            ->where('nama', '!=', '-')->get();

        return view('admin/kelolalogin', [
            'datalogin' => $datalogin, 
            'pegawai' => $pegawai, 
        ]);
    }

    public function tambahlogin(Request $request)
    {
        $validate = $request->validate([
            'username' => ['required', 'min:3'],
            'password' => ['required', 'min:3']
        ]);

        $pegawai = DB::table('pegawai')->where('nik', $request->nik)->first();
        $password = Hash::make($request->password);

        // simpan login
        $login_rsba = [
            'nik' => $pegawai->nik,
            'nama' => $pegawai->nama,
            'jk' => $pegawai->jk,
            'alamat' => $pegawai->alamat,
            'username' => $request->username,
            'password' => $password,
            'level' => $request->level
        ];
        DB::table('login_rsba')->updateOrInsert(['nik' => $pegawai->nik], $login_rsba);

        $request->session()->flash('success', 'Berhasil Tambah Login '.$pegawai->nama);
        return redirect('admin/kelolalogin');
    }

    public function aksilogin(Request $request)
    {
        foreach ($request->action as $id => $value) {
            // dd($id);
            if($id == 'reset'){
                $password = Hash::make($value);
                $petugas = DB::table('login_rsba')->where('nik', $value)->limit(1)->update(array('password' => $password));
        
                $request->session()->flash('warning', 'Berhasil Reset Password '. $value);
            }elseif($id == 'hapus'){
                DB::table('login_rsba')->where('nik',$value)->delete();
                $request->session()->flash('warning', 'Data Berhasil Dihapus '. $value);
            }
        }
        return redirect('admin/kelolalogin');
    }

    public function postlogin(Request $request){
        $validate = $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        if(Auth::guard('pegawai')->attempt(['username' => $request->username, 'password' => $request->password])){
            // session nik
            $username = Auth::guard('pegawai')->user()->nik;
            Session::put('username', $username);

            if(Auth::guard('pegawai')->user()->level == "Petugas"){
                return redirect('pegawai');
            }else if(Auth::guard('pegawai')->user()->level == "Dokter"){
                // session poli
                $getpoli = DB::table('jadwal as j')->select('j.kd_poli', 'p.nm_poli')->join('poliklinik as p', 'p.kd_poli', '=', 'j.kd_poli')->where('kd_dokter', $username)->first();
                if($getpoli != null){
                    Session::put('getpoli', $getpoli->kd_poli);
                    Session::put('nmpoli', ucwords(strtolower($getpoli->nm_poli)));
                }else{
                    Session::put('getpoli', '-');
                    Session::put('nmpoli', '-');
                }

                $getspecialis = DB::table('dokter as d')->select('d.kd_dokter', 'd.nm_dokter','s.nm_sps', 's.kd_sps')->join('spesialis as s', 's.kd_sps','=', 'd.kd_sps')->where('d.kd_dokter', $username)->first();
                Session::put('kd_sps', $getspecialis->kd_sps ?? null);
                Session::put('nm_sps', $getspecialis->nm_sps ?? null);

                return redirect('dokter');
            }else if(Auth::guard('pegawai')->user()->level == "Admin"){
                return redirect('admin');
            }else if(Auth::guard('pegawai')->user()->level == "Apotek"){
                return redirect('/apotek');
            }
        }
        return redirect()->back()->withErrors(['msg' =>['Username dan password tidak cocok']]);
    }

    public function logout(Request $request){
        if (Auth::guard('pegawai')->check()) {
            Auth::guard('pegawai')->logout();
        }
        Session::flush();
        return redirect('/login');
    }
}
