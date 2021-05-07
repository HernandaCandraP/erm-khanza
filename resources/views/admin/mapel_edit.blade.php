
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
                <h3 class="float-left">Edit Data Mata Pelajaran</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <form action="{{url('admin/mapel/update')}}/{{$mapel->id_mapel}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="modal-body">
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Mata Pelajaran<b class="text-danger">*</b></label>
                      <div class="col-sm-9">
                        <input type="text" name="nama_mapel" class="form-control" placeholder="Mata Pelajaean" autocomplete="off" value="{{old('nama_mapel', $mapel->nama_mapel) }}" required>
                        @error('nama_mapel')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Keterangan</label>
                      <div class="col-sm-9">
                        <input type="text" name="keterangan" class="form-control" placeholder="Keterangan" autocomplete="off" value="{{old('keterangan', $mapel->keterangan) }}">
                        @error('keterangan')<p class="text-danger">{{$message}}</p>@enderror
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
  <!-- /.content-wrapper -->
  <footer class="main-footer">
  @include('template.footer')
  </footer>

  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

@include('template.script')
</body>
</html>
