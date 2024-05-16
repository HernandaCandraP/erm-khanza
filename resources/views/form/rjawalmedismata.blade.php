<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

@include('template.navbar')

@include('template.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="content-header"></div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          @include('template.menudokter', ['no_rawat' => $no_rawat, 'specialis' => Session::get('getspecialis')])

          <div class="col-md-12 mt-4">
            <!-- Horizontal Form -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">::[ Penilaian Awal Medis Rawat Jalan Mata ]::</h3>
              </div>
              <!-- /.card-header -->
              <form class="form-horizontal">
                <div class="card-header">
                  <div class="row text-sm"><div class="col-sm-6">
                    <div class="row">
                      <div class="col-5 text-right"><b>Nama</b></div>
                      <div class="col-7">&nbsp; {{$detailpasien[0]->nm_pasien}}</div>
                      <div class="col-5 text-right"><b>No. RM</b></div>
                      <div class="col-7">&nbsp; {{$detailpasien[0]->no_rkm_medis}}</div>
                      <div class="col-5 text-right"><b>No. Rawat</b></div>
                      <div class="col-7">&nbsp; {{$detailpasien[0]->no_rawat}}</div>
                      <div class="col-5 text-right"><b>Umur</b></div>
                      <div class="col-7">&nbsp; {{$detailpasien[0]->umur}}</div>
                    </div>
                  </div>
                  <div class="col-sm-6 pt-3">
                    <a href="{{ route('form/listdata', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'PenilaianAwalMedisRawatJalanMata']) }}" class="btn btn-warning btn-sm">List Penilaian Rawat Jalan Mata</a>
                  </div>
                  </div>
                </div>
              </form>
              @include('template.alert')
              <!-- form start -->
              <form action="{{route('form/tambah')}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div  class="card-body">
                  <div class="form-group row text-sm">
                    <div class="form-inline row">
                      <div class="col-xs-6 pt-1">&emsp;&emsp; Dokter &emsp;: {{$penilaianralanmata->nama}}</div>
                      <input type="text" name="no_rawat" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rawat}}" hidden>
                      <input type="text" name="nip" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                      <input type="text" name="niplama" class="form-control form-control-sm" value="{{ $penilaianralanmata->kd_dokter }}" hidden>
                      <input type="text" name="tabel" class="form-control form-control-sm" value="PenilaianAwalMedisRawatJalanMata" hidden>
                    </div>

                    <div class="form-inline row">
                      <div class="col-xs-6 pt-1">&emsp;&emsp; Tanggal &emsp;</div>
                      <div class="col-xs-6 pt-1">
                        <input type="datetime-local" name="tanggal" class="form-control form-control-sm" placeholder="Waktu Selesai" value="{{Carbon\Carbon::now()->format('Y-m-d H:i:s')}}">
                      </div>
                    </div>

                    <div class="form-inline row">
                      <div class="col-xs-4 pt-1">&emsp;&emsp;Anamnesis &emsp;</div>
                      <div class="col-xs-4 pt-1">
                        <select class="form-control form-control-sm" name="anamnesis">
                          <option value="Autoanamnesis" {{ old('anamnesis', $penilaianralanmata->anamnesis) == 'Autoanamnesis' ? 'selected':'' }}>Autoanamnesis</option>
                          <option value="Alloanamnesis" {{ old('anamnesis', $penilaianralanmata->anamnesis) == 'Alloanamnesis' ? 'selected':'' }}>Alloanamnesis</option>
                        </select>
                      </div>
                      <div class="col-xs-2">
                        <input type="text" name="hubungan" class="form-control form-control-sm" autocomplete="off" value="{{ old('hubungan', $penilaianralanmata->hubungan) }}">
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>I. RIWAYAT KESEHATAN</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group row text-sm"> 
                        <div class="col-sm-6"> 
                          <div class="form-inline">Keluhan Utama : 
                            <textarea class="form-control form-control-sm" rows="2" name="keluhan_utama" autocomplete="off" style="width: 100%">{{ old('keluhan_utama', $penilaianralanmata->keluhan_utama) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Penggunaan Obat : 
                            <textarea class="form-control form-control-sm" rows="2" name="rpo" autocomplete="off" style="width: 100%">{{ old('rpo', $penilaianralanmata->rpo) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-inline">Riwayat Penyakit Sekarang : 
                            <textarea class="form-control form-control-sm" rows="1" name="rps" autocomplete="off" style="width: 100%">{{ old('rps', $penilaianralanmata->rps) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Penyakit Dahulu : 
                            <textarea class="form-control form-control-sm" rows="1" name="rpd" autocomplete="off" style="width: 100%">{{ old('rpd', $penilaianralanmata->rpd) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Alergi :  
                            <input type="text" name="alergi" class="form-control form-control-sm" autocomplete="off" style="width: 70%" value="{{ old('alergi', $penilaianralanmata->alergi) }}">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>II. Pemeriksaan Fisik</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="row text-sm">
                        <div class="col-sm-2"> 
                          TD
                          <div class="input-group-append" >
                            <input type="text" name="td" class="form-control form-control-sm" style="width: 80%" value="{{ old('td', $penilaianralanmata->td) }}">
                            <span class="input-group-text text-sm">mmHg</span>
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          BB
                          <div class="input-group-append" >
                            <input type="text" name="bb" class="form-control form-control-sm" style="width: 80%" value="{{ old('bb', $penilaianralanmata->bb) }}">
                            <span class="input-group-text text-sm">Kg</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          Suhu
                          <div class="input-group-append" >
                            <input type="text" name="suhu" class="form-control form-control-sm" style="width: 80%" value="{{ old('suhu', $penilaianralanmata->suhu) }}">
                            <span class="input-group-text text-sm">°C</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          Nadi
                          <div class="input-group-append" >
                            <input type="text" name="nadi" class="form-control form-control-sm" style="width: 80%" value="{{ old('nadi', $penilaianralanmata->nadi) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          RR
                          <div class="input-group-append" >
                            <input type="text" name="rr" class="form-control form-control-sm" style="width: 80%" value="{{ old('rr', $penilaianralanmata->rr) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-6 pt-2">
                          Nyeri : 
                          <input type="text" name="nyeri" class="form-control form-control-sm" value="{{ old('nyeri', $penilaianralanmata->nyeri) }}">
                        </div>
                        <div class="col-sm-6 pt-2">
                          Status Nutrisi : 
                          <input type="text" name="status" class="form-control form-control-sm" value="{{ old('status', $penilaianralanmata->status) }}">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>III. STATUS OFTAMOLOGIS</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      
                      <table class="table">
                        <thead>
                          <tr class="d-flex">
                            <td class="col-5">OD: Mata Kanan</td>
                            <td class="col-2">&nbsp;</td>
                            <td class="col-5">OS : Mata Kiri</td>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="d-flex">
                            <td class="col-5 text-center">
                              <div class="form-inline">
                                <img src="{{asset('image/mata.png')}}" class="mx-auto d-block"  width="40%" /><br>
                              </div>
                            </td>
                            <td class="col-2 text-center">&nbsp;</td>
                            <td class="col-5 text-center">
                              <div class="form-inline">
                                <img src="{{asset('image/mata.png')}}" class="mx-auto d-block"  width="40%" /><br>
                              </div>
                            </td>
                          </tr>
                          <tr class="d-flex">
                            <td class="col-5 text-center"><input type="text" name="visuskanan" class="form-control form-control-sm" value="{{ old('visuskanan', $penilaianralanmata->visuskanan) }}"></td>
                            <td class="col-2 text-center">Visus SC</td>
                            <td class="col-5 text-center"><input type="text" name="visuskiri" class="form-control form-control-sm" value="{{ old('visuskiri', $penilaianralanmata->visuskiri) }}"></td>
                          </tr>
                          <tr class="d-flex">
                            <td class="col-5 text-center"><input type="text" name="cckanan" class="form-control form-control-sm" value="{{ old('cckanan', $penilaianralanmata->cckanan) }}"></td>
                            <td class="col-2 text-center">CC</td>
                            <td class="col-5 text-center"><input type="text" name="cckiri" class="form-control form-control-sm" value="{{ old('cckiri', $penilaianralanmata->cckiri) }}"></td>
                          </tr>
                          <tr class="d-flex">
                            <td class="col-5 text-center"><input type="text" name="palkanan" class="form-control form-control-sm" value="{{ old('palkanan', $penilaianralanmata->palkanan) }}"></td>
                            <td class="col-2 text-center">Palpebra</td>
                            <td class="col-5 text-center"><input type="text" name="palkiri" class="form-control form-control-sm" value="{{ old('palkiri', $penilaianralanmata->palkiri) }}"></td>
                          </tr>
                          <tr class="d-flex">
                            <td class="col-5 text-center"><input type="text" name="conkanan" class="form-control form-control-sm" value="{{ old('conkanan', $penilaianralanmata->conkanan) }}"></td>
                            <td class="col-2 text-center">Konjungtiva</td>
                            <td class="col-5 text-center"><input type="text" name="conkiri" class="form-control form-control-sm" value="{{ old('conkiri', $penilaianralanmata->conkiri) }}"></td>
                          </tr>
                          <tr class="d-flex">
                            <td class="col-5 text-center"><input type="text" name="corneakanan" class="form-control form-control-sm" value="{{ old('corneakanan', $penilaianralanmata->corneakanan) }}"></td>
                            <td class="col-2 text-center">Cornea</td>
                            <td class="col-5 text-center"><input type="text" name="corneakiri" class="form-control form-control-sm" value="{{ old('corneakiri', $penilaianralanmata->corneakiri) }}"></td>
                          </tr>
                          <tr class="d-flex">
                            <td class="col-5 text-center"><input type="text" name="coakanan" class="form-control form-control-sm" value="{{ old('coakanan', $penilaianralanmata->coakanan) }}"></td>
                            <td class="col-2 text-center">COA</td>
                            <td class="col-5 text-center"><input type="text" name="coakiri" class="form-control form-control-sm" value="{{ old('coakiri', $penilaianralanmata->coakiri) }}"></td>
                          </tr>
                          <tr class="d-flex">
                            <td class="col-5 text-center"><input type="text" name="pupilkanan" class="form-control form-control-sm" value="{{ old('pupilkanan', $penilaianralanmata->pupilkanan) }}"></td>
                            <td class="col-2 text-center">Pupil</td>
                            <td class="col-5 text-center"><input type="text" name="pupilkiri" class="form-control form-control-sm" value="{{ old('pupilkiri', $penilaianralanmata->pupilkiri) }}"></td>
                          </tr>
                          <tr class="d-flex">
                            <td class="col-5 text-center"><input type="text" name="lensakanan" class="form-control form-control-sm" value="{{ old('lensakanan', $penilaianralanmata->lensakanan) }}"></td>
                            <td class="col-2 text-center">Lensa</td>
                            <td class="col-5 text-center"><input type="text" name="lensakiri" class="form-control form-control-sm" value="{{ old('lensakiri', $penilaianralanmata->lensakiri) }}"></td>
                          </tr>
                          <tr class="d-flex">
                            <td class="col-5 text-center"><input type="text" name="funduskanan" class="form-control form-control-sm" value="{{ old('funduskanan', $penilaianralanmata->funduskanan) }}"></td>
                            <td class="col-2 text-center">Fundus Media</td>
                            <td class="col-5 text-center"><input type="text" name="funduskiri" class="form-control form-control-sm" value="{{ old('funduskiri', $penilaianralanmata->funduskiri) }}"></td>
                          </tr>
                          <tr class="d-flex">
                            <td class="col-5 text-center"><input type="text" name="papilkanan" class="form-control form-control-sm" value="{{ old('papilkanan', $penilaianralanmata->papilkanan) }}"></td>
                            <td class="col-2 text-center">Papil</td>
                            <td class="col-5 text-center"><input type="text" name="papilkiri" class="form-control form-control-sm" value="{{ old('papilkiri', $penilaianralanmata->papilkiri) }}"></td>
                          </tr>
                          <tr class="d-flex">
                            <td class="col-5 text-center"><input type="text" name="retinakanan" class="form-control form-control-sm" value="{{ old('retinakanan', $penilaianralanmata->retinakanan) }}"></td>
                            <td class="col-2 text-center">Retina</td>
                            <td class="col-5 text-center"><input type="text" name="retinakiri" class="form-control form-control-sm" value="{{ old('retinakiri', $penilaianralanmata->retinakiri) }}"></td>
                          </tr>
                          <tr class="d-flex">
                            <td class="col-5 text-center"><input type="text" name="makulakanan" class="form-control form-control-sm" value="{{ old('makulakanan', $penilaianralanmata->makulakanan) }}"></td>
                            <td class="col-2 text-center">Makula</td>
                            <td class="col-5 text-center"><input type="text" name="makulakiri" class="form-control form-control-sm" value="{{ old('makulakiri', $penilaianralanmata->makulakiri) }}"></td>
                          </tr>
                          <tr class="d-flex">
                            <td class="col-5 text-center"><input type="text" name="tiokanan" class="form-control form-control-sm" value="{{ old('tiokanan', $penilaianralanmata->tiokanan) }}"></td>
                            <td class="col-2 text-center">TIO</td>
                            <td class="col-5 text-center"><input type="text" name="tiokiri" class="form-control form-control-sm" value="{{ old('tiokiri', $penilaianralanmata->rr) }}"></td>
                          </tr>
                          <tr class="d-flex">
                            <td class="col-5 text-center"><input type="text" name="mbokanan" class="form-control form-control-sm" value="{{ old('mbokanan', $penilaianralanmata->mbokanan) }}"></td>
                            <td class="col-2 text-center">MBO</td>
                            <td class="col-5 text-center"><input type="text" name="mbokiri" class="form-control form-control-sm" value="{{ old('mbokiri', $penilaianralanmata->mbokiri) }}"></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>IV. PEMERIKSAAN PENUNJANG</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group row text-sm"> 
                        <div class="col-sm-4 pt-1">
                          <div class="form-inline">Laboratorium : 
                            <textarea class="form-control form-control-sm" rows="2" name="lab" autocomplete="off" style="width: 100%">{{ old('lab', $penilaianralanmata->lab) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-4 pt-1">
                          <div class="form-inline">Radiologi : 
                            <textarea class="form-control form-control-sm" rows="2" name="rad" autocomplete="off" style="width: 100%">{{ old('rad', $penilaianralanmata->rad) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-4 pt-1">
                          <div class="form-inline">Penunjang Lain : 
                            <textarea class="form-control form-control-sm" rows="2" name="penunjang" autocomplete="off" style="width: 100%">{{ old('penunjang', $penilaianralanmata->penunjang) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6 pt-1">
                          <div class="form-inline">Tes Penglihatan : 
                            <textarea class="form-control form-control-sm" rows="2" name="tes" autocomplete="off" style="width: 100%">{{ old('tes', $penilaianralanmata->tes) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6 pt-1">
                          <div class="form-inline">Pemeriksaan Lain :
                            <textarea class="form-control form-control-sm" rows="2" name="pemeriksaan" autocomplete="off" style="width: 100%">{{ old('pemeriksaan', $penilaianralanmata->pemeriksaan) }}</textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>V. DIAGNOSIS/ASESMEN</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group row text-sm"> 
                        <div class="col-sm-6 pt-1">
                          <div class="form-inline">Asesmen Kerja :
                            <textarea class="form-control form-control-sm" rows="2" name="diagnosis" autocomplete="off" style="width: 100%">{{ old('diagnosis', $penilaianralanmata->diagnosis) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6 pt-1">
                          <div class="form-inline">Asesmen Banding :
                            <textarea class="form-control form-control-sm" rows="2" name="diagnosisbdg" autocomplete="off" style="width: 100%">{{ old('diagnosisbdg', $penilaianralanmata->diagnosisbdg) }}</textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>VI. PERMASALAHAN & TATALAKSANA</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group row text-sm"> 
                        <div class="col-sm-6 pt-1">
                          <div class="form-inline">Permasalahan :
                            <textarea class="form-control form-control-sm" rows="2" name="permasalahan" autocomplete="off" style="width: 100%">{{ old('permasalahan', $penilaianralanmata->permasalahan) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6 pt-1">
                          <div class="form-inline">Terapi / Pengobatan :
                            <textarea class="form-control form-control-sm" rows="2" name="terapi" autocomplete="off" style="width: 100%">{{ old('terapi', $penilaianralanmata->terapi) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-12 pt-1">
                          <div class="form-inline">Tindakan / Rencana Tindakan :
                            <textarea class="form-control form-control-sm" rows="2" name="tindakan" autocomplete="off" style="width: 100%">{{ old('tindakan', $penilaianralanmata->tindakan) }}</textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>VII. EDUKASI</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group row text-sm"> 
                        <div class="col-sm-12 pt-1">
                            <textarea class="form-control form-control-sm" rows="2" name="edukasi" autocomplete="off" style="width: 100%">{{ old('edukasi', $penilaianralanmata->edukasi) }}</textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  {{-- <a href="{{ route('form/hapus', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'PenilaianAwalMedisRawatJalanMata', 'niplama' => $penilaianralanmata->kd_dokter]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')"><i class="fas fa-trash"></i></a> --}}
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')" name="action" value="hapus"><i class="fas fa-trash"></i></button>
                  {{-- <button type="submit" class="btn btn-info float-right">Simpan Data</button> --}}
                  <button type="submit" class="btn btn-info float-right ml-3" name="action" value="simpan">Simpan</button>
                  <button type="submit" class="btn btn-warning float-right ml-3" name="action" value="update">Update</button>
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
  @include('template.footer')
  </footer>
</div>
<!-- ./wrapper -->

<!-- SCRIPTS -->
@include('template.script')
</body>
</html>
