<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use DB;
use App\Guru;
use App\Siswa;
use App\Kelas;
use App\Mapel;
use App\DetailMapel;
use App\Rules\MatchOldPasswordGuru;
use App\Classes\Pengujian;

use Auth;

class IndikatorMutuController extends Controller
{
    public function indikatormutu(){

        dd();

        // $pengujian_jaccard = $pengujian->pengujian_jaccard($data_jaccard);

        // return view('guru/home', [
        //     'pegawai' => $pegawai, 
        // ]);
    }

    public function kebersihanTangan()
    {
        // Untuk testing, tampilkan data pasien terakhir
        $datamutu = DB::table('mutu_nasional')
            ->orderBy('tahun', 'desc')
            ->where('detail_mutu', 'kebersihan-tangan')
            ->get();

            // dd($datamutu);
        // return view('indikatormutu.kebersihan_tangan', compact('datamutu'));
        return view('indikatormutu.kebersihan_tangan', [
            'datamutu' => $datamutu,
        ]);
        // Jika ada data yang ingin ditampilkan, bisa ditambahkan di sini
        //`
    // return view('indikatormutu.kebersihan_tangan');
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
    // hapus data berdasarkan id
    DB::table('mutu_nasional')->where('id', $id)->delete();

    // kembali ke halaman sebelumnya
    return redirect()->back();
    }

    public function destroy($id)
    {

    DB::table('mutu_nasional')->where('id', $id)->delete();
    return redirect()->back()->with('success', 'Data berhasil dihapus!');

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
    // Validasi input
    $request->validate([
        'tahun' => 'required|numeric|min:2020|max:2100',
        'bulan' => 'required|string',
        'numerator' => 'required|numeric',
        'denumerator' => 'required|numeric',
    ]);

    // Update data
    DB::table('mutu_nasional')
        ->where('id', $id)
        ->update([
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'numerator' => $request->numerator,
            'denumerator' => $request->denumerator,
            'detail_mutu' => $request->detail_mutu,
        ]);

    // Redirect ke halaman sebelumnya
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

    //public function identifikasiPasienStore(Request $request)
    //{

    //return back()->with('hasil', $request->all());
    
    //}

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

    //public function seksioEmergensiStore(Request $request)
    //{

    //return back()->with('hasil', $request->all());

    //}

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

    //public function waktuTungguRajalStore(Request $request)
    //{

    //return back()->with('hasil', $request->all());
    
    //}

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

    //public function penundaanOperasiStore(Request $request)
    //{

    //return back()->with('hasil', $request->all());
    
    //}

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

    //public function visiteDokterStore(Request $request)
    //{

    //return back()->with('hasil', $request->all());
    
    //}

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

    //public function hasilKritisLabStore(Request $request)
    //{

    //return back()->with('hasil', $request->all());
    
    //}

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

    //public function formulariumStore(Request $request)
    //{

    //return back()->with('hasil', $request->all());
    
    //}

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

    //public function pencegahanJatuhStore(Request $request)
    //{

    //return back()->with('hasil', $request->all());

    //}

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

    //public function clinicalPathwayStore(Request $request)
    //{

    //return back()->with('hasil', $request->all());

    //}

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

    //public function tanggapKomplainStore(Request $request)
    //{

    //return back()->with('hasil', $request->all());
    
    //}

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

    //public function kepuasanPasienStore(Request $request)
    //{

    //return back()->with('hasil', $request->all());
    
    //}

}