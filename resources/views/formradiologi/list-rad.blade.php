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
              <form action="{{route('menu-radiologi')}}" method="post" class="form-horizontal">
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
                <h5 class="float-left">Pemeriksaan Radiologi <b>{{$tanggalawal ?? null}} - {{$tanggalakhir ?? null}}</b></h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Rawat</th>
                    <th>Nama Pasien</th>
                    <th>No. RM</th>
                    <th>Tanggal Periksa</th>
                    <th>Jam Periksa</th>
                    <th>Status</th>
                    <th>Bacaan</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($pasienrad as $no => $key)
                      <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        @if ($cekperiksa[$no] >= 1)
                          <td><a href="{{ route('form-radiologi', ['no_rawat' => $key->no_rawat, 'tgl_periksa' => $key->tgl_periksa, 'jam' => $key->jam]) }}" class="btn btn-block btn-success btn-xs">{{$key->no_rawat}}</a></td>
                        @else
                          <td><a href="{{ route('form-radiologi', ['no_rawat' => $key->no_rawat, 'tgl_periksa' => $key->tgl_periksa, 'jam' => $key->jam]) }}" class="btn btn-block btn-primary btn-xs">{{$key->no_rawat}}</a></td>
                        @endif
                        <td>{{$key->nm_pasien}}</td>
                        <td>{{$key->no_rkm_medis}}</td>
                        <td>{{$key->tgl_periksa}}</td>
                        <td>{{$key->jam}}</td>
                        @if ($cekperiksa[$no] >= 1)
                          <th class="text-success">Sudah Dibaca</th>
                        @else
                          <th class="text-danger">Belum Dibaca</th>
                        @endif
                        <td>{{$cekperiksa[$no]}}</td>
                      </tr> 
                    @endforeach
                  </tbody>
                </table>

                <hr>
                <div class="card-body row">
                  <div class="col-md-12">
                    <b>dr. Candra Harmindasatya, Sp. Rad</b>
                    <div><b>Radiologi</b></div>
                  </div>
                  <div class="col-md-4">
                    <div><b>{{$bpjs[1]['jenis']}}</b></div>
                    <div>Total Permintaan :&emsp; {{$bpjs[1]['permintaan']}}</div>
                    <div>Total Bacaan :&emsp;{{$bpjs[1]['bacaan']}}</div>
                    <input readonly type="text" class="knob" value="{{$bpjs[1]['hasil']}}" data-width="100" data-height="100" data-fgColor="#059212">
                  </div>
                  <div class="col-md-4">
                    <div><b>{{$bpjs[0]['jenis']}}</b></div>
                    <div>Total Permintaan :&emsp; {{$bpjs[0]['permintaan']}}</div>
                    <div>Total Bacaan :&emsp;{{$bpjs[0]['bacaan']}}</div>
                    <input readonly type="text" class="knob" value="{{$bpjs[0]['hasil']}}" data-width="100" data-height="100" data-fgColor="#059212">
                  </div>
                  <div class="col-md-4">
                    <div><b>{{$bpjs[2]['jenis']}}</b></div>
                    <div>Total Permintaan :&emsp; {{$bpjs[2]['permintaan']}}</div>
                    <div>Total Bacaan :&emsp;{{$bpjs[2]['bacaan']}}</div>
                    <input readonly type="text" class="knob" value="{{$bpjs[2]['hasil']}}" data-width="100" data-height="100" data-fgColor="#059212">
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
  $(document).ready(function (){
    var table = $('#example').DataTable({
        pageLength: 10
    });
  });
</script>
</body>
</html>
