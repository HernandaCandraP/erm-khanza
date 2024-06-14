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
                <h3 class="card-title">::[ Permintaan Radiologi ]::</h3>
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
                      <input type="text" name="tabel" class="form-control form-control-sm" value="PermintaanRadiologi" hidden>
                    </div>

                    <div class="form-inline row text-sm pb-2">
                      <div class="col-xs-3 pt-1">&emsp;&emsp; Tanggal &emsp;</div>
                      <div class="col-xs-3 pt-1">
                        <input type="date" name="tgl_permintaan" class="form-control form-control-sm" placeholder="tgl_permintaan" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                      </div>
                      <div class="col-xs-3 pt-1">&emsp;&emsp; Jam &emsp;</div>
                      <div class="col-xs-3 pt-1">
                        <input type="time" name="jam_permintaan" class="form-control form-control-sm" placeholder="jam_permintaan" value="{{Carbon\Carbon::now()->format('H:i:s')}}">
                      </div>
                    </div>
                  </div>


                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>Data Pemeriksaan</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group row text-sm"> 
                        <div class="col-sm-12"> 
                          <div class="">Informasi Tambahan Permintaan Foto 
                            <input type="text" name="informasi_tambahanrad" class="form-control form-control-sm" autocomplete="off" style="width: 100%">
                          </div>
                          <div class=" pt-2">Indikasi Pemeriksaan / Diagnosisi Klinis
                            <input type="text" name="diagnosa_klinisrad" class="form-control form-control-sm" autocomplete="off" style="width: 100%">
                          </div>
                        </div>
                        <div class="col-sm-12 pt-2 select2-purple">
                          Pemeriksaan
                          <select class="select2 form-control form-control-sm" multiple="multiple" name="pemeriksaanrad[]" id="pemeriksaanrad" data-placeholder="Pilih Pemeriksaan" data-dropdown-css-class="select2-purple" required>
                            @foreach ($jnsperawatanrad as $key)
                              <option value="{{ $key->kd_jenis_prw }}"> {{ $key->kd_jenis_prw }} - {{$key->nm_perawatan}} </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info float-right ml-3" name="action" value="simpan">Simpan</button>
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
