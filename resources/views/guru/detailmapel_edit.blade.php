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
                <h3 class="float-left">Edit Detail Ujian</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <form action="{{url('guru/detailmapel/update')}}/{{$detailmapel->id_detail}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="modal-body">
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Mata Pelajaran</label>
                      <div class="col-sm-9">
                        <select class="form-control " name="id_mapel" id="id_mapel" required>
                          <option value="null" disabled>== Pilih Mata Pelajaran ==</option>
                          @foreach ($mapel as $key)
                            <option value="{{ $key->id_mapel }}" {{$detailmapel->id_mapel == $key->id_mapel ? "selected" : "" }}>{{$key->nama_mapel}} </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Kelas</label>
                      <div class="col-sm-9 select2-purple">
                        <select class="select2 form-control" multiple="multiple" name="id_kelas[]" id="id_kelas" data-placeholder="Pilih Kelas" data-dropdown-css-class="select2-purple" required>
                          @foreach ($kls as $key)
                            <option  
                              @foreach ($detailmapel->kelas as $kelas) {
                                {{$kelas->id_kelas == $key->id_kelas ? "selected" : "" }}
                              @endforeach
                              value="{{ $key->id_kelas }}"> {{$key->nama_kelas}} 
                            </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Waktu Mulai</label>
                      <div class="col-sm-9">
                        <input type="datetime-local" name="waktu_mulai" class="form-control" placeholder="Waktu Mulai" value="{{ date('Y-m-d\TH:i', strtotime( $detailmapel->waktu_mulai)) }}" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Waktu Selesai</label>
                      <div class="col-sm-9">
                        <input type="datetime-local" name="waktu_selesai" class="form-control" placeholder="Waktu Selesai" value="{{ date('Y-m-d\TH:i', strtotime( $detailmapel->waktu_selesai)) }}" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Waktu Test</label>
                      <div class="col-sm-9">
                        <input type="time" name="waktu_test" class="form-control" placeholder="Waktu Test" value="{{ $detailmapel->waktu_test }}" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Keterangan</label>
                      <div class="col-sm-9">
                        <input type="text" name="keterangan" class="form-control" placeholder="Keterangan" value="{{ $detailmapel->keterangan }}" required>
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
