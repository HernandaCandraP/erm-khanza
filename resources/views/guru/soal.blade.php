
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
                <h4 class="float-left">Soal Mata Pelajaran {{$detailmapel->nama_mapel}}</h4><br><br>
                <h6 class="float-left">Kelas {{$kelas}}</h6>
                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus"></i> Tambah Data</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>Soal</th>
                    <th>Jawaban</th>
                    <th>Terakhir Diubah</th>
                    <th>Aksi&emsp;&ensp;</th>
                  </tr>
                  </thead>
                  <tbody>
                @foreach ($soal as $key)
                <tr>
                    <th scope="row"> {{$no++}}. </th>
                    <td><textarea rows="5" cols="20" disabled>{{$key->soal}}</textarea></td>
                    <td><textarea rows="5" cols="40" disabled>{{$key->jawaban}}</textarea></td>
                    <td>{{$key->nama_guru}}</td>
                    <td>
                      <a href="{{url('guru/soal/edit')}}/{{$key->id_soal}}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                      <a href="{{url('guru/soal/hapus')}}/{{$key->id_soal}}/{{$key->id_detail}}" class="btn btn-danger btn-sm" onclick="return confirm('Menghapus data akan berpengaruh pada perhitungan, memungkinkan terjadi perubahan data !!')"><i class="fas fa-trash"></i></a>
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
        <h4 class="modal-title">Tambah Soal {{$detailmapel->nama_mapel}}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{url('guru/soal/tambah')}}/{{$detailmapel->id_detail}}" method="post" class="form-horizontal">
      {{ csrf_field() }}
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Soal</label>
            <div class="col-sm-9">
              <textarea type="text" name="soal" rows="3" class="form-control" placeholder="Soal" required></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Jawaban</label>
            <div class="col-sm-9">
              <textarea type="text" name="jawaban" rows="6" class="form-control" placeholder="Jawaban"></textarea>
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

@include('template.script')
</body>
</html>
