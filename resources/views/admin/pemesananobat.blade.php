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
                <h5 class="float-left">List Obat</b></h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kandungan</th>
                    <th>Jenis Obat</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($obat as $no => $key)
                      <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td>{{$key->kode_brng}}</td>
                        <td>{{$key->nama_brng}}</td>
                        <td>{{$key->letak_barang}}</td>
                        <td>{{$key->nama}}</td>
                        <td><a href="{{ route('pemesananobat', ['kode_brng' => $key->kode_brng, 'nama_brng' => $key->nama_brng]) }}" class="btn btn-block btn-success btn-xs">Cek Harga Terendah</a></td>
                      </tr> 
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="col-md-12 col-lg-12">
            <div class="card">
              @if ($kode_brng)
              <div class="card-body">
                <h3>SUPLIER {{$kode_brng}} - <b>{{$nama_brng}}</b></h3>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>Kode Suplier</th>
                    <th>Nama Suplier</th>
                    <th>Tanggal Pesan</th>
                    <th>Harga (/pcs)</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($pemesanan as $no => $key)
                      <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td>{{$key->kode_suplier}}</td>
                        <td>{{$key->nama_suplier}}</td>
                        <td>{{$key->tgl_pesan}}</td>
                        <td>{{$key->pcs}}</td>
                      </tr> 
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              @endif
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

<!-- SCRIPTS -->
@include('template.script_table')
<script>
  $(document).ready(function (){
    var table = $('#example').DataTable({
        pageLength: 5
    });
  });
</script>
</body>
</html>
