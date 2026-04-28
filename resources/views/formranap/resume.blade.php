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

          @include('template.menudokterranap', ['no_rawat' => $no_rawat])
          <div class="col-md-12 mt-4">
          <!-- Horizontal Form -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">::[ Resume Medis Rawat Inap ]::</h3>
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
                    {{-- <div class="col-sm-6 pt-3">
                      <a href="{{ route('formri-listdata', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'DataResumeMedisPasienRawatInap']) }}" class="btn btn-warning btn-sm">List Data Resume Sebelumnya</a>
                    </div> --}}
                  </div>
                </div>
              </form>
              @include('template.alert')
              <!-- form start -->
              <form action="{{route('formri-tambah')}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div  class="card-body">
                  <div class="form-group row ">
                    <label class="col-sm-12 col-form-label-sm text-danger"><h5>{{$cekriwayatresume == 0 ? "Resume Belum Tersimpan" :  ""}}</h5><hr></label>
                    <label class="col-sm-2 col-form-label-sm">Dokter Penanggung jawab</label>
                    <div class="col-sm-4">
                      : &nbsp; {{ $dataresume->nama }}
                      <input type="text" name="no_rawat" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rawat}}" hidden>
                      <input type="text" name="nip" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                        <input type="text" name="niplama" class="form-control form-control-sm" value="{{ $dataresume->kd_dokter }}" hidden>
                      <input type="text" name="tabel" class="form-control form-control-sm" value="DataResumeMedisPasienRawatInap" hidden>
                    </div>
                    <label class="col-sm-2 col-form-label-sm">Kamar </label>
                    <div class="col-sm-4 col-form-label-sm">: {{ $detailpasien[0]->kd_kamar }} - {{ $detailpasien[0]->nm_bangsal }}</div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Tanggal Masuk </label>
                    <div class="col-sm-4 col-form-label-sm">: {{ $detailpasien[0]->tgl_masuk }} : {{ $detailpasien[0]->jam_masuk }}</div>
                    <label class="col-sm-2 col-form-label-sm">Tanggal Keluar </label>
                    <div class="col-sm-4 col-form-label-sm">: {{Carbon\Carbon::now()->format('Y-m-d : H:i:s')}}</div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm" hidden>Alasan Masuk Dirawat</label>
                    <div class="col-sm-4" hidden>
                      <input type="text" name="alasan" class="form-control form-control-sm" autocomplete="off" value="{{ old('alasan', $dataresume->alasan) }}">
                    </div>
                    <label class="col-sm-2 col-form-label-sm">Diagnosa Awal Masuk</label>
                    <div class="col-sm-4">
                      <input type="text" name="diagnosa_awal" class="form-control form-control-sm" autocomplete="off" value="{{ old('diagnosa_awal', $detailpasien[0]->diagnosa_awal) }}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Keluhan Utama Riwayat Penyakit</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-sm" rows="3" name="keluhan_utama" autocomplete="off">{{ old('keluhan_utama', $dataresume->keluhan_utama) }}</textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Pemeriksaan Fisik</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-sm" rows="3" name="pemeriksaan_fisik" autocomplete="off">{{ old('pemeriksaan_fisik', $dataresume->pemeriksaan_fisik) }}, {{ old('ket_keadaan', $dataresume->ket_keadaan) }}</textarea>
                    </div>
                  </div>
                  <div class="form-group row" hidden>
                    <label class="col-sm-2 col-form-label-sm">Jalannya Penyakit Selama Perawatan</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-sm" rows="3" name="jalannya_penyakit" autocomplete="off">{{ old('jalannya_penyakit', $dataresume->jalannya_penyakit) }}</textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Pemeriksaan Penunjang & Hasil Lab</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-sm" rows="3" name="pemeriksaan_penunjang" autocomplete="off">{{ old('pemeriksaan_penunjang', $dataresume->pemeriksaan_penunjang) }}</textarea>
                    </div>
                  </div>
                  <div class="form-group row" hidden>
                    <label class="col-sm-2 col-form-label-sm">Hasil Penunjang Lab Terpenting</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-sm" rows="3" name="hasil_laborat" autocomplete="off">{{ old('hasil_laborat', $dataresume->hasil_laborat) }}</textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Terapi / Tindakan Selama Perawatan</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-sm" rows="3" name="tindakan_dan_operasi" autocomplete="off">{{ old('tindakan_dan_operasi', $dataresume->tindakan_dan_operasi) }}</textarea>
                    </div>
                  </div>
                  <div class="form-group row" hidden>
                    <label class="col-sm-2 col-form-label-sm">Obat-obatan Selama Perawatan</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-sm" rows="3" name="obat_di_rs" autocomplete="off">{{ old('obat_di_rs', $dataresume->obat_di_rs) }}</textarea>
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
                    </div>
                    {{-- <div class="col-sm-8 select2-purple">
                      <select class="form-control form-control-sm select2" id="tags" name="diagnosa_utama" >
                        @foreach ($jnspenyakit as $key)
                          <option value="{{ $key->kd_penyakit }}">{{ $key->kd_penyakit }} - {{ $key->nm_penyakit }}</option>
                        @endforeach
                      </select>
                    </div> --}}
                    <div class="col-sm-2">
                      <input type="text" name="kd_diagnosa_utama" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_diagnosa_utama', $dataresume->kd_diagnosa_utama) }}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Diagnosa Sekunder</label>
                    <div class="col-sm-8">
                      <input type="text" name="diagnosa_sekunder" class="form-control form-control-sm" autocomplete="off" value="{{ old('diagnosa_sekunder', $dataresume->diagnosa_sekunder) }}">
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_diagnosa_sekunder" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_diagnosa_sekunder', $dataresume->kd_diagnosa_sekunder) }}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Diagnosa Sekunder 2</label>
                    <div class="col-sm-8">
                      <input type="text" name="diagnosa_sekunder2" class="form-control form-control-sm" autocomplete="off" value="{{ old('diagnosa_sekunder2', $dataresume->diagnosa_sekunder2) }}">
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_diagnosa_sekunder2" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_diagnosa_sekunder2', $dataresume->kd_diagnosa_sekunder2) }}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Diagnosa Sekunder 3</label>
                    <div class="col-sm-8">
                      <input type="text" name="diagnosa_sekunder3" class="form-control form-control-sm" autocomplete="off" value="{{ old('diagnosa_sekunder3', $dataresume->diagnosa_sekunder3) }}">
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_diagnosa_sekunder3" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_diagnosa_sekunder3', $dataresume->kd_diagnosa_sekunder3) }}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Diagnosa Sekunder 4</label>
                    <div class="col-sm-8">
                      <input type="text" name="diagnosa_sekunder4" class="form-control form-control-sm" autocomplete="off" value="{{ old('diagnosa_sekunder4', $dataresume->diagnosa_sekunder4) }}">
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_diagnosa_sekunder4" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_diagnosa_sekunder4', $dataresume->kd_diagnosa_sekunder4) }}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Prosedur</label>
                    <div class="col-sm-8">
                      <input type="text" name="prosedur_utama" class="form-control form-control-sm" autocomplete="off" value="{{ old('prosedur_utama', $dataresume->prosedur_utama) }}">
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_prosedur_utama" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_prosedur_utama', $dataresume->kd_prosedur_utama) }}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Komorbit</label>
                    <div class="col-sm-8">
                      <input type="text" name="prosedur_sekunder" class="form-control form-control-sm" autocomplete="off" value="{{ old('prosedur_sekunder', $dataresume->prosedur_sekunder) }}">
                    </div>
                    <div class="col-sm-2">
                      <input type="text" name="kd_prosedur_sekunder" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_prosedur_sekunder', $dataresume->kd_prosedur_sekunder) }}">
                    </div>
                  </div>
                  <div class="form-group row" hidden>
                    <label class="col-sm-2 col-form-label-sm">Prosedur Sekunder 2</label>
                    <div class="col-sm-8">
                      <input type="text" name="prosedur_sekunder2" class="form-control form-control-sm" autocomplete="off" value="{{ old('prosedur_sekunder2', $dataresume->prosedur_sekunder2) }}">
                    </div>
                    <div class="col-sm-2" hidden>
                      <input type="text" name="kd_prosedur_sekunder2" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_prosedur_sekunder2', $dataresume->kd_prosedur_sekunder2) }}">
                    </div>
                  </div>
                  <div class="form-group row" hidden>
                    <label class="col-sm-2 col-form-label-sm">Prosedur Sekunder 3</label>
                    <div class="col-sm-8">
                      <input type="text" name="prosedur_sekunder3" class="form-control form-control-sm" autocomplete="off" value="{{ old('prosedur_sekunder3', $dataresume->prosedur_sekunder3) }}">
                    </div>
                    <div class="col-sm-2" hidden>
                      <input type="text" name="kd_prosedur_sekunder3" class="form-control form-control-sm" autocomplete="off" value="{{ old('kd_prosedur_sekunder3', $dataresume->kd_prosedur_sekunder3) }}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Alergi</label>
                    <div class="col-sm-10">
                      <input type="text" name="alergi" class="form-control form-control-sm" autocomplete="off" value="{{ old('alergi', $dataresume->alergi) }}">
                    </div>
                  </div>
                  <div class="form-group row" hidden>
                    <label class="col-sm-2 col-form-label-sm">Diet</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-sm" rows="2" name="diet" autocomplete="off">{{ old('diet', $dataresume->diet) }}</textarea>
                    </div>
                  </div>
                  <div class="form-group row" hidden>
                    <label class="col-sm-3 col-form-label-sm">Hasil Lab yang Belum Selesai (Pending)</label>
                    <div class="col-sm-9">
                      <textarea class="form-control form-control-sm" rows="2" name="lab_belum" autocomplete="off">{{ old('lab_belum', $dataresume->lab_belum) }}</textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Edukasi</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-sm" rows="3" name="edukasi" autocomplete="off">{{ old('edukasi', $dataresume->edukasi) }}</textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Obat Pulang</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-sm" rows="2" name="obat_pulang" autocomplete="off">{{ old('obat_pulang', $dataresume->obat_pulang) }}</textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Keadaan Pulang</label>
                    <div class="col-sm-3">
                      <select class="form-control form-control-sm" name="ket_keadaan">
                        <option value="Membaik" {{ old('ket_keadaan', $dataresume->ket_keadaan) == 'Membaik' ? 'selected':'' }}>Membaik</option>
                        <option value="Sembuh" {{ old('ket_keadaan', $dataresume->ket_keadaan) == 'Sembuh' ? 'selected':'' }}>Sembuh</option>
                        <option value="Keadaan Khusus" {{ old('ket_keadaan', $dataresume->ket_keadaan) == 'Keadaan Khusus' ? 'selected':'' }}>Keadaan Khusus</option>
                        <option value="Meninggal" {{ old('ket_keadaan', $dataresume->ket_keadaan) == 'Meninggal' ? 'selected':'' }}>Meninggal</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Cara Keluar</label>
                    <div class="col-sm-3">
                      <select class="form-control form-control-sm" name="cara_keluar">
                        <option value="Atas Izin Dokter" {{ old('cara_keluar', $dataresume->cara_keluar) == 'Atas Izin Dokter' ? 'selected':'' }}>Atas Izin Dokter</option>
                        <option value="Pindah RS" {{ old('cara_keluar', $dataresume->cara_keluar) == 'Pindah RS' ? 'selected':'' }}>Pindah RS</option>
                        <option value="Pulang Atas Permintaan Sendiri" {{ old('cara_keluar', $dataresume->cara_keluar) == 'Pulang Atas Permintaan Sendiri' ? 'selected':'' }}>Pulang Atas Permintaan Sendiri</option>
                        <option value="Lainnya" {{ old('cara_keluar', $dataresume->cara_keluar) == 'Lainnya' ? 'selected':'' }}>Lainnya</option>
                      </select>
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="ket_keluar" class="form-control form-control-sm" autocomplete="off" value="{{ old('ket_keluar', $dataresume->ket_keluar) }}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Dilanjutkan</label>
                    <div class="col-sm-3">
                      <select class="form-control form-control-sm" name="dilanjutkan">
                        <option value="Kembali Ke RS" {{ old('dilanjutkan', $dataresume->dilanjutkan) == 'Kembali Ke RS' ? 'selected':'' }}>Kembali Ke RS</option>
                        <option value="RS Lain" {{ old('dilanjutkan', $dataresume->dilanjutkan) == 'RS Lain' ? 'selected':'' }}>RS Lain</option>
                        <option value="Dokter Luar" {{ old('dilanjutkan', $dataresume->dilanjutkan) == 'Dokter Luar' ? 'selected':'' }}>Dokter Luar</option>
                        <option value="Puskesmes" {{ old('dilanjutkan', $dataresume->dilanjutkan) == 'Puskesmes' ? 'selected':'' }}>Puskesmas</option>
                        <option value="Lainnya" {{ old('dilanjutkan', $dataresume->dilanjutkan) == 'Lainnya' ? 'selected':'' }}>Lainnya</option>
                      </select>
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="ket_dilanjutkan" class="form-control form-control-sm" autocomplete="off" value="{{ old('ket_dilanjutkan', $dataresume->ket_dilanjutkan) }}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label-sm">Tanggal & Jam Kontrol &emsp;</label>
                    <div class="col-sm-3">
                      <input type="datetime-local" name="kontrol" class="form-control form-control-sm" placeholder="Waktu Selesai" value="{{Carbon\Carbon::now()->format('Y-m-d H:i:s')}}">
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  {{-- <a href="{{ route('form/hapus', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'DataResumeMedisPasienRawatInap', 'niplama' => $dataresume->kd_dokter]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')"><i class="fas fa-trash"></i></a> --}}
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')" name="action" value="hapus"><i class="fas fa-trash"></i></button>
                  {{-- <button type="submit" class="btn btn-info float-right ml-3">Simpan Data</button> --}}
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
</script>
</body>
</html>