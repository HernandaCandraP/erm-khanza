<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DetailMapel;
use App\DetailSiswa;
use App\Soal;
use App\Mapel;
use App\Kelas;
use App\MapelKelas;
use Auth;

class DetailMapelController extends Controller
{
    public function detailmapel(){
        $detailmapel = DetailMapel::join('mapel', 'detailmapel.id_mapel', '=', 'mapel.id_mapel')->select('detailmapel.*', 'mapel.nama_mapel')->get();
        $mapel = Mapel::all();
        $kls = Kelas::all();

        return view('guru/detail_mapel', [
            'detailmapel' => $detailmapel, 
            'kls' => $kls,
            'mapel' => $mapel,
        ]);
    }

    public function detailmapelsiswa(){
        $user = Auth::guard('siswa')->user();
        
        $detailmapel = DetailMapel::join('mapel', 'detailmapel.id_mapel', '=', 'mapel.id_mapel')
                ->join('mapelkelas', 'detailmapel.id_detail', '=', 'mapelkelas.id_detail')
                ->where('mapelkelas.id_kelas', $user->id_kelas)
                ->leftJoin('detailsiswa', function($join) use($user){
                    $join->on('detailmapel.id_detail', '=', 'detailsiswa.id_detail')
                    ->where('detailsiswa.id_siswa', $user->id_siswa);
                })
                ->select('detailmapel.*', 'mapel.nama_mapel', 'detailsiswa.id_siswa', 'detailsiswa.is_end')->get();

        return view('siswa/detail_mapel', [
            'detailmapel' => $detailmapel, 
        ]);
    }

    public function lanjutkan($id_detail)
    {
        $id_siswa = Auth::guard('siswa')->user()->id_siswa;

        // get id_soal first
        $id_soal = Soal::where('id_detail', $id_detail)->first()->id_soal;

        return redirect()->to('siswa/soal/'.$id_detail. '/'.$id_soal);
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
        // konversi ke integer
        if($request->waktu_selesai != null){
            $request['waktu-selesai'] = strtotime($request->waktu_selesai);
            $request['start'] = strtotime($request->waktu_mulai);
        }
        else{
            $request['waktu-selesai'] = 1;
            $request['start'] = 0;
        }

        $validate = $request->validate([
            'id_mapel' => ['required'],
            'id_kelas' => ['required'],
            'waktu_mulai' => ['required'],
            'waktu_selesai' => ['required'],
            'waktu-selesai' => ['gt:start'],
            'waktu_test' => ['required'],
            'keterangan' => ['max:50'],
        ]);
        
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

        // get nama_mapel
        $namamapel = Mapel::where('id_mapel', $request->id_mapel)->first();
        $request->session()->flash('success', 'Berhasil Menambahkan Ujian '.$namamapel->nama_mapel);
        return redirect()->back();
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
        // konversi ke integer
        if($request->waktu_selesai != null){
            $request['waktu-selesai'] = strtotime($request->waktu_selesai);
            $request['start'] = strtotime($request->waktu_mulai);
        }
        else{
            $request['waktu-selesai'] = 1;
            $request['start'] = 0;
        }

        $validate = $request->validate([
            'id_mapel' => ['required'],
            'id_kelas' => ['required'],
            'waktu_mulai' => ['required'],
            'waktu_selesai' => ['required'],
            'waktu-selesai' => ['gt:start'],
            'waktu_test' => ['required'],
            'keterangan' => ['max:50'],
        ]);

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
        
        // get nama_mapel
        $namamapel = Mapel::where('id_mapel', $request->id_mapel)->first();
        $request->session()->flash('success', 'Ujian Mata Pelajaran ' .$namamapel->nama_mapel. ' Berhasil Diedit');
        return redirect()->route('homeguru/detailmapel');
    }


    public function delete(Request $request, $id)
    {
        $data = DetailMapel::find($id);

        $namamapel = Mapel::where('id_mapel', $data->id_mapel)->first();
        // cek apakah ada relasi ke tabel lain
        try{
            $data->delete();
            $request->session()->flash('warning', 'Ujian: ' .$namamapel->nama_mapel. ' Berhasil Dihapus');
            return redirect()->back();
        } catch (\Exception $e){
            $request->session()->flash('error', 'Ujian: ' .$namamapel->nama_mapel. ' Tidak Dapat Dihapus');
            return redirect()->back();
        }
    }
}
