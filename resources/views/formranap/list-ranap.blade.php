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
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="fa fa-users"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">PASIEN RAWAT INAP BULAN INI</span>
                <span class="info-box-number">{{$pasienbulan}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->

      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="float-left">Pasien Belum Pulang</h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Rawat</th>
                    <th>No. RM</th>
                    <th>Nama Pasien</th>
                    <th>Kd. Kamar</th>
                    <th>Kamar</th>
                    <th>Diagnosa Awal</th>
                    <th>Diagnosa Akhir</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Keluar</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($pasienranap as $no => $key)
                    <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td><a href="{{ route('formri-soap', ['no_rawat' => $key->no_rawat]) }}" class="btn btn-block btn-primary btn-xs"> {{$key->no_rawat}}</a></td>
                        <td>{{$key->no_rkm_medis}}</td>
                        <td>{{$key->nm_pasien}}</td>
                        <td>{{$key->kd_kamar}}</td>
                        <td>{{$key->nm_bangsal}}</td>
                        <td>{{$key->diagnosa_awal}}</td>
                        <td>{{$key->diagnosa_akhir}}</td>
                        <td>{{$key->tgl_masuk}} {{$key->jam_masuk}}</td>
                        <td>{{$key->tgl_keluar}} {{$key->jam_keluar}}</td>
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
