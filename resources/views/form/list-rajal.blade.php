<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
    <link rel="stylesheet" href="{{asset('tampilan/plugins/daterangepicker/daterangepicker.css')}}">
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
          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="float-left">Pencarian Berdasarkan Tanggal</h5>
              </div>
              <!-- /.card-header -->
              <form action="{{route('menu-rajal')}}" method="post" class="form-horizontal">
              {{ csrf_field() }}
              <div class="card-body row">
                <div class="input-group col-lg-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" name="tanggal" class="form-control float-right" id="reservation">
                </div>
                <button type="submit" class="btn btn-success float-right ml-3" name="action" value="simpan">Cari Data</button>
              </div>
              </form>
              
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="float-left">Antrian {{Session::get('nmpoli')}} <b>{{$tanggalawal ?? null}} - {{$tanggalakhir ?? null}}</b></h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Rawat</th>
                    <th>No. RM</th>
                    <th>Nama Lengkap</th>
                    <th>Dokter Dituju</th>
                    <th>Poli</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($pasienterakhir as $no => $key)
                    <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <th>
                          @if ($key->status_poli == 'Barudihapus' || Session::get('getpoli') == '-')
                            <a href="{{ route('form-awalmedis'. strtolower(Session::get('nm_sps')), ['no_rawat' => $key->no_rawat]) }}" class="btn btn-block btn-primary btn-xs">{{$key->no_rawat}}</a>
                          @else
                            <a href="{{ route('form-soap', ['no_rawat' => $key->no_rawat]) }}" class="btn btn-block btn-primary btn-xs">{{$key->no_rawat}}</a>
                          @endif
                          <div class="text-center">{{$key->no_peserta}}</div>
                        </th>
                        <td>{{$key->no_rkm_medis}}</td>
                        <td>
                          {{-- <a href="{{ route('pasien/ralan', ['no_rawat' => $key->no_rawat]) }}">{{$key->nm_pasien}}</a> --}}
                          {{$key->nm_pasien}} 
                          <span style="color: blue; font-size: 13px;">({{$key->status_poli}} , {{$key->png_jawab}}) <br> {{$key->alamat}}, {{$key->nm_kel}}, {{$key->nm_kec}}</span>
                        </td>
                        <td>{{$key->nm_dokter}}</td>
                        <td>{{Session::get('nmpoli')}}</td>
                        <th>
                          @if ($key->stts == 'Belum' )
                            <p class="text-danger">Belum Diperiksa</p>
                          @else
                            <p class="text-success">Sudah Diperiksa</p>
                          @endif
                        </th>
                    </tr> 
                    @endforeach
                  
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="float-left">Rujukan Internal {{Session::get('nmpoli')}}</h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Rawat</th>
                    <th>No. RM</th>
                    <th>Nama Lengkap</th>
                    <th>Poli Pengirim</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($rujukaninternal as $no => $key)
                    <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <th><a href="{{ route('form-soap', ['no_rawat' => $key->no_rawat]) }}" class="btn btn-block btn-primary btn-xs">{{$key->no_rawat}}</a></th>
                        <td>{{$key->no_rkm_medis}}</td>
                        <td>
                          {{-- <a href="{{ route('pasien/ralan', ['no_rawat' => $key->no_rawat]) }}">{{$key->nm_pasien}}</a> --}}
                          {{$key->nm_pasien}}
                        </td>
                        <td>{{$key->nm_poli}}</td>
                        <th>
                          @if ($key->stts == 'Belum' )
                            <p class="text-danger">Belum Diperiksa</p>
                          @else
                            <p class="text-success">Sudah Diperiksa</p>
                          @endif
                        </th>
                    </tr> 
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
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

<!-- REQUIRED SCRIPTS -->
@include('template.script_table')
<script src="{{asset('tampilan/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('tampilan/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('tampilan/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('tampilan/plugins/daterangepicker/daterangepicker.js')}}"></script>

<script>
  $(function () {
    $('.knob').knob({
      draw: function () {
      }
    })
    $('#reservation').daterangepicker()
  })
</script>
</body>
</html>
