<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
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
                <h5 class="float-left">Antrean Rawat Jalan</h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Rawat</th>
                    <th>No. RM / Nama Lengkap</th>
                    <th>Status</th>
                    <th style="width: 30%" >Antrean</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($antrean as $no => $key)
                    <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td>{{$key->no_rawat}}</td>
                        <td>{{$key->no_rkm_medis}} <br> <b>{{$key->nm_pasien}}</b></th>
                        <td>{{$key->status}}</td>
                        <td>
                            @if ($key->dikirim == '0000-00-00 00:00:00' )
                              <a href="{{ route('updateantrean', ['no_rawat' => $key->no_rawat, 'status' => "taskid3"]) }}" class="btn  btn-block btn-danger btn-xs">Pendaftaran</a>
                            @else
                              {{$key->dikirim}}
                            @endif <br>
                        {{-- </td>
                        <td> --}}
                            @if ($key->diterima == '0000-00-00 00:00:00' )
                              <a href="{{ route('updateantrean', ['no_rawat' => $key->no_rawat, 'status' => "taskid4"]) }}" class="btn  btn-block btn-warning btn-xs">Masuk Poli</a></td>
                            @else
                              {{$key->diterima}}
                            @endif <br>
                        {{-- </td>
                        <td> --}}
                            @if ($key->kembali == '0000-00-00 00:00:00' )
                              <a href="{{ route('updateantrean', ['no_rawat' => $key->no_rawat, 'status' => "taskid5"]) }}" class="btn  btn-block btn-primary btn-xs">Keluar Poli</a>
                            @else
                              {{$key->kembali}}
                            @endif
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
<script>
  $(function () {
    $('.knob').knob({
      draw: function () {
      }
    })
  })
</script>
</body>
</html>
