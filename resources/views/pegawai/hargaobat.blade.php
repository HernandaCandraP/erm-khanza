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
                <h5 class="float-left">Stok Obat Apotek</h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>Kode</th>
                    <th>Nama Obat</th>
                    <th>Stok Apotek</th>
                    <th>Total Harga Dasar</th>
                    <th>Total Harga Beli</th>
                    <th>Total Harga Jual</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($hargaobat as $no => $key)
                    <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td>{{$key->kode_brng}}</td>
                        <td>{{$key->nama_brng}}</td>
                        <td>{{$key->stok}}</td>
                        <td>{{$key->dasar}} = @money($key->ttl_dasar)</td>
                        <td>{{$key->h_beli}} = @money($key->ttl_beli)</td>
                        <td>{{$key->ralan}} = @money($key->ttl_jual)</td>
                        <div hidden>{{$dasar += $key->ttl_dasar}} {{$beli += $key->ttl_beli}} {{$jual += $key->ttl_jual}}</div>
                    </tr> 
                    @endforeach
                  </tbody>
                
                </table>

                <h5>Total Harga Dasar : @money($dasar)</h5>
                <h5>Total Harga Beli : @money($beli)</h5>
                <h5>Total Harga Jual : @money($jual)</h5>
                
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
