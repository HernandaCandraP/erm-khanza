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
                @include('template.alert')
                <h5 class="float-left">List Obat &emsp;</b></h5>
                <a href="{{ route('selisihobat', ['aksi' => 'CekSelisihHarga']) }}" class="btn btn-sm btn-info" role="button">Cek Selisih Harga Obat</a>
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
              @if ($hargaobat)
              <div class="card-body">
                <h3>SUPLIER {{$hargaobat->kode_brng}} - <b>{{$hargaobat->nama_brng}}</b></h3>
                <h5>Harga Dasar: @money($hargaobat->dasar)</h5>
                <h5>Harga Beli: @money($hargaobat->h_beli)</h5>
                <h5>Harga Jual: @money($hargaobat->ralan)</h5>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>Kode Suplier</th>
                    {{-- <th>Nama Suplier</th> --}}
                    <th>Tanggal Faktur</th>
                    <th>Barang Datang</th>
                    <th>Total Harga</th>
                    <th>Diskon</th>
                    <th>Setelah Diskon</th>
                    <th>PPN</th>
                    <th>Total PCS</th>
                    <th>Harga (/pcs)</th>
                    <th>Harga Jual 30% (/pcs)</th>
                    <th>Harga Jual 30% +7%</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php $no = 1 @endphp
                    @foreach ($pemesanan as $key)
                      <tr>
                        <th scope="row"> {{$no++}} </th>
                        <td>{{$key->kode_suplier}}</td>
                        {{-- <td>{{$key->nama_suplier}}</td> --}}
                        <td>{{$key->tgl_faktur}}</td>
                        <td>{{$key->jumlah}} @'{{$key->h_pesan}}</td>
                        <td>@money($key->subtotal)</td>
                        <td>{{$key->dis}}%</td>
                        <td>@money($key->total)</td>
                        @if ($key->ppn == 0)
                          <td>(0%)<br> @money($key->hargappn)</td>
                        @else
                          <td>(11%)<br> @money($key->hargappn)</td>
                        @endif
                        <td>{{$key->jumlah2}}</td>
                        <td>@money($key->pcs)</td>
                        <td>@money($key->hargajual)
                          <a href="{{ route('updatehargaobat', ['kode_brng' => $hargaobat->kode_brng, 'hargajual' => $key->hargajual]) }}" class="btn btn-sm btn-warning" role="button">Update Harga</a>
                        </td>
                        <td>@money($key->hargajuallagi)
                          <a href="{{ route('updatehargaobat', ['kode_brng' => $hargaobat->kode_brng, 'hargajual' => $key->hargajuallagi]) }}" class="btn btn-sm btn-warning" role="button">Update Harga</a>
                        </td>
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
