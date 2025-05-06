<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
    <link rel="stylesheet" href="{{asset('tampilan/plugins/daterangepicker/daterangepicker.css')}}">
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
                <h5 class="float-left">Pencarian Berdasarkan Tanggal</h5>
              </div>
              <!-- /.card-header -->
              <form action="{{route('absensi.detailrekapadmin', ['id' => $id])}}" method="post" class="form-horizontal">
              {{ csrf_field() }}
              <div class="card-body row">
                <div class="input-group col-lg-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" name="tanggal" class="form-control float-right" id="reservation">
                </div>
                <button type="submit" class="btn btn-success float-right ml-3" name="action" value="simpan">Cari Data</button>
              </div>
              </form>
              
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          
        </div>
      </div>
      
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="float-left">Absensi {{$pegawai[0]->nama ?? null}} <b>({{$tanggalawal ?? null}} - {{$tanggalakhir ?? null}})</b></h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body row">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>No.</th>
                      <th>Shift</th>
                      <th>Jam Datang</th>
                      <th>Jam Pulang</th>
                      <th>Status</th>
                      <th>Keterlambatan</th>
                      <th>Durasi</th>
                      <th>Foto Datang</th>
                      <th>Foto Pulang</th>
                    </tr>
                    </thead>
                    <tbody>
                      @foreach ($pegawai as $key => $value)
                        <tr>
                          <th scope="row"> {{$key+1}}. </th>
                          <td>{{$value->shift}}</td>
                          <td>{{$value->jam_datang}}</td>
                          <td>{{$value->jam_pulang}}</td>
                          <td>{{$value->status}}</td>
                          <td>{{$value->keterlambatan}}</td>
                          <td>{{$value->durasi}}</td>
                          <td><img src="{{asset('webcam/'.$value->photo) ?? null}}" height="150"/></td>
                          <td><img src="{{asset('webcam/'.$value->photopulang) ?? null}}" height="150"/></td>
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
<!-- InputMask -->
<script src="{{asset('tampilan/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('tampilan/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('tampilan/plugins/daterangepicker/daterangepicker.js')}}"></script>

<script>
  $(function () {
    $('.knob').knob({
      draw: function () {
      }
    })
    $('#reservation').daterangepicker()
  })
</script>
</body>
</html>
