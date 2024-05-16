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
                <h3 class="card-title"><a href="{{ url()->previous() }}" class="btn btn-primary"><i class="fa fa-arrow-left nav-icon"></i></a>Riwayat Medis Umum Rawat Inap</h3>
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
                    <th>Riwayat Penyakit Sekarang</th>
                    <th>Riwayat Penyakit Dahulu</th>
                    <th>Riwayat Penyakit Keluarga</th>
                    <th>Riwayat Penggunaan Obat</th>
                    <th>Riwayat Alergi</th>
                    <th>Keadaan Umum</th>
                    <th>GCS</th>
                    <th>Kesadaran</th>
                    <th>TD(mmHg)</th>
                    <th>Nadi(x/menit)</th>
                    <th>RR(x/mnt)</th>
                    <th>Suhu</th>
                    <th>SpO2</th>
                    <th>BB(Kg)</th>
                    <th>TB(cm)</th>
                    <th>Kepala</th>
                    <th>Mata</th>
                    <th>Gigi & Mulut</th>
                    <th>THT</th>
                    <th>Thoraks</th>
                    <th>Jantung</th>
                    <th>Paru</th>
                    <th>Abdomen</th>
                    <th>Genital & Anus</th>
                    <th>Ekstremitas</th>
                    <th>Kulit</th>
                    <th>Ket. Pemeriksaan Fisik</th>
                    <th>TFU</th>
                    <th>TBJ</th>
                    <th>His</th>
                    <th>Kontraksi</th>
                    <th>DJJ</th>
                    <th>Inspeksi</th>
                    <th>inspekulo</th>
                    <th>VT</th>
                    <th>RT</th>
                    <th>Ultrasonografi</th>
                    <th>Kardiotokografi</th>
                    <th>Laboratorium</th>
                    <th>Penunjang Lainnya</th>
                    <th>Diagnosis/Asesmen</th>
                    <th>Tatalaksana</th>
                    <th>Edukasi</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($data as $no => $key)
                  <tr>
                    <th scope="row"> {{$no+1}}. </th>
                    <td><a class="btn text-primary" href="{{ route('formri-mediskebidanan', ['no_rawat' => $key->no_rawat]) }}">{{$key->no_rawat}}</a></td>
                    <td>{{$key->tanggal}}</td>
                    <td>{{$key->nama}}</td>
                    <td>{{$key->anamnesis}}</td>
                    <td>{{$key->hubungan}}</td>
                    <td>{{$key->keluhan_utama}}</td>
                    <td>{{$key->rps}}</td>
                    <td>{{$key->rpd}}</td>
                    <td>{{$key->rpk}}</td>
                    <td>{{$key->rpo}}</td>
                    <td>{{$key->alergi}}</td>
                    <td>{{$key->keadaan}}</td>
                    <td>{{$key->gcs}}</td>
                    <td>{{$key->kesadaran}}</td>
                    <td>{{$key->td}}</td>
                    <td>{{$key->nadi}}</td>
                    <td>{{$key->rr}}</td>
                    <td>{{$key->suhu}}</td>
                    <td>{{$key->spo}}</td>
                    <td>{{$key->bb}}</td>
                    <td>{{$key->tb}}</td>
                    <td>{{$key->kepala}}</td>
                    <td>{{$key->mata}}</td>
                    <td>{{$key->gigi}}</td>
                    <td>{{$key->tht}}</td>
                    <td>{{$key->thoraks}}</td>
                    <td>{{$key->jantung}}</td>
                    <td>{{$key->paru}}</td>
                    <td>{{$key->abdomen}}</td>
                    <td>{{$key->genital}}</td>
                    <td>{{$key->ekstremitas}}</td>
                    <td>{{$key->kulit}}</td>
                    <td>{{$key->ket_fisik}}</td>
                    <td>{{$key->tfu}}</td>
                    <td>{{$key->tbj}}</td>
                    <td>{{$key->his}}</td>
                    <td>{{$key->kontraksi}}</td>
                    <td>{{$key->kulit}}</td>
                    <td>{{$key->djj}}</td>
                    <td>{{$key->inspeksi}}</td>
                    <td>{{$key->inspekulo}}</td>
                    <td>{{$key->vt}}</td>
                    <td>{{$key->rt}}</td>
                    <td>{{$key->ultra}}</td>
                    <td>{{$key->kardio}}</td>
                    <td>{{$key->lab}}</td>
                    <td>{{$key->diagnosis}}</td>
                    <td>{{$key->tata}}</td>
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
