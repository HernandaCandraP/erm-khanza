
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
                      <label class="col-sm-3 col-form-label">NIS<b class="text-danger">*</b></label>
                      <div class="col-sm-9">
                        <input type="text" name="nis" class="form-control" autocomplete="off" placeholder="NIS" value="{{old('nis', $siswa->nis) }}" >
                        @error('nis')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Username<b class="text-danger">*</b></label>
                      <div class="col-sm-9">
                        <input type="text" name="username" class="form-control" autocomplete="off" placeholder="Username" value="{{old('username', $siswa->username) }}">
                        @error('username')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Nama Siswa<b class="text-danger">*</b></label>
                      <div class="col-sm-9">
                        <input type="text" name="nama_siswa" class="form-control" autocomplete="off" placeholder="Nama Siswa" value="{{old('nama_siswa', $siswa->nama_siswa) }}">
                        @error('nama_siswa')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Kelas<b class="text-danger">*</b></label>
                      <div class="col-sm-9">
                        <select class="form-control select2" name="id_kelas" id="id_kelas">
                          <option value="null" disabled>== Pilih Kelas ==</option>
                          @foreach ($kelas as $key)
                            <option value="{{ $key->id_kelas }}" {{ old('id_kelas', $siswa->id_kelas) == $key->id_kelas ? 'selected':''}}> {{$key->nama_kelas}} </option>
                          @endforeach
                        </select>
                        @error('kelas')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Jenis Kelamin<b class="text-danger">*</b></label>
                      <div class="col-sm-9">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin) =="Laki-laki" ? "checked" : "" }} >
                            <label class="form-check-label">Laki-Laki</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin) =="Perempuan" ? "checked" : "" }}>
                            <label class="form-check-label">Perempuan</label>
                          </div>
                          @error('jenis_kelamin')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Alamat</label>
                      <div class="col-sm-9">
                        <input type="text" name="alamat" class="form-control" autocomplete="off" placeholder="Alamat" value="{{old('alamat', $siswa->alamat) }}">
                        @error('alamat')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">No. Telp</label>
                      <div class="col-sm-9">
                        <input type="number" name="no_telp" class="form-control" autocomplete="off" placeholder="No. Telp" value="{{old('no_telp', $siswa->no_telp) }}">
                        @error('no_telp')<p class="text-danger">{{$message}}</p>@enderror
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
