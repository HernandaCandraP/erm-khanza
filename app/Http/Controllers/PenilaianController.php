<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DetailMapel;
use App\Soal;
use App\Mapel;
use App\Kelas;
use App\Jawaban;

class PenilaianController extends Controller
{
    public function penilaianguru($id_detail)
    {
        $namamapel = DetailMapel::join('mapel', 'detailmapel.id_mapel', '=', 'mapel.id_mapel')->select('detailmapel.*', 'mapel.nama_mapel')->where('id_detail', $id_detail)->first();
        $mapel = Mapel::all();
        $kls = Kelas::all();

        // get array in id_detail
        $getsoal = Soal::select('id_soal')->where('id_detail', $id_detail)->get();

        $getsoal_arr = array();
        foreach($getsoal as $key => $value){
            $getsoal_arr[$key] = $value->id_soal;
        }

        // get jumlah soal 
        $count_soal = Soal::where('id_detail',$id_detail)->count();

        return view('guru/penilaian', [
            'namamapel' => $namamapel, 
            'kls' => $kls,
            'getsoal_arr' => $getsoal_arr,
            'id_detail' => $id_detail,
            'count_soal' => $count_soal
        ]);
    }

    public function penilaianedit($id_detail, $id_siswa)
    {
        $tampilnilai = Soal::leftjoin('jawaban', function($join) use($id_siswa){
            $join->on('soal.id_soal', '=', 'jawaban.id_soal')
            ->where('jawaban.id_siswa', $id_siswa);
        })
        ->select('soal.soal', 'jawaban.*')->where('id_detail', $id_detail)->get();

        return view('guru/penilaian_edit', [
            'tampilnilai'=>$tampilnilai,
            'id_detail' => $id_detail,
            'id_siswa' => $id_siswa
        ]);
    }

    public function penilaianupdate(Request $request)
    {
        $data = Jawaban::find($request->modal_input_id);
        $data->nilai_guru = $request->modal_input_nilai;

        $data->save();
        return redirect()->back();
    }
}
