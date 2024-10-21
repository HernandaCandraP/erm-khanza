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
          <div class="col-md-12 mt-4">
          <!-- Horizontal Form -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">::[ Form Radiologi ]::</h3>
              </div>
              <!-- /.card-header -->
              <form class="form-horizontal">
                <div class="card-header">
                  <div class="row text-sm">
                    <div class="col-sm-6">
                      <div class="row">
                        <div class="col-5 text-right"><b>Nama</b></div>
                        <div class="col-7">&nbsp; {{$pasienrad[0]->nm_pasien}}</div>
                        <div class="col-5 text-right"><b>No. RM</b></div>
                        <div class="col-7">&nbsp; {{$pasienrad[0]->no_rkm_medis}}</div>
                        <div class="col-5 text-right"><b>No. Rawat</b></div>
                        <div class="col-7">&nbsp; {{$pasienrad[0]->no_rawat}}</div>
                        <div class="col-5 text-right"><b>Umur</b></div>
                        <div class="col-7">&nbsp; {{$pasienrad[0]->umur}}</div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="row">
                        <div class="col-3 text-right"><b>Tanggal Periksa</b></div>
                        <div class="col-7">&nbsp; {{$pasienrad[0]->tgl_periksa}}</div>
                        <div class="col-3 text-right"><b>Jam Periksa</b></div>
                        <div class="col-7">&nbsp; {{$pasienrad[0]->jam}}</div>
                        <div class="col-3 text-right"><b>Status</b></div>
                        <div class="col-7">&nbsp; {{$pasienrad[0]->status}}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              @include('template.alert')
              <!-- form start -->
              <form action="{{route('formrad-tambah')}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div  class="card-body">
                  <div class="form-group row text-sm">
                    <div class="col-sm-12">


                      @foreach ($pasienrad as $key => $value)
                        <div class="form-group row">
                          <div class="col-sm-12 p-2 bg-info h6">Nama Pemeriksaan : {{$value->kd_jenis_prw}} - {{$value->nm_perawatan}}</div>
                          <div class="col-sm-2"> Proyeksi
                            <div class="input-group-append" >
                              <input type="text" name="scores[{{$key}}][proyeksi]" class="form-control form-control-sm" style="width: 80%" value="{{ old('proyeksi', $value->proyeksi) }}">
                              <input type="text" name="tabel" class="form-control form-control-sm" value="PenilaianRadiologi" hidden>
                              <input type="text" name="scores[{{$key}}][kd_jenis_prw]" class="form-control form-control-sm" value="{{$value->kd_jenis_prw}}" hidden>
                              <input type="text" name="no_rawat" class="form-control form-control-sm" value="{{$value->no_rawat}}" hidden>
                              <input type="text" name="tgl_periksa" class="form-control form-control-sm" value="{{$value->tgl_periksa}}" hidden>
                              <input type="text" name="jam" class="form-control form-control-sm" value="{{$value->jam}}" hidden>
                            </div>
                          </div>
                          <div class="col-sm-2"> kV
                            <div class="input-group-append" >
                              <input type="text" name="scores[{{$key}}][kV]" class="form-control form-control-sm" style="width: 80%" value="{{ old('kV', $value->kV) }}">
                            </div>
                          </div>
                          <div class="col-sm-2"> mAS
                            <div class="input-group-append" >
                              <input type="text" name="scores[{{$key}}][mAS]" class="form-control form-control-sm" style="width: 80%" value="{{ old('mAS', $value->mAS) }}">
                            </div>
                          </div>
                          <div class="col-sm-2"> FFD
                            <div class="input-group-append" >
                              <input type="text" name="scores[{{$key}}][FFD]" class="form-control form-control-sm" style="width: 80%" value="{{ old('FFD', $value->FFD) }}">
                            </div>
                          </div>
                          <div class="col-sm-2"> BSF
                            <div class="input-group-append" >
                              <input type="text" name="scores[{{$key}}][BSF]" class="form-control form-control-sm" style="width: 80%" value="{{ old('BSF', $value->BSF) }}">
                            </div>
                          </div>
                          <div class="col-sm-2"> Inak
                            <div class="input-group-append" >
                              <input type="text" name="scores[{{$key}}][inak]" class="form-control form-control-sm" style="width: 80%" value="{{ old('inak', $value->inak) }}">
                            </div>
                          </div>
                          <div class="pt-2 col-sm-2"> Jml Penyinaran
                            <div class="input-group-append" >
                              <input type="text" name="scores[{{$key}}][jml_penyinaran]" class="form-control form-control-sm" style="width: 80%" value="{{ old('jml_penyinaran', $value->jml_penyinaran) }}">
                            </div>
                          </div>
                          <div class="pt-2 col-sm-2"> Dosis Radiasi
                            <div class="input-group-append" >
                              <input type="text" name="scores[{{$key}}][dosis]" class="form-control form-control-sm" style="width: 80%" value="{{ old('dosis', $value->dosis) }}">
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group row">
                        <div class="col-sm-12 p-2 bg-info h6">Hasil</div>
                        <div class="col-sm-12">
                          <textarea class="form-control form-control-sm" rows="15" name="hasil" autocomplete="off" style="width: 100%">{{ old('hasil', $hasilrad->hasil ?? null) }}</textarea>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group row">
                        <div class="col-sm-12 p-2 bg-info h6">Gambar</div>
                        @if($gambarrad)   
                          @foreach ($gambarrad as $key => $value)
                            <div class="col-sm-6">
                              <img src="{{$value}}" class="img-fluid w-100"/><br>
                            </div>
                          @endforeach 
                        @else
                          <div class="col-sm-6">Gambar Radiologi tidak tersedia, silahkan hubungi petugas radiologi</div> 
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-warning float-right ml-3" name="action" value="update_with_time">Simpan</button>
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
  $(document).ready(function() {
    myFunction();
  });

  var myFunction = function(){
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  }
</script>
</body>
</html>
