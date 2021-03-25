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
              <span class="info-box-icon bg-info"><i class="far fa-building"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Kelas</span>
                <span class="info-box-number">{{$totalkelas}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="fa fa-users"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Siswa</span>
                <span class="info-box-number">{{$totalsiswa}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-danger"><i class="fa fa-book"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Mata Pelajaran</span>
                <span class="info-box-number">{{$totalmapel}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-success"><i class="fas fa-file"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Kategori Soal</span>
                <span class="info-box-number">{{$detailmapel}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->

          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Precision & Recall
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-4 col-md-4">
                    <h5 class="text-center" style="line-height:120px">Cosine Similarity</h5>
                  </div>
                  <div class="col-4 col-md-4 text-center">
                    <input type="text" class="knob" value="30.97" data-width="120" data-height="120" data-fgColor="#4835FF" disabled readonly>
                    <div class="knob-label">Precision</div>
                  </div>
                  <div class="col-4 col-md-4 text-center">
                  <input type="text" class="knob" value="30" data-width="120" data-height="120" data-fgColor="#00a65a" disabled readonly>
                    <div class="knob-label">Recall</div>
                  </div>
                </div><hr>
                <!-- /.row -->
                <div class="row">
                  <div class="col-4 col-md-4">
                    <h5 class="text-center" style="line-height:120px">Jaccard Similarity</h5>
                  </div>
                  <div class="col-4 col-md-4 text-center">
                    <input type="text" class="knob" value="30.97" data-width="120" data-height="120" data-fgColor="#4835FF" disabled readonly>
                    <div class="knob-label">Precision</div>
                  </div>
                  <div class="col-4 col-md-4 text-center">
                  <input type="text" class="knob" value="30" data-width="120" data-height="120" data-fgColor="#00a65a" disabled readonly>
                    <div class="knob-label">Recall</div>
                  </div>
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->

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
