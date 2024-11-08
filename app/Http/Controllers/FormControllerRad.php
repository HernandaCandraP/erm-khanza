<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;

class FormControllerRad extends Controller
{
    public function menuradiologi (Request $request){
        if($request->tanggal != null){
            $date = date("Y-m-d", strtotime(substr($request->tanggal, 0, 10))); // date('Y-m-d'); //
            $enddate = date("Y-m-d", strtotime(substr($request->tanggal, -10)));
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }else{
            $date = date("Y-m-d", strtotime("-5 day")); // date('Y-m-d'); //
            $enddate = date("Y-m-d");
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }

        $pasienrad = DB::table('periksa_radiologi as pr')->select('pr.tgl_periksa', 'p.no_rkm_medis', 'p.nm_pasien','pr.no_rawat', 'pr.jam')->distinct()
            ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'pr.no_rawat')
            ->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->whereBetween('pr.tgl_periksa', [$date, $enddate])
            // ->where('pr.tgl_periksa', '>' ,  DB::raw('CURDATE() - INTERVAL 7 DAY'))
            // ->where('pr.kd_dokter', Session::get('username'))
            ->orderBy('pr.tgl_periksa', 'DESC')
            ->get();
        
        $cekperiksa = [];
        foreach ($pasienrad as $key => $value) {
            $hasilrad = DB::table('hasil_radiologi')->where([['no_rawat', $value->no_rawat], ['tgl_periksa', $value->tgl_periksa], ['jam', $value->jam]])->first();

            $hasilrad = preg_replace('/\s+/', '', $hasilrad->hasil ?? null);
            $hasilrad = strtolower($hasilrad);
            $total = substr_count($hasilrad, 'yth');
            $cekperiksa[] = $total;
        }
        
        $hasilrad = DB::table('hasil_radiologi as hr')->select('hr.*', 'rp.kd_pj')
            ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'hr.no_rawat')
            ->whereBetween('hr.tgl_periksa', [$date, $enddate])
            ->orderBy('hr.tgl_periksa', 'DESC')
            ->get();

        $totalbacaanBPJS= 0;
        $totalbacaanUmum= 0;
        foreach($hasilrad as $key => $value) {
            $string = preg_replace('/\s+/', '', $value->hasil);
            $string = strtolower($string);
            $string2 = substr_count($string, 'yth');
            if($value->kd_pj == "BPJ"){
                $totalbacaanBPJS += $string2;
            }elseif($value->kd_pj == "A02"){
                $totalbacaanUmum += $string2;
            }
        }
        
        $totalPermintaan = DB::table('periksa_radiologi as pr')
            ->selectRaw('COUNT(CASE WHEN rp.kd_pj = "BPJ" THEN 1 END) AS BPJS')
            ->selectRaw('COUNT(CASE WHEN rp.kd_pj = "A02" THEN 1 END) AS Umum')
            ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'pr.no_rawat')
            ->leftjoin('jns_perawatan_radiologi as jpr', 'jpr.kd_jenis_prw', '=', 'pr.kd_jenis_prw')
            ->whereBetween('pr.tgl_periksa', [$date, $enddate])
            ->get();
        
        // UMUM
        if($totalbacaanUmum >= 1){
            $persetaseUmum = round(($totalbacaanUmum / $totalPermintaan[0]->Umum) * 100, 1);
            $persetaseUmum = ($persetaseUmum >= 100) ? 100 : $persetaseUmum;
        }else{
            $persetaseUmum = 0;
        }
        $bpjs[] = ['hasil' => $persetaseUmum, 
                'bacaan' =>$totalbacaanUmum, 
                'permintaan' =>$totalPermintaan[0]->Umum,
                'jenis' => 'UMUM'];
        
        // BPJS
        if($totalbacaanBPJS >= 1){
            $persetaseBPJS = round(($totalbacaanBPJS / $totalPermintaan[0]->BPJS) * 100, 1);
            $persetaseBPJS = ($persetaseBPJS >= 100) ? 100 : $persetaseBPJS;
        }else{
            $persetaseBPJS = 0;
        }
        $bpjs[] = ['hasil' => $persetaseBPJS, 
                'bacaan' =>$totalbacaanBPJS, 
                'permintaan' =>$totalPermintaan[0]->BPJS,
                'jenis' => 'BPJS'];
        
        $bpjs[] = ['hasil' => ($persetaseBPJS + $persetaseUmum) / 2, 
                'bacaan' => $totalbacaanBPJS + $totalbacaanUmum, 
                'permintaan' =>$totalPermintaan[0]->BPJS + $totalPermintaan[0]->Umum,
                'jenis' => 'TOTAL'];

        return view('formradiologi/list-rad', [
            'pasienrad' => $pasienrad,
            'cekperiksa' => $cekperiksa,
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
            'bpjs' => $bpjs, 
        ]);
    }

    public function formradiologi (Request $request){
        $pasienrad = DB::table('periksa_radiologi as pr')->select('pr.*', 'p.no_rkm_medis', 'p.umur', 'p.nm_pasien', 'jpr.kd_jenis_prw', 'jpr.nm_perawatan')
            ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'pr.no_rawat')
            ->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->leftjoin('jns_perawatan_radiologi as jpr', 'jpr.kd_jenis_prw', '=', 'pr.kd_jenis_prw')
            ->where([['pr.no_rawat', $request->no_rawat], ['pr.tgl_periksa', $request->tgl_periksa], ['pr.jam', $request->jam]])
            ->orderBy('pr.tgl_periksa', 'DESC')
            ->get();

        $hasilrad = DB::table('hasil_radiologi')
            ->where([['no_rawat', $request->no_rawat], ['tgl_periksa', $request->tgl_periksa], ['jam', $request->jam]])
            ->orderBy('tgl_periksa', 'DESC')
            ->first();

        $getgambar = DB::table('gambar_radiologi')
            ->where([['no_rawat', $request->no_rawat], ['tgl_periksa', $request->tgl_periksa], ['jam', $request->jam]])
            ->orderBy('tgl_periksa', 'DESC')
            ->get();

        $gambarrad = [];
        if($getgambar->isNotEmpty()){
            foreach ($getgambar as $key => $value) {
                $gambarrad[] = str_replace("simrs-rsba", "", env('APP_URL')) .'budiasih/radiologi/'. $value->lokasi_gambar;
            }
        }
        
        // dd($gambarrad);
        // dd(str_replace("simrs-rsba", "", env('APP_URL')));

        return view('formradiologi/form-rad', [
            'pasienrad' => $pasienrad,
            'hasilrad' => $hasilrad,
            'gambarrad' => $gambarrad
        ]);
    }

    public function create(Request $request)
    {      
        // dd($request->all());
        if($request->niplama != $request->nip && $request->action == 'update'){
            $request->session()->flash('error', "Data Tidak Dapat Disimpan, Dokter Penangung Jawab Tidak Sama");
            return redirect()->back();
        }elseif($request->niplama != $request->nip && $request->action == 'update_with_time'){
            $request->session()->flash('error', "Data Tidak Dapat Disimpan, Dokter Penangung Jawab Tidak Sama");
            return redirect()->back();
        }

        if ($request->tabel == 'PenilaianRadiologi') {
            $scores = $request->scores; 
            // dd($scores);
            foreach($scores as $row){
                DB::table('periksa_radiologi')->where(['no_rawat'=>$request->no_rawat,'tgl_periksa'=>$request->tgl_periksa,'jam'=>$request->jam,'kd_jenis_prw'=>$row['kd_jenis_prw']])
                ->update(['proyeksi'=>$row['proyeksi'], 
                    'kV'=>$row['kV'], 
                    'mAS'=>$row['mAS'], 
                    'FFD'=>$row['FFD'],
                    'BSF'=>$row['BSF'],
                    'inak'=>$row['inak'],
                    'jml_penyinaran'=>$row['jml_penyinaran'],
                    'dosis'=>$row['dosis'],
                ]);
            }

            // cek request hasilrad apakah ada?
            if($request->hasil != null){
                // simpan|update data
                DB::table('hasil_radiologi')->updateOrInsert(
                    ['no_rawat'=>$request->no_rawat,'tgl_periksa'=>$request->tgl_periksa,'jam'=>$request->jam], 
                    ['hasil'  => $request->hasil,]
                );
            } //else lewati saja
            // dd('kosong');

            $request->session()->flash('success', 'Berhasil menyimpan hasil radiologi');
            return redirect()->back()->withInput();
        }
        else{
            dd("nama tabel kosong");
        }
    }

    private function func_create(Request $request, $data, $namatabel, $route){
        // dd($request->all());
        DB::beginTransaction();
        try{            
            if($request->action == 'simpan'){
                DB::table($namatabel)->insert($data);
            }    
            elseif($request->action == 'update'){
                $dataupdate = DB::table($namatabel)->where('no_rawat','=', $data['no_rawat']);
                if($dataupdate->first() != null){
                    $dataupdate->update($data);
                }else{
                    $request->session()->flash('error', 'Belum ada data yang diupdate, silahkan simpan data');
                    return redirect()->back()->withInput();
                }
            }
            elseif($request->action == 'update_with_time'){
                if($request->tgl_perawatan_lama != "-" && $request->jam_rawat_lama !="-"){
                    $dataupdate = DB::table($namatabel)->where([['no_rawat','=', $data['no_rawat']], ['tgl_perawatan', '=',$request->tgl_perawatan_lama], ['jam_rawat', '=',$request->jam_rawat_lama]]);
                    if($dataupdate->first() != null){
                        $dataupdate->update($data);
                    }else{
                        $request->session()->flash('error', 'Belum ada data yang diupdate, silahkan simpan data');
                        return redirect()->back()->withInput();
                    }
                }else{
                    $request->session()->flash('error', 'Silahkan pilih data untuk diupdate');
                    return redirect()->back()->withInput();
                }
            }

            DB::commit();
            $request->session()->flash('success', 'Berhasil menyimpan data');
            return redirect()->route($route, ['no_rawat' => $request->no_rawat]);

        }catch(\Exception $e){
            // dd($e);
            DB::rollback(); 
            $request->session()->flash('error', 'Gagal Simpan, Sudah Ada Data Sebelumnya');
            return redirect()->back()->withInput();
        }
    }
}