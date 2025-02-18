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
          <div class="col-md-12">
            <form action="{{route('admin-biayarajal')}}" method="post" class="form-horizontal">
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
          </div>
          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-body">
                <h3 class="p-3">Tanggal : <b>{{$tanggalawal}} - {{$tanggalakhir}}</b></h3>
                <h3 class="p-3"><b>Rawat Jalan</b></h3>
                <table id="examplepdf" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No Rekam Medis</th>
                    <th>No Rawat</th>
                    <th>Nama Pasien</th>
                    <th>Poli</th>
                    <th>PJ</th>
                    <th>Pembayaran Rajal</th>
                    <th>Total Putang BPJS</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($detailpasien as $no => $key)
                      <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td>{{$key->no_rkm_medis}}</td>
                        <td>{{$key->no_rawat}}</td>
                        <td>{{$key->nm_pasien}}</td>
                        <td>{{$key->nm_poli}}</td>
                        <td>{{$key->pj}}</td>
                        <td>{{$key->besar_bayar}}</td>
                        <td>{{$key->totalpiutang}}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <div class="card">
              <div class="card-body">
                <h3 class="p-3"> <b>Rawat Inap</b></h3>
                <table id="examplepdf2" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No Rekam Medis</th>
                    <th>No Rawat</th>
                    <th>Nama Pasien</th>
                    <th>Kelas</th>
                    <th>PJ</th>
                    <th>Pembayaran Inap</th>
                    <th>Total Putang BPJS</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($detailpasienranap as $no => $key)
                      <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td>{{$key->no_rkm_medis}}</td>
                        <td>{{$key->no_rawat}}</td>
                        <td>{{$key->nm_pasien}}</td>
                        <td>{{$key->kelas}} - {{$key->nm_bangsal}}</td>
                        <td>{{$key->pj}}</td>
                        <td>{{$key->besar_bayar}}</td>
                        <td>{{$key->totalpiutang}}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            
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

<!-- SCRIPTS -->
@include('template.script_table')
<script src="{{asset('tampilan/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('tampilan/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('tampilan/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('tampilan/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('tampilan/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('tampilan/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('tampilan/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('tampilan/plugins/daterangepicker/daterangepicker.js')}}"></script>

<script>
  $(function () {
    $("#examplepdf").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#examplepdf_wrapper .col-md-6:eq(0)');

    $("#examplepdf2").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#examplepdf_wrapper .col-md-6:eq(0)');

    $('#reservation').daterangepicker()
  });
</script>
</body>
</html>
