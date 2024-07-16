<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use DB;

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

        return view('admin/kelolalogin', [
            'datalogin' => $datalogin, 
        ]);
    }

    public function resetpassword(Request $request)
    {
        $datalogin = DB::table('login_rsba')->orderby("nik", "ASC")->get();

        dd($request->nik);
        return view('admin/kelolalogin', [
            'datalogin' => $datalogin, 
        ]);
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
                Session::put('kd_sps', $getspecialis->kd_sps);
                Session::put('nm_sps', $getspecialis->nm_sps);

                return redirect('dokter');
            }else if(Auth::guard('pegawai')->user()->level == "Admin"){
                return redirect('admin');
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
