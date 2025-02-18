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
              <div class="card-body">
                {{-- <a href="{{ route('selisihobat', ['aksi' => 'UpdateHarga']) }}" class="btn btn-sm btn-danger m-4" role="button" onclick="return confirm('Apakah yakin ingin mengupdate semua harga obat ?')">Update Seluruh Harga Obat</a> --}}
                <table id="examplepdf" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>Kode Suplier</th>
                    <th>Nama Obat</th>
                    <th>Tanggal Faktur</th>
                    <th>Barang Datang</th>
                    <th>Total Harga</th>
                    <th>Diskon</th>
                    <th>Setelah Diskon</th>
                    <th>PPN</th>
                    <th>Total PCS</th>
                    <th>Harga (/pcs)</th>
                    <th>Harga Jual 30% (/pcs)</th>
                    <th>Harga Jual 30% + 7%</th>
                    <th>Harga Jual SIMRS</th>
                    <th>Selisih</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($selisihharagaobat as $no => $key)
                      @if ($key != null)
                      <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td>{{$key->kode_suplier}}</td>
                        <td>{{$key->nama_brng}}</td>
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
                        <td>@money($key->hargajual)</td>
                        <td>@money($key->hargajuallagi)</td>
                        <td>@money($key->ralan)</td>
                        <td>{{number_format($key->ralan - $key->hargajuallagi,2,'.','')}}</td>
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

<!-- SCRIPTS -->
@include('template.script_table')
<script src="{{asset('tampilan/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('tampilan/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('tampilan/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('tampilan/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('tampilan/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script>
  $(document).ready(function (){
    var table = $('#example').DataTable({
        pageLength: 5
    });
  });
  $(function () {
    $("#examplepdf").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#examplepdf_wrapper .col-md-6:eq(0)');
  });
</script>
</body>
</html>
