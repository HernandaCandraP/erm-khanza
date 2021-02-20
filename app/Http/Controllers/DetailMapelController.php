<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DetailMapel;
use App\Mapel;
use App\Kelas;
use App\MapelKelas;
use Auth;

class DetailMapelController extends Controller
{
    public function detailmapel(){
        $no = 1;
        
        $detailmapel = DetailMapel::join('mapel', 'detailmapel.id_mapel', '=', 'mapel.id_mapel')->select('detailmapel.*', 'mapel.nama_mapel')->get();
        $mapel = Mapel::all();
        $kls = Kelas::all();

        return view('guru/detail_mapel', [
            'detailmapel' => $detailmapel, 
            'kls' => $kls,
            'mapel' => $mapel,
            'no' => $no
        ]);
    }

    public function detailmapelsiswa(){
        $no = 1;
        $kelassiswa = Auth::guard('siswa')->user()->id_kelas;
        
        $detailmapel = DetailMapel::join('mapel', 'detailmapel.id_mapel', '=', 'mapel.id_mapel')
                ->join('mapelkelas', 'detailmapel.id_detail', '=', 'mapelkelas.id_detail')
                ->where('mapelkelas.id_kelas', $kelassiswa)
                ->select('detailmapel.*', 'mapel.nama_mapel')->get();

        return view('siswa/detail_mapel', [
            'detailmapel' => $detailmapel, 
            'no' => $no
        ]);
    }

    public function keterangan($id)
    {
        $detailmapel = DetailMapel::join('mapel', 'detailmapel.id_mapel', '=', 'mapel.id_mapel')->select('detailmapel.*', 'mapel.nama_mapel')->find($id);

        return view('siswa/keterangan', [
            'detailmapel'=>$detailmapel,
        ]);
    }

    public function create(Request $request)
    {
        $data = new DetailMapel;

        $data->id_mapel = $request->id_mapel;
        $data->waktu_mulai = $request->waktu_mulai;
        $data->waktu_selesai = $request->waktu_selesai;
        $data->waktu_test = $request->waktu_test;
        $data->keterangan = $request->keterangan;

        // fungsi simpan data detailmapel.
        $data->save();

        // get last ID
        $last_id = DetailMapel::orderBy('id_detail', 'DESC')->first();

        // save multiple data kelas
        $count_kelas = count($request->id_kelas);
        for ($i=0; $i < $count_kelas ; $i++) { 
            $simpankelas = new MapelKelas;
            // perlu mengambil id terakhir dari tabel detailmapel.
            $simpankelas->id_detail = (int)$last_id->id_detail;
            $simpankelas->id_kelas = $request->id_kelas[$i];
            $simpankelas->save();
        }

        return redirect()->route('homeguru/detailmapel');
    }

    public function edit($id)
    {
        $detailmapel = DetailMapel::find($id);
        $mapel = Mapel::all();
        $kls = Kelas::all();

        // return $detailmapel->waktu_mulai;
        return view('guru/detailmapel_edit', [
            'detailmapel'=>$detailmapel,
            'mapel'=>$mapel,
            'kls'=>$kls,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = DetailMapel::find($id);

        $data->id_mapel = $request->id_mapel;
        $data->waktu_mulai = $request->waktu_mulai;
        $data->waktu_selesai = $request->waktu_selesai;
        $data->waktu_test = $request->waktu_test;
        $data->keterangan = $request->keterangan;

        $data->save();

        // cek update tabel kelasmakul
        foreach ($request->id_kelas as $key => $kelas) {
            $mk = MapelKelas::where('id_detail', $id)->where('id_kelas', $kelas)->count();
            if($mk == 0){
                $simpankelas = new MapelKelas;
                $simpankelas->id_detail = $id;
                $simpankelas->id_kelas = $kelas;
                $simpankelas->save();
            }
        }
        
        // delete semua data yang tidak sesuai dengan inputan nilai
        $not_in = MapelKelas::where('id_detail', $id)->whereNotIn('id_kelas', $request->id_kelas);
        if($not_in->count() != 0){
            $not_in->delete();
        }
        
        return redirect()->route('homeguru/detailmapel');
    }


    public function delete($id)
    {
        $data = DetailMapel::find($id);
        $data->delete();
        return redirect()->route('homeguru/detailmapel');
    }
}
