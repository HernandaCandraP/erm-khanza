<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Guru;
use App\Siswa;
use App\Kelas;
use App\Mapel;
use App\DetailMapel;
use App\Rules\MatchOldPasswordGuru;

use Auth;

class GuruController extends Controller
{
    public function index(){
        $detailmapel = DetailMapel::count();
        $totalsiswa = Siswa::count();
        $totalkelas = Kelas::count();
        $totalmapel = Mapel::count();
        return view('guru/home', [
            'detailmapel' => $detailmapel, 
            'totalkelas' => $totalkelas, 
            'totalsiswa' => $totalsiswa, 
            'totalmapel' => $totalmapel]);
    }

    public function admin(){
        $totalguru = Guru::count();
        $totalsiswa = Siswa::count();
        $totalkelas = Kelas::count();
        $totalmapel = Mapel::count();
        return view('admin/home', [
            'totalguru' => $totalguru, 
            'totalkelas' => $totalkelas, 
            'totalsiswa' => $totalsiswa, 
            'totalmapel' => $totalmapel]);
    }

    public function tampilguru(){
        $guru = Guru::all();
        return view('admin/guru', ['guru' => $guru]);
    }

    public function profile(){
        $guru = Auth::guard('guru')->user();
        return view('admin/profile_detail', ['guru' => $guru]);
    }

    public function gantipassword(Request $request){
        $validate = $request->validate([
            'password_lama' => ['required', 'min:5', 'max:20', new MatchOldPasswordGuru],
            'password_baru' => ['required' , 'min:5', 'max:20'],
            'ulangi_password_baru' => ['required', 'min:5', 'same:password_baru', 'max:20'],
        ]);

        // proses ubah password
        $data = Guru::find(auth()->guard('guru')->user()->id_guru);
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
