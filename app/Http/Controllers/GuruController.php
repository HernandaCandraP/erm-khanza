<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Guru;

class GuruController extends Controller
{
    public function index(){
        // $guru = Guru::all();
        // return view('guru', ['guru' => $guru]);
        return view('guru/home');
    }

    public function admin(){
        return view('admin/home');
    }
}
