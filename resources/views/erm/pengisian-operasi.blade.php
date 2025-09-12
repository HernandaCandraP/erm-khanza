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
              <form action="{{route('simrs.operasi')}}" method="post" class="form-horizontal">
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

          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="float-left">ERM OPERASI <b>{{$tanggalawal ?? null}} - {{$tanggalakhir ?? null}}</b></h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Rawat</th>
                    <th>Nama Lengkap</th>
                    <th>Dokter Dituju</th>
                    <th>Poli</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($listpasien as $no => $value)
                    <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td>{{$value->no_rawat}}</td>
                        <td>{{$value->nm_pasien}} {{$value->no_rkm_medis}}</td>
                        <td>{{$value->nm_dokter}}</td>
                        <td>{{$value->nm_poli}} <br>({{$value->status_lanjut}})</td>
                        <td>
                          <ul>
                            <li>
                              {!! $value->cpo != null ? "<b class='text-success'>Check list Pre Opersai</b>" : "<b class='text-danger'>Check list Pre Opersai</b>" !!}
                            </li> 
                            <li>
                              {!! $value->ssa != null ? "<b class='text-success'>Sign-In Sebelum Anastesi</b>" : "<b class='text-danger'>Sign-In Sebelum Anastesi</b>" !!}
                            </li> 
                            <li>
                              {!! $value->tsi != null ? "<b class='text-success'>Time-out Sebelum Insisi</b>" : "<b class='text-danger'>Time-out Sebelum Insisi</b>" !!}
                            </li>
                             <li>
                              {!! $value->ssml != null ? "<b class='text-success'>Sign-Out Sebelum Menutup Luka</b>" : "<b class='text-danger'>Sign-Out Sebelum Menutup Luka</b>" !!}
                            </li>
                            <li>
                              {!! $value->clpo != null ? "<b class='text-success'>Checklist Post Operasi</b>" : "<b class='text-danger'>Checklist Post Operasi</b>" !!}
                            </li>
                            <li>
                              {!! $value->ppo != null ? "<b class='text-success'>Penilaian Pre Operasi</b>" : "<b class='text-danger'>Penilaian Pre Operasi</b>" !!}
                            </li>
                            <li>
                              {!! $value->ppa != null ? "<b class='text-success'>Penilaian Pre Anastesi</b>" : "<b class='text-danger'>Penilaian Pre Anastesi</b>" !!}
                            </li>
                          </ul>
                        </td>
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
