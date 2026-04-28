<?php

namespace App\Classes;
use DB;

class Sekuel{
    public function getAlergi($no_rawat){
        // tampilkan alergi di soap
        $alergi = '';
        // ambil riwayat alergi
        $riwayatalergi = DB::table('alergi_pasien as a')->select('r.no_rkm_medis', 'a.note')
            ->leftjoin('reg_periksa as r', 'r.no_rawat','=', 'a.no_rawat')
            ->where('r.no_rawat', $no_rawat)->get();
        if($riwayatalergi != null){
            foreach ($riwayatalergi as $key => $value) {
                $alergi .= $value->note;
            }
        }
        return $alergi;
    }

    public function getResep($norm){ 
        // ambil obat terakhir
        $listnorawat = DB::table('reg_periksa')->select('no_rawat')
            ->where('no_rkm_medis', $norm)
            ->where('kd_poli','!=','IGDK')
            ->orderby('tgl_registrasi', 'DESC')->limit(10)->get();
    
        $arraylistnorawat = [];
        foreach ($listnorawat as $key => $value) {
            array_push($arraylistnorawat, $value->no_rawat);
        }         
        
        // ambil seluruh riwayat resep
        $datalistnorawatlengkap = DB::table('resep_obat as ro')->select('ro.no_resep', 'ro.no_rawat', 'ro.status', 'rd.*', 'db.nama_brng', 'db.letak_barang', 'db.ralan', 'gb.stok')
            ->join('resep_dokter as rd', 'rd.no_resep','=', 'ro.no_resep')
            ->leftjoin('databarang as db', 'db.kode_brng', '=', 'rd.kode_brng')
            ->leftjoin('gudangbarang as gb', 'gb.kode_brng','=', 'rd.kode_brng')
            ->where('gb.stok', '>', '0')
            ->orderby('ro.no_resep', 'DESC')
            ->where('ro.status', 'ralan')
            ->whereIn('ro.no_rawat', $arraylistnorawat)->get();

        // ambil total obat per resep untuk menghitung colspan
        $datalistnorawatperresep = DB::table('resep_obat as ro')->select('ro.no_resep','dr.nm_dokter', DB::raw('count(*) as total'))
            ->join('resep_dokter as rd', 'rd.no_resep','=', 'ro.no_resep')
            ->leftjoin('dokter as dr', 'dr.kd_dokter','=', 'ro.kd_dokter')
            ->orderby('ro.no_resep', 'DESC')
            ->groupby('ro.no_resep')
            ->where('ro.status', 'ralan')
            ->whereIn('ro.no_rawat', $arraylistnorawat)->get();

        return([$datalistnorawatlengkap, $datalistnorawatperresep]);
    }

    public function riwayatlab($no_rawat){
        $riwayatlab = DB::table('periksa_lab')
            ->select('jns_perawatan_lab.kd_jenis_prw','jns_perawatan_lab.nm_perawatan','periksa_lab.biaya', 'periksa_lab.tgl_periksa' ,'periksa_lab.jam')
            ->join('jns_perawatan_lab', 'periksa_lab.kd_jenis_prw', '=', 'jns_perawatan_lab.kd_jenis_prw')
            ->where('periksa_lab.no_rawat', $no_rawat)
            ->where('periksa_lab.kategori', 'PK')
            ->orderBy('periksa_lab.tgl_periksa', 'DESC')
            ->get();

        $riwayatdelatillab = DB::table('detail_periksa_lab')
            ->select('template_laboratorium.pemeriksaan','detail_periksa_lab.nilai','template_laboratorium.satuan','detail_periksa_lab.nilai_rujukan','detail_periksa_lab.kd_jenis_prw')
            ->join('template_laboratorium', 'detail_periksa_lab.id_template', '=', 'template_laboratorium.id_template')
            ->where('detail_periksa_lab.no_rawat', $no_rawat)
            ->get();

        return array($riwayatlab, $riwayatdelatillab);
    }

    public function riwayatrad($no_rawat){
        $pasienrad = DB::table('periksa_radiologi as pr')->select('jpr.kd_jenis_prw', 'jpr.nm_perawatan', 'pr.tgl_periksa', 'pr.jam')
            ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'pr.no_rawat')
            ->leftjoin('jns_perawatan_radiologi as jpr', 'jpr.kd_jenis_prw', '=', 'pr.kd_jenis_prw')
            ->where('pr.no_rawat', $no_rawat)
            ->orderBy('pr.tgl_periksa', 'DESC')
            ->get();

        $hasilrad = DB::table('hasil_radiologi')
            ->where('no_rawat', $no_rawat)
            ->orderBy('tgl_periksa', 'DESC')
            ->get();

        $getgambar = DB::table('gambar_radiologi')
            ->where('no_rawat', $no_rawat)
            ->orderBy('tgl_periksa', 'DESC')
            ->get();

        if($getgambar->isNotEmpty()){
            foreach ($getgambar as $key => $value) {
                $getgambar[$key]->lokasi_gambar = str_replace("simrs-rsba", "", env('APP_URL')) .'budiasih/radiologi/'. $value->lokasi_gambar;
            }
        }

        $grouprad = DB::table('periksa_radiologi as pr')->select('pr.tgl_periksa', 'pr.jam')
            ->where('pr.no_rawat', $no_rawat)
            ->orderBy('pr.tgl_periksa', 'ASC')
            ->groupBy('pr.tgl_periksa', 'pr.jam')
            ->get();

        return array($pasienrad, $hasilrad, $getgambar, $grouprad);
    }
}

?>