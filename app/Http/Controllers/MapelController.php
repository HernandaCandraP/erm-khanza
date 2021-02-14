<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mapel;

class MapelController extends Controller
{
    public function tampilmapel(){
        $no = 1;
        $mapel = Mapel::all();
        return view('admin/mapel', ['mapel' => $mapel, 'no' => $no]);
    }

    public function create(Request $request)
    {
        $data = new Mapel;

        $data->nama_mapel = $request->nama_mapel;
        $data->keterangan = $request->keterangan;
        
        $data->save();
        return redirect()->route('homeadmin/mapel');
    }

    public function edit($id)
    {
        $mapel = Mapel::find($id);

        return view('admin/mapel_edit', ['mapel'=>$mapel]);
    }

    public function update(Request $request, $id)
    {
        $data = Mapel::find($id);

        $data->nama_mapel = $request->nama_mapel;
        $data->keterangan = $request->keterangan;

        $data->save();
        return redirect()->route('homeadmin/mapel');
    }


    public function delete($id)
    {
        $data = Mapel::find($id);
        $data->delete();
        return redirect()->route('homeadmin/mapel');
    }
}
