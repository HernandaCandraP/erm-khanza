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
                <h3 class="card-title">::[ Penilaian Awal Medis Rawat Jalan Dalam ]::</h3>
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
                    <a href="{{ route('form/listdata', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'PenilaianAwalMedisRawatJalanDalam']) }}" class="btn btn-warning btn-sm">List Penilaian Rawat Jalan Dalam</a>
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
                      <div class="col-xs-6 pt-1">&emsp;&emsp; Dokter &emsp;: {{$penilaianralandalam->nama}}</div>
                      <input type="text" name="no_rawat" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rawat}}" hidden>
                      <input type="text" name="nip" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                      <input type="text" name="niplama" class="form-control form-control-sm" value="{{ $penilaianralandalam->kd_dokter }}" hidden>
                      <input type="text" name="tabel" class="form-control form-control-sm" value="PenilaianAwalMedisRawatJalanDalam" hidden>
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
                          <option value="Autoanamnesis" {{ old('anamnesis', $penilaianralandalam->anamnesis) == 'Autoanamnesis' ? 'selected':'' }}>Autoanamnesis</option>
                          <option value="Alloanamnesis" {{ old('anamnesis', $penilaianralandalam->anamnesis) == 'Alloanamnesis' ? 'selected':'' }}>Alloanamnesis</option>
                        </select>
                      </div>
                      <div class="col-xs-2">
                        <input type="text" name="hubungan" class="form-control form-control-sm" autocomplete="off" value="{{ old('hubungan', $penilaianralandalam->hubungan) }}">
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
                            <textarea class="form-control form-control-sm" rows="1" name="keluhan_utama" autocomplete="off" style="width: 100%">{{ old('keluhan_utama', $penilaianralandalam->keluhan_utama) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Penggunaan Obat : 
                            <textarea class="form-control form-control-sm" rows="1" name="rpo" autocomplete="off" style="width: 100%">{{ old('rpo', $penilaianralandalam->rpo) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-inline">Riwayat Penyakit Sekarang : 
                            <textarea class="form-control form-control-sm" rows="1" name="rps" autocomplete="off" style="width: 100%">{{ old('rps', $penilaianralandalam->rps) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Penyakit Dahulu : 
                            <textarea class="form-control form-control-sm" rows="1" name="rpd" autocomplete="off" style="width: 100%">{{ old('rpd', $penilaianralandalam->rpd) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Alergi :  
                            <input type="text" name="alergi" class="form-control form-control-sm" autocomplete="off" style="width: 70%" value="{{ old('alergi', $penilaianralandalam->alergi) }}">
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
                        <div class="col-sm-3"> 
                          Status Nutrisi
                          <div class="input-group-append" >
                            <input type="text" name="status" class="form-control form-control-sm" value="{{ old('status', $penilaianralandalam->status) }}">
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          TD
                          <div class="input-group-append" >
                            <input type="text" name="td" class="form-control form-control-sm" style="width: 80%" value="{{ old('td', $penilaianralandalam->td) }}">
                            <span class="input-group-text text-sm">mmHg</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          Nadi
                          <div class="input-group-append" >
                            <input type="text" name="nadi" class="form-control form-control-sm" style="width: 80%" value="{{ old('nadi', $penilaianralandalam->nadi) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          Suhu
                          <div class="input-group-append" >
                            <input type="text" name="suhu" class="form-control form-control-sm" style="width: 80%" value="{{ old('suhu', $penilaianralandalam->suhu) }}">
                            <span class="input-group-text text-sm">°C</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          RR
                          <div class="input-group-append" >
                            <input type="text" name="rr" class="form-control form-control-sm" style="width: 80%" value="{{ old('rr', $penilaianralandalam->rr) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          BB
                          <div class="input-group-append" >
                            <input type="text" name="bb" class="form-control form-control-sm" style="width: 80%" value="{{ old('bb', $penilaianralandalam->bb) }}">
                            <span class="input-group-text text-sm">Kg</span>
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          Nyeri
                          <div class="input-group-append" >
                            <input type="text" name="nyeri" class="form-control form-control-sm" value="{{ old('nyeri', $penilaianralandalam->nyeri) }}">
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          GCS(E,V,M)
                          <div class="input-group-append" >
                            <input type="text" name="gcs" class="form-control form-control-sm" value="{{ old('gcs', $penilaianralandalam->gcs) }}">
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-inline">Kondisi Umum : 
                            <textarea class="form-control form-control-sm" rows="3" name="kondisi" autocomplete="off" style="width: 100%">{{ old('kondisi', $penilaianralandalam->kondisi) }}</textarea>
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
                        <div class="col-sm-3"> 
                          Kepala&emsp;
                          <select class="form-control form-control-sm" name="kepala">
                            <option value="Normal" {{ old('kepala', $penilaianralandalam->kepala) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('kepala', $penilaianralandalam->kepala) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('kepala', $penilaianralandalam->kepala) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                          <input type="text" name="keterangan_kepala" class="form-control form-control-sm" value="{{ old('keterangan_kepala', $penilaianralandalam->keterangan_kepala) }}">
                        </div>
                        <div class="col-sm-3"> 
                          Thoraks&emsp;
                          <select class="form-control form-control-sm" name="thoraks">
                            <option value="Normal" {{ old('thoraks', $penilaianralandalam->thoraks) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('thoraks', $penilaianralandalam->thoraks) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('thoraks', $penilaianralandalam->thoraks) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                          <input type="text" name="keterangan_thorak" class="form-control form-control-sm" value="{{ old('keterangan_thorak', $penilaianralandalam->keterangan_thorak) }}">
                        </div>
                        <div class="col-sm-3"> 
                          Abdomen&emsp;
                          <select class="form-control form-control-sm" name="abdomen">
                            <option value="Normal" {{ old('abdomen', $penilaianralandalam->abdomen) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('abdomen', $penilaianralandalam->abdomen) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('abdomen', $penilaianralandalam->abdomen) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                          <input type="text" name="keterangan_abdomen" class="form-control form-control-sm" value="{{ old('keterangan_abdomen', $penilaianralandalam->keterangan_abdomen) }}">
                        </div>
                        <div class="col-sm-3"> 
                          Ekstremitas&emsp;
                          <select class="form-control form-control-sm" name="ekstremitas">
                            <option value="Normal" {{ old('ekstremitas', $penilaianralandalam->ekstremitas) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('ekstremitas', $penilaianralandalam->ekstremitas) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('ekstremitas', $penilaianralandalam->ekstremitas) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                          <input type="text" name="keterangan_ekstremitas" class="form-control form-control-sm" value="{{ old('keterangan_ekstremitas', $penilaianralandalam->keterangan_ekstremitas) }}">
                        </div>
                        <div class="col-sm-12">
                          <div class="form-inline">Kondisi Umum : 
                            <textarea class="form-control form-control-sm" rows="3" name="lainnya" autocomplete="off" style="width: 100%">{{ old('lainnya', $penilaianralandalam->lainnya) }}</textarea>
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
                            <textarea class="form-control form-control-sm" rows="2" name="lab" autocomplete="off" style="width: 100%">{{ old('lab', $penilaianralandalam->lab) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-4 pt-1">
                          <div class="form-inline">Radiologi :
                            <textarea class="form-control form-control-sm" rows="2" name="rad" autocomplete="off" style="width: 100%">{{ old('rad', $penilaianralandalam->rad) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-4 pt-1">
                          <div class="form-inline">Penunjang Lainnya :
                            <textarea class="form-control form-control-sm" rows="2" name="penunjanglain" autocomplete="off" style="width: 100%">{{ old('penunjanglain', $penilaianralandalam->penunjanglain) }}</textarea>
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
                            <textarea class="form-control form-control-sm" rows="2" name="diagnosis" autocomplete="off" style="width: 100%">{{ old('diagnosis', $penilaianralandalam->diagnosis) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6 pt-1">
                          <div class="form-inline">Asesmen Banding :
                            <textarea class="form-control form-control-sm" rows="2" name="diagnosis2" autocomplete="off" style="width: 100%">{{ old('diagnosis2', $penilaianralandalam->diagnosis2) }}</textarea>
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
                        <div class="col-sm-6 pt-1">
                          <div class="form-inline">Permasalahan
                            <textarea class="form-control form-control-sm" rows="2" name="permasalahan" autocomplete="off" style="width: 100%">{{ old('permasalahan', $penilaianralandalam->permasalahan) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6 pt-1">
                          <div class="form-inline">Terapi/Pengobatan :
                            <textarea class="form-control form-control-sm" rows="2" name="terapi" autocomplete="off" style="width: 100%">{{ old('terapi', $penilaianralandalam->terapi) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-12 pt-1">
                          <div class="form-inline">Tindakan/Rencana Tindakan
                            <textarea class="form-control form-control-sm" rows="2" name="tindakan" autocomplete="off" style="width: 100%">{{ old('tindakan', $penilaianralandalam->tindakan) }}</textarea>
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
                            <textarea class="form-control form-control-sm" rows="2" name="edukasi" autocomplete="off" style="width: 100%">{{ old('edukasi', $penilaianralandalam->edukasi) }}</textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  {{-- <a href="{{ route('form/hapus', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'PenilaianAwalMedisRawatJalanDalam', 'niplama' => $penilaianralandalam->kd_dokter]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')"><i class="fas fa-trash"></i></a> --}}
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
