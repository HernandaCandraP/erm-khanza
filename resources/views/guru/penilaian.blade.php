<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  @include('template.navbar')
  @include('template.sidebar')

  <div class="content-wrapper">
    <section class="content-header"></section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <a href="{{url('guru/penilaian/')}}/{{$id_detail}}/cetak" target="blank" class="btn btn-warning float-right"><i class="fas fa-print"></i> Cetak Laporan Penilaian</a>
                <h5>Perikasa Hasil Penilaian</h5>
                <h6>Mata Pelajaran {{$namamapel->nama_mapel}} ({{$namamapel->waktu_mulai}} - {{$namamapel->waktu_selesai}})</h6> 
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <!-- we are adding the accordion ID so Bootstrap's collapse plugin detects it -->
                <div id="accordion">
                @foreach($kls as $value)
                  <div class="card card-primary">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#kelas{{$value->nama_kelas}}{{$value->id_kelas}}">
                          Kelas {{$value->nama_kelas}} <i class="fas fa-angle-down"></i>
                        </a>
                      </h4>
                    </div>
                    <div id="kelas{{$value->nama_kelas}}{{$value->id_kelas}}" class="collapse" data-parent="#accordion">
                      <div class="card-body">
                        <table id="select{{$value->nama_kelas}}{{$value->id_kelas}}" class="table table-bordered table-striped">
                          <thead>
                          <tr>
                            <th>No.</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Nilai Cosine</th>
                            <th>Nilai Jaccard</th>
                            <th>Penilaian Guru</th>
                            <th>Aksi</th>
                          </tr>
                          </thead>
                          <tbody>
                          @foreach ($value->getsiswa as $no =>$key)
                          <tr>
                              <th scope="row"> {{$no+1}}. </th>
                              <td>{{$key->nis}}</td>
                              <td>{{$key->nama_siswa}}</td>
                              <td>@if(empty($avg_cosine[$key->id_siswa]))
                                    0
                                  @else {{$avg_cosine[$key->id_siswa]}}
                                  @endif 
                              </td>
                              <td>@if(empty($avg_jaccard[$key->id_siswa]))
                                    0
                                  @else {{$avg_jaccard[$key->id_siswa]}}
                                  @endif 
                              </td>
                              <td>{{number_format($key->getjawaban->whereIn('id_soal',$getsoal_arr)->sum('nilai_guru') / ($count_soal), 0, '.', '')}}</td>
                              <td> <a href="{{url('guru/penilaian/edit')}}/{{$id_detail}}/{{$key->id_siswa}}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i>penialain manual</a></td>
                          </tr> 
                          @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                @endforeach
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <footer class="main-footer">
  @include('template.footer')
  </footer>

  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

@include('template.script_table')
<script>
@foreach($kls as $value)
  $(function () {
    $("#select{{$value->nama_kelas}}{{$value->id_kelas}}").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
@endforeach
</script>
</body>
</html>
