
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
    @include('template.head')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  @include('template.navbar')
  @include('template.sidebar')

  <div class="content-wrapper">
    <section class="content-header">
      <!-- Main content -->
      <div class="container-fluid">
        <div class="row">
          <div class="col-6 col-sm-6 col-md-4">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Perhitungan Cosine</span>
                <span class="info-box-number">{{$tampilnilai->sum('nilai_cosine')/$tampilnilai->count('nilai_cosine')}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-6 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Perhitungan Jaccard</span>
                <span class="info-box-number">{{$tampilnilai->sum('nilai_jaccard')/$tampilnilai->count('nilai_jaccard')}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-6 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Penialian Guru</span>
                <span class="info-box-number">{{$tampilnilai->sum('nilai_guru')/$tampilnilai->count('nilai_guru')}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
          @foreach($tampilnilai as $no => $value )
            <div class="card card-primary card-outline">
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-9">
                    <h5 class="card-title">{{$no+1}}. {{$value->soal}}</h5>
                    <!-- <textarea class="form-control" name="jawaban_siswa" rows="5" placeholder="jawaban">soal</textarea> -->
                    <textarea class="form-control" style="background: #ffff" name="jawaban_siswa" rows="5" placeholder="jawaban kosong" disabled>{{$value->jawaban_siswa}}</textarea><br>
                  </div>
                  <div class="col-lg-3">
                    <a class="btn bg-primary">
                      Cosine Similarity&emsp;: &nbsp; <b>{{$value->nilai_cosine}}</b> 
                    </a>
                    <a class="btn bg-info">
                      Jaccard Similarity &ensp;: &nbsp; <b>{{$value->nilai_jaccard}}</b> 
                    </a>
                    <a class="btn bg-lightblue">
                      @if($value->nilai_guru == null)
                        Penilaian Guru &emsp;&ensp;&nbsp;: &nbsp; <b>-</b> 
                      @else
                        Penilaian Guru &emsp;&ensp;&nbsp;: &nbsp; <b>{{$value->nilai_guru}}</b> 
                      @endif
                    </a>
                  </div>  
                </div>
              </div>
            </div><!-- /.card -->
          @endforeach
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
  @include('template.footer')
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
@include('template.script')
</body>
</html>
