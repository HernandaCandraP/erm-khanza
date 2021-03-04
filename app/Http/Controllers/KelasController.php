<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Kelas;
use App\Siswa;

class KelasController extends Controller
{
    public function tampilkelas(){
        $no = 1;
        $kelas = Kelas::all();
        return view('admin/kelas', ['kelas' => $kelas, 'no' => $no]);
    }

    public function detail($id)
    {
        $no = 1;
        $kelas = Kelas::find($id);
        $siswa = Siswa::where('id_kelas', $id)->get();
        return view('admin/kelas_detail', ['kelas'=>$kelas, 'siswa'=>$siswa, 'no'=>$no]);
    }

    public function create(Request $request)
    {
        $validate = $request->validate([
            'nama_kelas' => ['required', 'unique:kelas,nama_kelas', 'max:10'],
            'keterangan' => ['max:50']
        ]);

        $data = new Kelas;

        $data->nama_kelas = $request->nama_kelas;
        $data->keterangan = $request->keterangan;
        
        $data->save();
        $request->session()->flash('success', 'Berhasil Menambahkan Kelas '.$request->nama_kelas);
        return redirect()->back();
    }

    public function edit($id)
    {
        $kelas = Kelas::find($id);

        return view('admin/kelas_edit', ['kelas'=>$kelas]);
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'nama_kelas' => ['required', 'unique:kelas,nama_kelas,'.$id.',id_kelas', 'max:10'],
            'keterangan' => ['max:50']
        ]);

        $data = Kelas::find($id);

        $data->nama_kelas = $request->nama_kelas;
        $data->keterangan = $request->keterangan;

        $data->save();
        $request->session()->flash('success', 'Kelas ' .$data->nama_kelas. ' Berhasil Diedit');
        return redirect()->route('homeadmin/kelas');
    }


    public function delete(Request $request, $id)
    {
        $data = Kelas::find($id);

        // cek apakah ada relasi ke tabel lain
        if($data->getsiswa()->exists()){
            $request->session()->flash('error', 'Kelas ' .$data->nama_kelas. ' Tidak Dapat Dihapus');
            return redirect()->back();
        }

        $data->delete();
        $request->session()->flash('warning', 'Kelas ' .$data->nama_kelas. ' Berhasil Dihapus');
        return redirect()->route('homeadmin/kelas');
    }
}
