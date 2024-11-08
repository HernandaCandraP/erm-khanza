<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Pegawai;
use App\Rules\MatchOldPasswordPegawai;
use Session;

use Auth;

class PetugasController extends Controller
{
    public function index(){
        $pegawai = "hello";

        return view('pegawai/home', [
            // 'detailmapel' => $detailmapel, 
            // 'totalkelas' => $totalkelas, 
            // 'totalsiswa' => $totalsiswa, 
            // 'totalmapel' => $totalmapel,
            // 'pengujian_cosine' => $pengujian_cosine,
            // 'pengujian_jaccard' => $pengujian_jaccard,
            'pegawai' => $pegawai, 
        ]);
    }


    public function antrean(){
        $username = Session::get('username');
        $month =  date('Y-m'); //'2023-06'; //
        $date = date('Y-m-d'); //'2023-06-15'; // 
          

        $antrean = DB::table('reg_periksa as a')->select('a.no_rawat', 'b.no_rkm_medis', 'b.nm_pasien', 'mb.status', 'mb.dikirim', 'mb.diterima','mb.kembali')
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->leftjoin('mutasi_berkas as mb', 'mb.no_rawat', '=', 'a.no_rawat')
            ->where('a.tgl_registrasi', $date)
            ->orderby('a.jam_reg', 'ASC')->get();

        // dd($pasienranap);


        return view('petugas/antrean-rajal', [
            'antrean' => $antrean, 
        ]);
    }

    public function persentase(Request $request){
        // $listdokter = DB::table('reg_periksa as a');
        $polibulanperdokterinput = DB::table('reg_periksa as a')->select('a.*', DB::raw('count(*) as jumlah'))
            ->leftjoin('pemeriksaan_ralan as pr', 'a.no_rawat','=', 'pr.no_rawat')
            ->leftjoin('penilaian_medis_ralan_bedah as prb', 'a.no_rawat','=', 'prb.no_rawat')
            ->leftjoin('penilaian_medis_ralan_paru as prp', 'a.no_rawat','=', 'prp.no_rawat')
            ->leftjoin('penilaian_medis_ralan_kandungan as prk', 'a.no_rawat','=', 'prk.no_rawat')
            ->leftjoin('penilaian_medis_ralan_neurologi as prn', 'a.no_rawat','=', 'prn.no_rawat')
            ->leftjoin('penilaian_medis_ralan_anak as pra', 'a.no_rawat','=', 'pra.no_rawat')
            ->leftjoin('penilaian_medis_ralan_mata as prm', 'a.no_rawat','=', 'prm.no_rawat')
            ->leftjoin('penilaian_medis_ralan_penyakit_dalam as prd', 'a.no_rawat','=', 'prd.no_rawat')
            ->leftjoin('penilaian_medis_ralan as pru', 'a.no_rawat','=', 'pru.no_rawat')
            ->leftjoin('penilaian_medis_igd as pmi', 'a.no_rawat','=', 'pmi.no_rawat')
            ->where('a.kd_dokter', $username)
            ->where(function ($query) use ($username) {
                $query->where('pr.nip', $username)
                    ->orWhere('prb.kd_dokter', $username)
                    ->orWhere('prp.kd_dokter', $username)
                    ->orWhere('prk.kd_dokter', $username)
                    ->orWhere('prn.kd_dokter', $username)
                    ->orWhere('pra.kd_dokter', $username)
                    ->orWhere('prm.kd_dokter', $username)
                    ->orWhere('prd.kd_dokter', $username)
                    ->orWhere('pru.kd_dokter', $username)
                    ->orWhere('pmi.kd_dokter', $username);
                })
            ->where('a.tgl_registrasi', 'like', '%'.$month.'%')
            ->groupBy('a.no_rawat') 
            ->get();

        $polibulanperdokter = DB::table('reg_periksa as a')->select('a.*', DB::raw('count(*) as jumlah'))
            ->leftjoin('pemeriksaan_ralan as pr', 'a.no_rawat','=', 'pr.no_rawat')
            ->leftjoin('penilaian_medis_ralan_bedah as prb', 'a.no_rawat','=', 'prb.no_rawat')
            ->leftjoin('penilaian_medis_ralan_paru as prp', 'a.no_rawat','=', 'prp.no_rawat')
            ->leftjoin('penilaian_medis_ralan_kandungan as prk', 'a.no_rawat','=', 'prk.no_rawat')
            ->leftjoin('penilaian_medis_ralan_neurologi as prn', 'a.no_rawat','=', 'prn.no_rawat')
            ->leftjoin('penilaian_medis_ralan_anak as pra', 'a.no_rawat','=', 'pra.no_rawat')
            ->leftjoin('penilaian_medis_ralan_mata as prm', 'a.no_rawat','=', 'prm.no_rawat')
            ->leftjoin('penilaian_medis_ralan_penyakit_dalam as prd', 'a.no_rawat','=', 'prd.no_rawat')
            ->leftjoin('penilaian_medis_ralan as pru', 'a.no_rawat','=', 'pru.no_rawat')
            ->leftjoin('penilaian_medis_igd as pmi', 'a.no_rawat','=', 'pmi.no_rawat')
            ->where('a.kd_dokter', $username)
            ->where('a.tgl_registrasi', 'like', '%'.$month.'%')
            ->groupBy('a.no_rawat') 
            ->get();
        dd($polibulanperdokter->count(), $polibulanperdokterinput->count());
    }

    public function updateantrean(Request $request)
    {
        $jarakwaktuakhir = 1200; //20mnt
        $jarakwaktuawal = 480; //8mnt
        $getdatetime = date('Y-m-d H:i:s');
        
        $inttime = strtotime($getdatetime);
        // $inttime = strtotime('2012-03-27 18:47:00');

        $asli = date("Y-m-d H:i:s",$inttime);
        $stringtime = date("Y-m-d H:i:s",($inttime - $jarakwaktuakhir)); 
        $awal = date("Y-m-d H:i:s",($inttime - $jarakwaktuawal));
        $random= mt_rand(($inttime - $jarakwaktuakhir),($inttime - $jarakwaktuawal));
        $stringrandom = date("Y-m-d H:i:s",$random);

        $random2= mt_rand(($random - $jarakwaktuakhir),($random - $jarakwaktuawal));
        $stringrandom2 = date("Y-m-d H:i:s",$random2);

        $antrean = DB::table('mutasi_berkas')->where('no_rawat', $request->no_rawat)->first();

        // dd($antrean);
        // dd($request->all());
        // dd($asli, $awal, $stringtime, $stringrandom);
        if($request->status == 'taskid3'){
            // dd("task id 3");
            DB::table('mutasi_berkas')->where('no_rawat', $request->no_rawat)->update(['dikirim' => $getdatetime]);
        }elseif($request->status == 'taskid4'){
            // dd("task id 4");
            // jika task id 3 masih kosong
            if($antrean->dikirim == '0000-00-00 00:00:00'){
                DB::table('mutasi_berkas')->where('no_rawat', $request->no_rawat)->update(['dikirim' => $stringrandom]);
            }
            DB::table('mutasi_berkas')->where('no_rawat', $request->no_rawat)->update(['diterima' => $getdatetime, 'status'=>'Sudah Diterima']);
        }elseif($request->status == 'taskid5'){
            // dd("task id 5");
            // jika task id 4 masih kosong
            if($antrean->dikirim == '0000-00-00 00:00:00'){
                DB::table('mutasi_berkas')->where('no_rawat', $request->no_rawat)->update(['diterima' => $stringrandom]);
            }
            // jika task id 3 masih kosong
            if($antrean->dikirim == '0000-00-00 00:00:00'){
                DB::table('mutasi_berkas')->where('no_rawat', $request->no_rawat)->update(['dikirim' => $stringrandom2]);
            }
            DB::table('mutasi_berkas')->where('no_rawat', $request->no_rawat)->update(['kembali' => $getdatetime]);
        }
        return redirect()->back();
    }

    public function profile(){ 
        $pegawai = Auth::guard('pegawai')->user();
        return view('admin/profile_detail', ['pegawai' => $pegawai]);
    }

    public function gantipassword(Request $request){
        $validate = $request->validate([
            'password_lama' => ['required', 'min:3', 'max:20', new MatchOldPasswordPegawai],
            'password_baru' => ['required' , 'min:3', 'max:20'],
            'ulangi_password_baru' => ['required', 'min:3', 'same:password_baru', 'max:20'],
        ]);

        // proses ubah password
        $data = Pegawai::find(auth()->guard('pegawai')->user()->id);
        $data->password = bcrypt($request->password_baru);
        $data->save();

        $request->session()->flash('success', 'Password Berhasil Diganti');
        return redirect()->back();
    }

    public function reset(Request $request, $id)
    {
        $data = Guru::find($id);
        $data->password = bcrypt($data->nip);
        
        $data->save();
        $request->session()->flash('success', 'Berhasil Reset Password untuk Guru ' .$data->nama_guru);
        return redirect()->back();
    }

    public function delete(Request $request, $id)
    {
        $data = Guru::find($id);
        // cek apakah ada relasi ke tabel lain
        try{
            $data->delete();
            $request->session()->flash('warning', 'Guru: ' .$data->nama_guru. ' Berhasil Dihapus');
            return redirect()->back();
        } catch (\Exception $e){
            $request->session()->flash('error', 'Guru: ' .$data->nama_guru. ' Tidak Dapat Dihapus');
            return redirect()->back();
        }
    }
}
