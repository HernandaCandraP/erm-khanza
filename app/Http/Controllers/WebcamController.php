<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class WebcamController extends Controller
{
    public function index(){
        setlocale(LC_ALL, 'IND');
        $date = Carbon::now()->format('Y-m-d');
        $nameday = Carbon::now()->formatLocalized('%A');
        $nik = Auth::guard('pegawai')->user()->nik;
        $getid = DB::table('pegawai')->select('id', 'departemen')->where('nik', $nik)->first();

        // list jam jaga
        $jamjaga = [];
        if($getid){
            if ($getid->departemen == 'MEN' || $getid->departemen == 'IT') {
                $jamjaga = array('Kantor' => '08:00:00');
            }elseif ($getid->departemen == 'MDS') {
                $jamjaga = array('Kantor' => '08:00:00', 'Magak' => '09:00:00');
            }else{
                $jamjaga = array('Pagi' => '07:00:00', 'Siang' => '14:00:00', 'Malam' => '21:00:00');
            }
        }

        // cek rekap presensi, jika hari ini sudah terisi data maka tidak perlu ditampilkan lagi button ambil foto
        $rekappresensi = DB::table('rekap_presensi')->where('id', $getid->id)->whereDate('jam_datang', $date)->first();

        // ambil dari temporary_presensi
        $presensi = DB::table('temporary_presensi')->where('id', $getid->id)->first();

        if($presensi){
            $photopulang = substr_replace($presensi->photo, 'pulang', -4, 0);
            $presensi->photopulang = $photopulang;
        }
        if($rekappresensi){
            $photopulang = substr_replace($rekappresensi->photo, 'pulang', -4, 0);
            $rekappresensi->photopulang = $photopulang;
        }

        return view('pegawai/webcam', [
            'presensi' => $presensi, 
            'jamjaga' => $jamjaga, 
            'rekappresensi' => $rekappresensi, 
        ]);
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('pegawai')->user()->nik;
        $getid = DB::table('pegawai')->select('id')->where('nik', $nik)->first();
        $date = Carbon::now()->format('Y-m-d');
        $datetime = Carbon::now()->format('Y-m-d H:i:s');

        $this->validate($request, [
            'image' => 'required'
        ]);

        $img = $request->image;
        $folderPath = "webcam/";

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        if($request->action == 'masuk'){
            $shift = $this->daftarshift($request->jamjaga);

            $fileName = $date. $shift . $getid->id . '.png';
            $file = $folderPath . $fileName;
            file_put_contents($file, $image_base64);

            // cek keterlambatan 
            $date_start = $date.' '.$request->jamjaga;
            if(Carbon::parse($date_start) >= Carbon::now()){
                $status = 'Tepat Waktu';
                $keterlambatan = '';
            }else{
                $status = 'Terlambat I';
                // cek lamanya
                $jam_datang = date_create($date_start);
                $jam_pulang = date_create($datetime);
                $keterlambatan = date_diff($jam_datang, $jam_pulang)->format("%H:%I:%S");
            }

            $presensidatang = [
                'id' => $getid->id,
                'shift' => $shift,
                'jam_datang' => $datetime,
                'status' => $status,
                'keterlambatan' => $keterlambatan,
                'photo' => $fileName,
            ];
            DB::table('temporary_presensi')->insert($presensidatang);
            $request->session()->flash('success', 'Berhasil menyimpan presensi datang');
        }elseif($request->action == 'pulang'){
            $presensi = DB::table('temporary_presensi')->where('id', $getid->id)->first();

            // Ambil durasi
            $jam_datang = date_create($presensi->jam_datang);
            $jam_pulang = date_create($datetime);
            $durasi = date_diff($jam_datang, $jam_pulang);
            // dd($durasi->format("%H:%I:%S"));
            
            $fileName = $date. $presensi->shift. $getid->id . 'pulang.png';
            $file = $folderPath . $fileName;
            file_put_contents($file, $image_base64);

            // simpan data di rekap presensi
            $presensipulang = [
                'id' => $getid->id,
                'shift' => $presensi->shift,
                'jam_datang' => $presensi->jam_datang,
                'jam_pulang' => $datetime,
                'status' => $presensi->status,
                'keterlambatan' => $presensi->keterlambatan,
                'durasi' => $durasi->format("%H:%I:%S"),
                'photo' => $presensi->photo,
            ];
            DB::table('rekap_presensi')->insert($presensipulang);

            // hapus temporary jika rekap sudah terisi
            DB::table('temporary_presensi')->where('id', $getid->id)->delete();
            $request->session()->flash('success', 'Berhasil menyimpan presensi pulang');
        }
        return redirect()->back();
    }

    public function rekap(){
        // ambil range bulan
        setlocale(LC_ALL, 'IND');
        $start = new Carbon('first day of this month');
        $end = new Carbon('last day of this month');
        $rangedate = array("start" => $start->formatLocalized('%d %B %Y'), "end" => $end->formatLocalized('%d %B %Y'));

        $month = Carbon::now()->format('m');
        $nameday = Carbon::now()->formatLocalized('%A');
        $nik = Auth::guard('pegawai')->user()->nik;
        $getid = DB::table('pegawai')->select('id')->where('nik', $nik)->first();
        $presensi = DB::table('rekap_presensi')
            ->select(DB::raw("count(jam_datang) as totaldatang"), DB::raw('count(jam_pulang) as totalpulang'))
            ->where('id', $getid->id)->whereMonth('jam_datang', $month)->get();

        return view('pegawai/rekapabsensi', [
            'rangedate' => $rangedate, 
            'presensi' => $presensi, 
        ]);
    }

    public function rekapadmin(){
        $pegawai = DB::table('rekap_presensi as rp')
            ->select('p.id', 'p.nama', 'p.jbtn')
            ->join('pegawai as p', 'p.id','=', 'rp.id')
            ->groupBy('rp.id')->get();

        return view('pegawai/rekapadmin', [
            'pegawai' => $pegawai, 
        ]);
    }

    public function detailrekapadmin(Request $request){
        if($request->tanggal != null){
            $date = date("Y-m-d", strtotime(substr($request->tanggal, 0, 10))); // date('Y-m-d'); //
            $enddate = date("Y-m-d", strtotime(substr($request->tanggal, -10)));
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }else{
            $start = new Carbon('first day of this month');
            $end = new Carbon('last day of this month');
            
            $date = $start->format("Y-m-d");
            $enddate = $end->format("Y-m-d");
            $tanggalawal = $start->formatLocalized('%d %B %Y');
            $tanggalakhir = $end->formatLocalized('%d %B %Y');
        }

        $pegawai = DB::table('rekap_presensi as rp')
            ->select('p.id', 'p.nama', 'p.jbtn', 'rp.*')
            ->join('pegawai as p', 'p.id','=', 'rp.id')
            ->where('rp.id', $request->id)
            ->whereBetween('rp.jam_datang', [$date, $enddate])
            ->get();
        
        if($pegawai){
            foreach ($pegawai as $key => $value) {
                $photopulang = substr_replace($value->photo, 'pulang', -4, 0);
                $pegawai[$key]->photopulang = $photopulang;
            }
        }
        // dd($pegawai, $date, $enddate);
        return view('pegawai/detailrekapadmin', [
            'pegawai' => $pegawai, 
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
            'id' => $request->id, 
        ]);
    }

    private function daftarshift($shift){
        if($shift == '07:00:00'){
            $namashift = 'Pagi';
        }elseif($shift == '08:00:00'){
            $namashift = 'Pagi2';
        }elseif($shift == '09:00:00'){
            $namashift = 'Pagi3';
        }elseif($shift == '11:00:00'){
            $namashift = 'Siang';
        }elseif($shift == '14:00:00'){
            $namashift = 'Siang2';
        }elseif($shift == '21:00:00'){
            $namashift = 'Malam';
        }else{
            $namashift = 'Malam2';
        }
        return $namashift;
    }
}