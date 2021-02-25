<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Soal;
use App\Jawaban;
use App\DetailMapel;
use App\DetailSiswa;
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

    public function simpanwaktu(Request $request, $id)
    {
        $id_siswa = Auth::guard('siswa')->user()->id_siswa;
        date_default_timezone_set('Asia/Jakarta'); # add your city to set local time zone
		$now = date('Y-m-d H:i:s');

        // cek apakah ada data yang disimpan pd tabel detailsiswa dengan id_detail dan id_siswa yg sama
        $cek_detail = DetailSiswa::where('id_detail', $request->id_detail)->where('id_siswa', $id_siswa)->first();
        // simpan data pada tabel detailsiswa
        if($cek_detail == null){
            $data = new DetailSiswa;
            $data->id_detail = $request->id_detail;
            $data->id_siswa = $id_siswa;
            $data->is_end = 0;
            $data->created_at = $now;

            $data->save();
            // print("simpan");
        }
        // get id_soal first
        $id_soal = Soal::where('id_detail', $request->id_detail)->first()->id_soal;

        return redirect()->to('siswa/soal/'.$request->id_detail. '/'.$id_soal);
    }

    public function soalsiswa($id_detail, $id_soal)
    {
        $id_siswa = Auth::guard('siswa')->user()->id_siswa;

        // get nama mapel
        $nama_mapel = DetailMapel::join('mapel', 'detailmapel.id_mapel', '=', 'mapel.id_mapel')->select('mapel.nama_mapel')->where('id_detail', $id_detail)->first();

        // get id_detail & id_soal untuk menyimpan jawaban dari siswa
        $tampilsoal = Soal::where('id_detail', $id_detail)->where('id_soal', $id_soal)->first();
        // untuk menampikan nomer soal apakah sudah dikerjakan / belum
        $getsoal = Soal::leftjoin('jawaban', function($join){
            $join->on('soal.id_soal', '=', 'jawaban.id_soal')
            ->where('jawaban.id_siswa', Auth::guard('siswa')->user()->id_siswa);
        })
        ->select('soal.id_soal', 'jawaban.jawaban_siswa','jawaban.id_siswa')->where('id_detail', $id_detail)->get();

        // cek apakah jawaban sudah terisi?
        $jawaban = Jawaban::where('id_siswa', $id_siswa)->where('id_soal', $id_soal)->first();

        // cek  ketika soal sudah diselesaikan maka link akan disable
        $cek_is_end = DetailSiswa::where('id_siswa', $id_siswa)->where('id_detail', $id_detail)->first();
        if($cek_is_end->is_end){
            return redirect('/404');
        }

        // mengambil waktu test
        $start_time = DetailSiswa::where('id_siswa', $id_siswa)->where('id_detail', $id_detail)->first()->created_at;

        // mengambil waktu akhir test
        $end_time = DetailMapel::where('id_detail', $id_detail)->first()->waktu_test;

        $detik =(strtotime($end_time) - strtotime('00:00:00'));
        $end_time = date("Y-m-d H:i:s", strtotime($start_time)+$detik);
        // print(((date("H", $end))) .'<br>');
        
        return view('siswa/jawaban', [
            'tampilsoal' => $tampilsoal, 
            'getsoal' => $getsoal, 
            'jawaban' => $jawaban,
            'end_time' => $end_time,
            'nama_mapel' =>$nama_mapel
        ]);
    }

    public function jawabansiswa(Request $request, $id_detail, $id_soal)
    {
        $id_siswa = Auth::guard('siswa')->user()->id_siswa;

        //cek apakah sudah ada data id_soal dan id_siswa yang sudah disimpan
        $cek_jawaban = Jawaban::where('id_siswa', $id_siswa)->where('id_soal', $id_soal)->first();

        if($cek_jawaban != null){
            // cek apakah jawaban sebelumnya sama dengan jawaban yang terbaru, jika tidak sama lalukan proses update
            if($cek_jawaban->jawaban_siswa != $request->jawaban_siswa){
                // jika jawaban diubah, lakukan proses update
                $jawaban = Jawaban::find($cek_jawaban->id_jawaban);
                $jawaban->id_siswa = $id_siswa;
                $jawaban->id_soal = $id_soal;
                $jawaban->jawaban_siswa = $request->jawaban_siswa;
                $jawaban->nilai_cosine = 10;
                $jawaban->nilai_jaccard = 10;
                $jawaban->save();
            }
        }else{
            // jika belum ada data yang tersimpan, lakukan proses simpan
            $data = new Jawaban;
            $data->id_siswa = $id_siswa;
            $data->id_soal = $id_soal;
            $data->jawaban_siswa = $request->jawaban_siswa;
            $data->nilai_cosine = 10;
            $data->nilai_jaccard = 10;
            $data->save();
        }

        if($request->redirect_id_soal != null){
            return redirect()->to('siswa/soal/'.$id_detail. '/'.$request->redirect_id_soal);
        }
        // untuk redirect ketika waktu habis atau ketika klik simpan
        if($request->timeout == 'timeout' || $request->simpan == 'simpan'){
            // mengubah tabel is_end ketika mengekhiri ujian / ujian berakhir
            $simpan = DetailSiswa::where('id_siswa', $id_siswa)->where('id_detail', $id_detail)->update(array('is_end' => 1));

            return redirect()->to('siswa/detailmapel');
        }
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
