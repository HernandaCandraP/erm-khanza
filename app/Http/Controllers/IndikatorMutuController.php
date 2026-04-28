<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use DB;
use Carbon\Carbon;


use Auth;

class IndikatorMutuController extends Controller
{
    public function indikatormutu(){

        dd();

    }

    public function formmpp(Request $request){
        // dd($request->all());
        if($request->tanggal != null){
            $date = date("Y-m-d", strtotime(substr($request->tanggal, 0, 10))); // date('Y-m-d'); //
            $enddate = date("Y-m-d", strtotime(substr($request->tanggal, -10)));
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }else{
            $date = new Carbon('first day of this month');
            $enddate = new Carbon('last day of this month');
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }

        $pasienranap = DB::table('kamar_inap as ki')->select('ki.*', 'p.no_rkm_medis', 'p.nm_pasien', 'b.nm_bangsal', 'rg.no_rawat2', 'p2.no_rkm_medis as no_rkm_medis2', 'p2.nm_pasien as nm_pasien2')
            ->leftjoin('reg_periksa as rp', 'rp.no_rawat', '=', 'ki.no_rawat')
            ->leftjoin('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->leftjoin('kamar as k', 'k.kd_kamar', '=', 'ki.kd_kamar')
            ->leftjoin('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
            ->leftjoin('dpjp_ranap as dr', 'dr.no_rawat', '=', 'ki.no_rawat')
            ->leftjoin('ranap_gabung as rg', 'rg.no_rawat', '=', 'ki.no_rawat')
            ->leftjoin('reg_periksa as rp2', 'rp2.no_rawat', '=', 'rg.no_rawat2')
            ->leftjoin('pasien as p2', 'p2.no_rkm_medis', '=', 'rp2.no_rkm_medis')
            ->whereBetween('ki.tgl_masuk', [$date, $enddate])
            ->orderby('ki.tgl_masuk', 'DESC')
            ->get();

        return view('erm/list-mpp', [
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
            'pasienranap' => $pasienranap, 
        ]);
    }

    public function kebersihanTangan()
    {
        $datamutu = DB::table('mutu_nasional')
            ->orderBy('tahun', 'desc')
            ->where('detail_mutu', 'kebersihan-tangan')
            ->get();

        return view('indikatormutu.kebersihan_tangan', [
            'datamutu' => $datamutu,
        ]);
    }

    public function simpanmutu(Request $request)
    {
        DB::table('mutu_nasional')->insert([
		'tahun' => $request->tahun,
		'bulan' => $request->bulan,
		'numerator' => $request->numerator,
		'denumerator' => $request->denumerator,
        'detail_mutu' => $request->detail_mutu,
	]);

        return redirect()->back();
    }

    public function hapus($id): RedirectResponse
    {

        DB::table('mutu_nasional')->where('id', $id)->delete();

            return redirect()->back();

    }

    public function destroy($id)
    {

    DB::table('mutu_nasional')->where('id', $id)->delete();
    return redirect()->back()->with('success', 'Data berhasil dihapus');
    
    }

    public function edit($id)
    {
        $editmutu = DB::table('mutu_nasional')->where('id', $id)->first();

        return view('indikatormutu.edit', [
            'editmutu' => $editmutu,
        ]);
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'tahun' => 'required|numeric|min:2020|max:2100',
        'bulan' => 'required|string',
        'numerator' => 'required|numeric',
        'denumerator' => 'required|numeric',
    ]);

    DB::table('mutu_nasional')
        ->where('id', $id)
        ->update([
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'numerator' => $request->numerator,
            'denumerator' => $request->denumerator,
            'detail_mutu' => $request->detail_mutu,
        ]);

    return redirect($request->redirect_url)
           ->with('success', 'Data berhasil diperbarui!');
}


    public function apd()
    {
        $datamutu = DB::table('mutu_nasional')
            ->orderBy('tahun', 'desc')
            ->where('detail_mutu', 'apd')
            ->get();

        return view('indikatormutu.apd', [
            'datamutu' => $datamutu, 
        ]);

    }



    public function identifikasiPasien()
    {
         $datamutu = DB::table('mutu_nasional')
            ->orderBy('tahun', 'desc')
            ->where('detail_mutu', 'identifikasi-pasien')
            ->get();

    return view('indikatormutu.identifikasi_pasien', [
        'datamutu' => $datamutu,
    ]);

    }

    public function seksioEmergensi()
    {
        $datamutu = DB::table('mutu_nasional')
            ->orderBy('tahun', 'desc')
            ->where('detail_mutu', 'seksio-emergensi')
            ->get();

    return view('indikatormutu.seksio_emergensi', [
        'datamutu' => $datamutu,
    ]);

    }

    public function waktuTungguRajal()
    {
        $datamutu = DB::table('mutu_nasional')
            ->orderBy('tahun', 'desc')
            ->where('detail_mutu', 'waktu-tunggu-rajal')
            ->get();

        return view('indikatormutu.waktu_tunggu_rajal', [
            'datamutu' => $datamutu,
        ]);

    }

    public function penundaanOperasi()
    {
        $datamutu = DB::table('mutu_nasional')
            ->orderBy('tahun', 'desc')
            ->where('detail_mutu', 'penundaan-operasi')
            ->get();

        return view('indikatormutu.penundaan_operasi', [
            'datamutu' => $datamutu
        ]);

    }

    public function visiteDokter()
    {
        $datamutu = DB::table('mutu_nasional')
            ->orderBy('tahun', 'desc')
            ->where('detail_mutu', 'visite-dokter')
            ->get();

        return view('indikatormutu.visite_dokter', [
            'datamutu' => $datamutu
        ]);

    }

    public function hasilKritisLab()
    {
        $datamutu = DB::table('mutu_nasional')
            ->orderBy('tahun', 'desc')
            ->where('detail_mutu', 'hasil-kritis-lab')
            ->get();

        return view('indikatormutu.hasil_kritis_lab', [
            'datamutu' => $datamutu
        ]);

    }

    public function formularium()
    {
        $datamutu = DB::table('mutu_nasional')
            ->orderBy('tahun', 'desc')
            ->where('detail_mutu', 'formularium')
            ->get();

        return view('indikatormutu.formularium', [
            'datamutu' => $datamutu
        ]);

    }

    public function pencegahanJatuh()
    {
    $datamutu = DB::table('mutu_nasional')
            ->orderBy('tahun', 'desc')
            ->where('detail_mutu', 'pencegahan-jatuh')
            ->get();

    return view('indikatormutu.pencegahan_jatuh', [
        'datamutu' => $datamutu
    ]);

    }

    public function clinicalPathway()
    {
        $datamutu = DB::table('mutu_nasional')
            ->orderBy('tahun', 'desc')
            ->where('detail_mutu', 'clinical-pathway')
            ->get();

        return view('indikatormutu.clinical_pathway', [
            'datamutu' =>$datamutu
        ]);

    }

    public function tanggapKomplain()
    {

        $datamutu = DB::table('mutu_nasional')
            ->orderBy('tahun', 'desc')
            ->where('detail_mutu', 'tanggap-komplain')
            ->get();

    return view('indikatormutu.tanggap_komplain', [
        'datamutu' => $datamutu
    ]);

    }

    public function kepuasanPasien()
    {
        $datamutu = DB::table('mutu_nasional')
            ->orderBy('tahun', 'desc')
            ->where('detail_mutu', 'kepuasan-pasien')
            ->get();

        return view('indikatormutu.kepuasan_pasien', [
            'datamutu' => $datamutu
        ]);

    }

}