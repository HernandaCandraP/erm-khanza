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

    public function postlogin(Request $request){
        $validate = $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        if(Auth::guard('pegawai')->attempt(['username' => $request->username, 'password' => $request->password])){
            // session nik
            $nik = Auth::guard('pegawai')->user()->nik;
            Session::put('nik', $nik);

            if(Auth::guard('pegawai')->user()->level == "Petugas"){
                return redirect('pegawai');
            }else if(Auth::guard('pegawai')->user()->level == "Dokter"){
                // session poli
                $getpoli = DB::table('jadwal')->select('kd_poli')->where('kd_dokter', $nik)->first();
                Session::put('getpoli', $getpoli->kd_poli);
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
