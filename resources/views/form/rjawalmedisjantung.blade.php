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
                <h3 class="card-title">::[ Penilaian Awal Medis Rawat Jalan Jantung ]::</h3>
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
                    <a href="{{ route('form/listdata', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'PenilaianAwalMedisRawatJalanJantung']) }}" class="btn btn-warning btn-sm">List Penilaian Rawat Jalan Jantung</a>
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
                      <div class="col-xs-6 pt-1">&emsp;&emsp; Dokter &emsp;: {{$penilaianmedisralan->nama}}</div>
                      <input type="text" name="no_rawat" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rawat}}" hidden>
                      <input type="text" name="nip" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                      <input type="text" name="niplama" class="form-control form-control-sm" value="{{ $penilaianmedisralan->kd_dokter }}" hidden>
                      <input type="text" name="tabel" class="form-control form-control-sm" value="PenilaianAwalMedisRawatJalanJantung" hidden>
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
                          <option value="Autoanamnesis" {{ old('anamnesis', $penilaianmedisralan->anamnesis) == 'Autoanamnesis' ? 'selected':'' }}>Autoanamnesis</option>
                          <option value="Alloanamnesis" {{ old('anamnesis', $penilaianmedisralan->anamnesis) == 'Alloanamnesis' ? 'selected':'' }}>Alloanamnesis</option>
                        </select>
                      </div>
                      <div class="col-xs-2">
                        <input type="text" name="hubungan" class="form-control form-control-sm" autocomplete="off" value="{{ old('hubungan', $penilaianmedisralan->hubungan) }}">
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
                            <textarea class="form-control form-control-sm" rows="3" name="keluhan_utama" autocomplete="off" style="width: 100%" required>{{ old('keluhan_utama', $penilaianmedisralan->keluhan_utama) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Penyakit Keluarga : 
                            <textarea class="form-control form-control-sm" rows="3" name="rpk" autocomplete="off" style="width: 100%">{{ old('rpk', $penilaianmedisralan->rpk) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Penggunaan Obat : 
                            <textarea class="form-control form-control-sm" rows="3" name="rpo" autocomplete="off" style="width: 100%">{{ old('rpo', $penilaianmedisralan->rpo) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-inline">Riwayat Penyakit Sekarang : 
                            <textarea class="form-control form-control-sm" rows="3" name="rps" autocomplete="off" style="width: 100%" required>{{ old('rps', $penilaianmedisralan->rps) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Penyakit Dahulu : 
                            <textarea class="form-control form-control-sm" rows="3" name="rpd" autocomplete="off" style="width: 100%">{{ old('rpd', $penilaianmedisralan->rpd) }}</textarea>
                          </div>
                          <div class="form-inline pt-2">Riwayat Alergi :  
                            <input type="text" name="alergi" class="form-control form-control-sm" autocomplete="off" style="width: 70%" value="{{ old('alergi', $penilaianmedisralan->alergi) }}">
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
                            <input type="text" name="td" class="form-control form-control-sm" style="width: 80%" value="{{ old('td', $penilaianmedisralan->td) }}">
                            <span class="input-group-text text-sm">mmHg</span>
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          BB
                          <div class="input-group-append" >
                            <input type="text" name="bb" class="form-control form-control-sm" style="width: 80%" value="{{ old('bb', $penilaianmedisralan->bb) }}">
                            <span class="input-group-text text-sm">Kg</span>
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          TB
                          <div class="input-group-append" >
                            <input type="text" name="tb" class="form-control form-control-sm" style="width: 80%" value="{{ old('bb', $penilaianmedisralan->bb) }}">
                            <span class="input-group-text text-sm">Cm</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          Suhu
                          <div class="input-group-append" >
                            <input type="text" name="suhu" class="form-control form-control-sm" style="width: 80%" value="{{ old('suhu', $penilaianmedisralan->suhu) }}">
                            <span class="input-group-text text-sm">°C</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          Nadi
                          <div class="input-group-append" >
                            <input type="text" name="nadi" class="form-control form-control-sm" style="width: 80%" value="{{ old('nadi', $penilaianmedisralan->nadi) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-2"> 
                          RR
                          <div class="input-group-append" >
                            <input type="text" name="rr" class="form-control form-control-sm" style="width: 80%" value="{{ old('rr', $penilaianmedisralan->rr) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          Keadaan Umum
                          <div class="col-xs-4 pt-1">
                            <select class="form-control form-control-sm" name="anamnesis">
                              <option value="Sehat" {{ old('anamnesis', $penilaianmedisralan->anamnesis) == 'Sehat' ? 'selected':'' }}>Sehat</option>
                              <option value="Sakit Ringan" {{ old('anamnesis', $penilaianmedisralan->anamnesis) == 'Sakit Ringan' ? 'selected':'' }}>Sakit Ringan</option>
                              <option value="Sakit Sedang" {{ old('anamnesis', $penilaianmedisralan->anamnesis) == 'Sakit Sedang' ? 'selected':'' }}>Sakit Sedang</option>
                              <option value="Sakit Berat" {{ old('anamnesis', $penilaianmedisralan->anamnesis) == 'Sakit Berat' ? 'selected':'' }}>Sakit Berat</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          Nyeri
                          <div class="input-group-append" >
                            <input type="text" name="nyeri" class="form-control form-control-sm" value="{{ old('nyeri', $penilaianmedisralan->nyeri) }}">
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          Status Nutrisi
                          <div class="input-group-append" >
                            <input type="text" name="status_nutrisi" class="form-control form-control-sm" value="{{ old('status_nutrisi', $penilaianmedisralan->status_nutrisi) }}">
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
                        <div class="col-sm-4"> 
                          Jantung&emsp;
                          <select class="form-control form-control-sm" name="jantung">
                            <option value="Normal" {{ old('jantung', $penilaianmedisralan->jantung) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('jantung', $penilaianmedisralan->jantung) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('jantung', $penilaianmedisralan->jantung) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                          <input type="text" name="keterangan_jantung" class="form-control form-control-sm" value="{{ old('keterangan_jantung', $penilaianmedisralan->keterangan_jantung) }}">
                        </div>
                        <div class="col-sm-4"> 
                          Paru&emsp;
                          <select class="form-control form-control-sm" name="paru">
                            <option value="Normal" {{ old('paru', $penilaianmedisralan->paru) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('paru', $penilaianmedisralan->paru) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('paru', $penilaianmedisralan->paru) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                          <input type="text" name="keterangan_paru" class="form-control form-control-sm" value="{{ old('keterangan_paru', $penilaianmedisralan->keterangan_paru) }}">
                        </div>
                        <div class="col-sm-4"> 
                          Ekstrimitas&emsp;
                          <select class="form-control form-control-sm" name="ekstrimitas">
                            <option value="Normal" {{ old('ekstrimitas', $penilaianmedisralan->ekstrimitas) == 'Normal' ? 'selected':'' }}>Normal</option>
                            <option value="Abnormal" {{ old('ekstrimitas', $penilaianmedisralan->ekstrimitas) == 'Abnormal' ? 'selected':'' }}>Abnormal</option>
                            <option value="Tidak Diperiksa" {{ old('ekstrimitas', $penilaianmedisralan->ekstrimitas) == 'Tidak Diperiksa' ? 'selected':'' }}>Tidak Diperiksa</option>
                          </select>
                          <input type="text" name="keterangan_ekstrimitas" class="form-control form-control-sm" value="{{ old('keterangan_ekstrimitas', $penilaianmedisralan->keterangan_ekstrimitas) }}">
                        </div>
                        <div class="col-sm-12">
                          <div class="form-inline">Lainnya : 
                            <textarea class="form-control form-control-sm" rows="3" name="lainnya" autocomplete="off" style="width: 100%">{{ old('lainnya', $penilaianmedisralan->lainnya) }}</textarea>
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
                            <textarea class="form-control form-control-sm" rows="2" name="lab" autocomplete="off" style="width: 100%">{{ old('lab', $penilaianmedisralan->lab) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-4 pt-1">
                          <div class="form-inline">EKG :
                            <textarea class="form-control form-control-sm" rows="2" name="ekg" autocomplete="off" style="width: 100%">{{ old('ekg', $penilaianmedisralan->ekg) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-4 pt-1">
                          <div class="form-inline">Penunjang Lainnya :
                            <textarea class="form-control form-control-sm" rows="2" name="penunjang_lain" autocomplete="off" style="width: 100%">{{ old('penunjang_lain', $penilaianmedisralan->penunjang_lain) }}</textarea>
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
                            <textarea class="form-control form-control-sm" rows="4" name="diagnosis" autocomplete="off" style="width: 100%" required>{{ old('diagnosis', $penilaianmedisralan->diagnosis) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-6 pt-1" hidden>
                          <div class="form-inline">Asesmen Banding :
                            <textarea class="form-control form-control-sm" rows="2" name="diagnosis2" autocomplete="off" style="width: 100%">{{ old('diagnosis2', $penilaianmedisralan->diagnosis2) }}</textarea>
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
                        <div class="col-sm-6 pt-1" hidden>
                          <div class="form-inline">Permasalahan
                            <textarea class="form-control form-control-sm" rows="2" name="permasalahan" autocomplete="off" style="width: 100%">{{ old('permasalahan', $penilaianmedisralan->permasalahan) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-12 pt-1">
                          <div class="form-inline">Terapi/Pengobatan :
                            <textarea class="form-control form-control-sm" rows="4" name="terapi" autocomplete="off" style="width: 100%">{{ old('terapi', $penilaianmedisralan->terapi) }}</textarea>
                          </div>
                          <table id="testing" class="table table-bordered table-striped text-sm">
                          </table>
                          <div class="row text-sm"> 
                            <div class="col-sm-10">
                              <div type="text" class="form-control btn btn-warning btn-sm addmore">+ Tambah Obat Rawat Jalan</div>
                            </div>
                            <div class="col-sm-2">
                              <div type="text" class="form-control btn btn-success btn-sm cekharga">Cek Harga</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-12 pt-4">
                          <div class="form-inline">Tindakan/Rencana Tindakan
                            <textarea class="form-control form-control-sm" rows="2" name="tindakan" autocomplete="off" style="width: 100%">{{ old('tindakan', $penilaianmedisralan->tindakan) }}</textarea>
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
                            <textarea class="form-control form-control-sm" rows="2" name="edukasi" autocomplete="off" style="width: 100%">{{ old('edukasi', $penilaianmedisralan->edukasi) }}</textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')" name="action" value="hapus"><i class="fas fa-trash"></i></button>
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
@include('template.script_resep')
</body>
</html>
