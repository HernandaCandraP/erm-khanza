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

          @include('template.menudokter', ['no_rawat' => $no_rawat])
          <div class="col-md-12 mt-4">
          <!-- Horizontal Form -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">::[ Resume Medis Rawat Jalan ]::</h3>
              </div>
              <!-- /.card-header -->
              <form class="form-horizontal">
                <div class="card-header">
                  <div class="row text-sm">
                    <div class="col-sm-6">
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
                      <a href="{{ route('form/listdata', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'DataResumeMedisPasienRawatJalan']) }}" class="btn btn-warning btn-sm">List Data Resume Sebelumnya</a>
                    </div>
                  </div>
                </div>
              </form>
              @include('template.alert')
              <!-- form start -->
              <form action="{{route('form/tambah')}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div  class="card-body">
                  <div class="form-group row ">
                    <label class="col-sm-2 col-form-label-sm">Dokter Penanggung jawab</label>
                    <div class="col-sm-5">
                      : &nbsp; {{ $dataresume->nama }}
                      <input type="text" name="no_rawat" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rawat}}" hidden>
                      <input type="text" name="nip" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                        <input type="text" name="niplama" class="form-control form-control-sm" value="{{ $dataresume->kd_dokter }}" hidden>
                      <input type="text" name="tabel" class="form-control form-control-sm" value="DataResumeMedisPasienRawatJalan" hidden>
                    </div>

                    <label class="col-sm-2 col-form-label-sm">&emsp; Kondisi Pasien Pulang</label>
                    <div class="col-sm-2">
                      <select class="form-control form-control-sm" name="kondisi_pulang">
                        <option value="Hidup" {{ old('kondisi_pulang', $dataresume->kondisi_pulang) == 'Hidup' ? 'selected':'' }}>Hidup</option>
                        <option value="Meninggal" {{ old('kondisi_pulang', $dataresume->kondisi_pulang) == 'Meninggal' ? 'selected':'' }}>Meninggal</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Keluhan Utama Riwayat Penyakit yang Positif</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-sm" rows="3" name="keluhan_utama" autocomplete="off">{{ old('keluhan_utama', $dataresume->keluhan_utama) }}</textarea>
                      @error('keluhan_utama')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Jalannya Penyakit Selama Perawatan</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-sm" rows="3" name="jalannya_penyakit" autocomplete="off">{{ old('jalannya_penyakit', $dataresume->jalannya_penyakit) }}</textarea>
                      @error('jalannya_penyakit')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Pemeriksaan Penunjang yang Positif</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-sm" rows="3" name="pemeriksaan_penunjang" autocomplete="off">{{ old('pemeriksaan_penunjang', $dataresume->pemeriksaan_penunjang) }}</textarea>
                      @error('pemeriksaan_penunjang')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Hasil Laboratorium yang Positif</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-sm" rows="3" name="hasil_laborat" autocomplete="off">{{ old('hasil_laborat', $dataresume->hasil_laborat) }}</textarea>
                      @error('hasil_laborat')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                  </div>
                  <hr>
                  <div class="form-group row">
                    <h3 class="col-sm-10">Diagnosa Akhir</h3>
                    <div class="col-sm-2">Kode ICD</div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Diagnosa Utama</label>
                    <div class="col-sm-8">
                      <input type="text" name="diagnosa_utama" class="form-control form-control-sm" autocomplete="off" value="{{ old('diagnosa_utama', $dataresume->diagnosa_utama) }}">
                      @error('diagnosa_utama')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_diagnosa_utama" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_diagnosa_utama', $dataresume->kd_diagnosa_utama) }}">
                      @error('kd_diagnosa_utama')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Diagnosa Sekunder 1</label>
                    <div class="col-sm-8">
                      <input type="text" name="diagnosa_sekunder" class="form-control form-control-sm" autocomplete="off" value="{{ old('diagnosa_sekunder', $dataresume->diagnosa_sekunder) }}">
                      @error('diagnosa_sekunder')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_diagnosa_sekunder" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_diagnosa_sekunder', $dataresume->kd_diagnosa_sekunder) }}">
                      @error('kd_diagnosa_sekunder')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Diagnosa Sekunder 2</label>
                    <div class="col-sm-8">
                      <input type="text" name="diagnosa_sekunder2" class="form-control form-control-sm" autocomplete="off" value="{{ old('diagnosa_sekunder2', $dataresume->diagnosa_sekunder2) }}">
                      @error('diagnosa_sekunder2')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_diagnosa_sekunder2" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_diagnosa_sekunder2', $dataresume->kd_diagnosa_sekunder2) }}">
                      @error('kd_diagnosa_sekunder2')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Diagnosa Sekunder 3</label>
                    <div class="col-sm-8">
                      <input type="text" name="diagnosa_sekunder3" class="form-control form-control-sm" autocomplete="off" value="{{ old('diagnosa_sekunder3', $dataresume->diagnosa_sekunder3) }}">
                      @error('diagnosa_sekunder3')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_diagnosa_sekunder3" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_diagnosa_sekunder3', $dataresume->kd_diagnosa_sekunder3) }}">
                      @error('kd_diagnosa_sekunder3')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Diagnosa Sekunder 4</label>
                    <div class="col-sm-8">
                      <input type="text" name="diagnosa_sekunder4" class="form-control form-control-sm" autocomplete="off" value="{{ old('diagnosa_sekunder4', $dataresume->diagnosa_sekunder4) }}">
                      @error('diagnosa_sekunder4')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_diagnosa_sekunder4" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_diagnosa_sekunder4', $dataresume->kd_diagnosa_sekunder4) }}">
                      @error('kd_diagnosa_sekunder4')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Prosedur Utama</label>
                    <div class="col-sm-8">
                      <input type="text" name="prosedur_utama" class="form-control form-control-sm" autocomplete="off" value="{{ old('prosedur_utama', $dataresume->diagnosa_sekunder) }}">
                      @error('prosedur_utama')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_prosedur_utama" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_prosedur_utama', $dataresume->kd_diagnosa_sekunder) }}">
                      @error('kd_prosedur_utama')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Prosedur Sekunder 1</label>
                    <div class="col-sm-8">
                      <input type="text" name="prosedur_sekunder" class="form-control form-control-sm" autocomplete="off" value="{{ old('prosedur_sekunder', $dataresume->prosedur_sekunder) }}">
                      @error('prosedur_sekunder')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_prosedur_sekunder" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_prosedur_sekunder', $dataresume->kd_prosedur_sekunder) }}">
                      @error('kd_prosedur_sekunder')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Prosedur Sekunder 2</label>
                    <div class="col-sm-8">
                      <input type="text" name="prosedur_sekunder2" class="form-control form-control-sm" autocomplete="off" value="{{ old('prosedur_sekunder2', $dataresume->prosedur_sekunder2) }}">
                      @error('prosedur_sekunder2')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_prosedur_sekunder2" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_prosedur_sekunder2', $dataresume->kd_prosedur_sekunder2) }}">
                      @error('kd_prosedur_sekunder2')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Prosedur Sekunder 3</label>
                    <div class="col-sm-8">
                      <input type="text" name="prosedur_sekunder3" class="form-control form-control-sm" autocomplete="off" value="{{ old('prosedur_sekunder3', $dataresume->prosedur_sekunder3) }}">
                      @error('prosedur_sekunder3')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_prosedur_sekunder3" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_prosedur_sekunder3', $dataresume->kd_prosedur_sekunder3) }}">
                      @error('kd_prosedur_sekunder3')<p class="text-danger">{{$message}}</p>@enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label-sm">Obat-obatan waktu pulang / nasihat</label>
                    <div class="col-sm-8">
                      <textarea class="form-control form-control-sm" rows="3" name="obat_pulang" autocomplete="off">{{ old('obat_pulang', $dataresume->obat_pulang) }}</textarea>
                      @error('obat_pulang')<p class="text-danger">{{$message}}</p>@enderror</textarea></div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  {{-- <a href="{{ route('form/hapus', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'DataResumeMedisPasienRawatJalan', 'niplama' => $dataresume->kd_dokter]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')"><i class="fas fa-trash"></i></a> --}}
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')" name="action" value="hapus"><i class="fas fa-trash"></i></button>
                  {{-- <button type="submit" class="btn btn-info float-right ml-3">Simpan Data</button> --}}
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
