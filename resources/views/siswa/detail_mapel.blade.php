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
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="float-left">Detail Ujian</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>Mata Pelajaran</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Waktu Test</th>
                    <th>Keterangan</th>
                    <th>Aksi&emsp;&ensp;</th>
                  </tr>
                  </thead>
                  <tbody>
                @foreach ($detailmapel as $no =>$key)
                <tr>
                    <th scope="row"> {{$no+1}}. </th>
                    <td>{{$key->nama_mapel}}</td>
                    <td>{{$key->waktu_mulai}}</td>
                    <td>{{$key->waktu_selesai}}</td>
                    <td>{{$key->waktu_test}}</td>
                    <td>{{$key->keterangan}}</td>
                    <!-- {{strtotime($key->waktu_mulai)}} -->
                    <td>
                    <!-- membuat kondisi apakah siswa sudah pernah mengerjakan soal tsb -->
                    @if($key->is_end > 0 || ((strtotime($key->waktu_mulai) < $now) && (strtotime($key->waktu_selesai) > $now ) ) )
                      <a href="{{url('siswa/ceknilai')}}/{{$key->id_detail}}" class="btn bg-indigo btn-sm">cek nilai</a>
                    @elseif($key->id_siswa === Auth::guard('siswa')->user()->id_siswa)
                      <a href="{{url('siswa/lanjutkan')}}/{{$key->id_detail}}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i>Lanjutkan</a>
                    @else
                      <a href="{{url('siswa/keterangan')}}/{{$key->id_detail}}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i>Mulai</a>
                    @endif
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
</body>
</html>
