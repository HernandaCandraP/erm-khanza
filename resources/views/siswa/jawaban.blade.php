<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
</head>
<body class="">
<!-- <div class="wrapper"> -->
<nav class="navbar navbar-expand navbar-light">
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-user-circle">
                @if( Str::length(Auth::guard('siswa')->user()) > 0)
                    {{ Auth::guard('siswa')->user()->username}}
                @elseif( Str::length(Auth::guard('guru')->user()) > 0)
                    {{ Auth::guard('guru')->user()->username}}
                @endif
            </i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <div class="dropdown-divider"></div>
            <a href="{{route('logout')}}" class="dropdown-item float-left">
              <i class="fas fa-power-off mr-2"></i> Logout
            </a>
        </div>
        </li>
    </ul>
</nav>

  <!-- <div class="content-wrapper"> -->
    <section class="content-header"></section>
    <!-- Main content -->
    <section class="content ">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="float-left">Ujian Mata Palajaran {{$nama_mapel->nama_mapel}}</h4>
                
                <h4 class="float-right" id='countdown'></h4>
                <h4 class="float-right" >Sisa Waktu:&ensp;</h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="modal-body">
                <form action="{{url('siswa/jawaban/')}}/{{$tampilsoal->id_detail}}/{{$tampilsoal->id_soal}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                  <div class="form-group row">
                    <div class="col-sm-9">
                      @if($tampilsoal->image != null)
                      <img style="border:1px solid;" src="{{asset('image/'. $tampilsoal->image)}}" class="img-fluid" width="300px" /><br>
                      @endif
                      <div style="background-color:transparent; border:none; text-align:justify; height:auto" class="form-control">@nl2br($tampilsoal->soal)</div><br>
                      <b>Jawaban:</b>
                      <textarea class="form-control" name="jawaban_siswa" rows="5" placeholder="jawaban">{{$jawaban['jawaban_siswa']}}</textarea><br>
                    </div>
                    <div class="col-sm-3">
                      <div class="card card-success">
                        <div class="card-header">
                          <h3 class="card-title">Different Width</h3>
                        </div>
                        <div class="card-body row text-center">
                        @foreach ($getsoal as $key => $value)
                          <div class="col-3">
                            <!-- kondisi untuk menampilkan soal yang dipilih -->
                            @if($value->id_soal == $tampilsoal->id_soal)
                              <!-- mengambil id_detail dan id_soal untuk fungsi pindah soal lainnya -->
                              <button type="submit" class="btn btn-primary" name="redirect_id_soal" value="{{ $value->id_soal }}">{{$key+1}}</button>
                            @elseif($value->jawaban_siswa == null)
                              <button type="submit" class="btn btn-default" name="redirect_id_soal" value="{{ $value->id_soal }}">{{$key+1}}</button>
                            @else
                              <button type="submit" class="btn btn-secondary" name="redirect_id_soal" value="{{ $value->id_soal }}">{{$key+1}}</button>
                            @endif
                            <br><br>
                          </div>
                        @endforeach
                        <button class="btn btn-danger form-control" name="simpan" value="simpan"onclick="return confirm('Apakah anda yakin ingin menyelasaikan ujian ini?')">Akhiri Ujian</button>
                        <button type="submit" id='timeout' name="timeout" value="timeout" hidden>timeout</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  <!-- </div> -->
  <footer>
    <div class="float-right d-none d-sm-inline">
      SMP Negeri 1 Durenan&emsp;
    </div>
    <strong>&emsp;Copyright &copy; 2021 <a href="https://github.com/HernandaCandraP/">Hernanda Candra P</a>.</strong> All rights reserved.
  </footer>
  <aside class="control-sidebar control-sidebar-dark"></aside>
<!-- </div> -->

@include('template.script')
<script>
// Set the date we're counting down to
var countDownDate = new Date("{{$end_time}}").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="countdown"
  document.getElementById("countdown").innerHTML = hours + ":"
  + minutes + ":" + seconds + "";

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("countdown").innerHTML = "Waktu Habis";
    document.getElementById("timeout").click();
    return;
  }
}, 1000);
</script>

</body>
</html>
