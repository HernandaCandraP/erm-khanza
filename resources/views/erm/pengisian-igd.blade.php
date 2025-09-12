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
              <form action="{{route('simrs.igd')}}" method="post" class="form-horizontal">
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
                <h5 class="float-left">ERM IGD <b>{{$tanggalawal ?? null}} - {{$tanggalakhir ?? null}}</b></h5>
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
                              {!! $value->triase != null ? "<b class='text-success'>Triase IGD</b>" : "<b class='text-danger'>Triase IGD</b>" !!}
                            </li>
                            <li>
                              {!! $value->awalkeperawatanigd != null ? "<b class='text-success'>Awal Keperawatan IGD</b>" : "<b class='text-danger'>Awal Keperawatan IGD</b>" !!}
                            </li>
                            <li>
                              {!! $value->awalmedisigd != null ? "<b class='text-success'>Awal Medis IGD</b>" : "<b class='text-danger'>Awal Medis IGD</b>" !!}
                            </li>
                            <li>
                              {!! $value->observasiigd != null ? "<b class='text-success'>Catatan Observasi IGD</b>" : "<b class='text-danger'>Catatan Observasi IGD</b>" !!}
                            </li>
                            <li>
                              {!! $value->pews != null ? "<b class='text-success'>Pemantauan PEWS</b>" : "<b class='text-danger'>Pemantauan PEWS</b>" !!}
                            </li>
                            <li>
                              {!! $value->ews != null ? "<b class='text-success'>Pemantauan EWS Dewasa</b>" : "<b class='text-danger'>Pemantauan EWS Dewasa</b>" !!}
                            </li>
                            <li>
                              {!! $value->ewsneonatus != null ? "<b class='text-success'>Pemantauan EWS Neonatus</b>" : "<b class='text-danger'>Pemantauan EWS Neonatus</b>" !!}
                            </li>
                            <li>
                              {!! $value->ekg != null ? "<b class='text-success'>Pemeriksaan EKG</b>" : "<b class='text-danger'>Pemeriksaan EKG</b>" !!}
                            </li>
                            <li>
                              {!! $value->persetujuanpenolakan != null ? "<b class='text-success'>Persetujuan Penolakan Tindakan</b>" : "<b class='text-danger'>Persetujuan Penolakan Tindakan</b>" !!}
                            </li>
                            {{-- <li>
                              @if ($value->jatuhdewasa != null)
                                <b class='text-success'>Risiko Jatuh Dewasa</b>
                              @elseif ($value->jatuhanak != null)
                                <b class='text-success'>Risiko Jatuh Anak</b>
                              @else
                                <b class='text-danger'>Kosong</b>
                              @endif
                            </li> --}}
                            <li>
                              {!! $value->terminal != null ? "<b class='text-success'>Penilaian Pasien Terminal</b>" : "<b class='text-danger'>Penilaian Pasien Terminal</b>" !!}
                            </li>
                            <li>
                              {!! $value->resep != null ? "<b class='text-success'>Resep</b>" : "<b class='text-danger'>Resep</b>" !!}
                            </li>
                            <li>
                              {!! $value->transfer != null ? "<b class='text-success'>Transfer Antar Ruang</b>" : "<b class='text-danger'>Transfer Antar Ruang</b>" !!}
                            </li>
                            <li>
                              {!! $value->resume != null ? "<b class='text-success'>Resume Pasien</b>" : "<b class='text-danger'>Resume Pasien</b>" !!}
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
