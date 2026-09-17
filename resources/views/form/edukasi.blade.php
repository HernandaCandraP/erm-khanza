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
                <h3 class="card-title">::[ Edukasi ]::</h3>
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
                    {{-- <a href="{{ route('form/listdata', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'PemeriksaanEKG']) }}" class="btn btn-warning btn-sm">Riwayat Pemeriksaan EKG</a> --}}
                  </div>
                  </div>
                </div>
              </form>
              @include('template.alert')
              <!-- form start -->
              <form action="{{route('form/tambah')}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div  class="card-body">
                  {{-- <label class="col-sm-12 col-form-label-sm text-danger"><h5>{{$cekriwayatekg == 0 ? "EKG Belum Tersimpan" :  ""}}</h5><hr></label> --}}
                  <div class="form-group row text-sm">
                    <div class="form-inline row">
                      <div class="col-xs-6 pt-1">&emsp;&emsp; Dokter &emsp;: {{$edukasi->nama}} &emsp;&emsp;</div>
                      <input type="text" name="no_rawat" class="form-control" value="{{$detailpasien[0]->no_rawat}}" hidden>
                      <input type="text" name="nip" class="form-control" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                      <input type="text" name="niplama" class="form-control" value="{{ $edukasi->nik }}" hidden>
                      <input type="text" name="tabel" class="form-control" value="HasilEdukasi" hidden>
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
                      <div class="row text-sm"> 
                        <div class="col-sm-12"> 
                          Materi
                          <div class="input-group-append" >
                            <textarea class="form-control" rows="3" name="materi_edukasi" autocomplete="off" style="width: 100%" required>{{ old('materi_edukasi', $edukasi->materi_edukasi) }}</textarea>
                          </div>
                        </div>
                        <div class="col-sm-5"> 
                          Keterangan
                          <div class="input-group-append" >
                            <input type="text" name="keterangan" class="form-control form-control-sm" autocomplete="off" style="width: 100%" value="{{ old('keterangan', $edukasi->keterangan) }}">
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          Lama Edukasi
                          <div class="input-group-append" >
                            <input type="text" name="lama_edukasi" class="form-control" autocomplete="off" style="width: 70%" value="{{ old('keterangan', $edukasi->keterangan) }}">
                            <span class="input-group-text text-sm">menit</span>
                          </div>
                        </div>
                        <div class="col-sm-4"> 
                          Status
                          <select class="form-control form-control-sm" name="status" id="status">
                            <option value="Awal" {{ old('status', $edukasi->status) == 'Awal' ? 'selected':'' }}>Awal</option>
                            <option value="Ulang" {{ old('status', $edukasi->status) == 'Ulang' ? 'selected':'' }}>Ulang</option>
                          </select>
                        </div>
                        <div class="col-sm-4"> 
                          Kepada
                          <select class="form-control form-control-sm" name="diberikan_pada" id="diberikan_pada">
                            <option value="Pasien" {{ old('diberikan_pada', $edukasi->diberikan_pada) == 'Pasien' ? 'selected':'' }}>Pasien</option>
                            <option value="Keluarga" {{ old('diberikan_pada', $edukasi->diberikan_pada) == 'Keluarga' ? 'selected':'' }}>Keluarga</option>
                            <option value="Lain-lain" {{ old('diberikan_pada', $edukasi->diberikan_pada) == 'Lain-lain' ? 'selected':'' }}>Lain-lain</option>
                          </select>
                          <input type="text" name="keterangan_diberikan_pada" class="form-control form-control-sm" autocomplete="off" value="{{ old('keterangan_diberikan_pada', $edukasi->keterangan_diberikan_pada) }}">
                        </div>
                        <div class="col-sm-4"> 
                          Metode
                          <select class="form-control form-control-sm" name="metode_edukasi" id="metode_edukasi">
                            <option value="Ceramah" {{ old('metode_edukasi', $edukasi->metode_edukasi) == 'Ceramah' ? 'selected':'' }}>Ceramah</option>
                            <option value="Diskusi" {{ old('metode_edukasi', $edukasi->metode_edukasi) == 'Diskusi' ? 'selected':'' }}>Diskusi</option>
                            <option value="Demonstrasi" {{ old('metode_edukasi', $edukasi->metode_edukasi) == 'Demonstrasi' ? 'selected':'' }}>Demonstrasi</option>
                          </select>
                        </div>
                        <div class="col-sm-4"> 
                          Hasil
                          <select class="form-control form-control-sm" name="hasil_verifikasi" id="hasil_verifikasi">
                            <option value="Sudah Mengerti" {{ old('hasil_verifikasi', $edukasi->hasil_verifikasi) == 'Sudah Mengerti' ? 'selected':'' }}>Sudah Mengerti</option>
                            <option value="Re-Edukasi" {{ old('hasil_verifikasi', $edukasi->hasil_verifikasi) == 'Re-Edukasi' ? 'selected':'' }}>Re-Edukasi</option>
                            <option value="Re-Demontrasi" {{ old('hasil_verifikasi', $edukasi->hasil_verifikasi) == 'Re-Demontrasi' ? 'selected':'' }}>Re-Demontrasi</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  {{-- <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')" name="action" value="hapus"><i class="fas fa-trash"></i></button> --}}
                  <button type="submit" class="btn btn-info float-right ml-3" name="action" value="simpan">Simpan</button>
                  {{-- <button type="submit" class="btn btn-warning float-right ml-3" name="action" value="update_with_time">Ganti</button> --}}
                </div>
                <!-- /.card-footer -->
              </form>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-sm">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No Rawat</th>
                    <th>Tgl. Perawatan</th>
                    <th>Nama</th>
                    <th>Materi Edukasi</th>
                    <th>Keterangan</th>
                    <th>Diberikan Kepada</th>
                    <th>Lama Edukasi</th>
                    <th>Metode Edukasi</th>
                    <th>Hasil Verifikasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($list_pelaksanaan_edu as $no => $key)
                    <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td>{{$key->no_rawat}}</td>
                        <td>{{$key->tanggal}}</td>
                        <td>{{$key->nama}}</td>
                        <td>{{$key->materi_edukasi}}</td>
                        <td>{{$key->keterangan}}</td>
                        <td>{{$key->diberikan_pada}} - {{$key->keterangan_diberikan_pada}}</td>
                        <td>{{$key->lama_edukasi}}</td>
                        <td>{{$key->metode_edukasi}}</td>
                        <td>{{$key->hasil_verifikasi}}</td>
                        <td>{{$key->status}}</td>
                        <td>
                          <form action="{{route('edukasi.hapus', ['no_rawat' => $key->no_rawat, 'tanggal' => $key->tanggal])}}" method="post" class="form-horizontal">
                          {{ csrf_field() }}
                              <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah yakin ingin menghapus')" ><i class="fas fa-trash"></i></button>
                          </form>
                        </td>
                    </tr> 
                    @endforeach
                  
                  </tbody>
                </table>
              </div>
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