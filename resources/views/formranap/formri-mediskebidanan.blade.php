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
          @include('template.menudokterranap', ['no_rawat' => $no_rawat, 'specialis' => Session::get('getspecialis')])

          <div class="col-md-12 mt-4">
            <!-- Horizontal Form -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">::[ Penilaian Awal Medis Kebidanan & Kandungan Rawat Inap ]::</h3>
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
                    <a href="{{ route('formri-listdata', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'PenilaianAwalMedisKebidananRawatInap']) }}" class="btn btn-warning btn-sm">List Penilaian Awal Medis Kebidanan Ranap</a>
                  </div>
                  </div>
                </div>
              </form>
              @include('template.alert')
              <!-- form start -->
              <form action="{{route('formri-tambah')}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div  class="card-body">
                  <div class="form-group row text-sm">
                    <div class="form-inline row">
                      <div class="col-xs-6 pt-1">&emsp;&emsp; Dokter &emsp;: {{$mediskebidanan->nama}}</div>
                      <input type="text" name="no_rawat" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rawat}}" hidden>
                      <input type="text" name="nip" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                      <input type="text" name="niplama" class="form-control form-control-sm" value="{{ $mediskebidanan->kd_dokter }}" hidden>
                      <input type="text" name="tabel" class="form-control form-control-sm" value="PenilaianAwalMedisKebidananRawatInap" hidden>
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
                          <option value="Autoanamnesis" {{ old('anamnesis', $mediskebidanan->anamnesis) == 'Autoanamnesis' ? 'selected':'' }}>Autoanamnesis</option>
                          <option value="Alloanamnesis" {{ old('anamnesis', $mediskebidanan->anamnesis) == 'Alloanamnesis' ? 'selected':'' }}>Alloanamnesis</option>
                        </select>
                      </div>
                      <div class="col-xs-2">
                        <input type="text" name="hubungan" class="form-control form-control-sm" autocomplete="off" value="{{ old('hubungan', $mediskebidanan->hubungan) }}">
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
                            <textarea class="form-control form-control-sm" rows="1" name="keluhan_utama" autocomplete="off" style="width: 100%">{{ old('keluhan_utama', $mediskebidanan->keluhan_utama) }}</textarea>
                          </div>
                          <div class="form-inline">Riwayat Penyakit Keluarga : 
                            <textarea class="form-control form-control-sm" rows="1" name="rpk" autocomplete="off" style="width: 100%">{{ old('rpk', $mediskebidanan->rpk) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Penggunaan Obat : 
                            <textarea class="form-control form-control-sm" rows="1" name="rpo" autocomplete="off" style="width: 100%">{{ old('rpo', $mediskebidanan->rpo) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-inline">Riwayat Penyakit Sekarang : 
                            <textarea class="form-control form-control-sm" rows="1" name="rps" autocomplete="off" style="width: 100%">{{ old('rps', $mediskebidanan->rps) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Penyakit Dahulu : 
                            <textarea class="form-control form-control-sm" rows="1" name="rpd" autocomplete="off" style="width: 100%">{{ old('rpd', $mediskebidanan->rpd) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Alergi :  
                            <input type="text" name="alergi" class="form-control form-control-sm" autocomplete="off" style="width: 70%" value="{{ old('alergi', $mediskebidanan->alergi) }}">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>II. Pemeriksaan Fisik</b> &emsp;
                        <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modal-lg">Cek Penilaian Petugas &nbsp;<i class="fas fa-exclamation"></i> </button>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="row text-sm"> 
                        <div class="col-sm-2"> 
                          Keadaan Umum&emsp;
                          <select class="form-control form-control-sm" name="keadaan">
                            <option value="Sehat" {{ old('keadaan', $mediskebidanan->keadaan) == 'Sehat' ? 'selected':'' }}>Sehat</option>
                            <option value="Sakit Ringan" {{ old('keadaan', $mediskebidanan->keadaan) == 'Sakit Ringan' ? 'selected':'' }}>Sakit Ringan</option>
                            <option value="Sakit Sedang" {{ old('keadaan', $mediskebidanan->keadaan) == 'Sakit Sedang' ? 'selected':'' }}>Sakit Sedang</option>
                            <option value="Sakit Berat" {{ old('keadaan', $mediskebidanan->keadaan) == 'Sakit Berat' ? 'selected':'' }}>Sakit Berat</option>
                          </select>
                        </div>
                        <div class="col-sm-2"> 
                          Kesadaran&emsp;
                          <select class="form-control form-control-sm" name="kesadaran">
                            <option value="Compos Mentis" {{ old('kesadaran', $mediskebidanan->kesadaran) == 'Compos Mentis' ? 'selected':'' }}>Compos Mentis</option>
                            <option value="Apatis" {{ old('kesadaran', $mediskebidanan->kesadaran) == 'Apatis' ? 'selected':'' }}>Apatis</option>
                            <option value="Somnolen" {{ old('kesadaran', $mediskebidanan->kesadaran) == 'Somnolen' ? 'selected':'' }}>Somnolen</option>
                            <option value="Sopor" {{ old('kesadaran', $mediskebidanan->kesadaran) == 'Sopor' ? 'selected':'' }}>Sopor</option>
                            <option value="Koma" {{ old('kesadaran', $mediskebidanan->kesadaran) == 'Koma' ? 'selected':'' }}>Koma</option>
                          </select>
                        </div>
                        <div class="col-sm-3"> 
                          GCS(E,V,M)
                          <div class="input-group-append" >
                            <input type="text" name="gcs" class="form-control form-control-sm" value="{{ old('gcs', $mediskebidanan->gcs) }}">
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          TB
                          <div class="input-group-append" >
                            <input type="text" name="tb" class="form-control form-control-sm" style="width: 80%" value="{{ old('tb', $mediskebidanan->tb) }}">
                            <span class="input-group-text text-sm">cm</span>
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          BB
                          <div class="input-group-append" >
                            <input type="text" name="bb" class="form-control form-control-sm" style="width: 80%" value="{{ old('bb', $mediskebidanan->bb) }}">
                            <span class="input-group-text text-sm">Kg</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          TD
                          <div class="input-group-append" >
                            <input type="text" name="td" class="form-control form-control-sm" style="width: 80%" value="{{ old('td', $mediskebidanan->td) }}">
                            <span class="input-group-text text-sm">mmHg</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          Nadi
                          <div class="input-group-append" >
                            <input type="text" name="nadi" class="form-control form-control-sm" style="width: 80%" value="{{ old('nadi', $mediskebidanan->nadi) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          RR
                          <div class="input-group-append" >
                            <input type="text" name="rr" class="form-control form-control-sm" style="width: 80%" value="{{ old('rr', $mediskebidanan->rr) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          Suhu
                          <div class="input-group-append" >
                            <input type="text" name="suhu" class="form-control form-control-sm" style="width: 80%" value="{{ old('suhu', $mediskebidanan->suhu) }}">
                            <span class="input-group-text text-sm">°C</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          SpO2
                          <div class="input-group-append" >
                            <input type="text" name="spo" class="form-control form-control-sm" style="width: 80%" value="{{ old('spo', $mediskebidanan->spo) }}">
                            <span class="input-group-text text-sm">%</span>
                          </div>
                        </div>
                        <hr>

                        <div class="col-sm-3" hidden> 
                          Kepala&emsp;
                          <select class="form-control form-control-sm" name="kepala">
                            <option value="Normal" {{ old('kepala', $mediskebidanan->kepala) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('kepala', $mediskebidanan->kepala) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('kepala', $mediskebidanan->kepala) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3" hidden> 
                          Mata&emsp;
                          <select class="form-control form-control-sm" name="mata">
                            <option value="Normal" {{ old('mata', $mediskebidanan->mata) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('mata', $mediskebidanan->mata) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('mata', $mediskebidanan->mata) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3" hidden> 
                          Gigi & Mulut&emsp;
                          <select class="form-control form-control-sm" name="gigi">
                            <option value="Normal" {{ old('gigi', $mediskebidanan->gigi) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('gigi', $mediskebidanan->gigi) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('gigi', $mediskebidanan->gigi) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3" hidden> 
                          THT&emsp;
                          <select class="form-control form-control-sm" name="tht">
                            <option value="Normal" {{ old('tht', $mediskebidanan->tht) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('tht', $mediskebidanan->tht) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('tht', $mediskebidanan->tht) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3" hidden> 
                          Thoraks&emsp;
                          <select class="form-control form-control-sm" name="thoraks">
                            <option value="Normal" {{ old('thoraks', $mediskebidanan->thoraks) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('thoraks', $mediskebidanan->thoraks) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('thoraks', $mediskebidanan->thoraks) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3" hidden> 
                          Jantung&emsp;
                          <select class="form-control form-control-sm" name="jantung">
                            <option value="Normal" {{ old('jantung', $mediskebidanan->jantung) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('jantung', $mediskebidanan->jantung) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('jantung', $mediskebidanan->jantung) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3" hidden> 
                          Paru&emsp;
                          <select class="form-control form-control-sm" name="paru">
                            <option value="Normal" {{ old('paru', $mediskebidanan->paru) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('paru', $mediskebidanan->paru) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('paru', $mediskebidanan->paru) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3" hidden> 
                          Abdomen&emsp;
                          <select class="form-control form-control-sm" name="abdomen">
                            <option value="Normal" {{ old('abdomen', $mediskebidanan->abdomen) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('abdomen', $mediskebidanan->abdomen) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('abdomen', $mediskebidanan->abdomen) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3" hidden> 
                          Genital & Anus&emsp;
                          <select class="form-control form-control-sm" name="genital">
                            <option value="Normal" {{ old('genital', $mediskebidanan->genital) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('genital', $mediskebidanan->genital) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('genital', $mediskebidanan->genital) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3" hidden> 
                          Ekstremitas&emsp;
                          <select class="form-control form-control-sm" name="ekstremitas">
                            <option value="Normal" {{ old('ekstremitas', $mediskebidanan->ekstremitas) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('ekstremitas', $mediskebidanan->ekstremitas) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('ekstremitas', $mediskebidanan->ekstremitas) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3" hidden> 
                          Kulit&emsp;
                          <select class="form-control form-control-sm" name="kulit">
                            <option value="Normal" {{ old('kulit', $mediskebidanan->kulit) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('kulit', $mediskebidanan->kulit) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('kulit', $mediskebidanan->kulit) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-12 pt-2">Keterangan 
                          <textarea class="form-control form-control-sm" rows="2" name="ket_fisik" autocomplete="off" style="width: 100%">{{ old('ket_fisik', $mediskebidanan->ket_fisik) }}</textarea>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>III. Status Obstetri / Ginekologi</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="row text-sm"> 
                        <div class="col-sm-3"> 
                          TFU
                          <div class="input-group-append" >
                            <input type="text" name="tfu" class="form-control form-control-sm" style="width: 80%" value="{{ old('tfu', $mediskebidanan->tfu) }}">
                            <span class="input-group-text text-sm">cm</span>
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          TBJ
                          <div class="input-group-append" >
                            <input type="text" name="tbj" class="form-control form-control-sm" style="width: 80%" value="{{ old('tbj', $mediskebidanan->tbj) }}">
                            <span class="input-group-text text-sm">gram</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          HIS
                          <div class="input-group-append" >
                            <input type="text" name="his" class="form-control form-control-sm" style="width: 80%" value="{{ old('his', $mediskebidanan->his) }}">
                            <span class="input-group-text text-sm">x/10mnt</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          Kontraksi&emsp;
                          <select class="form-control form-control-sm" name="kontraksi">
                            <option value="Ada" {{ old('kontraksi', $mediskebidanan->kontraksi) == 'Ada' ? 'selected':'' }}>Ada</option>
                            <option value="Tidak" {{ old('kontraksi', $mediskebidanan->kontraksi) == 'Tidak' ? 'selected':'' }}>Tidak</option>
                          </select>
                        </div>
                        <div class="col-sm-2"> 
                          DJJ
                          <div class="input-group-append" >
                            <input type="text" name="djj" class="form-control form-control-sm" style="width: 80%" value="{{ old('djj', $mediskebidanan->djj) }}">
                            <span class="input-group-text text-sm">Dpm</span>
                          </div>
                        </div>                        
                        <div class="col-sm-6 pt-2">Inspeksi :  
                          <textarea class="form-control form-control-sm" rows="2" name="inspeksi" autocomplete="off" style="width: 100%">{{ old('inspeksi', $mediskebidanan->inspeksi) }}</textarea>
                        </div>
                        <div class="col-sm-6 pt-2"> VT :
                          <textarea class="form-control form-control-sm" rows="2" name="vt" autocomplete="off" style="width: 100%">{{ old('vt', $mediskebidanan->vt) }}</textarea>
                        </div>
                        <div class="col-sm-6 pt-2"> Inspekulo :
                          <textarea class="form-control form-control-sm" rows="2" name="inspekulo" autocomplete="off" style="width: 100%">{{ old('inspekulo', $mediskebidanan->inspekulo) }}</textarea>
                        </div>
                        <div class="col-sm-6 pt-2"> RT :
                          <textarea class="form-control form-control-sm" rows="2" name="rt" autocomplete="off" style="width: 100%">{{ old('rt', $mediskebidanan->rt) }}</textarea>
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
                            <textarea class="form-control form-control-sm" rows="2" name="lab" autocomplete="off" style="width: 100%">{{ old('lab', $mediskebidanan->lab) }}</textarea>
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
                          <div class="form-inline">Ultrasonografi : 
                            <textarea class="form-control form-control-sm" rows="2" name="ultra" autocomplete="off" style="width: 100%">{{ old('ultra', $mediskebidanan->ultra) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-4 pt-1">
                          <div class="form-inline">Kardiotokografi : 
                            <textarea class="form-control form-control-sm" rows="2" name="kardio" autocomplete="off" style="width: 100%">{{ old('kardio', $mediskebidanan->kardio) }}</textarea>
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
                            <textarea class="form-control form-control-sm" rows="2" name="diagnosis" autocomplete="off" style="width: 100%">{{ old('diagnosis', $mediskebidanan->diagnosis) }}</textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>VI. TATA LAKSANA</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group row text-sm"> 
                        <div class="col-sm-12 pt-1">
                          <div class="form-inline">
                            <textarea class="form-control form-control-sm" rows="3" name="tata" autocomplete="off" style="width: 100%">{{ old('tata', $mediskebidanan->tata) }}</textarea>
                          </div>
                        </div>
                      </div>
                      <table id="testing" class="table table-bordered table-striped text-sm">
                            
                      </table>
                      <div class="col-sm-12">
                        <div type="text" class="btn btn-warning addmore float-right">+ Tambah Obat</div>
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
                            <textarea class="form-control form-control-sm" rows="2" name="edukasi" autocomplete="off" style="width: 100%">{{ old('edukasi', $mediskebidanan->edukasi) }}</textarea>
                        </div>
                      </div>
                    </div>
                  </div>


                </div>
                <!-- /.card-body -->
                <div class="card-footer"><button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')" name="action" value="hapus"><i class="fas fa-trash"></i></button>
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

<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Penilaian Petugas</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- form -->
      <div class="modal-body justify-content-between">
        @if ($ambilttv)
          <div class="form-group row">
            <div class="d-inline-block"><b>Riwayat Menstruasi : &emsp;</b></div>
            <div class="d-inline-block"><b>Umur Menarche</b> : <u>{{$ambilttv->riwayat_mens_umur}}</u> tahun, lamanya: <u>{{$ambilttv->riwayat_mens_lamanya}}</u> hari, banyaknya : <u>{{$ambilttv->riwayat_mens_banyaknya}}</u> pembalut &emsp;</div>
            <div class="d-inline-block"><b>Siklus</b> : <u>{{$ambilttv->riwayat_mens_siklus}}</u>, hari (<u>{{$ambilttv->riwayat_mens_ket_siklus}}</u>) &emsp;</div>
            <div class="d-inline-block"><b>Masalah yang dirasakan saat menstruasi</b> : <u>{{$ambilttv->riwayat_mens_dirasakan}}</u> &emsp;</div>
          </div>
          <div class="form-group row">
            <div class="d-inline-block"><b>Riwayat Perkawinan :&emsp;</b></div>
            <div class="d-inline-block"><b>Status menikah : </b><u>{{$ambilttv->riwayat_perkawinan_status}}</u> <u>{{$ambilttv->riwayat_perkawinan_ket_status}}</u>kali &emsp;</div>
          </div>
          <div class="form-group row">
            <div class="d-inline-block"><b>Riwayat Persalinan & Nifas :&emsp;</b></div>
            <div class="d-inline-block"><b>G</b> : <u>{{$ambilttv->riwayat_persalinan_g}}</u>&emsp;</div>
            <div class="d-inline-block"><b>P</b> : <u>{{$ambilttv->riwayat_persalinan_p}}</u>&emsp;</div>
            <div class="d-inline-block"><b>A</b> : <u>{{$ambilttv->riwayat_persalinan_a}}</u>&emsp;</div>
            <div class="d-inline-block"><b>Hidup</b> : <u>{{$ambilttv->riwayat_persalinan_hidup}}</u>&emsp;</div>
          </div>
          <div class="form-group row">
            <div class="d-inline-block"><b>Riwayat Kehamilan Sekarang :&emsp;</b></div>
            <div class="d-inline-block"><b>HPHT</b> : <u>{{$ambilttv->riwayat_hamil_hpht}}</u> &emsp;</div>
            <div class="d-inline-block"><b>Usia hamil</b> : <u>{{$ambilttv->riwayat_hamil_usiahamil}}</u> bln/mgg&emsp;</div>
            <div class="d-inline-block"><b>TP</b> : <u>{{$ambilttv->riwayat_hamil_tp}}</u> &emsp;</div>
            <div class="d-inline-block"><b>Riwayat imunisasi</b> : <u>{{$ambilttv->riwayat_hamil_imunisasi}}</u> &emsp;</div>
            <div class="d-inline-block"><b>ANC</b> : <u>{{$ambilttv->riwayat_hamil_anc}}</u>X <b>Ke:</b> <u>{{$ambilttv->riwayat_hamil_ancke}} {{$ambilttv->riwayat_hamil_ket_ancke}}</u> &emsp;</div>
            <div class="d-inline-block"><b>Keluhan Hamil Muda</b> : <u>{{$ambilttv->riwayat_hamil_keluhan_hamil_muda}}</u>&emsp;</div>
            <div class="d-inline-block"><b>Keluhan Hamil Tua</b> : <u>{{$ambilttv->riwayat_hamil_keluhan_hamil_tua}}</u>&emsp;</div>
          </div>
          <div class="form-group row">
            <div class="d-inline-block"><b>Riwayat KB: </b> <u>{{$ambilttv->riwayat_kb}}</u> &emsp;</div>
            <div class="d-inline-block"><b>Lamanya</b> : <u>{{$ambilttv->riwayat_kb_lamanya}}</u> &emsp;</div>
            <div class="d-inline-block"><b>Komplikasi</b> : <u>{{$ambilttv->riwayat_kb_komplikasi}}</u> <u>{{$ambilttv->riwayat_kb_ket_komplikasi}}</u> &emsp;</div>
            <div class="d-inline-block"><b>Kapan berhenti KB</b> : <u>{{$ambilttv->riwayat_kb_kapaberhenti}}</u> &emsp;</div>
            <div class="d-inline-block"><b>Alasan</b> : <u>{{$ambilttv->riwayat_kb_alasanberhenti}}</u> &emsp;</div>
            <div class="d-inline-block"><b>Riwayat Ginekologi</b> : <u>{{$ambilttv->riwayat_genekologi}}</u>&emsp;</div>
          </div>
          <div class="form-group row">
            <div class="d-inline-block"><b>Riwayat Kebiasaan : &emsp;</b></div>
            <div class="d-inline-block"><b>Obat / Vitamin</b> : <u>{{$ambilttv->riwayat_kebiasaan_obat}}</u> <u>{{$ambilttv->riwayat_kebiasaan_ket_obat}}</u> &emsp;</div>
            <div class="d-inline-block"><b>Merokok</b> : <u>{{$ambilttv->riwayat_kebiasaan_merokok}}</u> <u>{{$ambilttv->riwayat_kebiasaan_ket_merokok}}</u> batang/hari &emsp;</div>
            <div class="d-inline-block"><b>Alkohol</b> : <u>{{$ambilttv->riwayat_kebiasaan_alkohol}}</u> <u>{{$ambilttv->riwayat_kebiasaan_ket_alkohol}}</u> gelas/hari&emsp;</div>
            <div class="d-inline-block"><b>Obat Tidur/Narkoba</b> : <u>{{$ambilttv->riwayat_kebiasaan_narkoba}}</u> &emsp;</div>
          </div>
          <div class="form-group row">
            <div class="d-inline"><b>Pemeriksaan Kebidanan: &emsp;</b></div>
            <div class="d-inline-block"><b>Kesadaran Mental</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_mental}}</u> &emsp;</div>
            <div class="d-inline-block"><b>Keadaan Umum</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_keadaan_umum}}</u>&emsp;</div>
            <div class="d-inline-block"><b>GCS(E,V,M)</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_gcs}}</u>&emsp;</div>
            <div class="d-inline-block"><b>TD</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_td}}</u> mmHg&emsp;</div>
            <div class="d-inline-block"><b>Nadi</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_nadi}}</u> x/mnt&emsp;</div>
            <div class="d-inline-block"><b>RR</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_rr}}</u> x/mnt&emsp;</div>
            <div class="d-inline-block"><b>Suhu</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_suhu}}</u> °C&emsp;</div>
            <div class="d-inline-block"><b>SpO2</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_spo2}}</u> %&emsp;</div>
            <div class="d-inline-block"><b>BB</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_bb}}</u> Kg&emsp;</div>
            <div class="d-inline-block"><b>TB</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_tb}}</u> cm&emsp;</div>
            <div class="d-inline-block"><b>LILA</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_lila}}</u> cm&emsp;</div>
            <div class="d-inline-block"><b>TFU</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_tfu}}</u> cm&emsp;</div>
            <div class="d-inline-block"><b>TBJ</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_tbj}}</u> gr&emsp;</div>
            <div class="d-inline-block"><b>Letak</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_letak}}</u>&emsp;</div>
            <div class="d-inline-block"><b>Presentasi</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_presentasi}}</u>&emsp;</div>
            <div class="d-inline-block"><b>Penurunan</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_penurunan}}</u>&emsp;</div>
            <div class="d-inline-block"><b>Kontraksi/HIS</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_his}}</u> x/10', <b>Kekuatan</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_kekuatan}}</u> <b>Lamanya</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_lamanya}}</u> detik&emsp;</div>
            <div class="d-inline-block"><b>Gerak janin x/30 menit, DJJ: </b> : <u>{{$ambilttv->pemeriksaan_kebidanan_djj}}</u>/mnt <u>{{$ambilttv->pemeriksaan_kebidanan_ket_djj}}</u>&emsp;</div>
            <div class="d-inline-block"><b>portio</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_portio}}</u>&emsp;</div>
            <div class="d-inline-block"><b>Pembukaan Serviks</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_pembukaan}}</u> cm &emsp;</div>
            <div class="d-inline-block"><b>Ketuban</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_ketuban}}</u> kep/box&emsp;</div>
            <div class="d-inline-block"><b>Hodge</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_hodge}}</u>&emsp;</div>
            <div class="d-inline-block"><b>Panggul</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_panggul}}</u>&emsp;</div>
            <div class="d-inline-block"><b>Inspeluko</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_inspekulo}} {{$ambilttv->pemeriksaan_kebidanan_ket_inspekulo}}</u>&emsp;</div>
            <div class="d-inline-block"><b>Lakmus</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_lakmus}} {{$ambilttv->pemeriksaan_kebidanan_ket_lakmus}}</u>&emsp;</div>
            <div class="d-inline-block"><b>CTG</b> : <u>{{$ambilttv->pemeriksaan_kebidanan_ctg}} {{$ambilttv->pemeriksaan_kebidanan_ket_ctg}}</u>&emsp;</div>
          </div>
        @else
          <div class="form-group row p-4">
            <h4>Data Pemeriksaan Awal Kebidanan Belum Terisi</h4>
          </div>
        @endif
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <!-- end form -->
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
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
      document.getElementById("testing").insertRow(-1).innerHTML = '<tr><th>Jumlah</th><th>Aturan&emsp; Pakai</th><th>Total</th><th>Aksi</th></tr>';
    }

    document.getElementById("testing").insertRow(-1).innerHTML = '<tr><td><input id="jumlah'+i+'" class="form-control" name="jumlah[]" type="number"  min="1"  size="6" required/></td><td><input  id="aturan-pakai'+i+'" class="form-control" name="aturan_pakai[]" type="text"  size="8"/></td><td><select class="select2" name="resep[]" required><option value="" disabled selected>Pilih Obat</option> @foreach ($resepdokter as $key)<option value="{{ $key->kode_brng }}">{{$key->nama_brng}} </option>@endforeach</select></td><td><text type="text" class="btn btn-danger" id="hapusresep'+i+'">X</text></td></tr>';

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
