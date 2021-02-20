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
                @foreach ($detailmapel as $key)
                <tr>
                    <th scope="row"> {{$no++}}. </th>
                    <td>{{$key->nama_mapel}}</td>
                    <td>{{$key->waktu_mulai}}</td>
                    <td>{{$key->waktu_selesai}}</td>
                    <td>{{$key->waktu_test}}</td>
                    <td>{{$key->keterangan}}</td>
                    <td>
                      <a href="{{url('siswa/keterangan')}}/{{$key->id_detail}}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i>Mulai</a>
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

@include('template.script')
</body>
</html>
