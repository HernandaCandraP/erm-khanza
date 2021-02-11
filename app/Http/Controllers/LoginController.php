<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function postlogin(Request $request){
        // dd($request->all());
        // if(Auth::attempt($request->only('username', 'password'))){
        //     return redirect('siswa');
        // }
        if(Auth::guard('siswa')->attempt(['username' => $request->username, 'password' => $request->password])){
            return redirect('siswa');
        }
        
        if(Auth::guard('guru')->attempt(['username' => $request->username, 'password' => $request->password])){
            if(Auth::guard('guru')->user()->level == "Guru"){
                return redirect('guru');
            }else if(Auth::guard('guru')->user()->level == "Admin"){
                return redirect('admin');
            }
        }
        return redirect('login');
    }

    public function logout(Request $request){
        // Auth::logout();
        if(Auth::guard('siswa')->check()){
            Auth::guard('siswa')->logout();
        }

        if (Auth::guard('guru')->check()) {
            Auth::guard('guru')->logout();
        }
        return redirect('/');
    }
}
