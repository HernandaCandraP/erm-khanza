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
              <form action="{{route('menu-ranap')}}" method="post" class="form-horizontal">
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
        <!-- /.row -->
      </div><!-- /.container-fluid -->

      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="float-left">Pasien Rawat Inap <b>{{$tanggalawal ?? null}} - {{$tanggalakhir ?? null}}</b></h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Rawat</th>
                    <th>No. RM</th>
                    <th>Nama Pasien</th>
                    <th>Kd. Kamar</th>
                    <th>Kamar</th>
                    <th>Diagnosa Awal</th>
                    <th>Diagnosa Akhir</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Keluar</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($pasienranap as $no => $key)
                        @if ($key->no_rawat2 != null)
                          <tr>
                            <th scope="row"> {{$no+1}}. </th>
                            <td><a href="{{ route('formri-soap', ['no_rawat' => $key->no_rawat]) }}" class="btn btn-block btn-info btn-xs">{{$key->no_rawat}}</a></td>
                            <td>{{$key->no_rkm_medis}}</td>
                            <td>{{$key->nm_pasien}}</td>
                            <td>{{$key->kd_kamar}}</td>
                            <td>{{$key->nm_bangsal}}</td>
                            <td>{{$key->diagnosa_awal}}</td>
                            <td>{{$key->diagnosa_akhir}}</td>
                            <td>{{$key->tgl_masuk}} {{$key->jam_masuk}}</td>
                            <td>{{$key->tgl_keluar}} {{$key->jam_keluar}}</td>
                          </tr> 
                          <tr>
                            <th scope="row"> <b hidden>{{$no+1}}.</b></th>
                            <td><a href="{{ route('formri-soap', ['no_rawat' => $key->no_rawat2]) }}" class="btn btn-block btn-info btn-xs">{{$key->no_rawat2}}</a></td>
                            <td>{{$key->no_rkm_medis2}}</td>
                            <td>{{$key->nm_pasien2}}</td>
                            <td>{{$key->kd_kamar}}</td>
                            <td>{{$key->nm_bangsal}}</td>
                            <td>{{$key->diagnosa_awal}}</td>
                            <td>{{$key->diagnosa_akhir}}</td>
                            <td>{{$key->tgl_masuk}} {{$key->jam_masuk}}</td>
                            <td>{{$key->tgl_keluar}} {{$key->jam_keluar}}</td>
                            <td>&nbsp;</td>
                          </tr> 
                        @else
                          <tr>
                              <th scope="row"> {{$no+1}}. </th>
                              <td><a href="{{ route('formri-soap', ['no_rawat' => $key->no_rawat]) }}" class="btn btn-block btn-primary btn-xs">{{$key->no_rawat}}</a></td>
                              <td>{{$key->no_rkm_medis}}</td>
                              <td>{{$key->nm_pasien}}</td>
                              <td>{{$key->kd_kamar}}</td>
                              <td>{{$key->nm_bangsal}}</td>
                              <td>{{$key->diagnosa_awal}}</td>
                              <td>{{$key->diagnosa_akhir}}</td>
                              <td>{{$key->tgl_masuk}} {{$key->jam_masuk}}</td>
                              <td>{{$key->tgl_keluar}} {{$key->jam_keluar}}</td>
                          </tr> 
                        @endif
                     
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
