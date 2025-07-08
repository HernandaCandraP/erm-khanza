<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

@include('template.navbar')

@include('template.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="content-header"></div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="float-left">Data Akses</h5>
                <br><br><button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus"></i> Tambah Data </button> 
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                @include('template.alert')
                <form action="{{route('aksilogin')}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <table id="example3" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Jenis Kelamin</th>
                    <th>Username</th>
                    <th>Level</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($datalogin as $no => $key)
                    <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td>{{$key->nik}}</td>
                        <td>{{$key->nama}}</td>
                        <td>{{$key->alamat}}</td>
                        <td>{{$key->jk}}</td>
                        <td>{{$key->username}}</td>
                        <td>{{$key->level}}</td>
                        <th>
                          <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group mr-2" role="group" aria-label="First group">
                              <button type="submit" class="btn btn-warning" onclick="return confirm('Apakah yakin ingin mereset password {{$key->nama}}?')"  name="action[reset]" value="{{$key->nik}}">Reset</button>
                              <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah yakin ingin menghapus {{$key->nama}}?')"  name="action[hapus]" value="{{$key->nik}}">Hapus</button>
                            </div>
                          </div>
                        </th>
                    </tr> 
                    @endforeach
                  
                  </tbody>
                </table>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
  @include('template.footer')
  </footer>
</div>

<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Akses Login</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- form -->
      <form action="{{route('tambahlogin')}}" method="post" class="form-horizontal">
      {{ csrf_field() }}
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nama<b class="text-danger">*</b></label>
            <div class="col-sm-9">
              <div class="form-group">
                <select class="form-control select2" name="nik" id="nik">
                  @foreach ($pegawai as $key)
                    <option value="{{ $key->nik }}"> {{$key->nama}} </option>
                  @endforeach
                </select>
              </div>
              @error('nik')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Level<b class="text-danger">*</b></label>
            <div class="col-sm-9">
              <div class="form-group">
                <select class="form-control select2" name="level" id="level">
                  <option value="Petugas">Petugas</option>
                  <option value="Dokter">Dokter</option>
                  <option value="Admin">Admin</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Username</label>
            <div class="col-sm-9">
              <input type="text" name="username" class="form-control" autocomplete="off" placeholder="Username..." value="{{ old('username') }}">
              @error('username')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Password</label>
            <div class="col-sm-9">
              <input type="text" name="password" class="form-control" autocomplete="off" placeholder="Password..." value="{{ old('password') }}">
              @error('password')<p class="text-danger">{{$message}}</p>@enderror
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
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
@include('template.script_table')
<script src="{{asset('tampilan/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<script>
  $(function () {
    $('.knob').knob({
      draw: function () {
      }
    })
  })
  @if($errors->any() != null)
    $('#modal-lg').modal('show');
  @endif
</script>
</body>
</html>
