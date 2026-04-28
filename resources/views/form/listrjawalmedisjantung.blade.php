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
                <h3 class="card-title"><a href="{{ url()->previous() }}" class="btn btn-primary"><i class="fa fa-arrow-left nav-icon"></i></a>Riwayat Penilaian Awal Medis Rawat Jalan Dalam</h3>
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
                    <th>No</th>
                    <th>no_rawat</th>
                    <th>tanggal</th>
                    <th>Dokter</th>
                    <th>anamnesis</th>
                    <th>hubungan</th>
                    <th>keluhan_utama</th>
                    <th>rps</th>
                    <th>rpk</th>
                    <th>rpd</th>
                    <th>rpo</th>
                    <th>alergi</th>
                    <th>td</th>
                    <th>bb</th>
                    <th>tb</th>
                    <th>suhu</th>
                    <th>nadi</th>
                    <th>rr</th>
                    <th>keadaan_umum</th>
                    <th>nyeri</th>
                    <th>status_nutrisi</th>
                    <th>jantung</th>
                    <th>keterangan_jantung</th>
                    <th>paru</th>
                    <th>keterangan_paru</th>
                    <th>ekstrimitas</th>
                    <th>keterangan_ekstrimitas</th>
                    <th>lainnya</th>
                    <th>lab</th>
                    <th>ekg</th>
                    <th>penunjang_lain</th>
                    <th>diagnosis</th>
                    <th>diagnosis2</th>
                    <th>permasalahan</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($data as $no => $key)
                  <tr>
                    <th scope="row"> {{$no+1}}. </th>
                    <td><a class="btn text-primary" href="{{ route('form-awalmedisjantung', ['no_rawat' => $key->no_rawat]) }}">{{$key->no_rawat}}</a></td>
                    <td>{{$key->tanggal}}</td>
                    <td>{{$key->nama}}</td>
                    <td>{{$key->anamnesis}}</td>
                    <td>{{$key->hubungan}}</td>
                    <td>{{$key->keluhan_utama}}</td>
                    <td>{{$key->rps}}</td>
                    <td>{{$key->rpk}}</td>
                    <td>{{$key->rpd}}</td>
                    <td>{{$key->rpo}}</td>
                    <td>{{$key->alergi}}</td>
                    <td>{{$key->td}}</td>
                    <td>{{$key->bb}}</td>
                    <td>{{$key->tb}}</td>
                    <td>{{$key->suhu}}</td>
                    <td>{{$key->nadi}}</td>
                    <td>{{$key->rr}}</td>
                    <td>{{$key->keadaan_umum}}</td>
                    <td>{{$key->nyeri}}</td>
                    <td>{{$key->status_nutrisi}}</td>
                    <td>{{$key->jantung}}</td>
                    <td>{{$key->keterangan_jantung}}</td>
                    <td>{{$key->paru}}</td>
                    <td>{{$key->keterangan_paru}}</td>
                    <td>{{$key->ekstrimitas}}</td>
                    <td>{{$key->keterangan_ekstrimitas}}</td>
                    <td>{{$key->lainnya}}</td>
                    <td>{{$key->lab}}</td>
                    <td>{{$key->ekg}}</td>
                    <td>{{$key->penunjang_lain}}</td>
                    <td>{{$key->diagnosis}}</td>
                    <td>{{$key->diagnosis2}}</td>
                    <td>{{$key->permasalahan}}</td>
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
