<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Siswa;
use App\Kelas;

class SiswaController extends Controller
{
    public function index()
    {
        return view('siswa/home');
    }

    public function tampilsiswa(){
        $kelas = Kelas::all();
        $siswa = DB::table('siswa')->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')->select('siswa.*', 'kelas.nama_kelas')->get();
        return view('admin/siswa', [
            'siswa' => $siswa, 
            'kelas' => $kelas,
        ]);
    }

    public function create(Request $request)
    {
        $validate = $request->validate([
            'nis' => ['required', 'unique:siswa,nis', 'max:22'],
            'nama_siswa' => ['required', 'max:50'],
            'id_kelas' => ['required'],
            'jenis_kelamin' => ['required'],
            'alamat' => ['max:100'],
            'no_telp' => ['max:13'],
        ]);

        $data = new Siswa;

        $data->nis = $request->nis;
        $data->nama_siswa = $request->nama_siswa;
        $data->id_kelas = $request->id_kelas;
        $data->jenis_kelamin = $request->jenis_kelamin;
        $data->alamat = $request->alamat;
        $data->no_telp = $request->no_telp;
        $data->level = 'Siswa';
        $data->username = $request->nis;
        $data->password = bcrypt($request->nis);
        
        $data->save();
        $request->session()->flash('success', 'Berhasil Menambahkan Siswa Baru: '.$request->nama_siswa);
        return redirect()->route('homeadmin/siswa');
    }

    public function edit($id)
    {
        $siswa = Siswa::find($id);
        $kelas = Kelas::all();

        return view('admin/siswa_edit', ['siswa'=>$siswa, 'kelas'=>$kelas]);
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'nis' => ['required', 'unique:siswa,nis,'.$id.',id_siswa', 'max:22'],
            'nama_siswa' => ['required', 'max:50'],
            'id_kelas' => ['required'],
            'jenis_kelamin' => ['required'],
            'username' => ['required', 'max:50'],
            'alamat' => ['max:100'],
            'no_telp' => ['max:13'],
        ]);

        $data = Siswa::find($id);

        $data->nis = $request->nis;
        $data->nama_siswa = $request->nama_siswa;
        $data->id_kelas = $request->id_kelas;
        $data->jenis_kelamin = $request->jenis_kelamin;
        $data->alamat = $request->alamat;
        $data->no_telp = $request->no_telp;
        $data->level = 'Siswa';
        $data->username = $request->username;

        $data->save();
        $request->session()->flash('success', 'Siswa: ' .$data->nama_siswa. ' Berhasil Diedit');
        return redirect()->route('homeadmin/siswa');
    }

    public function reset(Request $request, $id)
    {
        $data = Siswa::find($id);
        $data->password = bcrypt($data->nis);
        
        $data->save();
        $request->session()->flash('success', 'Berhasil Reset Password untuk Siswa ' .$data->nama_siswa);
        return redirect()->back();
    }

    public function delete(Request $request, $id)
    {
        $data = Siswa::find($id);
        // cek apakah ada relasi ke tabel lain
        try{
            $data->delete();
            $request->session()->flash('warning', 'Siswa: ' .$data->nama_siswa. ' Berhasil Dihapus');
            return redirect()->back();
        } catch (\Exception $e){
            $request->session()->flash('error', 'Siswa: ' .$data->nama_siswa. ' Tidak Dapat Dihapus');
            return redirect()->back();
        }
    }
}
