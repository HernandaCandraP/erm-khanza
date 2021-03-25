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
          <div class="col-md-6 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Ujian yang Telah Dikerjakan</span>
                <span class="info-box-number">{{$ujian_diselesaikan}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-6 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="fas fa-times"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Ujian yang Belum Dikerjakan</span>
                <span class="info-box-number">{{$ujian_belum_dikerjakan}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          

          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Penilaian Rata Rata
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-4 col-md-4 text-center">
                    <input type="text" class="knob" value="{{$nilai_cosine}}" data-width="120" data-height="120" data-fgColor="#4835FF" disabled readonly>
                    <div class="knob-label">Penilaian Cosine</div>
                  </div>
                  <div class="col-4 col-md-4 text-center">
                    <input type="text" class="knob" value="{{$nilai_jaccard}}" data-width="120" data-height="120" data-fgColor="#4835FF" disabled readonly>
                    <div class="knob-label">Penilaian Jaccard</div>
                  </div>
                  <div class="col-4 col-md-4 text-center">
                  <input type="text" class="knob" value="{{$nilai_guru}}" data-width="120" data-height="120" data-fgColor="#00a65a" disabled readonly>
                    <div class="knob-label">Penilaian Guru</div>
                  </div>
                </div><hr>
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