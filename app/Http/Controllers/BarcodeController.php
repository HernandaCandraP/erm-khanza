<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Storage;

class BarcodeController extends Controller
{
    public function barcode(){
        
        $dokter = DB::table('dokter')->where('status', '1')->orderby('nm_dokter')->get();
        $poliklinik = DB::table('poliklinik')->where('status', '1')->get();

        return view('barcode/barcode', [
            'dokter' => $dokter, 
            'poliklinik' => $poliklinik, 
        ]);
    }

    function getpasien(Request $request){
        $detailpasien = DB::table('pasien as p')
        ->select('p.no_rkm_medis', 'p.nm_pasien', 'p.jk', 'p.tmp_lahir', 'p.tgl_lahir', 'p.alamat', 'p.pekerjaan', 'p.stts_nikah', 'p.agama', 'p.no_tlp', 'p.umur')
        ->where('p.no_rkm_medis', $request->norm)
        ->first();

        $riwayatperiksa = DB::table('reg_periksa as rp')
        ->select('rp.no_rawat', 'rp.tgl_registrasi', 'd.nm_dokter', 'p.nm_poli', 'rp.status_lanjut')
        ->leftjoin('dokter as d', 'd.kd_dokter', '=', 'rp.kd_dokter')
        ->leftjoin('poliklinik as p', 'p.kd_poli', '=', 'rp.kd_poli')
        ->where('rp.no_rkm_medis', $request->norm)
        ->get();
        
        if($detailpasien){
            setlocale(LC_ALL, 'IND');
            $detailpasien->tgl_lahir = Carbon::parse($detailpasien->tgl_lahir)->locale('id')->formatLocalized('%d %B %Y');     
        }
        // 122315	NY. ISTIQAMAH	3503054610690001	P	TRENGGALEK	1969-10-06	MURATUN/MARJI	RT/RW 014/004	-	PEDAGANG	JANDA	ISLAM	2020-07-11	085232627823	54 Th 11 Bl 19 Hr	SMP	ANAK	BAMBANG SUCIPTO	BPJ	0000665368323	1446	507	237	PETANI	RT/RW 014/004	GONDANG	TUGU	TRENGGALEK	-	5	11	1	-	-	267	JAWA TIMUR

        // dd($detailpasien);


        return response()->json(array('detailpasien'=> $detailpasien), 200);
    }

    function riwayatperiksa(Request $request){
        $riwayatperiksa = DB::table('reg_periksa as rp')
        ->select('rp.no_rawat', 'rp.tgl_registrasi', 'd.nm_dokter', 'p.nm_poli', 'rp.status_lanjut')
        ->leftjoin('dokter as d', 'd.kd_dokter', '=', 'rp.kd_dokter')
        ->leftjoin('poliklinik as p', 'p.kd_poli', '=', 'rp.kd_poli')
        ->where('rp.no_rkm_medis', $request->norm)
        ->orderby('rp.no_rawat', 'DESC')
        ->take(5)->get();

        return response()->json(array('riwayatperiksa'=> $riwayatperiksa), 200);
    }

    function simpanregistrasi(Request $request){
        // dd($request->all());
        $time = Carbon::now()->format('H:i:s');
        // ambil no rawat terakhir
        $newDate = date("Y/m/d", strtotime($request->tgl_registrasi));
        $norawatterakhir = DB::table('reg_periksa as rp')->select('rp.no_rawat')->where('no_rawat', 'like', '%' . $newDate . '%')
            ->orderby('rp.no_rawat', 'DESC')
            ->first();

        $pasien =  DB::table('pasien as p')->where('no_rkm_medis', $request->no_rkm_medis)->first();

        if($norawatterakhir){
            $norawatselanjutnya = substr($norawatterakhir->no_rawat, -6) + 1;
            $no_rawat = $newDate .'/'. sprintf("%06d", $norawatselanjutnya);
        }else{
            $no_rawat = $newDate .'/'. sprintf("%06d", 1);
        }
        
        $noreg = DB::table('reg_periksa as rp')->select('rp.no_reg')
        ->orderby('rp.no_rawat', 'DESC')
        ->where('rp.kd_poli', $request->poliklinik)
        ->first();

        $umur = Carbon::parse($pasien->tgl_lahir)->diff(Carbon::now())->format('%y');
        $regperiksa = [
            'no_reg' => $noreg->no_reg,
            'no_rawat' => $no_rawat,
            'tgl_registrasi' => $request->tgl_registrasi,
            'jam_reg' => $time,
            'kd_dokter' => $request->dokter,
            'no_rkm_medis' => $request->no_rkm_medis,
            'kd_poli' => $request->poliklinik,
            'p_jawab' => $pasien->namakeluarga,
            'almt_pj' => $pasien->alamatpj,
            'hubunganpj' => $pasien->keluarga,
            'biaya_reg' => 80000,
            'stts' => 'Belum',
            'stts_daftar' => 'Lama',
            'status_lanjut' => 'Ralan',
            'kd_pj' => $pasien->kd_pj,
            'umurdaftar' => $umur,
            'sttsumur' => 'Th',
            'status_bayar' => 'Belum Bayar',
            'status_poli' => 'Lama'
        ];
        // dd($regperiksa);
        DB::table('reg_periksa')->insert($regperiksa);

        $request->session()->flash('success', 'Berhasil simpan registrasi pasien');
        return redirect('tambahpasien');
    }

    function cetakkartu(Request $request){
        $detailpasien = DB::table('pasien as p')
        ->select('p.no_rkm_medis', 'p.nm_pasien', 'p.jk', 'p.tmp_lahir', 'p.tgl_lahir', 'p.alamat', 'p.pekerjaan', 'p.stts_nikah', 'p.agama', 'p.no_tlp', 'p.umur')
        ->where('p.no_rkm_medis', $request->norm)
        ->first();
        
        // 122315	NY. ISTIQAMAH	3503054610690001	P	TRENGGALEK	1969-10-06	MURATUN/MARJI	RT/RW 014/004	-	PEDAGANG	JANDA	ISLAM	2020-07-11	085232627823	54 Th 11 Bl 19 Hr	SMP	ANAK	BAMBANG SUCIPTO	BPJ	0000665368323	1446	507	237	PETANI	RT/RW 014/004	GONDANG	TUGU	TRENGGALEK	-	5	11	1	-	-	267	JAWA TIMUR

        // dd($detailpasien);

        return view('barcode/cetakkartu', [
            // 'presensi' => $presensi, 
            // 'rekappresensi' => $rekappresensi, 
        ]);
    }

    public function tambahpasien(Request $request){
        // dd($request->all());
        if($request->all()){
            // fungsi simpan data
            if($request->action == "simpan"){
                $this->simpanDataPasien($request);
            }
            // fungsi hapus data
            else if($request->action == "hapus"){
                $this->hapusDataPasien($request);
            }
            // fungsi update data
            else if($request->action == "update"){
                if($request->datapasien[0]){
                    $pasien = DB::table('pasien')->where('no_rkm_medis',$request->datapasien[0])->first();
                    return view('admin/pasienedit', [
                        'pasien' => $pasien, 
                    ]);                    
                }
            }
            // fungsi simpan dari update data
            else if($request->action == "simpanupdate"){
                $this->simpanUpdatePasien($request);
            }
        }

        // tambah 20 pasien terakhir tanggal daftar tiap poli
        $pasien = DB::table('pasien as p')->orderby('p.no_rkm_medis', 'desc')->take(5)->get();
        
        return view('barcode/home', [
            'pasien' => $pasien
        ]);
    }

    private function simpanDataPasien(Request $request){
        // jika input kode otomatis
        if($request->rm_manual == null){
            $pasien = DB::table('pasien as p')->select('p.no_rkm_medis')
            ->orderby('p.no_rkm_medis', 'DESC')
            ->first();
            // No RM + 1
            $norm = $pasien->no_rkm_medis + 1;
        }

        // menghitung umur
        $tgl_lahir = new DateTime($request->tgl_lahir);
        $tgl_daftar = new DateTime($request->tgl_daftar);
        if ($tgl_lahir > $tgl_daftar) { 
            $thn = "0";
            $bln = "0";
            $tgl = "0";
        }
        $thn = $tgl_daftar->diff($tgl_lahir)->y;
        $bln = $tgl_daftar->diff($tgl_lahir)->m;
        $tgl = $tgl_daftar->diff($tgl_lahir)->d;
        $umur = $thn. " Th " .$bln. " Bl " .$tgl. " Hr";

        // cek apakah ada no Rm sebelumnya
        $ceknorm = DB::table('pasien as p')->select('p.no_rkm_medis', 'p.nm_pasien', 'p.alamat')->where('p.no_rkm_medis', $norm)->first();
        if($ceknorm == null){
            $values = array(
                'no_rkm_medis' => $norm,
                'nm_pasien' => $request->nm_pasien,
                'no_ktp' => $request->no_ktp,
                'jk' => $request->jk,
                'tmp_lahir' => "-",
                'tgl_lahir' => $request->tgl_lahir,
                'nm_ibu' => $request->nm_ibu,
                'alamat' => $request->alamat,
                'gol_darah' => $request->gol_darah,
                'pekerjaan' => $request->pekerjaan,
                'stts_nikah' => $request->stts_nikah,
                'agama' => $request->agama,
                'tgl_daftar' => $request->tgl_daftar,
                'no_tlp' => $request->no_tlp,
                'umur' => $umur,
                'pnd' => $request->pnd,
                'keluarga' => $request->keluarga,
                'namakeluarga' => $request->namakeluarga,
                'kd_pj' => $request->kd_pj,
                'no_peserta' => $request->no_peserta,
                'kd_kel' => 1,
                'kd_kec' => 1,
                'kd_kab' => 1,
                'pekerjaanpj' => "-",
                'alamatpj' => $request->alamat,
                'kelurahanpj' => $request->nm_kab,
                'kecamatanpj' => $request->nm_kec,
                'kabupatenpj' => $request->nm_kab,
                'perusahaan_pasien' => "-",
                'suku_bangsa' => 1,
                'bahasa_pasien' => 1,
                'cacat_fisik' => 1,
                'email' => "-",
                'nip' => "-",
                'kd_prop' => 1,
                'propinsipj' => "-" 
            );

            DB::table('pasien')->insert($values);

            $request->session()->flash('success', 'No RM: ' .$norm. ' Nama ' .$request->nm_pasien. ' Alamat: ' .$request->alamat);
            return redirect('tambahpasien');
        }else{
            $request->session()->flash('error', 'No RM: ' .$norm. 'Sudah terisi atas nama : ' .$ceknorm->nm_pasien. ' Alamat: ' .$ceknorm->alamat);
            return redirect('tambahpasien');
        }
    }

    private function simpanUpdatePasien(Request $request){
        // menghitung umur
        $tgl_lahir = new DateTime($request->tgl_lahir);
        $tgl_daftar = new DateTime($request->tgl_daftar);
        if ($tgl_lahir > $tgl_daftar) { 
            $thn = "0";
            $bln = "0";
            $tgl = "0";
        }
        $thn = $tgl_daftar->diff($tgl_lahir)->y;
        $bln = $tgl_daftar->diff($tgl_lahir)->m;
        $tgl = $tgl_daftar->diff($tgl_lahir)->d;
        $umur = $thn. " Th " .$bln. " Bl " .$tgl. " Hr";


        if($request->rmbaru != $request->rmlama){
            $ceknorm = DB::table('pasien as p')->select('p.no_rkm_medis', 'p.nm_pasien', 'p.alamat')->where('p.no_rkm_medis', $request->rmbaru)->first();
        }else{
            $ceknorm = null;    //jika rmbaru dan rmlama bernilai sama buat nilai $ceknorm menjadi null agar bisa menjalankan fungsi update
        }
        
        if($ceknorm == null){
            $values = array(
                'no_rkm_medis' => $request->rmbaru,
                'nm_pasien' => $request->nm_pasien,
                'no_ktp' => $request->no_ktp,
                'jk' => $request->jk,
                'tmp_lahir' => "-",
                'tgl_lahir' => $request->tgl_lahir,
                'nm_ibu' => $request->nm_ibu,
                'alamat' => $request->alamat,
                'gol_darah' => $request->gol_darah,
                'pekerjaan' => $request->pekerjaan,
                'stts_nikah' => $request->stts_nikah,
                'agama' => $request->agama,
                'tgl_daftar' => $request->tgl_daftar,
                'no_tlp' => $request->no_tlp,
                'umur' => $umur,
                'pnd' => $request->pnd,
                'keluarga' => $request->keluarga,
                'namakeluarga' => $request->namakeluarga,
                // 'kd_pj' => $request->kd_pj,
                'no_peserta' => $request->no_peserta,
                'kd_kel' => 1,
                'kd_kec' => 1,
                'kd_kab' => 1,
                'pekerjaanpj' => "-",
                'alamatpj' => $request->alamat,
                'kelurahanpj' => $request->nm_kab,
                'kecamatanpj' => $request->nm_kec,
                'kabupatenpj' => $request->nm_kab,
                'perusahaan_pasien' => "-",
                'suku_bangsa' => 1,
                'bahasa_pasien' => 1,
                'cacat_fisik' => 1,
                'email' => "-",
                'nip' => "-",
                'kd_prop' => 1,
                'propinsipj' => "-" 
            );

            DB::table('pasien')->where('no_rkm_medis', $request->rmlama)->update($values);

            $request->session()->flash('success', 'No RM: ' .$request->rmbaru. ' Nama : ' .$request->nm_pasien. 'Alamat: ' .$request->alamat);
        }else{
            $request->session()->flash('error', 'No RM: ' .$request->rmbaru. ' Sudah terisi atas nama : ' .$ceknorm->nm_pasien. 'Alamat: ' .$ceknorm->alamat);
            return redirect('tambahpasien');
        }
    }

    private function hapusDataPasien(Request $request){
        // dd($request->datapasien);
        // dd($request->all());
        if($request->datapasien){
            try{
                DB::table('pasien')->where('no_rkm_medis',$request->datapasien[0])->delete();
                $request->session()->flash('warning', 'Pasien dengan NO RM ' .$request->datapasien[0]. ' berhasil dihapus');
                return redirect('tambahpasien');
            } catch (\Exception $e){
                $request->session()->flash('error', 'Pasien dengan NO RM ' .$request->datapasien[0]. ' tidak dapat dihapus');
                return redirect('tambahpasien');
            }
        }else{
            $request->session()->flash('error', 'Pasien yang akan dihapus belum dipilih');
            return redirect('tambahpasien');
        }
    }

    function ambil_pasien(Request $request){
        // dd($request->search);
        $search = $request->search;
        // ini hanya mencari kata nama, sekarang kita tambahkan pakai pekerjaan oke
        $sql = DB::table('pasien as p')->select('p.no_rkm_medis', 'p.nm_pasien', 'p.alamat')
            ->where('p.nm_pasien', 'like', '%'.$search.'%')
            ->orwhere('p.no_rkm_medis', 'like', '%'.$search.'%')
            ->orderby('p.no_rkm_medis', 'ASC')->limit(10)->get();
        
        if ($sql->isNotEmpty()) {
            $list = array();
            foreach ($sql as $key => $value) {
                $gabungan = $value->no_rkm_medis." - ".$value->nm_pasien." - ".$value->alamat ;
                $list[$key]['id'] = $value->no_rkm_medis;
                $list[$key]['text'] = $gabungan;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }
}