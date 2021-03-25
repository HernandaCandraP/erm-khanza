<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Soal;
use App\Jawaban;
use App\DetailMapel;
use App\DetailSiswa;
use Auth;
use File;
use App\Classes\TextMining;

class SoalController extends Controller
{
    public function tampilsoal($id){
        $no = 1;
        $kelas = '';
        $soal = Soal::where('id_detail',$id)->get();
        $detailmapel = DetailMapel::join('mapel', 'detailmapel.id_mapel', '=', 'mapel.id_mapel')->select('detailmapel.*', 'mapel.nama_mapel')->where('id_detail', $id)->first();

        $countkelas = $detailmapel->kelas->count()-1;
        foreach($detailmapel->kelas as $key => $value){
            if($key == $countkelas-1) {
                $kelas .= $value->nama_kelas;
            }elseif($key < $countkelas){
                $kelas .= $value->nama_kelas . ', ';
            }elseif($key == $countkelas ){
                if($countkelas ==0){
                    $kelas = $value->nama_kelas;
                }elseif($countkelas == 1) {
                    $kelas .= ' dan ' .$value->nama_kelas;
                }else{
                    $kelas .= ', dan ' .$value->nama_kelas;
                }
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
        $getsoal = Soal::leftjoin('jawaban', function($join) use($id_siswa){
            $join->on('soal.id_soal', '=', 'jawaban.id_soal')
            ->where('jawaban.id_siswa', $id_siswa);
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
    
    public function create(Request $request, $id)
    {
        $mining = new TextMining(); //memanggil fungsi textmining

        $validate = $request->validate([
            'image' => ['mimes:jpeg,png,jpg','max:2048'],
            'soal' => ['required'],
            'jawaban' => ['required'],
        ]);

        $data = new Soal;

        $data->id_detail = $id;
        $data->soal = $request->soal;
        $data->jawaban = $request->jawaban;
        $data->stemming = $mining->textMining($request->jawaban); //memasukkan text mining ke db

        if($files = $request->file('image')){
            $destinationPath = 'image/';  //upload path
            $profileImage = date('YmdHis'). "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            $data->image = $profileImage; //save image
        }
        $data->save();
        $request->session()->flash('success', 'Soal berhasil ditambahkan');
        return redirect()->to('guru/soal/'.$id);
    }

    public function edit($id)
    {
        $soal = Soal::find($id);

        return view('guru/soal_edit', ['soal'=>$soal]);
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'image' => ['mimes:jpeg,png,jpg','max:2048'],
            'soal' => ['required'],
            'jawaban' => ['required'],
        ]);
     
        $mining = new TextMining(); //memanggil fungsi textmining
        $data = Soal::find($id);

        if($data->jawaban != $request->jawaban){ //ketika jawaban tidak sama dgn sebelunya (untuk menguranggi proses di db)
            $data->stemming = $mining->textMining($request->jawaban); //memasukkan text mining ke db
        }
        $data->id_detail = $request->id_detail;
        $data->soal = $request->soal;
        $data->jawaban = $request->jawaban;

        if($files = $request->file('image')){
            $imagePath = public_path("image/" .$data->image);
            if (File::exists($imagePath)){
                File::delete($imagePath);
            }
            $destinationPath = 'image/';  //upload path
            $profileImage = date('YmdHis'). "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            $data->image = $profileImage; //save image
        }

        $data->save();
        $request->session()->flash('success', 'Soal berhasil diedit');
        return redirect()->to('guru/soal/'.$request->id_detail);
    }

    public function deleteimage(Request $request, $id_soal)
    {
        $data = Soal::find($id_soal);
        // menghapus image pada filder
        $imagePath = public_path("image/" .$data->image);
        if (File::exists($imagePath)){
            File::delete($imagePath);
        }

        // menghapus image pada database
        $data->image = "";
        $data->save();
        $request->session()->flash('success', 'Gambar berhasil dihapus');
        return redirect()->back();
    }


    public function delete($id, $id_detail)
    {
        $data = Soal::find($id);
        $data->delete();
        return redirect()->to('guru/soal/'.$id_detail);
    }
}
