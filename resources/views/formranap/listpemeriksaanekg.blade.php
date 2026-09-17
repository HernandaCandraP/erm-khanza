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
          <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="card card-primary">
              <!-- /.card-header -->
              <div class="card-header">
                <h3 class="card-title"><a href="{{ url()->previous() }}" class="btn btn-primary"><i class="fa fa-arrow-left nav-icon"></i></a>Riwayat Pemeriksaan EKG</h3>
              </div>
              <div class="card-body">
                <div class="row text-sm">
                  <div class="col-3 text-right"><b>Nama</b></div>
                  <div class="col-9">&nbsp; {{$detailpasien->nm_pasien}}</div>
                  <div class="col-3 text-right"><b>No. RM</b></div>
                  <div class="col-9">&nbsp; {{$detailpasien->no_rkm_medis}}</div>
                  <div class="col-3 text-right"><b>Umur</b></div>
                  <div class="col-9">&nbsp; {{$detailpasien->umur}}</div>
                </div>
              </div>
              <hr>
              <div class="card-body">
              @include('template.alert')
                <table id="example1" class="table table-bordered table-striped text-sm">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No Rawat</th>
                    <th>Tgl. Perawatan</th>
                    <th>Nama Dokter</th>
                    <th>Diagnosa Klinis</th>
                    <th>Kiriman Dari</th>
                    <th>Irama</th>
                    <th>Laju Jantung</th>
                    <th>Gelombang P</th>
                    <th>Interval PR</th>
                    <th>Axis</th>
                    <th>Kompleks QRS</th>
                    <th>Segmen ST</th>
                    <th>Gelombang T</th>
                    <th>Kesimpulan</th>
                  </tr>
                  </thead>
                  <tbody>
                @foreach ($data as $no => $key)
                <tr>
                    <th scope="row"> {{$no+1}}. </th>
                    <td><a class="btn text-primary" href="{{ route('ekgranap', ['no_rawat' => $key->no_rawat]) }}">{{$key->no_rawat}}</a></td>
                    <td>{{$key->tanggal}}</td>
                    <td>{{$key->nama}}</td>
                    <td>{{$key->diagnosa_klinis}}</td>
                    <td>{{$key->kiriman_dari}}</td>
                    <td>{{$key->irama}}</td>
                    <td>{{$key->laju_jantung}}</td>
                    <td>{{$key->gelombangp}}</td>
                    <td>{{$key->intervalpr}}</td>
                    <td>{{$key->axis}}</td>
                    <td>{{$key->kompleksqrs}}</td>
                    <td>{{$key->segmenst}}</td>
                    <td>{{$key->gelombangt}}</td>
                    <td>{{$key->kesimpulan}}</td>
                </tr> 
                @endforeach
                  
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
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
@include('template.script_table')
</body>
</html>
