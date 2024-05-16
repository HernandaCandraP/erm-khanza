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
                <h3 class="card-title"><a href="{{ url()->previous() }}" class="btn btn-primary"><i class="fa fa-arrow-left nav-icon"></i></a>Riwayat Penilaian Awal Medis Rawat Jalan Mata</h3>
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
                    <th>Riwayat Penggunaan Obat</th>
                    <th>Riwayat Alergi</th>
                    <th>Status Nutrisi</th>
                    <th>TD(mmHg)</th>
                    <th>Nadi(x/menit)</th>
                    <th>RR(x/mnt)</th>
                    <th>Suhu</th>
                    <th>Nyeri</th>
                    <th>BB(Kg)</th>
                    <td>Visus SC Kanan</td>
                    <td>Visus SC Kiri</td>
                    <td>CC Kanan</td>
                    <td>CC Kiri</td>
                    <td>Palpebra Kanan</td>
                    <td>Palpebra Kiri</td>
                    <td>Konjungtiva Kanan</td>
                    <td>Konjungtiva Kiri</td>
                    <td>Cornea Kanan</td>
                    <td>Cornea Kiri</td>
                    <td>COA Kanan</td>
                    <td>COA Kiri</td>
                    <td>Pupil Kanan</td>
                    <td>Pupil Kiri</td>
                    <td>Lensa Kanan</td>
                    <td>Lensa Kiri</td>
                    <td>Fundus Media Kanan</td>
                    <td>Fundus Media Kiri</td>
                    <td>Papil Kanan</td>
                    <td>Papil Kiri</td>
                    <td>Retina Kanan</td>
                    <td>Retina Kiri</td>
                    <td>Makula Kanan</td>
                    <td>Makula Kiri</td>
                    <td>TIO Kanan</td>
                    <td>TIO Kiri</td>
                    <td>MBO Kanan</td>
                    <td>MBO Kiri</td>
                    <td>Laboratorium </td>
                    <td>Radiologi </td>
                    <td>Penunjang Lain </td>
                    <td>Tes Penglihatan </td>
                    <td>Pemeriksaan Lain</td>
                    <td>Asesmen Kerja</td>
                    <td>Asesmen Banding</td>
                    <td>Permasalahan</td>
                    <td>Terapi / Pengobatan</td>
                    <td>Tindakan / Rencana Tindakan</td>
                    <td>Edukasi</td>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($data as $no => $key)
                  <tr>
                    <th scope="row"> {{$no+1}}. </th>
                    <td><a class="btn text-primary" href="{{ route('form-awalmedismata', ['no_rawat' => $key->no_rawat]) }}">{{$key->no_rawat}}</a></td>
                    <td>{{$key->tanggal}}</td>
                    <td>{{$key->nama}}</td>
                    <td>{{$key->anamnesis}}</td>
                    <td>{{$key->hubungan}}</td>
                    <td>{{$key->keluhan_utama}}</td>
                    <td>{{$key->rps}}</td>
                    <td>{{$key->rpd}}</td>
                    <td>{{$key->rpo}}</td>
                    <td>{{$key->alergi}}</td>
                    <td>{{$key->status}}</td>
                    <td>{{$key->td}}</td>
                    <td>{{$key->nadi}}</td>
                    <td>{{$key->rr}}</td>
                    <td>{{$key->suhu}}</td>
                    <td>{{$key->nyeri}}</td>
                    <td>{{$key->bb}}</td>
                    <td>{{$key->visuskanan}}</td>
                    <td>{{$key->visuskiri}}</td>
                    <td>{{$key->cckanan}}</td>
                    <td>{{$key->cckiri}}</td>
                    <td>{{$key->palkanan}}</td>
                    <td>{{$key->palkiri}}</td>
                    <td>{{$key->conkanan}}</td>
                    <td>{{$key->conkiri}}</td>
                    <td>{{$key->corneakanan}}</td>
                    <td>{{$key->corneakiri}}</td>
                    <td>{{$key->coakanan}}</td>
                    <td>{{$key->coakiri}}</td>
                    <td>{{$key->pupilkanan}}</td>
                    <td>{{$key->pupilkiri}}</td>
                    <td>{{$key->lensakanan}}</td>
                    <td>{{$key->lensakiri}}</td>
                    <td>{{$key->funduskanan}}</td>
                    <td>{{$key->funduskiri}}</td>
                    <td>{{$key->papilkanan}}</td>
                    <td>{{$key->papilkiri}}</td>
                    <td>{{$key->retinakanan}}</td>
                    <td>{{$key->retinakiri}}</td>
                    <td>{{$key->makulakanan}}</td>
                    <td>{{$key->makulakiri}}</td>
                    <td>{{$key->tiokanan}}</td>
                    <td>{{$key->tiokiri}}</td>
                    <td>{{$key->mbokanan}}</td>
                    <td>{{$key->mbokiri}}</td>
                    <td>{{$key->lab}}</td>
                    <td>{{$key->rad}}</td>
                    <td>{{$key->penunjang}}</td>
                    <td>{{$key->tes}}</td>
                    <td>{{$key->pemeriksaan}}</td>
                    <td>{{$key->diagnosis}}</td>
                    <td>{{$key->diagnosisbdg}}</td>
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
