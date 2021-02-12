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

    public function tampilguru(){
        $no = 1;
        $guru = Guru::all();
        return view('admin/guru', ['guru' => $guru, 'no' => $no]);
    }

    public function create(Request $request)
    {
        $data = new Guru;

        $data->nip = $request->nip;
        $data->nama_guru = $request->nama_guru;
        $data->jenis_kelamin = $request->jenis_kelamin;
        $data->alamat = $request->alamat;
        $data->no_telp = $request->no_telp;
        $data->level = $request->level;
        $data->username = $request->username;
        $data->password = bcrypt($request->password);
        
        $data->save();
        return redirect()->route('homeadmin/guru');
    }

    public function edit($id)
    {
        $guru = Guru::find($id);

        return view('admin/guru_edit', ['guru'=>$guru]);
    }

    public function update(Request $request, $id)
    {
        $data = Guru::find($id);

        $data->nip = $request->nip;
        $data->nama_guru = $request->nama_guru;
        $data->jenis_kelamin = $request->jenis_kelamin;
        $data->alamat = $request->alamat;
        $data->no_telp = $request->no_telp;
        $data->level = $request->level;
        $data->username = $request->username;
        // $data->password = bcrypt($request->password);

        $data->save();
        return redirect()->route('homeadmin/guru');
    }


    public function delete($id)
    {
        $data = Guru::find($id);
        $data->delete();
        return redirect()->route('homeadmin/guru');
    }
}
