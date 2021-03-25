<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DetailMapel;
use App\Soal;
use App\Mapel;
use App\Kelas;
use App\Jawaban;
use Auth;
use App\Classes\TextMining;
use App\Classes\NaziefAndriani;
use App\Classes\CosineSimilarity;
use App\Classes\JaccardSimilarity;

class PenilaianController extends Controller
{
    public function penilaianguru(Request $request, $id_detail)
    {
        $namamapel = DetailMapel::join('mapel', 'detailmapel.id_mapel', '=', 'mapel.id_mapel')->select('detailmapel.*', 'mapel.nama_mapel')->where('id_detail', $id_detail)->first();
        $mapel = Mapel::all();
        $kls = Kelas::join('mapelkelas', 'kelas.id_kelas', '=', 'mapelkelas.id_kelas')->select('kelas.*')->where('id_detail', $id_detail)->get();

        // get array in id_detail
        $getsoal = Soal::select('id_soal')->where('id_detail', $id_detail)->get();

        $getsoal_arr = array();
        foreach($getsoal as $key => $value){
            $getsoal_arr[$key] = $value->id_soal;
        }

        // get jumlah soal 
        $count_soal = Soal::where('id_detail',$id_detail)->count();
        // jika jumlah soal == 0 maka akan kembali pada halaman sebelumnya
        if($count_soal == 0){
            $request->session()->flash('warning', 'Periksa Ujian : ' .$namamapel->nama_mapel. ' Tidak Dapat Dibuka Dikarenakan Soal Masih Kosong');
            return redirect()->back();
        }

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
        ->select('soal.soal','soal.image', 'jawaban.*')->where('id_detail', $id_detail)->get();

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

    public function jawabansiswa(Request $request, $id_detail, $id_soal)
    {
        // panggil fungsi textmining
        $mining = new TextMining();
        $jaccard = new JaccardSimilarity();
        $cosine = new CosineSimilarity();

        $katakunci= Soal::find($id_soal); // ambil kunci jawaban

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
                $jawaban->nilai_cosine = $cosine->prosesCosine($katakunci->jawaban, $request->jawaban_siswa);
                $jawaban->nilai_jaccard = $jaccard->prosesJaccard($katakunci->jawaban, $request->jawaban_siswa);

                $jawaban->save();
            }
        }else{
            // jika belum ada data yang tersimpan, lakukan proses simpan
            $data = new Jawaban;
            $data->id_siswa = $id_siswa;
            $data->id_soal = $id_soal;
            $data->jawaban_siswa = $request->jawaban_siswa;
            $data->nilai_cosine = $cosine->prosesCosine($katakunci->jawaban, $request->jawaban_siswa);
            $data->nilai_jaccard = $jaccard->prosesJaccard($katakunci->jawaban, $request->jawaban_siswa);

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

    public function ceknilai($id_detail)
    {
        $tampilnilai = Soal::leftjoin('jawaban', function($join){
            $join->on('soal.id_soal', '=', 'jawaban.id_soal')
            ->where('jawaban.id_siswa', Auth::guard('siswa')->user()->id_siswa);
        })
        ->select('soal.soal', 'soal.image', 'jawaban.*')->where('id_detail', $id_detail)->get();

        return view('siswa/cek_nilai', [
            'tampilnilai'=>$tampilnilai
        ]);
    }
}
