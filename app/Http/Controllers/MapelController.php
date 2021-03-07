<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mapel;

class MapelController extends Controller
{
    public function tampilmapel(){
        $mapel = Mapel::all();
        return view('admin/mapel', ['mapel' => $mapel]);
    }

    public function create(Request $request)
    {
        $validate = $request->validate([
            'nama_mapel' => ['required', 'unique:mapel,nama_mapel', 'max:50'],
            'keterangan' => ['max:50']
        ]);
        $data = new Mapel;

        $data->nama_mapel = $request->nama_mapel;
        $data->keterangan = $request->keterangan;
        
        $data->save();
        $request->session()->flash('success', 'Berhasil Menambahkan Mata Pelajaran '.$request->nama_mapel);
        return redirect()->back();
    }

    public function edit($id)
    {
        $mapel = Mapel::find($id);

        return view('admin/mapel_edit', ['mapel'=>$mapel]);
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'nama_mapel' => ['required', 'unique:mapel,nama_mapel,'.$id.',id_mapel', 'max:50'],
            'keterangan' => ['max:50']
        ]);

        $data = Mapel::find($id);

        $data->nama_mapel = $request->nama_mapel;
        $data->keterangan = $request->keterangan;

        $data->save();
        $request->session()->flash('success', 'Mata Pelajaran ' .$data->nama_mapel. ' Berhasil Diedit');
        return redirect()->route('homeadmin/mapel');
    }


    public function delete(Request $request, $id)
    {
        $data = Mapel::find($id);
        try{
            $data->delete();
            $request->session()->flash('warning', 'Mata Pelajaran ' .$data->nama_mapel. ' Berhasil Dihapus');
            return redirect()->back();
        } catch (\Exception $e){
            $request->session()->flash('error', 'Mata Pelajaran ' .$data->nama_mapel. ' Tidak Dapat Dihapus');
            return redirect()->back();
        }
    }
}
