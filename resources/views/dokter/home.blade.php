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
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-info"><i class="far fa-hospital"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">TOTAL PASIEN</span>
                <span class="info-box-number">{{$totalpasien}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="fa fa-users"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">PASIEN BULAN INI</span>
                <span class="info-box-number">{{$pasienbulan}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-danger"><i class="fa fa-user-plus"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">PASIEN POLI BULAN INI</span>
                <span class="info-box-number">{{$polibulan}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-success"><i class="fas fa-user-clock"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">PASIEN POLI HARI INI</span>
                <span class="info-box-number">{{$polihari}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->

      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 col-sm-6 col-12">
            <div class="info-box">
              Pasien Poliklinik Paru Paling Aktif
            </div>
          </div>
          <div class="col-md-6 col-sm-6 col-12">
            <div class="info-box">
              Antrian 10 Pasien Terakhir Poliklinik Paru
            </div>
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
@include('template.script')
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
