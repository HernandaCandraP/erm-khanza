<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" /> --}}
    <style type="text/css">
        #results { padding:20px; border:1px solid; background:#ccc; 
          -moz-transform: scaleX(-1);
          -o-transform: scaleX(-1);
          -webkit-transform: scaleX(-1);
          transform: scaleX(-1);
          filter: FlipH;
          -ms-filter: "FlipH";}
        #results2 { 
          -moz-transform: scaleX(-1);
          -o-transform: scaleX(-1);
          -webkit-transform: scaleX(-1);
          transform: scaleX(-1);
          filter: FlipH;
          -ms-filter: "FlipH";}
        #my_camera {
          -moz-transform: scaleX(-1);
          -o-transform: scaleX(-1);
          -webkit-transform: scaleX(-1);
          transform: scaleX(-1);
          filter: FlipH;
          -ms-filter: "FlipH";
        } 
    </style>
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
          <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Presensi Pegawai</h3>
              </div>
              <div class="card-body">
                @include('template.alert')
                @if ($errors->any())
                  <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                  </div>
                @endif
                <form method="POST" action="{{ route('webcam.capture') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div id="my_camera"></div>
                            <br/>
                            <input type="hidden" name="image" class="image-tag">
                        </div>
                        <div class="col-md-6">
                            <div id="results"></div>
                        </div>
                        <div class="col-sm-12 text-center">
                          @if ($departemen == 'MAR') 
                            <input type="text" name="keterangan" class="form-control-sm is-valid" placeholder="Keterangan..." required>
                            <input type=button class="btn btn-info ml-3" value="Ambil Foto" onClick="take_snapshot()">
                            <button type="submit" class="btn btn-warning float-right ml-3" onclick="return confirm('Absensi Sekarang ?')" name="action" value="pulang">Absensi</button>
                          @elseif ($rekappresensi->isNotEmpty()) 
                            <h4 class="text-success">Sudah Melakukan Absensi</h4>
                          @elseif($presensi == null)
                            <input type=button class="btn btn-info ml-3" value="Ambil Foto" onClick="take_snapshot()">
                            <select class="form-select form-select-lg mb-3" name="jamjaga" id="jamjaga">
                              @foreach ($jamjaga as $nama => $jam)
                                <option value="{{$jam}}"> {{$nama}} - {{$jam}} </option>
                              @endforeach
                            </select>
                            <button type="submit" class="btn btn-success float-right ml-3" name="action" value="masuk">Absensi Masuk</button>
                          @elseif($rekappresensi->isEmpty())
                            <input type="text" name="keterangan" class="form-control-sm is-valid" placeholder="Keterangan...">
                            <input type=button class="btn btn-info ml-3" value="Ambil Foto" onClick="take_snapshot()">
                            <button type="submit" class="btn btn-warning float-right ml-3" onclick="return confirm('Pulang Sekarang ?')" name="action" value="pulang">Absensi Pulang</button>
                          @endif

                        </div>
                    </div>
                </form>
              </div>
              <hr> 
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                      @if ($departemen == 'MAR') 
                        @foreach ($rekappresensi as $key => $value)
                          <div>Jam : <b>{{$value->jam_datang}}</b></div>
                          <div>Ket : <b>{{$value->keterangan}}</b></div>
                          <div class="pt-2">Photo: <img id="results2" src="{{asset('webcam/'.$value->photo) ?? null}}" height="200"/></div>
                          <hr>
                        @endforeach
                      @else
                        @if ($presensi != null) 
                          <div>Jam Datang : <b>{{$presensi->jam_datang ?? $rekappresensi[0]->jam_datang}}</b></div>
                          {{-- <div>Status : {{$presensi->status ?? null}} - {{$presensi->keterlambatan ?? null}}</div> --}}
                          <div class="pt-2">Photo Datang : <img id="results2" src="{{asset('webcam/'.$presensi->photo) ?? null}}" height="200"/></div>
                        @elseif ($rekappresensi->isNotEmpty()) 
                          <div>Jam Datang : <b>{{$rekappresensi[0]->jam_datang}}</b></div>
                          <div>Jam Pulang : {{$rekappresensi[0]->jam_pulang}}</div>
                          <div>Ket : <b>{{$rekappresensi[0]->keterangan}}</b></div>
                          {{-- <div>Status : {{$rekappresensi[0]->status ?? null}} - {{$rekappresensi[0]->keterlambatan ?? null}}</div> --}}
                          <div>Durasi : {{$presensi->durasi ?? $rekappresensi[0]->durasi}}</div>
                          <div class="pt-2">Photo Datang : <img id="results2" src="{{asset('webcam/'.$rekappresensi[0]->photo) ?? null}}" height="200"/></div>
                          <div class="pt-2">Photo Pulang : <img id="results2" src="{{asset('webcam/'.$rekappresensi[0]->photopulang) ?? null}}" height="200"/></div>
                        @endif
                      @endif
                    </div>
                </div>
              </div>
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
<script language="JavaScript">
    Webcam.set({
      width: 250,
      height: 332,
      image_format: 'jpeg',
      jpeg_quality: 90,
    });
    Webcam.attach( '#my_camera' );

    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
        } );
    }
</script>
</body>
</html>
