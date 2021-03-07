
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
                      <label class="col-sm-3 col-form-label">NIP<b class="text-danger">*</b></label>
                      <div class="col-sm-9">
                        <input type="text" name="nip" class="form-control" autocomplete="off" placeholder="NIP" value="{{ old('nip', $guru->nip) }}">
                        @error('nip')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Username<b class="text-danger">*</b></label>
                      <div class="col-sm-9">
                        <input type="text" name="username" class="form-control" autocomplete="off" placeholder="Username" value="{{ old('username', $guru->username) }}">
                        @error('username')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Nama Guru<b class="text-danger">*</b></label>
                      <div class="col-sm-9">
                        <input type="text" name="nama_guru" class="form-control" autocomplete="off" placeholder="Nama Guru" value="{{ old('nama_guru', $guru->nama_guru) }}">
                        @error('nama_guru')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Jenis Kelamin<b class="text-danger">*</b></label>
                      <div class="col-sm-9">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki" {{ old('jenis_kelamin', $guru->jenis_kelamin) =="Laki-laki" ? "checked" : "" }} >
                            <label class="form-check-label">Laki-Laki</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan" {{ old('jenis_kelamin', $guru->jenis_kelamin) =="Perempuan" ? "checked" : "" }}>
                            <label class="form-check-label">Perempuan</label>
                          </div>
                          @error('jenis_kelamin')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Alamat</label>
                      <div class="col-sm-9">
                        <input type="text" name="alamat" class="form-control" autocomplete="off" placeholder="Alamat" value="{{ old('alamat', $guru->alamat) }}">
                        @error('alamat')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">No. Telp<b class="text-danger">*</b></label>
                      <div class="col-sm-9">
                        <input type="number" name="no_telp" class="form-control" autocomplete="off" placeholder="No. Telp" value="{{ old('no_telp', $guru->no_telp) }}">
                        @error('no_telp')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Level<b class="text-danger">*</b></label>
                      <div class="col-sm-9">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="level" value="Guru" {{ old('level', $guru->level) =="Guru" ? "checked" : "" }}>
                            <label class="form-check-label">Guru</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="level" value="Admin" {{ old('level', $guru->level) =="Admin" ? "checked" : "" }}>
                            <label class="form-check-label">Admin</label>
                          </div>
                          @error('level')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer justify-content-between float-right">
                  <button type="submit" class="btn btn-primary">Simpan Data</button>
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
