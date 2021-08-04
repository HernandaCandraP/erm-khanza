<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DetailMapel;
use App\Soal;
use App\Mapel;
use App\Kelas;
use App\Siswa;
use App\Jawaban;
use App\DetailSiswa;
use Auth;
use DB;
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
        $count_soal = count($getsoal);
        // jika jumlah soal == 0 maka akan kembali pada halaman sebelumnya
        if($count_soal == 0){
            $request->session()->flash('warning', 'Periksa Ujian : ' .$namamapel->nama_mapel. ' Tidak Dapat Dibuka Dikarenakan Soal Masih Kosong');
            return redirect()->back();
        }

        // $where_in = (implode(",",$getsoal_arr));
        $sum_nilai = Jawaban::select('id_siswa',
        DB::raw('SUM(CASE
            WHEN jawaban_siswa is null or jawaban_siswa = "" then 0
            WHEN nilai_cosine < 2  then 10
            WHEN nilai_cosine < 12  then 20
            WHEN nilai_cosine < 22  then 30
            WHEN nilai_cosine < 32  then 40
            WHEN nilai_cosine < 42  then 50
            WHEN nilai_cosine < 52  then 60
            WHEN nilai_cosine < 62  then 70
            WHEN nilai_cosine < 72  then 80
            WHEN nilai_cosine < 82  then 90
            ELSE 100 END) as nilai_cosine'), 
        DB::raw('SUM(CASE
            WHEN jawaban_siswa is null or jawaban_siswa = "" then 0
            WHEN nilai_jaccard < 6 then 20
            WHEN nilai_jaccard < 16  then 30
            WHEN nilai_jaccard < 26  then 40
            WHEN nilai_jaccard < 36  then 50
            WHEN nilai_jaccard < 46  then 60
            WHEN nilai_jaccard < 56  then 70
            WHEN nilai_jaccard < 66  then 80
            WHEN nilai_jaccard < 76  then 90
            ELSE 100 END) as nilai_jaccard')
        )->groupby("id_siswa")->whereIn('id_soal', $getsoal_arr)->get();


        $avg_cosine = [];
        $avg_jaccard = [];
        foreach ($sum_nilai as $key => $value) {
            // print($value);
            $avg_cosine[$value->id_siswa] = number_format(($value->nilai_cosine/$count_soal), 0, '.', '');
            $avg_jaccard[$value->id_siswa] = number_format(($value->nilai_jaccard/$count_soal), 0, '.', '');
        }

        return view('guru/penilaian', [
            'namamapel' => $namamapel, 
            'kls' => $kls,
            'getsoal_arr' => $getsoal_arr,
            'id_detail' => $id_detail,
            'count_soal' => $count_soal,
            'avg_cosine' => $avg_cosine,
            'avg_jaccard' => $avg_jaccard,
        ]);
    }

    public function cetakpenilaian(Request $request, $id_detail)
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

        $count_soal = count($getsoal);
        
        $sum_nilai = Jawaban::select('id_siswa',
        DB::raw('SUM(CASE
            WHEN jawaban_siswa is null or jawaban_siswa = "" then 0
            WHEN nilai_cosine < 2  then 10
            WHEN nilai_cosine < 12  then 20
            WHEN nilai_cosine < 22  then 30
            WHEN nilai_cosine < 32  then 40
            WHEN nilai_cosine < 42  then 50
            WHEN nilai_cosine < 52  then 60
            WHEN nilai_cosine < 62  then 70
            WHEN nilai_cosine < 72  then 80
            WHEN nilai_cosine < 82  then 90
            ELSE 100 END) as nilai_cosine'), 
        DB::raw('SUM(CASE
            WHEN jawaban_siswa is null or jawaban_siswa = "" then 0
            WHEN nilai_jaccard < 6 then 20
            WHEN nilai_jaccard < 16  then 30
            WHEN nilai_jaccard < 26  then 40
            WHEN nilai_jaccard < 36  then 50
            WHEN nilai_jaccard < 46  then 60
            WHEN nilai_jaccard < 56  then 70
            WHEN nilai_jaccard < 66  then 80
            WHEN nilai_jaccard < 76  then 90
            ELSE 100 END) as nilai_jaccard')
        )->groupby("id_siswa")->whereIn('id_soal', $getsoal_arr)->get();

        $avg_cosine = [];
        $avg_jaccard = [];
        foreach ($sum_nilai as $key => $value) {
            // print($value);
            $avg_cosine[$value->id_siswa] = number_format(($value->nilai_cosine/$count_soal), 0, '.', '');
            $avg_jaccard[$value->id_siswa] = number_format(($value->nilai_jaccard/$count_soal), 0, '.', '');
        }


        // get jumlah soal 
        $count_soal = Soal::where('id_detail',$id_detail)->count();

        return view('guru/penilaian_cetak', [
            'namamapel' => $namamapel, 
            'kls' => $kls,
            'getsoal_arr' => $getsoal_arr,
            'id_detail' => $id_detail,
            'count_soal' => $count_soal, 
            'avg_cosine' => $avg_cosine,
            'avg_jaccard' => $avg_jaccard,
        ]);
    }

    public function penilaianedit($id_detail, $id_siswa)
    {
        $tampilnilai = Soal::leftjoin('jawaban', function($join) use($id_siswa){
            $join->on('soal.id_soal', '=', 'jawaban.id_soal')
            ->where('jawaban.id_siswa', $id_siswa);
        })
        ->select('soal.soal','soal.image', 'soal.jawaban', 'jawaban.jawaban_siswa','jawaban.id_jawaban', 'jawaban.nilai_guru',
        DB::raw('CASE
            WHEN jawaban_siswa is null or jawaban_siswa = "" then 0
            WHEN nilai_cosine < 2  then 10
            WHEN nilai_cosine < 12  then 20
            WHEN nilai_cosine < 22  then 30
            WHEN nilai_cosine < 32  then 40
            WHEN nilai_cosine < 42  then 50
            WHEN nilai_cosine < 52  then 60
            WHEN nilai_cosine < 62  then 70
            WHEN nilai_cosine < 72  then 80
            WHEN nilai_cosine < 82  then 90
            ELSE 100 END as nilai_cosine'), 
        DB::raw('CASE
            WHEN jawaban_siswa is null or jawaban_siswa = "" then 0
            WHEN nilai_jaccard < 6 then 20
            WHEN nilai_jaccard < 16  then 30
            WHEN nilai_jaccard < 26  then 40
            WHEN nilai_jaccard < 36  then 50
            WHEN nilai_jaccard < 46  then 60
            WHEN nilai_jaccard < 56  then 70
            WHEN nilai_jaccard < 66  then 80
            WHEN nilai_jaccard < 76  then 90
            ELSE 100 END as nilai_jaccard')
        )
        ->where('id_detail', $id_detail)->get();

        $data_siswa = Siswa::select('nama_siswa', 'nis')->where('id_siswa', $id_siswa)->first();

        return view('guru/penilaian_edit', [
            'tampilnilai'=>$tampilnilai,
            'id_detail' => $id_detail,
            'id_siswa' => $id_siswa,
            'data_siswa' => $data_siswa
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
                $jawaban->stemming_jawaban_siswa = $mining->textMining($request->jawaban_siswa);
                $jawaban->nilai_cosine = $cosine->prosesCosine($katakunci->stemming, $jawaban->stemming_jawaban_siswa);
                $jawaban->nilai_jaccard = $jaccard->prosesJaccard($katakunci->stemming, $jawaban->stemming_jawaban_siswa);

                $jawaban->save();
            }
        }else{
            // jika belum ada data yang tersimpan, lakukan proses simpan
            $data = new Jawaban;
            $data->id_siswa = $id_siswa;
            $data->id_soal = $id_soal;
            $data->jawaban_siswa = $request->jawaban_siswa;
            $data->stemming_jawaban_siswa = $mining->textMining($request->jawaban_siswa);
            $data->nilai_cosine = $cosine->prosesCosine($katakunci->stemming, $data->stemming_jawaban_siswa);
            $data->nilai_jaccard = $jaccard->prosesJaccard($katakunci->stemming, $data->stemming_jawaban_siswa);

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
        ->select('soal.soal','soal.image', 'soal.jawaban', 'jawaban.jawaban_siswa', 'jawaban.nilai_guru',
        DB::raw('CASE
            WHEN jawaban_siswa is null or jawaban_siswa = "" then 0
            WHEN nilai_cosine < 2  then 10
            WHEN nilai_cosine < 12  then 20
            WHEN nilai_cosine < 22  then 30
            WHEN nilai_cosine < 32  then 40
            WHEN nilai_cosine < 42  then 50
            WHEN nilai_cosine < 52  then 60
            WHEN nilai_cosine < 62  then 70
            WHEN nilai_cosine < 72  then 80
            WHEN nilai_cosine < 82  then 90
            ELSE 100 END as nilai_cosine'), 
        DB::raw('CASE
            WHEN jawaban_siswa is null or jawaban_siswa = "" then 0
            WHEN nilai_jaccard < 6 then 20
            WHEN nilai_jaccard < 16  then 30
            WHEN nilai_jaccard < 26  then 40
            WHEN nilai_jaccard < 36  then 50
            WHEN nilai_jaccard < 46  then 60
            WHEN nilai_jaccard < 56  then 70
            WHEN nilai_jaccard < 66  then 80
            WHEN nilai_jaccard < 76  then 90
            ELSE 100 END as nilai_jaccard')
        )
        ->where('id_detail', $id_detail)->get();

        return view('siswa/cek_nilai', [
            'tampilnilai'=>$tampilnilai
        ]);
    }
}
