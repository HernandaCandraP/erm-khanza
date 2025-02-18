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
              <form action="{{route('riwayatradiologi')}}" method="post" class="form-horizontal">
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
          
        </div>
      </div>
      
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="float-left">Riwayat Radiologi <b>{{$tanggalawal ?? null}} - {{$tanggalakhir ?? null}}</b></h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Rawat</th>
                    <th>Nama Perawatan</th>
                    <th>Status</th>
                    <th>Tanggal Periksa</th>
                    <th>Jam Periksa</th>
                    <th>Biaya</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($riwayatradiologi as $no => $key)
                      <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td>{{$key->no_rawat}}</td>
                        <td>{{$key->kd_jenis_prw}} {{$key->nm_perawatan}}</td>
                        <td>{{$key->status}}</td>
                        <td>{{$key->tgl_periksa}}</td>
                        <td>{{$key->jam}}</td>
                        <td>@money($key->biaya)</td>
                      </tr> 
                    @endforeach
                  </tbody>
                </table>
                <div class="form-group pt-3">
                  <div class="card card-primary collapsed-card">
                    <div class="card-header">
                      <div type="button" class="btn btn-tool col-sm-12" data-card-widget="collapse"><h3 class="card-title">Data Radiologi</h3><i class="fas fa-plus"></i></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="example1" class="table table-bordered text-sm">
                        <tbody>
                          @foreach ($riwayatradiologigroupnorawat as $key => $value)
                            <tr>
                              <th rowspan="{{$value->jumlah +1}}">{{$key+1}}. </th>
                              <th rowspan="{{$value->jumlah +1}}">{{$value->no_rawat}} <br>{{$value->no_rkm_medis}} <br>{{$value->nm_pasien}}</th>
                              <td rowspan="{{$value->jumlah +1}}">{{$value->hasil}}</td>
                              <td rowspan="{{$value->jumlah +1}}">Bacaan: {{$cekperiksa[$key]}}</td>
                              <td rowspan="{{$value->jumlah +1}}">{{$value->png_jawab}}</td>
                              <td rowspan="{{$value->jumlah +1}}">
                                @if($gambarrad[$key])   
                                  @foreach ($gambarrad[$key] as $keygambar => $valuegambar)
                                      Gambar {{$keygambar+1}}<img src="{{$valuegambar}}" class="img-fluid w-100"/><br>
                                  @endforeach 
                                @else
                                  <div class="col-sm-6">Gambar Radiologi tidak tersedia, silahkan hubungi petugas radiologi</div> 
                                @endif
                              </td>
                              <th>Perawatan</th>
                              <th>Harga</th>
                            </tr> 
                            @foreach ($riwayatradiologi as $keylengkap => $valuelengkap)
                              @if ($valuelengkap->no_rawat == $value->no_rawat && $valuelengkap->tgl_periksa == $value->tgl_periksa && $valuelengkap->jam == $value->jam)
                              <tr>
                                <td>{{$valuelengkap->kd_jenis_prw}} {{$valuelengkap->nm_perawatan}}</td>
                                <td>@money($valuelengkap->biaya)</td>
                              </tr>
                              @endif
                            @endforeach
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <hr>
                <h4>Total Pendapatan &nbsp;: @money($hargatotal)</h4>

                <div class="card-body row">
                  <div class="col-md-12">
                    <b>dr. Candra Harmindasatya, Sp. Rad</b>
                    <div><b>Radiologi</b></div>
                  </div>
                  <div class="col-md-4">
                    <div><b>{{$bpjs[1]['jenis']}}</b></div>
                    <div>Total Permintaan :&emsp; {{$bpjs[1]['permintaan']}}</div>
                    <div>Total Bacaan :&emsp;{{$bpjs[1]['bacaan']}}</div>
                    <input readonly type="text" class="knob" value="{{$bpjs[1]['hasil']}}" data-width="100" data-height="100" data-fgColor="#059212">
                  </div>
                  <div class="col-md-4">
                    <div><b>{{$bpjs[0]['jenis']}}</b></div>
                    <div>Total Permintaan :&emsp; {{$bpjs[0]['permintaan']}}</div>
                    <div>Total Bacaan :&emsp;{{$bpjs[0]['bacaan']}}</div>
                    <input readonly type="text" class="knob" value="{{$bpjs[0]['hasil']}}" data-width="100" data-height="100" data-fgColor="#059212">
                  </div>
                  <div class="col-md-4">
                    <div><b>{{$bpjs[2]['jenis']}}</b></div>
                    <div>Total Permintaan :&emsp; {{$bpjs[2]['permintaan']}}</div>
                    <div>Total Bacaan :&emsp;{{$bpjs[2]['bacaan']}}</div>
                    <input readonly type="text" class="knob" value="{{$bpjs[2]['hasil']}}" data-width="100" data-height="100" data-fgColor="#059212">
                  </div>
                </div>
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
