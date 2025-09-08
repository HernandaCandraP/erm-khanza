<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Pegawai;
use Session;

use Auth;

class PasienController extends Controller
{

    public function show(Request $request)
    {
        // dd($test);
        // select * from reg_periksa where no_rkm_medis = '221065'
        $detailpasien = DB::table('reg_periksa as a')->select('b.nm_pasien', 'b.no_rkm_medis','a.no_rawat', 'b.umur')->join('pasien as b', 'a.no_rkm_medis','=', 'b.no_rkm_medis')->where('no_rawat', $request->no_rawat)->get();
        // select b.nm_pasien, b.no_rkm_medis,a.no_rawat, b.umur from reg_periksa a, pasien b where a.no_rkm_medis = b.no_rkm_medis and no_rawat='2024/01/11/000035' limit 10;
        
        // $detailpasien = DB::table('pasien')->where('no_rkm_medis', )
        // dd($detailpasien);
        $riwayatpasien = DB::select(
            "select a.tgl_registrasi, b.nm_pasien, b.no_rkm_medis,a.no_rawat, b.umur, c.nm_poli, d.nm_dokter, e.keluhan, e.pemeriksaan
            , GROUP_CONCAT((k.pemeriksaan), ' (', j.nilai_rujukan, ') = ', j.nilai, k.satuan, '.') as lab
            from reg_periksa a 
                    left join pasien b on a.no_rkm_medis = b.no_rkm_medis
                    left join poliklinik c on c.kd_poli = a.kd_poli
                    left join dokter d on d.kd_dokter = a.kd_dokter
                    left join pemeriksaan_ralan e on e.no_rawat = a.no_rawat
                    left join diagnosa_pasien g on g.no_rawat = a.no_rawat
                    left join detail_periksa_lab j on  j.no_rawat= a.no_rawat
                    left join template_laboratorium k on k.id_template=j.id_template
            where a.no_rawat='2022/10/14/000001' GROUP BY a.no_rawat
            ");

            // $riwayatpasien = DB::select(
            //     "select a.tgl_registrasi, b.nm_pasien, b.no_rkm_medis,a.no_rawat, b.umur, c.nm_poli, d.nm_dokter, e.keluhan, e.pemeriksaan, GROUP_CONCAT(DISTINCT(f.nm_penyakit), '(', f.kd_penyakit, ').') as penyakit
            //     , GROUP_CONCAT(DISTINCT(i.nama_brng), ' (', h.jml, ').') as obat
            //     , GROUP_CONCAT((k.pemeriksaan), ' (', j.nilai_rujukan, ') = ', j.nilai, k.satuan, '.') as lab
            //     from reg_periksa a 
            //             left join pasien b on a.no_rkm_medis = b.no_rkm_medis
            //             left join poliklinik c on c.kd_poli = a.kd_poli
            //             left join dokter d on d.kd_dokter = a.kd_dokter
            //             left join pemeriksaan_ralan e on e.no_rawat = a.no_rawat
            //             left join diagnosa_pasien g on g.no_rawat = a.no_rawat
            //             left join penyakit f on f.kd_penyakit = g.kd_penyakit
            //             left join detail_pemberian_obat h on h.no_rawat = a.no_rawat
            //             left join databarang i on i.kode_brng = h.kode_brng
            //             left join detail_periksa_lab j on  j.no_rawat= a.no_rawat
            //             left join template_laboratorium k on k.id_template=j.id_template
            //     where a.no_rawat='2022/10/14/000001' GROUP BY a.no_rawat
            // ");

        // dd($riwayatpasien);

        return view('pasien/ralan', [
            'detailpasien' => $detailpasien, 
            'riwayatpasien' => $riwayatpasien, 
            // 'totalsiswa' => $totalsiswa, 
            // 'totalmapel' => $totalmapel,
            // 'pengujian_cosine' => $pengujian_cosine,
            // 'pengujian_jaccard' => $pengujian_jaccard,
        ]);
        
    }

    public function kontrol(Request $request)
    {
        // dd(1);
        if($request->tanggal != null){
            $date = date("Y-m-d", strtotime(substr($request->tanggal, 0, 10))); // date('Y-m-d'); //
            $enddate = date("Y-m-d", strtotime(substr($request->tanggal, -10)));
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }else{            
            $date = date('Y-m-d');
            $enddate = date('Y-m-d');
            $tanggalawal = date('d F Y', strtotime($date));
            $tanggalakhir = date('d F Y', strtotime($enddate));
        }

        $pasienterakhir = DB::table('reg_periksa as a')->select('a.*', 'p.nm_poli', 'b.nm_pasien', 'c.nm_dokter', 'rk.tgl_kontrol')
            ->leftjoin('pasien as b', 'a.no_rkm_medis', '=', 'b.no_rkm_medis')
            ->leftjoin('dokter as c', 'a.kd_dokter', '=', 'c.kd_dokter')
            ->leftjoin('poliklinik as p', 'a.kd_poli', '=', 'p.kd_poli')
            ->leftjoin('rencana_kontrol as rk', 'a.no_rawat', '=', 'rk.no_rawat')
            ->whereBetween('a.tgl_registrasi', [$date, $enddate])->where('a.kd_pj', 'BPJ')->orderby('a.no_rawat', 'asc')->get();

        return view('pasien/kontrol', [ 
            'pasienterakhir' => $pasienterakhir, 
            'tanggalawal' => $tanggalawal, 
            'tanggalakhir' => $tanggalakhir, 
        ]);
    }

    public function showkontrol(Request $request)
    {
        dd($request->all());
        $user = User::find($id);

        return response()->json($user);
    }

    public function create(Request $request)
    {
        $validate = $request->validate([
            'nip' => ['required', 'unique:guru,nip', 'max:22'],
            'nama_guru' => ['required', 'max:50'],
            'jenis_kelamin' => ['required'],
            'alamat' => ['max:100'],
            'no_telp' => ['max:13'],
            'level' => ['required']
        ]);

        $data = new Guru;

        $data->nip = $request->nip;
        $data->nama_guru = $request->nama_guru;
        $data->jenis_kelamin = $request->jenis_kelamin;
        $data->alamat = $request->alamat;
        $data->no_telp = $request->no_telp;
        $data->level = $request->level;
        $data->username = $request->nip;
        $data->password = bcrypt($request->nip);
        
        $data->save();
        $request->session()->flash('success', 'Berhasil Menambahkan Guru Baru: '.$request->nama_guru);
        return redirect()->route('homeadmin/guru');
    }

    public function edit($id)
    {
        $guru = Guru::find($id);

        return view('admin/guru_edit', ['guru'=>$guru]);
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'nip' => ['required', 'unique:guru,nip,'.$id.',id_guru', 'max:22'],
            'nama_guru' => ['required', 'max:50'],
            'jenis_kelamin' => ['required'],
            'username' => ['required', 'max:50'],
            'alamat' => ['max:100'],
            'no_telp' => ['max:13'],
            'level' => ['required']
        ]);

        $data = Guru::find($id);

        $data->nip = $request->nip;
        $data->nama_guru = $request->nama_guru;
        $data->jenis_kelamin = $request->jenis_kelamin;
        $data->alamat = $request->alamat;
        $data->no_telp = $request->no_telp;
        $data->level = $request->level;
        $data->username = $request->username;

        $data->save();
        $request->session()->flash('success', 'Guru: ' .$data->nama_guru. ' Berhasil Diedit');
        return redirect()->route('homeadmin/guru');
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
