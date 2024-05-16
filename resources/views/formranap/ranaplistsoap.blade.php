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
                <h3 class="card-title"><a href="{{ url()->previous() }}" class="btn btn-primary"><i class="fa fa-arrow-left nav-icon"></i></a>Riwayat SOAP</h3>
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
                    <th>Jam</th>
                    <th>Suhu(C)</th>
                    <th>Tensi</th>
                    <th>Nadi(/menit)</th>
                    <th>Respirasi(/menit)</th>
                    <th>Tinggi(cm)</th>
                    <th>Berat(Kg)</th>
                    <th>SpO2(%)</th>
                    <th>GCS(E,V,M)</th>
                    <th>Kesadaran</th>
                    <th>Subject</th>
                    <th>Object</th>
                    <th>Alergi</th>
                    <th>Plan</th>
                    <th>Asesmen</th>
                    <th>Instruksi</th>
                    <th>Evaluasi</th>
                    <th>NIP</th>
                    <th>Dokter/Paramedis</th>
                  </tr>
                  </thead>
                  <tbody>
                @foreach ($data as $no => $key)
                <tr>
                    <th scope="row"> {{$no+1}}. </th>
                    <td><a class="btn text-primary" href="{{ route('formri-soap', ['no_rawat' => $key->no_rawat, 'tgl_perawatan' => $key->tgl_perawatan, 'jam_rawat' => $key->jam_rawat]) }}">{{$key->no_rawat}}</a></td>
                    <td>{{$key->tgl_perawatan}}</td>
                    <td>{{$key->jam_rawat}}</td>
                    <td>{{$key->suhu_tubuh}}</td>
                    <td>{{$key->tensi}}</td>
                    <td>{{$key->nadi}}</td>
                    <td>{{$key->respirasi}}</td>
                    <td>{{$key->tinggi}}</td>
                    <td>{{$key->berat}}</td>
                    <td>{{$key->spo2}}</td>
                    <td>{{$key->gcs}}</td>
                    <td>{{$key->kesadaran}}</td>
                    <td>{{$key->keluhan}}</td>
                    <td>{{$key->pemeriksaan}}</td>
                    <td>{{$key->alergi}}</td>
                    <td>{{$key->rtl}}</td>
                    <td>{{$key->penilaian}}</td>
                    <td>{{$key->instruksi}}</td>
                    <td>{{$key->evaluasi}}</td>
                    <td>{{$key->nip}}</td>
                    <td>{{$key->nama}}</td>
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
