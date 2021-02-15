<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Soal;
use App\DetailMapel;
use Auth;

class SoalController extends Controller
{
    public function tampilsoal($id){
        $no = 1;
        $kelas = '';
        $soal = Soal::join('guru', 'soal.id_guru', '=', 'guru.id_guru')->select('soal.*', 'guru.nama_guru')->where('id_detail',$id)->get();
        $detailmapel = DetailMapel::join('mapel', 'detailmapel.id_mapel', '=', 'mapel.id_mapel')->select('detailmapel.*', 'mapel.nama_mapel')->where('id_detail', $id)->first();

        $countkelas = $detailmapel->kelas->count()-1;
        foreach($detailmapel->kelas as $key => $value){
            if($key == $countkelas-1) {
                $kelas .= $value->nama_kelas;
            }elseif($key < $countkelas){
                $kelas .= $value->nama_kelas . ', ';
            }elseif($key == $countkelas ){
                $kelas .= ' dan ' .$value->nama_kelas;
            }
        }

        return view('guru/soal', [
            'soal' => $soal, 
            'detailmapel'=> $detailmapel,
            'kelas' => $kelas,
            'no' => $no,
        ]);
    }

    public function create(Request $request, $id)
    {
        $data = new Soal;
        $id_guru = Auth::guard('guru')->user()->id_guru;

        $data->id_detail = $id;
        $data->soal = $request->soal;
        $data->jawaban = $request->jawaban;
        $data->stemming = '-';
        $data->id_guru = $id_guru;
        
        $data->save();
        return redirect()->to('guru/soal/'.$id);
    }

    public function edit($id)
    {
        $soal = Soal::find($id);

        return view('guru/soal_edit', ['soal'=>$soal]);
    }

    public function update(Request $request, $id)
    {
        $data = Soal::find($id);
        $id_guru = Auth::guard('guru')->user()->id_guru;

        $data->id_detail = $request->id_detail;
        $data->soal = $request->soal;
        $data->jawaban = $request->jawaban;
        $data->stemming = '-';
        $data->id_guru = $id_guru;

        $data->save();
        return redirect()->to('guru/soal/'.$request->id_detail);
    }


    public function delete($id, $id_detail)
    {
        $data = Soal::find($id);
        $data->delete();
        return redirect()->to('guru/soal/'.$id_detail);
    }
}
