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
              <form action="{{route('simrs.rawatinap')}}" method="post" class="form-horizontal">
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

          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="float-left">ERM RAWAT INAP <b>{{$tanggalawal ?? null}} - {{$tanggalakhir ?? null}}</b></h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Rawat</th>
                    <th>Nama Lengkap</th>
                    <th>Dokter Dituju</th>
                    <th>Poli</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($listpasien as $no => $value)
                    <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td>{{$value->no_rawat}}</td>
                        <td>{{$value->nm_pasien}} {{$value->no_rkm_medis}}</td>
                        <td>{{$value->nm_dokter}}</td>
                        <td>{{$value->nm_poli}} <br>({{$value->status_lanjut}})</td>
                        <td>
                          <ul>
                            <li>
                              {!! $value->pak != null ? "<b class='text-success'>Awal Keperawatan Ranap</b>" : "<b class='text-danger'>Awal Keperawatan Ranap</b>" !!}
                            </li>
                            <li>
                              {!! $value->pkk != null ? "<b class='text-success'>Keperawatan Kebidanan dan Kandungan</b>" : "<b class='text-danger'>Keperawatan Kebidanan dan Kandungan</b>" !!}
                            </li>
                            <li>
                              {!! $value->pmu != null ? "<b class='text-success'>Medis Umum Ranap</b>" : "<b class='text-danger'>Medis Umum Ranap</b>" !!}
                            </li>  
                            <li>
                              {!! $value->mrk != null ? "<b class='text-success'>Medis Kebidanan dan Kandungan</b>" : "<b class='text-danger'>Medis Kebidanan dan Kandungan</b>" !!}
                            </li>  
                            <li>
                              {!! $value->rjd != null ? "<b class='text-success'>Resiko Jatuh Dewasa</b>" : "<b class='text-danger'>Resiko Jatuh Dewasa</b>" !!} 
                            </li>  
                            <li>
                              {!! $value->rja != null ? "<b class='text-success'>Resiko Jatuh Anak</b>" : "<b class='text-danger'>Resiko Jatuh Anak</b>" !!}
                            </li>  
                            <li>
                              {!! $value->rjl != null ? "<b class='text-success'>Resiko Jatuh Lansia</b>" : "<b class='text-danger'>Resiko Jatuh Lansia</b>" !!}
                            </li>
                            <li>
                              {!! $value->rjn != null ? "<b class='text-success'>Resiko Jatuh Neonatus</b>" : "<b class='text-danger'>Resiko Jatuh Neonatus</b>" !!}
                            </li>   
                            <li>
                              {!! $value->rjg != null ? "<b class='text-success'>Resiko Jatuh Geriatri</b>" : "<b class='text-danger'>Resiko Jatuh Geriatri</b>" !!}
                            </li>  
                            <li>
                              {!! $value->rjp != null ? "<b class='text-success'>Resiko Jatuh Psikiatri</b>" : "<b class='text-danger'>Resiko Jatuh Psikiatri</b>" !!}
                            </li>
                            <li>
                              {!! $value->pptl != null ? "<b class='text-success'>Penilaian Pasien Terminal</b>" : "<b class='text-danger'>Penilaian Pasien Terminal</b>" !!}
                            </li>
                            <li>
                              {!! $value->ckh != null ? "<b class='text-success'>Check List Kriteria Masuk HCU</b>" : "<b class='text-danger'>Check List Kriteria Masuk HCU</b>" !!}
                            </li>
                            <li>
                              {!! $value->ckk != null ? "<b class='text-success'>Check List Kriteria Keluar HCU</b>" : "<b class='text-danger'>Check List Kriteria Keluar HCU</b>" !!}
                            </li>        
                            <li>
                              {!! $value->ppa != null ? "<b class='text-success'>Pemantauan PEWS Anak</b>" : "<b class='text-danger'>Pemantauan PEWS Anak</b>" !!}
                            </li>
                            <li>
                              {!! $value->ppd != null ? "<b class='text-success'>Pemantauan PEWS Dewasa</b>" : "<b class='text-danger'>Pemantauan PEWS Dewasa</b>" !!}
                            </li>
                            <li>
                              {!! $value->cor != null ? "<b class='text-success'>Catatan Observasi Ranap</b>" : "<b class='text-danger'>Catatan Observasi Ranap</b>" !!}
                            </li>
                            <li>
                              {!! $value->ckr != null ? "<b class='text-success'>Catatan Keperawatan Ranap</b>" : "<b class='text-danger'>Catatan Keperawatan Ranap</b>" !!}
                            </li>
                            <li>
                              {!! $value->ccg != null ? "<b class='text-success'>Catatan Cek GDS</b>" : "<b class='text-danger'>Catatan Cek GDS</b>" !!}
                            </li>
                            <li>
                              {!! $value->mrt != null ? "<b class='text-success'>Monitoring Reaksi Tranfusi</b>" : "<b class='text-danger'>Monitoring Reaksi Tranfusi</b>" !!}
                            </li>
                            <li>
                              {!! $value->hpe != null ? "<b class='text-success'>Hasil Pemeriksaan EKG</b>" : "<b class='text-danger'>Hasil Pemeriksaan EKG</b>" !!}
                            </li>
                            <li>
                              {!! $value->pun != null ? "<b class='text-success'>Penilaian Ulang Nyeri</b>" : "<b class='text-danger'>Penilaian Ulang Nyeri</b>" !!}
                            </li>
                            <li>
                              {!! $value->cp != null ? "<b class='text-success'>Catatan Persalinan</b>" : "<b class='text-danger'>Catatan Persalinan</b>" !!}
                            </li>
                            <li>
                              {!! $value->tpa != null ? "<b class='text-success'>Transfer Pasien Antar Ruang</b>" : "<b class='text-danger'>Transfer Pasien Antar Ruang</b>" !!}
                            </li>
                            <li>
                              {!! $value->pp != null ? "<b class='text-success'>Perencanaan Pemulangan</b>" : "<b class='text-danger'>Perencanaan Pemulangan</b>" !!}
                            </li>
                            <li>
                              {!! $value->rpr != null ? "<b class='text-success'>Resume Pasien Ranap</b>" : "<b class='text-danger'>Resume Pasien Ranap</b>" !!}
                            </li>
                            <li>
                              {!! $value->pptn != null ? "<b class='text-success'>Persetujuan / Penolakan Tindakan</b>" : "<b class='text-danger'>Persetujuan / Penolakan Tindakan</b>" !!}
                            </li>
                            <li>
                              {!! $value->co != null ? "<b class='text-success'>Catatan Observasi Ranap Kebidanan</b>" : "<b class='text-danger'>Catatan Observasi Ranap Kebidanan</b>" !!}  
                            </li>
                            <li>
                              {!! $value->cop != null ? "<b class='text-success'>Catatan Observasi Ranap Postpartum</b>" : "<b class='text-danger'>Catatan Observasi Ranap Postpartum</b>" !!}
                            </li>
                          </ul>
                        </td>
                    </tr> 
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
