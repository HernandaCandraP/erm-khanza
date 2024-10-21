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

          @include('template.menudokter', ['no_rawat' => $no_rawat])
          <div class="col-md-12 mt-4">
          <!-- Horizontal Form -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><a href="{{ url()->previous() }}" class="btn btn-primary"><i class="fa fa-arrow-left nav-icon"></i></a>::[ Rincian Biaya Rawat Jalan ]::</h3>
              </div>
              <!-- /.card-header -->
              <form class="form-horizontal">
                <div class="card-header">
                  <div class="row text-sm">
                    <div class="col-sm-6">
                      <div class="row">
                        <div class="col-5 text-right"><b>Nama</b></div>
                        <div class="col-7">&nbsp; {{$detailpasien[0]->nm_pasien}}</div>
                        <div class="col-5 text-right"><b>No. RM</b></div>
                        <div class="col-7">&nbsp; {{$detailpasien[0]->no_rkm_medis}}</div>
                        <div class="col-5 text-right"><b>No. Rawat</b></div>
                        <div class="col-7">&nbsp; {{$detailpasien[0]->no_rawat}}</div>
                        <div class="col-5 text-right"><b>Umur</b></div>
                        <div class="col-7">&nbsp; {{$detailpasien[0]->umur}}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <div class="form-group row text-sm">
                <div class="col-sm-6 card-success">
                  <div class="card-header">
                    <h3 class="card-title">Biaya Obat</h3>
                  </div>
                  <div class="card-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">Obat</th>
                          <th scope="col">Harga</th>
                          <th scope="col">Jumlah</th>
                          <th scope="col">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($biayaobat as $key => $value)
                        <tr>
                          <td>{{$value->nama_brng}}</td>
                          <td>@money($value->biaya_obat)</td>
                          <td>{{$value->jml}}</td>
                          <td>@money($value->total)</td>
                        </tr>
                        @endforeach
                        <tr>
                          <th colspan="3" class="text-center">Total</th>
                          <th>@money($biayaobat->sum('total'))</th>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="col-sm-6 card-success">
                  <div class="card-header">
                    <h3 class="card-title">Biaya Penanganan Dokter</h3>
                  </div>
                  <div class="card-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">Penanganan</th>
                          <th scope="col">Harga</th>
                          <th scope="col">Jumlah</th>
                          <th scope="col">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php($totalbiayapenanganandr = 0)
                        @foreach ($biayapenanganandr as $key => $value)
                        <tr>
                          <td>{{$value->nm_perawatan}}</td>
                          <td>@money($value->biaya_rawat)</td>
                          <td>{{$value->total}}</td>
                          <td>@money($value->total * $value->biaya_rawat)</td>
                          @php($totalbiayapenanganandr += $value->total * $value->biaya_rawat)
                        </tr>
                        @endforeach
                        <tr>
                          <th colspan="3" class="text-center">Total</th>
                          <th>@money($totalbiayapenanganandr)</th>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="col-sm-6 card-success">
                  <div class="card-header">
                    <h3 class="card-title">Biaya Penanganan Perawat</h3>
                  </div>
                  <div class="card-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">Penanganan</th>
                          <th scope="col">Harga</th>
                          <th scope="col">Jumlah</th>
                          <th scope="col">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php($totalbiayapenangananpr = 0)
                        @foreach ($biayapenangananpr as $key => $value)
                        <tr>
                          <td>{{$value->nm_perawatan}}</td>
                          <td>@money($value->biaya_rawat)</td>
                          <td>{{$value->total}}</td>
                          <td>@money($value->total * $value->biaya_rawat)</td>
                          @php($totalbiayapenangananpr += $value->total * $value->biaya_rawat)
                        </tr>
                        @endforeach
                        <tr>
                          <th colspan="3" class="text-center">Total</th>
                          <th>@money($totalbiayapenangananpr)</th>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="col-sm-6 card-success">
                  <div class="card-header">
                    <h3 class="card-title">Kamar</h3>
                  </div>
                  <div class="card-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">Kamar</th>
                          <th scope="col">Harga</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>{{$detailpasien[0]->nm_bangsal}}</td>
                          <td>@money($detailpasien[0]->ttl_biaya)</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="col-sm-12 card-success">
                  <div class="card-header">
                    <h3 class="card-title">Biaya Laboratorium</h3>
                  </div>
                  <div class="card-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">Penanganan</th>
                          <th scope="col">Harga</th>
                          <th scope="col">Jumlah</th>
                          <th scope="col">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php($totalbiayalab = 0)
                        @foreach ($biayalab as $key => $value)
                        <tr>
                          <td>{{$value->nm_perawatan}}</td>
                          <td>@money($value->biaya)</td>
                          <td>{{$value->total}}</td>
                          <td>@money($value->total * $value->biaya)</td>
                          @php($totalbiayalab += $value->total * $value->biaya)
                        </tr>
                        @endforeach
                        <tr>
                          <th colspan="3" class="text-center">Total</th>
                          <th>@money($totalbiayalab)</th>
                        </tr>
                      </tbody>
                    </table>
                    <div class="card card-warning collapsed-card">
                      <div class="card-header">
                        <div type="button" class="btn btn-tool col-sm-12" data-card-widget="collapse"><h3 class="card-title">Bacaan Laboratorium</h3><i class="fas fa-plus"></i></div>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">Pemeriksaan</th>
                              <th scope="col">Detail</th>
                              <th scope="col">Hasil</th>
                              <th scope="col">Satuan</th>
                              <th scope="col">Nilai Satuan</th>
                              <th scope="col">Keterangan</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($bacaanlab as $key => $value)
                            <tr>
                              <td>{{$value->nm_perawatan}}</td>
                              <td>{{$value->pemeriksaan}}</td>
                              <td>{{$value->nilai}}</td>
                              <td>{{$value->satuan}}</td>
                              <td>{{$value->nilai_rujukan}}</td>
                              <td>{{$value->keterangan}}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 card-success">
                  <div class="card-header">
                    <h3 class="card-title">Biaya Radiologi</h3>
                  </div>
                  <div class="card-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">Penanganan</th>
                          <th scope="col">Harga</th>
                          <th scope="col">Jumlah</th>
                          <th scope="col">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php($totalbiayarad = 0)
                        @foreach ($biayarad as $key => $value)
                        <tr>
                          <td>{{$value->nm_perawatan}}</td>
                          <td>@money($value->biaya)</td>
                          <td>{{$value->total}}</td>
                          <td>@money($value->total * $value->biaya)</td>
                          @php($totalbiayarad += $value->total * $value->biaya)
                        </tr>
                        @endforeach
                        <tr>
                          <th colspan="3" class="text-center">Total</th>
                          <th>@money($totalbiayarad)</th>
                        </tr>
                      </tbody>
                    </table>
                    <div class="card card-warning collapsed-card">
                      <div class="card-header">
                        <div type="button" class="btn btn-tool col-sm-12" data-card-widget="collapse"><h3 class="card-title">Bacaan Radiologi</h3><i class="fas fa-plus"></i></div>
                      </div>
                      <div class="card-body">
                        <div class="form-group row text-sm">
                          @foreach ($bacaanrad as $key => $value)
                            <div class="col-sm-6">
                              <hr>
                              <b>Hasil Bacaan {{$key+1}}</b>
                              <p>{{$value->hasil}}</p>
                              <hr>
                            </div>
                          @endforeach
                          @if($gambarrad)   
                            @foreach ($gambarrad as $key => $value)
                              <div class="col-sm-4">
                                <img src="{{$value}}" class="img-fluid w-100"/><br>
                              </div>
                            @endforeach  
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 card-success">
                  <div class="card-header">
                    <h3 class="card-title">Total Harga Pelayanan : </h3>
                  </div>
                  <div class="card-body text-center">
                    <h3>@money($hargatotal)</h3>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
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
@include('template.script')
</body>
</html>
