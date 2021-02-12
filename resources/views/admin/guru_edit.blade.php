
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
                <h3 class="float-left">Edit Data Guru</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <form action="{{url('admin/guru/update')}}/{{$guru->id_guru}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="modal-body">
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">NIP</label>
                      <div class="col-sm-9">
                        <input type="text" name="nip" class="form-control" placeholder="NIP" value="{{ $guru->nip }}" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Username</label>
                      <div class="col-sm-9">
                        <input type="text" name="username" class="form-control" placeholder="Username" value="{{ $guru->username }}" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Password</label>
                      <div class="col-sm-9">
                        <input type="text" name="password" class="form-control" placeholder="Password" value="{{ $guru->password }}" disabled>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Nama Guru</label>
                      <div class="col-sm-9">
                        <input type="text" name="nama_guru" class="form-control" placeholder="Nama Guru" value="{{ $guru->nama_guru }}" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Jenis Kelamin</label>
                      <div class="col-sm-9">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki" required {{ ($guru->jenis_kelamin=="Laki-laki")? "checked" : "" }} >
                            <label class="form-check-label">Laki-Laki</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan" required {{ ($guru->jenis_kelamin=="Perempuan")? "checked" : "" }}>
                            <label class="form-check-label">Perempuan</label>
                          </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Alamat</label>
                      <div class="col-sm-9">
                        <input type="text" name="alamat" class="form-control" placeholder="Alamat" value="{{ $guru->alamat }}" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">No. Telp</label>
                      <div class="col-sm-9">
                        <input type="text" name="no_telp" class="form-control" placeholder="No. Telp" value="{{ $guru->no_telp }}" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Level</label>
                      <div class="col-sm-9">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="level" value="Guru" required {{ ($guru->level=="Guru")? "checked" : "" }}>
                            <label class="form-check-label">Guru</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="level" value="Admin" required {{ ($guru->level=="Admin")? "checked" : "" }}>
                            <label class="form-check-label">Admin</label>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer justify-content-between float-right">
                  <button type="submit" class="btn btn-primary">Tambah Data</button>
                </div>
                </form>
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
