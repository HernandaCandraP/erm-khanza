<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Kelas;

class KelasController extends Controller
{
    public function tampilkelas(){
        $no = 1;
        $kelas = Kelas::all();
        return view('admin/kelas', ['kelas' => $kelas, 'no' => $no]);
    }

    public function create(Request $request)
    {
        $data = new Kelas;

        $data->nama_kelas = $request->nama_kelas;
        $data->keterangan = $request->keterangan;
        
        $data->save();
        return redirect()->route('homeadmin/kelas');
    }

    public function edit($id)
    {
        $kelas = Kelas::find($id);

        return view('admin/kelas_edit', ['kelas'=>$kelas]);
    }

    public function update(Request $request, $id)
    {
        $data = Kelas::find($id);

        $data->nama_kelas = $request->nama_kelas;
        $data->keterangan = $request->keterangan;

        $data->save();
        return redirect()->route('homeadmin/kelas');
    }


    public function delete($id)
    {
        $data = Kelas::find($id);
        $data->delete();
        return redirect()->route('homeadmin/kelas');
    }
}
