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
                <h3 class="float-left">Data Siswa Kelas {{ $kelas->nama_kelas }}</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>NIS</th>
                    <th>Username</th>
                    <th>Nama Siswa</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>No. Telp</th>
                  </tr>
                  </thead>
                  <tbody>
                @foreach ($siswa as $key)
                <tr>
                    <th scope="row"> {{$no++}}. </th>
                    <td>{{$key->nis}}</td>
                    <td>{{$key->username}}</td>
                    <td>{{$key->nama_siswa}}</td>
                    <td>{{$key->jenis_kelamin}}</td>
                    <td>{{$key->alamat}}</td>
                    <td>{{$key->no_telp}}</td>
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
