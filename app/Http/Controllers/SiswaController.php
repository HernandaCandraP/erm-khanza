<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Siswa;
use App\Kelas;
use App\DetailSiswa;
use App\Jawaban;
use Auth;
use App\Rules\MatchOldPasswordSiswa;

class SiswaController extends Controller
{
    public function index(){
        $id_siswa = Auth::guard('siswa')->user()->id_siswa;
        $ujian_diselesaikan = DetailSiswa::where('id_siswa', $id_siswa)->where('is_end', 1)->count();
        $ujian_belum_dikerjakan = DetailSiswa::where('id_siswa', $id_siswa)->where('is_end', 0)->count();
        
        $nilai_cosine = Jawaban::where('id_siswa', $id_siswa)->select('nilai_cosine')->average('nilai_cosine');
        $nilai_jaccard = Jawaban::where('id_siswa', $id_siswa)->select('nilai_jaccard')->average('nilai_jaccard');
        $nilai_guru = Jawaban::where('id_siswa', $id_siswa)->select('nilai_guru')->average('nilai_guru');

        return view('siswa/home', [
            'ujian_diselesaikan' => $ujian_diselesaikan,
            'ujian_belum_dikerjakan' => $ujian_belum_dikerjakan,
            'nilai_cosine' => $nilai_cosine,
            'nilai_jaccard' => $nilai_jaccard,
            'nilai_guru' => $nilai_guru
        ]);
    }

    public function tampilsiswa(){
        $kelas = Kelas::all();
        $siswa = DB::table('siswa')->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')->select('siswa.*', 'kelas.nama_kelas')->get();
        return view('admin/siswa', [
            'siswa' => $siswa, 
            'kelas' => $kelas,
        ]);
    }

    public function profile(){
        $siswa = Auth::guard('siswa')->user();
        return view('siswa/profile_detail', ['siswa' => $siswa]);
    }

    public function gantipassword(Request $request){
        $validate = $request->validate([
            'password_lama' => ['required', 'min:5', 'max:20', new MatchOldPasswordSiswa],
            'password_baru' => ['required' , 'min:5', 'max:20'],
            'ulangi_password_baru' => ['required', 'min:5', 'same:password_baru', 'max:20'],
        ]);

        // proses ubah password
        $data = Siswa::find(auth()->guard('siswa')->user()->id_siswa);
        $data->password = bcrypt($request->password_baru);
        $data->save();

        $request->session()->flash('success', 'Password Berhasil Diganti');
        return redirect()->back();
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
