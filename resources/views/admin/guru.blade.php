
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
                <h3 class="float-left">Data Guru</h3>
                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus"></i> Tambah Data</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>NIP</th>
                    <th>Username</th>
                    <th>Nama Guru</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>No. Telp</th>
                    <th>Level</th>
                    <th>Aksi&emsp;&ensp;</th>
                  </tr>
                  </thead>
                  <tbody>
                @foreach ($guru as $key)
                <tr>
                    <th scope="row"> {{$no++}}. </th>
                    <td>{{$key->nip}}</td>
                    <td>{{$key->username}}</td>
                    <td>{{$key->nama_guru}}</td>
                    <td>{{$key->jenis_kelamin}}</td>
                    <td>{{$key->alamat}}</td>
                    <td>{{$key->no_telp}}</td>
                    <td>{{$key->level}}</td>
                    <td>
                      <a href="{{url('admin/guru/edit')}}/{{$key->id_guru}}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                      <a href="{{url('admin/guru/hapus')}}/{{$key->id_guru}}" class="btn btn-danger btn-sm" onclick="return confirm('Menghapus data akan berpengaruh pada perhitungan, memungkinkan terjadi perubahan data !!')"><i class="fas fa-trash"></i></a>
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
  <!-- /.content-wrapper -->
  <footer class="main-footer">
  @include('template.footer')
  </footer>

  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Data Guru</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- form -->
      <form action="{{route('homeadmin/guru/tambah')}}" method="post" class="form-horizontal">
      {{ csrf_field() }}
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">NIP</label>
            <div class="col-sm-9">
              <input type="text" name="nip" class="form-control" placeholder="NIP" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Username</label>
            <div class="col-sm-9">
              <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Password</label>
            <div class="col-sm-9">
              <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nama Guru</label>
            <div class="col-sm-9">
              <input type="text" name="nama_guru" class="form-control" placeholder="Nama Guru" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Jenis Kelamin</label>
            <div class="col-sm-9">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki" required>
                  <label class="form-check-label">Laki-Laki</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan" required>
                  <label class="form-check-label">Perempuan</label>
                </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Alamat</label>
            <div class="col-sm-9">
              <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">No. Telp</label>
            <div class="col-sm-9">
              <input type="text" name="no_telp" class="form-control" placeholder="No. Telp" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Level</label>
            <div class="col-sm-9">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="level" value="Guru" required>
                  <label class="form-check-label">Guru</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="level" value="Admin" required>
                  <label class="form-check-label">Admin</label>
                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Tambah Data</button>
      </div>
      </form>
      <!-- end form -->
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

@include('template.script_table')
</body>
</html>
