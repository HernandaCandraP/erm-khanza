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
              <form action="{{route('riwayatradiologi')}}" method="post" class="form-horizontal">
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
                <h5 class="float-left">Riwayat Radiologi <b>{{$tanggalawal ?? null}} - {{$tanggalakhir ?? null}}</b></h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Rawat</th>
                    <th>Nama Perawatan</th>
                    <th>Status</th>
                    <th>Tanggal Periksa</th>
                    <th>Jam Periksa</th>
                    <th>Biaya</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($riwayatradiologi as $no => $key)
                      <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td>{{$key->no_rawat}}</td>
                        <td>{{$key->nm_perawatan}}</td>
                        <td>{{$key->status}}</td>
                        <td>{{$key->tgl_periksa}}</td>
                        <td>{{$key->jam}}</td>
                        <td>@money($key->biaya)</td>
                      </tr> 
                    @endforeach
                  </tbody>
                </table>
                <hr>
                <h4>Total Pemeriksaan : {{$riwayatradiologi->count()}}</h4>
                <h4>Total Pendapatan &nbsp;: @money($hargatotal)</h4>
                <h4>Total Bacaan &nbsp;: {{$totalbacaan}}</h4>

                <div class="card-body row">
                  <div class="col-md-4">
                    <b>Candra Harmindasatya, Sp. Rad</b>
                    <div><b>Radiologi</b></div>
                    <div>Total Peneriksaan :&emsp; {{$riwayatradiologi->count()}}</div>
                    <div>Total bacaan :&emsp;{{$totalbacaan}}</div>
                  </div>
                  <div class="col-md-4">
                    @if ($totalbacaan == 0)
                      <input readonly type="text" class="knob" value="0" data-width="100" data-height="100" data-fgColor="#059212">
                    @else
                      <input readonly type="text" class="knob" value="{{ round(($totalbacaan / $riwayatradiologi->count()) * 100, 1)}}" data-width="100" data-height="100" data-fgColor="#059212">
                    @endif
                  </div>
                </div>
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
