
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
                <h3 class="float-left">Data Kelas</h3>
                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus"></i> Tambah Data</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>Nama Kelas</th>
                    <th>Keterangan</th>
                    @if(Auth::guard('guru')->user()->level == "Admin")
                    <th>Aksi&emsp;&ensp;</th>
                    @endif
                  </tr>
                  </thead>
                  <tbody>
                @foreach ($kelas as $key)
                <tr>
                    <th scope="row"> {{$no++}}. </th>
                    <td>{{$key->nama_kelas}} &ensp;
                      @if(Auth::guard('guru')->user()->level == "Admin")
                        <a href="{{url('admin/kelas/detail')}}/{{$key->id_kelas}}" class="btn btn-success btn-sm"><i class="fas fa-search"></i>detail</a>
                      @endif
                      @if(Auth::guard('guru')->user()->level == "Guru")
                        <a href="{{url('guru/kelas/detail')}}/{{$key->id_kelas}}" class="btn btn-success btn-sm"><i class="fas fa-search"></i>detail</a>
                      @endif
                    </td>
                    <td>{{$key->keterangan}}</td>
                    @if(Auth::guard('guru')->user()->level == "Admin")
                    <td>
                      <a href="{{url('admin/kelas/edit')}}/{{$key->id_kelas}}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                      <a href="{{url('admin/kelas/hapus')}}/{{$key->id_kelas}}" class="btn btn-danger btn-sm" onclick="return confirm('Menghapus data akan berpengaruh pada perhitungan, memungkinkan terjadi perubahan data !!')"><i class="fas fa-trash"></i></a>
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
<!-- ./wrapper -->

<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Data Kelas</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('homeadmin/kelas/tambah')}}" method="post" class="form-horizontal">
      {{ csrf_field() }}
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nama Kelas</label>
            <div class="col-sm-9">
              <input type="text" name="nama_kelas" class="form-control" placeholder="Nama Kelas" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Keterangan</label>
            <div class="col-sm-9">
              <input type="text" name="keterangan" class="form-control" placeholder="Keterangan">
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
