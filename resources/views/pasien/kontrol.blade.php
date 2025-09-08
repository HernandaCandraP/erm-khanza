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
              <form action="{{route('kontrol')}}" method="post" class="form-horizontal">
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
                <h5 class="float-left">Antrian {{Session::get('nmpoli')}} <b>{{$tanggalawal ?? null}} - {{$tanggalakhir ?? null}}</b></h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Rawat</th>
                    <th>No. RM</th>
                    <th>Nama Lengkap</th>
                    <th>Dokter Dituju</th>
                    <th>Poliklinik</th>
                    <th>Tgl Registrasi</th>
                    <th>Rencana Kontrol</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($pasienterakhir as $no => $key)
                    <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <th>{{$key->no_rawat}}</th>
                        <td>{{$key->no_rkm_medis}}</td>
                        <td>{{$key->nm_pasien}}</td>
                        <td>{{$key->nm_dokter}}</td>
                        <td>{{$key->nm_poli}}</td>
                        <td>{{$key->tgl_registrasi}}</td>
                        <td>{{$key->tgl_kontrol}}</td>
                        {{-- <td><a href="{{ route('pasien/ralan', ['no_rawat' => $key->no_rawat]) }}">Edit Kontrol</a></td> --}}
                        <td>
                          <input id="rencana_kontrol" type="date" class="form-control form-control-sm" placeholder="tgl_perawatan" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                          <a
                              href="javascript:void(0)"
                              id="show-user"
                              data-url="{{ route('kontrol.show', ['no_rawat' => $key->no_rawat, 'no_rkm_medis' => $key->no_rkm_medis, 'tabel' => 'DataKontrolRawatJalan']) }}"
                              class="btn btn-info"
                          >Show</a>
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

<!-- Modal -->
<div class="modal fade" id="userShowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Show User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> <span id="user-id"></span></p>
                <p><strong>Name:</strong> <span id="user-name"></span></p>
                <p><strong>Email:</strong> <span id="user-email"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- REQUIRED SCRIPTS -->
@include('template.script_table')
<script src="{{asset('tampilan/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('tampilan/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('tampilan/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('tampilan/plugins/daterangepicker/daterangepicker.js')}}"></script>

<script type="text/javascript">
  $(function () {
    $('.knob').knob({
      draw: function () {
      }
    })
    $('#reservation').daterangepicker()
  })

  $(document).ready(function () {

      /* When click show user */
      $('body').on('click', '#show-user', function () {
          var userURL = $(this).data('url');
          console.log(userURL);
          
          $.get(userURL, function (data) {
              $('#userShowModal').modal('show');
              $('#user-id').text(data.id);
              $('#user-name').text(data.name);
              $('#user-email').text(data.email);
          })
      });

  });

</script>

</body>
</html>
