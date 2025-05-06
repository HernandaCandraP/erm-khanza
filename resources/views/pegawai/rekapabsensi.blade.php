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

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="card card-primary">
              <!-- /.card-header -->
              <div class="card-header">
                <h3 class="card-title">Rekap Absensi <b> {{$rangedate['start']}} - {{$rangedate['end']}}</b></h3>
              </div>
              <div class="card-body">
                <blockquote>
                  <h3>Total Jam Datang</h3>
                  <div>{{$presensi[0]->totaldatang}}</div>
                </blockquote>
                <blockquote>
                  <h3>Total Jam Pulang</h3>
                  <div>{{$presensi[0]->totalpulang}}</div>
                </blockquote>
              </div>
              <hr>
              <!-- /.card-body -->
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
