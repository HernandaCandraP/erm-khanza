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
                <h3 class="card-title">::[ Penanganan Dokter ]::</h3>
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
                </div>
              </form>
              @include('template.alert')
              <!-- form start -->
              <form action="{{route('form/tambah')}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div  class="card-body">
                  <div class="form-group row text-sm">
                    <div class="form-inline row">
                      {{-- <div class="col-xs-6 pt-1">&emsp;&emsp; Dokter Perujuk &emsp;: {{$penilaianralanparu->nama}}</div> --}}
                      <input type="text" name="no_rawat" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rawat}}" hidden>
                      <input type="text" name="nip" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                      <input type="text" name="niplama" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik }}" hidden>
                      <input type="text" name="tabel" class="form-control form-control-sm" value="PenangananDokter" hidden>
                    </div>

                    <div class="form-inline row text-sm pb-2">
                      <div class="col-xs-3 pt-1">&emsp;&emsp; Tanggal &emsp;</div>
                      <div class="col-xs-3 pt-1">
                        <input type="date" name="tgl_perawatan" class="form-control form-control-sm" placeholder="tgl_permintaan" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                      </div>
                      <div class="col-xs-3 pt-1">&emsp;&emsp; Jam &emsp;</div>
                      <div class="col-xs-3 pt-1">
                        <input type="time" name="jam_rawat" class="form-control form-control-sm" placeholder="jam_permintaan" value="{{Carbon\Carbon::now()->format('H:i:s')}}">
                      </div>
                    </div>
                  </div>


                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>Data Penanganan</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group row text-sm"> 
                        <div class="col-sm-12 pt-2 select2-purple">
                          Penanganan
                          <select required class="select2 form-control form-control-sm" multiple="multiple" name="pemeriksaan[]" id="pemeriksaan" data-placeholder="Pilih Pemeriksaan" data-dropdown-css-class="select2-purple">
                            @foreach ($jnspenanganan as $key)
                              <option value="{{ $key->kd_jenis_prw }}"> {{ $key->kd_jenis_prw }} - {{$key->nm_perawatan}} </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <button type="submit" class="btn btn-info float-right ml-3" name="action" value="simpan">Simpan</button>
                    </div>
                  </div>

                </div>
                <!-- /.card-body -->
              </form>
              <div  class="card-body">
                <div class="card card-primary card-outline">
                  <div class="card-header">
                    <h6 class="card-title text-sm">
                      <b>List Hasil Penanganan Dokter</b>
                    </h6>
                  </div>
                  <div class="card-footer">
                    <table id="example1" class="table table-bordered table-striped text-sm">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>No Rawat</th>
                        <th>Perawatan/Tindakan</th>
                        <th>Dokter yang Menangani</th>
                        <th>Tgl. Rawat</th>
                        <th>Jam Rawat</th>
                        <th>Biaya</th>
                        <th>Aksi</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach ($getpenanganandr as $no => $key)
                        <tr>
                            <th scope="row"> {{$no+1}}. </th>
                            <td>{{$key->no_rawat}}</td>
                            <td>{{$key->nm_perawatan}}</td>
                            <td>{{$key->nama}}</td>
                            <td>{{$key->tgl_perawatan}}</td>
                            <td>{{$key->jam_rawat}}</td>
                            <td>{{$key->biaya_rawat}}</td>
                            <td><a href="{{ route('form/hapus', ['no_rawat' => $key->no_rawat, 'tabel' => 'PenangananDokter', 'niplama' => $key->kd_dokter, 'tgl_perawatan_lama' => $key->tgl_perawatan, 'jam_rawat_lama' => $key->jam_rawat, 'kd_jenis_prw' => $key->kd_jenis_prw]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')"><i class="fas fa-trash"></i></a></td>
                        </tr> 
                        @endforeach
                      
                      </tbody>
                    </table>
                  </div>
                </div>
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
