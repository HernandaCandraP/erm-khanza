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
              <div class="card-header">
                <h3 class="card-title"><a href="{{ url()->previous() }}" class="btn btn-primary"><i class="fa fa-arrow-left nav-icon"></i></a>Riwayat Resume</h3>
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
              <!-- /.card-header -->
              <div class="card-body">
              @include('template.alert')
                <table id="example1" class="table table-bordered table-striped text-sm">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Rawat</th>
                    <th>Keluhan utama riwayat penyakit yang positif</th>
                    <th>Jalannya Penyakit selama perawatan</th>
                    <th>Pemeriksaan penunjang yang posisitf</th>
                    <th>Hasil laboratorium yang positif</th>
                    <th>Pulang</th>
                    <th>Dokter penangung jawab</th>
                    <th>Diagnosa Utama (ICD)</th>
                    <th>Diagnosa Sekunder 1 (ICD)</th>
                    <th>Diagnosa Sekunder 2 (ICD10)</th>
                    <th>Diagnosa Sekunder 3 (ICD10)</th>
                    <th>Diagnosa Sekunder 4 (ICD10)</th>
                    <th>Prosedur Utama (ICD)</th10>
                    <th>prosedur Sekunder 1 (ICD9)</th>
                    <th>prosedur Sekunder 2 (ICD9)</th>
                    <th>prosedur Sekunder 3 (ICD9)</th>
                    <th>Obat-obatan waktu pulang/nasihat</th>
                  </tr>
                  </thead>
                  <tbody>
                @foreach ($data as $no => $key)
                <tr>
                    <th scope="row"> {{$no+1}}. </th>
                    <td><a class="btn text-primary" href="{{ route('form-resume', ['no_rawat' => $key->no_rawat]) }}">{{$key->no_rawat}}</a></td>
                    <td>{{$key->keluhan_utama}}</td>
                    <td>{{$key->jalannya_penyakit}}</td>
                    <td>{{$key->pemeriksaan_penunjang}}</td>
                    <td>{{$key->hasil_laborat}}</td>
                    <td>{{$key->kondisi_pulang}}</td>
                    <td>{{$key->nama}}</td>
                    <td>{{$key->diagnosa_utama}} ( {{$key->kd_diagnosa_utama}} )</td>
                    <td>{{$key->diagnosa_sekunder}} ( {{$key->kd_diagnosa_sekunder}} )</td>
                    <td>{{$key->diagnosa_sekunder2}} ( {{$key->kd_diagnosa_sekunder2}} )</td>
                    <td>{{$key->diagnosa_sekunder3}} ( {{$key->kd_diagnosa_sekunder3}} )</td>
                    <td>{{$key->diagnosa_sekunder4}} ( {{$key->kd_diagnosa_sekunder4}} )</td>
                    <td>{{$key->prosedur_utama}} ( {{$key->kd_prosedur_utama}} )</td>
                    <td>{{$key->prosedur_sekunder}} ( {{$key->kd_prosedur_sekunder}} )</td>
                    <td>{{$key->prosedur_sekunder2}} ( {{$key->kd_prosedur_sekunder2}} )</td>
                    <td>{{$key->prosedur_sekunder3}} ( {{$key->kd_prosedur_sekunder3}} )</td>
                    <td>{{$key->obat_pulang}}</td>
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
