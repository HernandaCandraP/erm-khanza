<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Siswa;

class SiswaController extends Controller
{
    public function index()
    {
        return view('siswa/home');
    }

    public function tampilsiswa(){
        $no = 1;
        $siswa = Siswa::all();
        return view('admin/siswa', ['siswa' => $siswa, 'no' => $no]);
    }

    public function create(Request $request)
    {
        $data = new Siswa;

        $data->nis = $request->nis;
        $data->nama_siswa = $request->nama_siswa;
        $data->id_kelas = $request->id_kelas;
        $data->jenis_kelamin = $request->jenis_kelamin;
        $data->alamat = $request->alamat;
        $data->no_telp = $request->no_telp;
        $data->level = 'Siswa';
        $data->username = $request->username;
        $data->password = bcrypt($request->password);
        
        $data->save();
        return redirect()->route('homeadmin/siswa');
    }

    public function edit($id)
    {
        $siswa = Siswa::find($id);

        return view('admin/siswa_edit', ['siswa'=>$siswa]);
    }

    public function update(Request $request, $id)
    {
        $data = Siswa::find($id);

        $data->nis = $request->nis;
        $data->nama_siswa = $request->nama_siswa;
        $data->id_kelas = $request->id_kelas;
        $data->jenis_kelamin = $request->jenis_kelamin;
        $data->alamat = $request->alamat;
        $data->no_telp = $request->no_telp;
        $data->level = $request->level;
        $data->username = $request->username;
        // $data->password = bcrypt($request->password);

        $data->save();
        return redirect()->route('homeadmin/siswa');
    }

    public function delete($id)
    {
        $data = Siswa::find($id);
        $data->delete();
        return redirect()->route('homeadmin/siswa');
    }
}
