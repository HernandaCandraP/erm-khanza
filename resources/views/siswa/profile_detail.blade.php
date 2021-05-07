
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
                <h3 class="float-left">Profile Siswa</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              @include('template.alert')
                <div class="modal-body">
                  <div class="form-group row">
                    <label class="col-sm-3">NIS</label>
                    <div class="col-sm-9">{{$siswa->nis}}</div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3">Nama</label>
                    <div class="col-sm-9">{{$siswa->nama_siswa}}</div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3">Username</label>
                    <div class="col-sm-9">{{$siswa->username}}</div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3">Jenis Kelamin</label>
                    <div class="col-sm-9">{{$siswa->jenis_kelamin}}</div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3">Alamat</label>
                    <div class="col-sm-9">{{$siswa->alamat}}</div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3">No Telp</label>
                    <div class="col-sm-9">{{$siswa->no_telp}}</div>
                  </div>
                </div>
                <div class="modal-footer justify-content-center">
                  <button type="submit" class="btn btn-warning" data-toggle="modal" data-target="#modal-password">Ganti Pasword</button>
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
  <!-- /.content-wrapper -->
  <footer class="main-footer">
  @include('template.footer')
  </footer>

  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<div class="modal fade" id="modal-password">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Ubah Password</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('homesiswa/profile/password')}}" method="post" class="form-horizontal">
      {{ csrf_field() }}
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Password Lama<b class="text-danger">*</b></label>
            <div class="col-sm-9">
              <input type="password" name="password_lama" class="form-control" autocomplete="off" placeholder="Password Lama" value="{{ old('password_lama') }}">
              @error('password_lama')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Password Baru<b class="text-danger">*</b></label>
            <div class="col-sm-9">
              <input type="password" name="password_baru" class="form-control" autocomplete="off" placeholder="Password Baru" value="{{ old('password_baru') }}">
              @error('password_baru')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Ulangi Password Baru<b class="text-danger">*</b></label>
            <div class="col-sm-9">
              <input type="password" name="ulangi_password_baru" class="form-control" autocomplete="off" placeholder="Ulangi Password Baru" value="{{ old('ulangi_password_baru') }}">
              @error('ulangi_password_baru')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Ganti Password</button>
      </div>
      </form>
      <!-- end form -->
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

@include('template.script')
<script>
  @if($errors->any() != null)
    $('#modal-password').modal('show');
  @endif
</script>
</body>
</html>
