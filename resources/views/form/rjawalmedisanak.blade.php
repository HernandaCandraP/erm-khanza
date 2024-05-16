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
                <h3 class="card-title">::[ Penilaian Awal Medis Rawat Jalan Anak ]::</h3>
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
                    <a href="{{ route('form/listdata', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'PenilaianAwalMedisRawatJalanAnak']) }}" class="btn btn-warning btn-sm">List Penilaian Rawat Jalan Anak</a>
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
                      <div class="col-xs-6 pt-1">&emsp;&emsp; Dokter &emsp;: {{$penilaianralananak->nama}}</div>
                      <input type="text" name="no_rawat" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rawat}}" hidden>
                      <input type="text" name="nip" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                      <input type="text" name="niplama" class="form-control form-control-sm" value="{{ $penilaianralananak->kd_dokter }}" hidden>
                      <input type="text" name="tabel" class="form-control form-control-sm" value="PenilaianAwalMedisRawatJalanAnak" hidden>
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
                          <option value="Autoanamnesis" {{ old('anamnesis', $penilaianralananak->anamnesis) == 'Autoanamnesis' ? 'selected':'' }}>Autoanamnesis</option>
                          <option value="Alloanamnesis" {{ old('anamnesis', $penilaianralananak->anamnesis) == 'Alloanamnesis' ? 'selected':'' }}>Alloanamnesis</option>
                        </select>
                      </div>
                      <div class="col-xs-2">
                        <input type="text" name="hubungan" class="form-control form-control-sm" autocomplete="off" value="{{ old('hubungan', $penilaianralananak->hubungan) }}">
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
                            <textarea class="form-control form-control-sm" rows="1" name="keluhan_utama" autocomplete="off" style="width: 100%">{{ old('keluhan_utama', $penilaianralananak->keluhan_utama) }}</textarea>
                          </div>
                          <div class="form-inline">Riwayat Penyakit Keluarga : 
                            <textarea class="form-control form-control-sm" rows="1" name="rpk" autocomplete="off" style="width: 100%">{{ old('rpk', $penilaianralananak->rpk) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Penggunaan Obat : 
                            <textarea class="form-control form-control-sm" rows="1" name="rpo" autocomplete="off" style="width: 100%">{{ old('rpo', $penilaianralananak->rpo) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-inline">Riwayat Penyakit Sekarang : 
                            <textarea class="form-control form-control-sm" rows="1" name="rps" autocomplete="off" style="width: 100%">{{ old('rps', $penilaianralananak->rps) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Penyakit Dahulu : 
                            <textarea class="form-control form-control-sm" rows="1" name="rpd" autocomplete="off" style="width: 100%">{{ old('rpd', $penilaianralananak->rpd) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Alergi :  
                            <input type="text" name="alergi" class="form-control form-control-sm" autocomplete="off" style="width: 70%" value="{{ old('alergi', $penilaianralananak->alergi) }}">
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
                          Keadaan Umum&emsp;
                          <select class="form-control form-control-sm" name="keadaan">
                            <option value="Sehat" {{ old('keadaan', $penilaianralananak->keadaan) == 'Sehat' ? 'selected':'' }}>Sehat</option>
                            <option value="Sakit Ringan" {{ old('keadaan', $penilaianralananak->keadaan) == 'Sakit Ringan' ? 'selected':'' }}>Sakit Ringan</option>
                            <option value="Sakit Sedang" {{ old('keadaan', $penilaianralananak->keadaan) == 'Sakit Sedang' ? 'selected':'' }}>Sakit Sedang</option>
                            <option value="Sakit Berat" {{ old('keadaan', $penilaianralananak->keadaan) == 'Sakit Berat' ? 'selected':'' }}>Sakit Berat</option>
                          </select>
                        </div>
                        <div class="col-sm-2"> 
                          Kesadaran&emsp;
                          <select class="form-control form-control-sm" name="kesadaran">
                            <option value="Compos Mentis" {{ old('kesadaran', $penilaianralananak->kesadaran) == 'Compos Mentis' ? 'selected':'' }}>Compos Mentis</option>
                            <option value="Apatis" {{ old('kesadaran', $penilaianralananak->kesadaran) == 'Apatis' ? 'selected':'' }}>Apatis</option>
                            <option value="Somnolen" {{ old('kesadaran', $penilaianralananak->kesadaran) == 'Somnolen' ? 'selected':'' }}>Somnolen</option>
                            <option value="Sopor" {{ old('kesadaran', $penilaianralananak->kesadaran) == 'Sopor' ? 'selected':'' }}>Sopor</option>
                            <option value="Koma" {{ old('kesadaran', $penilaianralananak->kesadaran) == 'Koma' ? 'selected':'' }}>Koma</option>
                          </select>
                        </div>
                        <div class="col-sm-3"> 
                          GCS(E,V,M)
                          <div class="input-group-append" >
                            <input type="text" name="gcs" class="form-control form-control-sm" value="{{ old('gcs', $penilaianralananak->gcs) }}">
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          TB
                          <div class="input-group-append" >
                            <input type="text" name="tb" class="form-control form-control-sm" style="width: 80%" value="{{ old('tb', $penilaianralananak->tb) }}">
                            <span class="input-group-text text-sm">cm</span>
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          BB
                          <div class="input-group-append" >
                            <input type="text" name="bb" class="form-control form-control-sm" style="width: 80%" value="{{ old('bb', $penilaianralananak->bb) }}">
                            <span class="input-group-text text-sm">Kg</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          TD
                          <div class="input-group-append" >
                            <input type="text" name="td" class="form-control form-control-sm" style="width: 80%" value="{{ old('td', $penilaianralananak->td) }}">
                            <span class="input-group-text text-sm">mmHg</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          Nadi
                          <div class="input-group-append" >
                            <input type="text" name="nadi" class="form-control form-control-sm" style="width: 80%" value="{{ old('nadi', $penilaianralananak->nadi) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          RR
                          <div class="input-group-append" >
                            <input type="text" name="rr" class="form-control form-control-sm" style="width: 80%" value="{{ old('rr', $penilaianralananak->rr) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          Suhu
                          <div class="input-group-append" >
                            <input type="text" name="suhu" class="form-control form-control-sm" style="width: 80%" value="{{ old('suhu', $penilaianralananak->suhu) }}">
                            <span class="input-group-text text-sm">°C</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          SpO2
                          <div class="input-group-append" >
                            <input type="text" name="spo" class="form-control form-control-sm" style="width: 80%" value="{{ old('spo', $penilaianralananak->spo) }}">
                            <span class="input-group-text text-sm">%</span>
                          </div>
                        </div>
                        <hr>

                        <div class="col-sm-3"> 
                          Kepala&emsp;
                          <select class="form-control form-control-sm" name="kepala">
                            <option value="Normal" {{ old('kepala', $penilaianralananak->kepala) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('kepala', $penilaianralananak->kepala) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('kepala', $penilaianralananak->kepala) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3"> 
                          Mata&emsp;
                          <select class="form-control form-control-sm" name="mata">
                            <option value="Normal" {{ old('mata', $penilaianralananak->mata) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('mata', $penilaianralananak->mata) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('mata', $penilaianralananak->mata) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3"> 
                          Gigi & Mulut&emsp;
                          <select class="form-control form-control-sm" name="gigi">
                            <option value="Normal" {{ old('gigi', $penilaianralananak->gigi) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('gigi', $penilaianralananak->gigi) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('gigi', $penilaianralananak->gigi) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3"> 
                          THT&emsp;
                          <select class="form-control form-control-sm" name="tht">
                            <option value="Normal" {{ old('tht', $penilaianralananak->tht) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('tht', $penilaianralananak->tht) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('tht', $penilaianralananak->tht) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3"> 
                          Thoraks&emsp;
                          <select class="form-control form-control-sm" name="thoraks">
                            <option value="Normal" {{ old('thoraks', $penilaianralananak->thoraks) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('thoraks', $penilaianralananak->thoraks) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('thoraks', $penilaianralananak->thoraks) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3"> 
                          Abdomen&emsp;
                          <select class="form-control form-control-sm" name="abdomen">
                            <option value="Normal" {{ old('abdomen', $penilaianralananak->abdomen) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('abdomen', $penilaianralananak->abdomen) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('abdomen', $penilaianralananak->abdomen) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3"> 
                          Genital & Anus&emsp;
                          <select class="form-control form-control-sm" name="genital">
                            <option value="Normal" {{ old('genital', $penilaianralananak->genital) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('genital', $penilaianralananak->genital) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('genital', $penilaianralananak->genital) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3"> 
                          Ekstremitas&emsp;
                          <select class="form-control form-control-sm" name="ekstremitas">
                            <option value="Normal" {{ old('ekstremitas', $penilaianralananak->ekstremitas) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('ekstremitas', $penilaianralananak->ekstremitas) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('ekstremitas', $penilaianralananak->ekstremitas) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-3"> 
                          Kulit&emsp;
                          <select class="form-control form-control-sm" name="kulit">
                            <option value="Normal" {{ old('kulit', $penilaianralananak->kulit) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('kulit', $penilaianralananak->kulit) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('kulit', $penilaianralananak->kulit) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                        </div>
                        <div class="col-sm-12 pt-2"> 
                          <textarea class="form-control form-control-sm" rows="2" name="ket_fisik" autocomplete="off" style="width: 100%">{{ old('ket_fisik', $penilaianralananak->ket_fisik) }}</textarea>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>III. STATUS LOKALIS</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group row text-sm"> 
                        <div class="col-sm-12"> 
                          <div class="form-inline">
                            <img src="{{asset('image/semua.png')}}" class="mx-auto d-block"/><br>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-inline">Riwayat Penyakit Sekarang : 
                            <textarea class="form-control form-control-sm" rows="3" name="ket_lokalis" autocomplete="off" style="width: 100%">{{ old('ket_lokalis', $penilaianralananak->ket_lokalis) }}</textarea>
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
                        <div class="col-sm-12 pt-1">
                          <div class="form-inline">
                            <textarea class="form-control form-control-sm" rows="2" name="penunjang" autocomplete="off" style="width: 100%">{{ old('penunjang', $penilaianralananak->penunjang) }}</textarea>
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
                            <textarea class="form-control form-control-sm" rows="2" name="diagnosis" autocomplete="off" style="width: 100%">{{ old('diagnosis', $penilaianralananak->diagnosis) }}</textarea>
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
                            <textarea class="form-control form-control-sm" rows="2" name="tata" autocomplete="off" style="width: 100%">{{ old('tata', $penilaianralananak->tata) }}</textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>VII. KONSUL/RUJUK</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group row text-sm"> 
                        <div class="col-sm-12 pt-1">
                            <textarea class="form-control form-control-sm" rows="2" name="konsul" autocomplete="off" style="width: 100%">{{ old('konsul', $penilaianralananak->konsul) }}</textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  {{-- <a href="{{ route('form/hapus', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'PenilaianAwalMedisRawatJalanAnak', 'niplama' => $penilaianralananak->kd_dokter]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')"><i class="fas fa-trash"></i></a> --}}
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
