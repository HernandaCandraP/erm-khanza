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
                <h3 class="card-title"><a href="{{ url()->previous() }}" class="btn btn-primary"><i class="fa fa-arrow-left nav-icon"></i></a>Riwayat Penilaian Awal Medis Rawat Jalan Syaraf</h3>
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
                    <th>Anamanesis</th>
                    <th>Hubungan</th>
                    <th>Keluhan Utama</th>
                    <th>Riwayat Penggunaan Obat</th>
                    <th>Riwayat Penyakit Sekarang</th>
                    <th>Riwayat Penyakit Dahulu</th>
                    <th>Riwayat Alergi</th>
                    <th>Kesadaran</th>
                    <th>Status</th>
                    <th>TD(mmHg)</th>
                    <th>Nadi(x/menit)</th>
                    <th>Suhu</th>
                    <th>RR(x/mnt)</th>
                    <th>BB(Kg)</th>
                    <th>Nyeri</th>
                    <th>GCS</th>
                    <th>Kepala</th>
                    <th>Thoraks</th>
                    <th>Abdomen</th>
                    <th>Ekstremitas</th>
                    <th>Columna</th>
                    <th>Muskuloskeletal</th>
                    <th>Lainnya</th>
                    <th>Laboratorium</th>
                    <th>Radiologi</th>
                    <th>Pemeriksaan</th>
                    <th>Diagnosis</th>
                    <th>Diagnosis Banding</th>
                    <th>Permasalahan</th>
                    <th>Terapi</th>
                    <th>Tindakan</th>
                    <th>Edukasi</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($data as $no => $key)
                  <tr>
                    <th scope="row"> {{$no+1}}. </th>
                    <td><a class="btn text-primary" href="{{ route('form-awalmedissyaraf', ['no_rawat' => $key->no_rawat]) }}">{{$key->no_rawat}}</a></td>
                    <td>{{$key->tanggal}}</td>
                    <td>{{$key->nama}}</td>
                    <td>{{$key->anamnesis}}</td>
                    <td>{{$key->hubungan}}</td>
                    <td>{{$key->keluhan_utama}}</td>
                    <td>{{$key->rpo}}</td>
                    <td>{{$key->rps}}</td>
                    <td>{{$key->rpd}}</td>
                    <td>{{$key->alergi}}</td>
                    <td>{{$key->kesadaran}}</td>
                    <td>{{$key->status}}</td>
                    <td>{{$key->td}}</td>
                    <td>{{$key->nadi}}</td>
                    <td>{{$key->suhu}}</td>
                    <td>{{$key->rr}}</td>
                    <td>{{$key->bb}}</td>
                    <td>{{$key->nyeri}}</td>
                    <td>{{$key->gcs}}</td>
                    <td>{{$key->kepala}}, {{$key->keterangan_kepala}}</td>
                    <td>{{$key->thoraks}}, {{$key->keterangan_thoraks}}</td>
                    <td>{{$key->abdomen}}, {{$key->keterangan_abdomen}}</td>
                    <td>{{$key->ekstremitas}}, {{$key->keterangan_ekstremitas}}</td>
                    <td>{{$key->columna}}, {{$key->keterangan_columna}}</td>
                    <td>{{$key->muskulos}}, {{$key->keterangan_muskulos}}</td>
                    <td>{{$key->lainnya}}</td>
                    <td>{{$key->lab}}</td>
                    <td>{{$key->rad}}</td>
                    <td>{{$key->penunjanglain}}</td>
                    <td>{{$key->diagnosis}}</td>
                    <td>{{$key->diagnosis2}}</td>
                    <td>{{$key->permasalahan}}</td>
                    <td>{{$key->terapi}}</td>
                    <td>{{$key->tindakan}}</td>
                    <td>{{$key->edukasi}}</td>
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
