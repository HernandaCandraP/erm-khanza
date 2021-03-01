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
                <h3 class="float-left">Detail Ujian</h3>
                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus"></i> Tambah Data</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Waktu Test</th>
                    <th>Keterangan</th>
                    <th>Aksi&emsp;&ensp;</th>
                  </tr>
                  </thead>
                  <tbody>
                @foreach ($detailmapel as $key)
                <tr>
                    <th scope="row"> {{$no++}}. </th>
                    <td>{{$key->nama_mapel}} <br> 
                      <a href="{{url('guru/soal')}}/{{$key->id_detail}}" class="btn btn-success btn-sm">Cek Soal</a>
                      <a href="{{url('guru/penilaian')}}/{{$key->id_detail}}" class="btn btn-warning btn-sm">Periksa Ujian</a>
                    </td>
                    <td>
                      @foreach($key->kelas as $kelas)
                        <b class="btn btn-info btn-sm">{{$kelas->nama_kelas}}</b>
                      @endforeach
                    </td>
                    <td>{{$key->waktu_mulai}}</td>
                    <td>{{$key->waktu_selesai}}</td>
                    <td>{{$key->waktu_test}}</td>
                    <td>{{$key->keterangan}}</td>
                    <td>
                      <a href="{{url('guru/detailmapel/edit')}}/{{$key->id_detail}}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                      <a href="{{url('guru/detailmapel/hapus')}}/{{$key->id_detail}}" class="btn btn-danger btn-sm" onclick="return confirm('Menghapus data akan berpengaruh pada perhitungan, memungkinkan terjadi perubahan data !!')"><i class="fas fa-trash"></i></a>
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

<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Detail Ujian</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- form -->
      <form action="{{route('homeguru/detailmapel/tambah')}}" method="post" class="form-horizontal">
      {{ csrf_field() }}
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Mata Pelajaran</label>
            <div class="col-sm-9">
                <select class="form-control " name="id_mapel" id="id_mapel" required>
                  <option value="null" disabled selected>== Pilih Mata Pelajaran ==</option>
                  @foreach ($mapel as $key)
                    <option value="{{ $key->id_mapel }}">{{$key->nama_mapel}} </option>
                  @endforeach
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Kelas</label>
            <div class="col-sm-9 select2-purple">
                  <select class="select2" multiple="multiple" name="id_kelas[]" id="id_kelas" data-placeholder="Pilih Kelas" data-dropdown-css-class="select2-purple" required>
                    @foreach ($kls as $key)
                      <option value="{{ $key->id_kelas }}"> {{$key->nama_kelas}} </option>
                    @endforeach
                  </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Waktu Mulai</label>
            <div class="col-sm-9">
              <input type="datetime-local" name="waktu_mulai" class="form-control" placeholder="Waktu Mulai" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Waktu Selesai</label>
            <div class="col-sm-9">
              <input type="datetime-local" name="waktu_selesai" class="form-control" placeholder="Waktu Selesai" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Waktu Test</label>
            <div class="col-sm-9">
              <input type="time" name="waktu_test" class="form-control" placeholder="Waktu Test" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Keterangan</label>
            <div class="col-sm-9">
              <input type="text" name="keterangan" class="form-control" placeholder="Keterangan" required>
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
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  })
</script>
</body>
</html>
