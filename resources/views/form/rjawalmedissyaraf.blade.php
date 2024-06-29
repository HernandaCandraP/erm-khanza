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
                <h3 class="card-title">::[ Penilaian Awal Medis Rawat Jalan Syaraf ]::</h3>
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
                    <a href="{{ route('form/listdata', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'PenilaianAwalMedisRawatJalanSyaraf']) }}" class="btn btn-warning btn-sm">List Penilaian Rawat Jalan Syaraf</a>
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
                      <div class="col-xs-6 pt-1">&emsp;&emsp; Dokter &emsp;: {{$penilaianralansyaraf->nama}}</div>
                      <input type="text" name="no_rawat" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rawat}}" hidden>
                      <input type="text" name="nip" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                      <input type="text" name="niplama" class="form-control form-control-sm" value="{{ $penilaianralansyaraf->kd_dokter }}" hidden>
                      <input type="text" name="tabel" class="form-control form-control-sm" value="PenilaianAwalMedisRawatJalanSyaraf" hidden>
                      <input type="date" name="tgl_perawatan2" class="form-control form-control-sm" placeholder="tgl_perawatan" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" hidden>
                      <input type="time" name="jam_rawat" class="form-control form-control-sm" placeholder="jam_rawat" value="{{Carbon\Carbon::now()->format('H:i:s')}}" hidden>
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
                          <option value="Autoanamnesis" {{ old('anamnesis', $penilaianralansyaraf->anamnesis) == 'Autoanamnesis' ? 'selected':'' }}>Autoanamnesis</option>
                          <option value="Alloanamnesis" {{ old('anamnesis', $penilaianralansyaraf->anamnesis) == 'Alloanamnesis' ? 'selected':'' }}>Alloanamnesis</option>
                        </select>
                      </div>
                      <div class="col-xs-2">
                        <input type="text" name="hubungan" class="form-control form-control-sm" autocomplete="off" value="{{ old('hubungan', $penilaianralansyaraf->hubungan) }}">
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
                            <textarea class="form-control form-control-sm" rows="1" name="keluhan_utama" autocomplete="off" style="width: 100%">{{ old('keluhan_utama', $penilaianralansyaraf->keluhan_utama) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Penggunaan Obat : 
                            <textarea class="form-control form-control-sm" rows="1" name="rpo" autocomplete="off" style="width: 100%">{{ old('rpo', $penilaianralansyaraf->rpo) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-inline">Riwayat Penyakit Sekarang : 
                            <textarea class="form-control form-control-sm" rows="1" name="rps" autocomplete="off" style="width: 100%">{{ old('rps', $penilaianralansyaraf->rps) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Penyakit Dahulu : 
                            <textarea class="form-control form-control-sm" rows="1" name="rpd" autocomplete="off" style="width: 100%">{{ old('rpd', $penilaianralansyaraf->rpd) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Alergi :  
                            <input type="text" name="alergi" class="form-control form-control-sm" autocomplete="off" style="width: 70%" value="{{ old('alergi', $penilaianralansyaraf->alergi) }}">
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
                          Kesadaran&emsp;
                          <select class="form-control form-control-sm" name="kesadaran">
                            <option value="Compos Mentis" {{ old('kesadaran', $penilaianralansyaraf->kesadaran) == 'Compos Mentis' ? 'selected':'' }}>Compos Mentis</option>
                            <option value="Apatis" {{ old('kesadaran', $penilaianralansyaraf->kesadaran) == 'Apatis' ? 'selected':'' }}>Apatis</option>
                            <option value="Delirum" {{ old('kesadaran', $penilaianralansyaraf->kesadaran) == 'Delirum' ? 'selected':'' }}>Delirum</option>
                          </select>
                        </div>
                        <div class="col-sm-2"> 
                          TD
                          <div class="input-group-append" >
                            <input type="text" name="td" class="form-control form-control-sm" style="width: 80%" value="{{ old('td', $penilaianralansyaraf->td) }}">
                            <span class="input-group-text text-sm">mmHg</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          Nadi
                          <div class="input-group-append" >
                            <input type="text" name="nadi" class="form-control form-control-sm" style="width: 80%" value="{{ old('nadi', $penilaianralansyaraf->nadi) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          Suhu
                          <div class="input-group-append" >
                            <input type="text" name="suhu" class="form-control form-control-sm" style="width: 80%" value="{{ old('suhu', $penilaianralansyaraf->suhu) }}">
                            <span class="input-group-text text-sm">°C</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          RR
                          <div class="input-group-append" >
                            <input type="text" name="rr" class="form-control form-control-sm" style="width: 80%" value="{{ old('rr', $penilaianralansyaraf->rr) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-2" hidden> 
                          Status Nutrisi&emsp;
                          <select class="form-control form-control-sm" name="status">
                            <option value="Skor < 2" {{ old('status', $penilaianralansyaraf->status) == 'Skor < 2' ? 'selected':'' }}>Skor < 2</option>
                            <option value="Skor >= 2" {{ old('status', $penilaianralansyaraf->status) == 'Skor >= 2' ? 'selected':'' }}>Skor >= 2</option>
                          </select>
                        </div>
                        <div class="col-sm-2"> 
                          BB
                          <div class="input-group-append" >
                            <input type="text" name="bb" class="form-control form-control-sm" style="width: 80%" value="{{ old('bb', $penilaianralansyaraf->bb) }}">
                            <span class="input-group-text text-sm">Kg</span>
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          Nyeri
                          <div class="input-group-append" >
                            <input type="text" name="nyeri" class="form-control form-control-sm" value="{{ old('nyeri', $penilaianralansyaraf->nyeri) }}">
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          GCS(E,V,M)
                          <div class="input-group-append" >
                            <input type="text" name="gcs" class="form-control form-control-sm" value="{{ old('gcs', $penilaianralansyaraf->gcs) }}">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>III. STATUS KELAINAN</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group row text-sm"> 
                        <div class="col-sm-3" hidden> 
                          Kepala&emsp;
                          <select class="form-control form-control-sm" name="kepala">
                            <option value="Normal" {{ old('kepala', $penilaianralansyaraf->kepala) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('kepala', $penilaianralansyaraf->kepala) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('kepala', $penilaianralansyaraf->kepala) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                          <input type="text" name="keterangan_kepala" class="form-control form-control-sm" value="{{ old('keterangan_kepala', $penilaianralansyaraf->keterangan_kepala) }}">
                        </div>
                        <div class="col-sm-3" hidden> 
                          Thoraks&emsp;
                          <select class="form-control form-control-sm" name="thoraks">
                            <option value="Normal" {{ old('thoraks', $penilaianralansyaraf->thoraks) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('thoraks', $penilaianralansyaraf->thoraks) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('thoraks', $penilaianralansyaraf->thoraks) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                          <input type="text" name="keterangan_thoraks" class="form-control form-control-sm" value="{{ old('keterangan_thoraks', $penilaianralansyaraf->keterangan_thoraks) }}">
                        </div>
                        <div class="col-sm-3" hidden> 
                          Abdomen&emsp;
                          <select class="form-control form-control-sm" name="abdomen">
                            <option value="Normal" {{ old('abdomen', $penilaianralansyaraf->abdomen) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('abdomen', $penilaianralansyaraf->abdomen) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('abdomen', $penilaianralansyaraf->abdomen) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                          <input type="text" name="keterangan_abdomen" class="form-control form-control-sm" value="{{ old('keterangan_abdomen', $penilaianralansyaraf->keterangan_abdomen) }}">
                        </div>
                        <div class="col-sm-3" hidden> 
                          Ekstremitas&emsp;
                          <select class="form-control form-control-sm" name="ekstremitas">
                            <option value="Normal" {{ old('ekstremitas', $penilaianralansyaraf->ekstremitas) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('ekstremitas', $penilaianralansyaraf->ekstremitas) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('ekstremitas', $penilaianralansyaraf->ekstremitas) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                          <input type="text" name="keterangan_ekstremitas" class="form-control form-control-sm" value="{{ old('keterangan_ekstremitas', $penilaianralansyaraf->keterangan_ekstremitas) }}">
                        </div>
                        <div class="col-sm-3" hidden> 
                          Columna Vertebralis&emsp;
                          <select class="form-control form-control-sm" name="columna">
                            <option value="Normal" {{ old('columna', $penilaianralansyaraf->columna) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('columna', $penilaianralansyaraf->columna) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('columna', $penilaianralansyaraf->columna) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                          <input type="text" name="keterangan_columna" class="form-control form-control-sm" value="{{ old('keterangan_columna', $penilaianralansyaraf->keterangan_columna) }}">
                        </div>
                        <div class="col-sm-3" hidden> 
                          Muskuloskeletal&emsp;
                          <select class="form-control form-control-sm" name="muskulos">
                            <option value="Normal" {{ old('muskulos', $penilaianralansyaraf->muskulos) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('muskulos', $penilaianralansyaraf->muskulos) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('muskulos', $penilaianralansyaraf->muskulos) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                          <input type="text" name="keterangan_muskulos" class="form-control form-control-sm" value="{{ old('keterangan_muskulos', $penilaianralansyaraf->keterangan_muskulos) }}">
                        </div>
                        <div class="col-sm-12">
                          <div class="form-inline">Keterangan : 
                            <textarea class="form-control form-control-sm" rows="3" name="lainnya" autocomplete="off" style="width: 100%">{{ old('lainnya', $penilaianralansyaraf->lainnya) }}</textarea>
                          </div>
                        </div>
                      </div>
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
                            <textarea class="form-control form-control-sm" rows="2" name="lab" autocomplete="off" style="width: 100%">{{ old('lab', $penilaianralansyaraf->lab) }}</textarea>
                          </div>
                          <div class="form-group">
                            <div class="card card-primary collapsed-card">
                              <div class="card-header">
                                <div type="button" class="btn btn-tool col-sm-12" data-card-widget="collapse"><h3 class="card-title">Permintaan Laboratorium</h3><i class="fas fa-plus"></i></div>
                              </div>
                              <!-- /.card-header -->
                              <div class="card-body">
                                <div class="form-group row text-sm"> 
                                  <div class="col-sm-12"> 
                                    Indikasi / Klinis :  <input type="text" name="diagnosa_klinislab" class="form-control form-control-sm" autocomplete="off" style="width: 100%">
                                  </div>
                                  <div class="col-sm-12">
                                    Informasi Tambahan : <input type="text" name="informasi_tambahanlab" class="form-control form-control-sm" autocomplete="off" style="width: 100%">
                                  </div>
                                  <div class="col-sm-12 pt-2 select2-purple">
                                    pemeriksaan
                                    <select class="select2 form-control form-control-sm" multiple="multiple" name="pemeriksaanlab[]" id="pemeriksaanlab" data-placeholder="Pilih Pemeriksaan" data-dropdown-css-class="select2-purple">
                                      @foreach ($jnsperawatanlab as $key)
                                        <option value="{{ $key->kd_jenis_prw }}***{{$key->id_template}}"> {{$key->nm_perawatan}} - {{$key->Pemeriksaan}} </option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                          </div>
                        </div>
                        <div class="col-sm-4 pt-1">
                          <div class="form-inline">Radiologi :
                            <textarea class="form-control form-control-sm" rows="2" name="rad" autocomplete="off" style="width: 100%">{{ old('rad', $penilaianralansyaraf->rad) }}</textarea>
                          </div>
                          <div class="form-group">
                            <div class="card card-primary collapsed-card">
                              <div class="card-header">
                                <div type="button" class="btn btn-tool col-sm-12" data-card-widget="collapse"><h3 class="card-title">Permintaan Radiologi</h3>&emsp;&emsp;<i class="fas fa-plus"></i></div>
                              </div>
                              <!-- /.card-header -->
                              <div class="card-body">
                                <div class="form-group row text-sm"> 
                                  <div class="col-sm-12"> 
                                    <div class="">Indikasi / Klinis
                                      <input type="text" name="diagnosa_klinisrad" class="form-control form-control-sm" autocomplete="off" style="width: 100%">
                                    </div>
                                    <div class="">Informasi Tambahan 
                                      <input type="text" name="informasi_tambahanrad" class="form-control form-control-sm" autocomplete="off" style="width: 100%">
                                    </div>
                                  </div>
                                  <div class="col-sm-12 pt-2 select2-purple">
                                    Pemeriksaan
                                    <select class="select2 form-control form-control-sm" multiple="multiple" name="pemeriksaanrad[]" id="pemeriksaanrad" data-placeholder="Pilih Pemeriksaan" data-dropdown-css-class="select2-purple">
                                      @foreach ($jnsperawatanrad as $key)
                                        <option value="{{ $key->kd_jenis_prw }}"> {{ $key->kd_jenis_prw }} - {{$key->nm_perawatan}} </option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                          </div>
                        </div>
                        <div class="col-sm-4 pt-1">
                          <div class="form-inline">Penunjang Lainnya :
                            <textarea class="form-control form-control-sm" rows="2" name="penunjanglain" autocomplete="off" style="width: 100%">{{ old('penunjanglain', $penilaianralansyaraf->penunjanglain) }}</textarea>
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
                        <div class="col-sm-12 pt-1">
                          <div class="form-inline">
                            <textarea class="form-control form-control-sm" rows="2" name="diagnosis" autocomplete="off" style="width: 100%">{{ old('diagnosis', $penilaianralansyaraf->diagnosis) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6 pt-1" hidden>
                          <div class="form-inline">Asesmen Banding :
                            <textarea class="form-control form-control-sm" rows="2" name="diagnosis2" autocomplete="off" style="width: 100%">{{ old('diagnosis2', $penilaianralansyaraf->diagnosis2) }}</textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>VI. PERMASALAHAN & TATA LAKSANA</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group row text-sm"> 
                        <div class="col-sm-6 pt-1" hidden>
                          <div class="form-inline">Permasalahan
                            <textarea class="form-control form-control-sm" rows="2" name="permasalahan" autocomplete="off" style="width: 100%">{{ old('permasalahan', $penilaianralansyaraf->permasalahan) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6 pt-1">
                          <div class="form-inline">Terapi/Pengobatan :
                            <textarea class="form-control form-control-sm" rows="2" name="terapi" autocomplete="off" style="width: 100%">{{ old('terapi', $penilaianralansyaraf->terapi) }}</textarea>
                          </div>
                          <table id="testing" class="table table-bordered table-striped text-sm">
                          </table>
                          <div class="col-sm-12">
                            <div type="text" class="btn btn-warning addmore float-right">+ Tambah Obat</div>
                          </div>
                        </div>
                        <div class="col-sm-6 pt-1">
                          <div class="form-inline">Tindakan/Rencana Tindakan
                            <textarea class="form-control form-control-sm" rows="2" name="tindakan" autocomplete="off" style="width: 100%">{{ old('tindakan', $penilaianralansyaraf->tindakan) }}</textarea>
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
                            <textarea class="form-control form-control-sm" rows="2" name="edukasi" autocomplete="off" style="width: 100%">{{ old('edukasi', $penilaianralansyaraf->edukasi) }}</textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  {{-- <a href="{{ route('form/hapus', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'PenilaianAwalMedisRawatJalanSyaraf', 'niplama' => $penilaianralansyaraf->kd_dokter]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')"><i class="fas fa-trash"></i></a> --}}
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')" name="action" value="hapus"><i class="fas fa-trash"></i></button>
                  {{-- <button type="submit" class="btn btn-info float-right">Simpan Data</button> --}}
                  <button type="submit" class="btn btn-info float-right ml-3" name="action" value="simpan">Simpan</button>
                  <button type="submit" class="btn btn-warning float-right ml-3" name="action" value="update">Ganti</button>
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
@include('template.script_table')
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  })


  var i=1;
  $(".addmore").on('click',function(){
    if(i == 1){
      document.getElementById("testing").insertRow(-1).innerHTML = '<tr><th>Jumlah</th><th>Aturan&emsp; Pakai</th><th>Obat</th><th>Aksi</th></tr>';
    }

    document.getElementById("testing").insertRow(-1).innerHTML = '<tr><td><input id="jumlah'+i+'" class="form-control" name="jumlah[]" type="number"  min="1"  size="6" required/></td><td><input  id="aturan-pakai'+i+'" class="form-control" name="aturan_pakai[]" type="text"  size="8"/></td><td><select class="select2" name="resep[]" required><option value="" disabled selected>Pilih Obat</option> @foreach ($resepdokter as $key)<option value="{{ $key->kode_brng }}">{{$key->nama_brng}} </option>@endforeach</select></td><td><text type="text" class="btn btn-danger" id="hapusresep'+i+'">X</text></td></tr>';

    $('.select2').select2();

    $('#hapusresep'+i).on('click', function() {
      $(this).parents("tr").remove();
      return false;
    });
    i++;
  });

  $(".addmorerad").on('click',function(){
    // if(i == 1){
    //   document.getElementById("testing").insertRow(-1).innerHTML = '<tr><th>Jumlah</th><th>Aturan&emsp; Pakai</th><th>Obat</th><th>Aksi</th></tr>';
    // }

    document.getElementById("permintaanlab").insertRow(-1).innerHTML = '<tr><td><input id="jumlah'+i+'" class="form-control" name="jumlah[]" type="number"  min="1"  size="6" required/></td><td><input  id="aturan-pakai'+i+'" class="form-control" name="aturan_pakai[]" type="text"  size="8"/></td><td><select class="select2" name="resep[]" required><option value="" disabled selected>Pilih Obat</option> @foreach ($resepdokter as $key)<option value="{{ $key->kode_brng }}">{{$key->nama_brng}} </option>@endforeach</select></td><td><text type="text" class="btn btn-danger" id="hapusresep'+i+'">X</text></td></tr>';

    $('.select2').select2();

    $('#hapusresep'+i).on('click', function() {
      $(this).parents("tr").remove();
      return false;
    });
    i++;
  });

  $("#hapusresep").on('click', function() {
    $(this).parents("tr").remove();
    return false;
  });
</script>
</body>
</html>
