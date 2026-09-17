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
                <h3 class="card-title">::[ Pemeriksaan EKG ]::</h3>
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
                    <a href="{{ route('formri-listdata', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'PemeriksaanEKG']) }}" class="btn btn-warning btn-sm">Riwayat Pemeriksaan EKG</a>
                  </div>
                  </div>
                </div>
              </form>
              @include('template.alert')
              <!-- form start -->
              <form action="{{route('formri-tambah')}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div  class="card-body">
                  <label class="col-sm-12 col-form-label-sm text-danger"><h5>{{$cekriwayatekg == 0 ? "EKG Belum Tersimpan" :  ""}}</h5><hr></label>
                  <div class="form-group row text-sm">
                    <div class="form-inline row">
                      <div class="col-xs-6 pt-1">&emsp;&emsp; Dokter &emsp;: {{$pem_ekg->nama}} &emsp;&emsp;</div>
                      <input type="text" name="no_rawat" class="form-control" value="{{$detailpasien[0]->no_rawat}}" hidden>
                      <input type="text" name="nip" class="form-control" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                      <input type="text" name="niplama" class="form-control" value="{{ $pem_ekg->kd_dokter }}" hidden>
                      <input type="text" name="tabel" class="form-control" value="HasilPemeriksaanEKG" hidden>
                      <input type="date" name="tgl_perawatan2" class="form-control" placeholder="tgl_perawatan" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" hidden>
                      <input type="time" name="jam_rawat" class="form-control" placeholder="jam_rawat" value="{{Carbon\Carbon::now()->format('H:i:s')}}" hidden>
                    </div>
                    
                    <div class="form-inline row">
                      <div class="col-xs-6 pt-1">&emsp;&emsp; Tanggal &emsp;</div>
                      <div class="col-xs-6 pt-1">
                        <input type="datetime-local" name="tanggal" class="form-control" placeholder="Waktu Selesai" value="{{Carbon\Carbon::now()->format('Y-m-d H:i:s')}}">
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="row">
                            <div class="col-3 text-right">Kiriman Dari</div>
                            <div class="col-9"><input type="text" name="kiriman_dari" class="form-control" autocomplete="off" style="width: 100%" value="{{ old('kiriman_dari', $pem_ekg->kiriman_dari) }}"></div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-3 text-right">Irama</div>
                            <div class="col-9"><input type="text" name="irama" class="form-control" autocomplete="off" style="width: 100%" value="{{ old('irama', $pem_ekg->irama) }}"></div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-3 text-right">Gelombang P</div>
                            <div class="col-9"><input type="text" name="gelombangp" class="form-control" autocomplete="off" style="width: 100%" value="{{ old('gelombangp', $pem_ekg->gelombangp) }}"></div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-3 text-right">Axis</div>
                            <div class="col-9"><input type="text" name="axis" class="form-control" autocomplete="off" style="width: 100%" value="{{ old('axis', $pem_ekg->axis) }}"></div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-3 text-right">Segmen ST</div>
                            <div class="col-5">
                              <div class="form-group">
                                <select class="form-control select2" name="segmenst" id="segmenst">
                                  <option value="Normal" {{ old('segmenst', $pem_ekg->segmenst) == 'Normal' ? 'selected':'' }}>Normal</option>
                                  <option value="Tidak Normal" {{ old('segmenst', $pem_ekg->segmenst) == 'Tidak Normal' ? 'selected':'' }}>Tidak Normal</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="row">
                            <div class="col-3 text-right">Diagnosa Klinis</div>
                            <div class="col-9"><input type="text" name="diagnosa_klinis" class="form-control" autocomplete="off" style="width: 100%" value="{{ old('diagnosa_klinis', $pem_ekg->diagnosa_klinis) }}"></div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-3 text-right">Laju Jantung</div>
                            <div class="col-9"><input type="text" name="laju_jantung" class="form-control" autocomplete="off" style="width: 100%" value="{{ old('laju_jantung', $pem_ekg->laju_jantung) }}"></div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-3 text-right">Interval PR</div>
                            <div class="col-9"><input type="text" name="intervalpr" class="form-control" autocomplete="off" style="width: 100%" value="{{ old('intervalpr', $pem_ekg->intervalpr) }}"></div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-3 text-right">Kompleks QRS</div>
                            <div class="col-9"><input type="text" name="kompleksqrs" class="form-control" autocomplete="off" style="width: 100%" value="{{ old('kompleksqrs', $pem_ekg->kompleksqrs) }}"></div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-3 text-right">Gelombang T</div>
                            <div class="col-5">
                              <div class="form-group">
                                <select class="form-control select2" name="gelombangt" id="gelombangt">
                                  <option value="Normal" {{ old('gelombangt', $pem_ekg->gelombangt) == 'Normal' ? 'selected':'' }}>Normal</option>
                                  <option value="Tidak Normal" {{ old('gelombangt', $pem_ekg->gelombangt) == 'Tidak Normal' ? 'selected':'' }}>Tidak Normal</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          Kesimpulan
                          <textarea class="form-control" rows="9" name="kesimpulan" autocomplete="off" style="width: 100%" required>{{ old('kesimpulan', $pem_ekg->kesimpulan) }}</textarea>
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
</body>
</html>