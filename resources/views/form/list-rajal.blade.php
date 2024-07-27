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
          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="float-left">Antrian {{Session::get('nmpoli')}}</h5>
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
                        <th><a href="{{ route('form-soap', ['no_rawat' => $key->no_rawat]) }}" class="btn btn-block btn-primary btn-xs">{{$key->no_rawat}}</a></th>
                        <td>{{$key->no_rkm_medis}}</td>
                        <td>
                          {{-- <a href="{{ route('pasien/ralan', ['no_rawat' => $key->no_rawat]) }}">{{$key->nm_pasien}}</a> --}}
                          {{$key->nm_pasien}}
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
                    <th>Poli</th>
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
<script>
  $(function () {
    $('.knob').knob({
      draw: function () {
      }
    })
  })
</script>
</body>
</html>
