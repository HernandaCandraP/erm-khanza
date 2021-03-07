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
                <h3 class="float-left">Data Siswa</h3>
                @if(Auth::guard('guru')->user()->level == "Admin")
                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus"></i> Tambah Data</button>
                @endif
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              @include('template.alert')
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>NIS</th>
                    <th>Username</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>No. Telp</th>
                    @if(Auth::guard('guru')->user()->level == "Admin")
                    <th>Aksi&emsp;&ensp;</th>
                    @endif
                  </tr>
                  </thead>
                  <tbody>
                @foreach ($siswa as $no => $key)
                <tr>
                    <th scope="row"> {{$no+1}}. </th>
                    <td>{{$key->nis}}</td>
                    <td>{{$key->username}}</td>
                    <td>{{$key->nama_siswa}}</td>
                    <td>{{$key->nama_kelas}}</td>
                    <td>{{$key->jenis_kelamin}}</td>
                    <td>{{$key->alamat}}</td>
                    <td>{{$key->no_telp}}</td>
                    @if(Auth::guard('guru')->user()->level == "Admin")
                    <td>
                      <a href="{{url('admin/siswa/edit')}}/{{$key->id_siswa}}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                      <a href="{{url('admin/siswa/hapus')}}/{{$key->id_siswa}}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus siswa bernama {{$key->nama_siswa}}')"><i class="fas fa-trash"></i></a>
                      <a href="{{url('admin/siswa/reset')}}/{{$key->id_siswa}}" class="btn btn-warning btn-sm" onclick="return confirm('Apakah yakin ingin reset password siswa: {{$key->nama_siswa}}')"><i class="fas fa-history"> Reset Password</i></a>
                    </td>
                    @endif
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

<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Data Siswa</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- form -->
      <form action="{{route('homeadmin/siswa/tambah')}}" method="post" class="form-horizontal">
      {{ csrf_field() }}
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">NIS<b class="text-danger">*</b></label>
            <div class="col-sm-9">
              <input type="text" name="nis" class="form-control" autocomplete="off" placeholder="NIS" value="{{ old('nis') }}">
              @error('nis')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nama Siswa<b class="text-danger">*</b></label>
            <div class="col-sm-9">
              <input type="text" name="nama_siswa" class="form-control" autocomplete="off" placeholder="Nama Siswa" value="{{ old('nama_siswa') }}">
              @error('nama_siswa')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Kelas<b class="text-danger">*</b></label>
            <div class="col-sm-9">
              <div class="form-group">
                <select class="form-control select2" name="id_kelas" id="id_kelas">
                  <option value="null" disabled selected>== Pilih Kelas ==</option>
                  @foreach ($kelas as $key)
                    <option value="{{ $key->id_kelas }}" {{ (collect(old('id_kelas'))->contains($key->id_kelas)) ? 'selected':''}}> {{$key->nama_kelas}} </option>
                  @endforeach
                </select>
              </div>
              @error('id_kelas')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Jenis Kelamin<b class="text-danger">*</b></label>
            <div class="col-sm-9">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki" {{old('jenis_kelamin')== 'Laki-laki' ? 'checked':''}}>
                  <label class="form-check-label">Laki-Laki</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan" {{old('jenis_kelamin')== 'Perempuan' ? 'checked':''}}>
                  <label class="form-check-label">Perempuan</label>
                </div>
                @error('jenis_kelamin')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Alamat</label>
            <div class="col-sm-9">
              <input type="text" name="alamat" class="form-control" autocomplete="off" placeholder="Alamat" value="{{ old('alamat') }}">
              @error('alamat')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">No. Telp</label>
            <div class="col-sm-9">
              <input type="number" name="no_telp" class="form-control" autocomplete="off" placeholder="No. Telp"  value="{{ old('no_telp') }}">
              @error('no_telp')<p class="text-danger">{{$message}}</p>@enderror
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
<script>
  @if($errors->any() != null)
    $('#modal-lg').modal('show');
  @endif
</script>
</body>
</html>
