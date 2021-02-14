
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
                <h3 class="float-left">Edit Data Siswa</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <form action="{{url('admin/siswa/update')}}/{{$siswa->id_siswa}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="modal-body">
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">NIS</label>
                      <div class="col-sm-9">
                        <input type="text" name="nis" class="form-control" placeholder="NIS" value="{{ $siswa->nis }}" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Username</label>
                      <div class="col-sm-9">
                        <input type="text" name="username" class="form-control" placeholder="Username" value="{{ $siswa->username }}" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Password</label>
                      <div class="col-sm-9">
                        <input type="text" name="password" class="form-control" placeholder="Password" value="{{ $siswa->password }}" disabled>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Nama Siswa</label>
                      <div class="col-sm-9">
                        <input type="text" name="nama_siswa" class="form-control" placeholder="Nama Siswa" value="{{ $siswa->nama_siswa }}" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Kelas</label>
                      <div class="col-sm-9">
                        <select class="form-control select2" name="id_kelas" id="id_kelas" required>
                          <option value="null" disabled>== Pilih Kelas ==</option>
                          @foreach ($kelas as $key)
                            <option value="{{ $key->id_kelas }}" {{$siswa->id_kelas == $key->id_kelas ? "selected" : "" }}> {{$key->nama_kelas}} </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Jenis Kelamin</label>
                      <div class="col-sm-9">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki" required {{ ($siswa->jenis_kelamin=="Laki-laki")? "checked" : "" }} >
                            <label class="form-check-label">Laki-Laki</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan" required {{ ($siswa->jenis_kelamin=="Perempuan")? "checked" : "" }}>
                            <label class="form-check-label">Perempuan</label>
                          </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Alamat</label>
                      <div class="col-sm-9">
                        <input type="text" name="alamat" class="form-control" placeholder="Alamat" value="{{ $siswa->alamat }}" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">No. Telp</label>
                      <div class="col-sm-9">
                        <input type="text" name="no_telp" class="form-control" placeholder="No. Telp" value="{{ $siswa->no_telp }}" required>
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
