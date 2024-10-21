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
                <h5 class="float-left">Pemeriksaan Radiologi</h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Rawat</th>
                    <th>Nama Pasien</th>
                    <th>No. RM</th>
                    <th>Tanggal Periksa</th>
                    <th>Jam Periksa</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($pasienrad as $no => $key)
                      <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        @if ($cekperiksa[$no] >= 1)
                          <td><a href="{{ route('form-radiologi', ['no_rawat' => $key->no_rawat, 'tgl_periksa' => $key->tgl_periksa, 'jam' => $key->jam]) }}" class="btn btn-block btn-success btn-xs">{{$key->no_rawat}}</a></td>
                        @else
                          <td><a href="{{ route('form-radiologi', ['no_rawat' => $key->no_rawat, 'tgl_periksa' => $key->tgl_periksa, 'jam' => $key->jam]) }}" class="btn btn-block btn-primary btn-xs">{{$key->no_rawat}}</a></td>
                        @endif
                        <td>{{$key->nm_pasien}}</td>
                        <td>{{$key->no_rkm_medis}}</td>
                        <td>{{$key->tgl_periksa}}</td>
                        <td>{{$key->jam}}</td>
                        @if ($cekperiksa[$no] >= 1)
                          <th class="text-success">Sudah Dibaca</th>
                        @else
                          <th class="text-danger">Belum Dibaca</th>
                        @endif
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
  $(document).ready(function (){
    var table = $('#example').DataTable({
        pageLength: 20
    });
  });
</script>
</body>
</html>
