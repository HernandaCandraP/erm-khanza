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
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <form action="{{url('siswa/simpanwaktu')}}/{{$detailmapel->id_detail}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div class="modal-body">
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-sm-3">Mata Pelajaran</label>
                      <div class="col-sm-9"> {{$detailmapel->nama_mapel}}</div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3">Waktu Mulai</label>
                      <div class="col-sm-9">
                        {{$detailmapel->waktu_mulai}}
                        <input type="text" name="id_detail" class="form-control" value="{{ $detailmapel->id_detail }}" hidden>
                        <input type="datetime-local" name="waktu_mulai" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime( $detailmapel->waktu_mulai)) }}" hidden>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3">Waktu Selesai</label>
                      <div class="col-sm-9">
                        {{$detailmapel->waktu_selesai}}
                        <input type="datetime-local" name="waktu_selesai" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime( $detailmapel->waktu_selesai)) }}" hidden>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3">Waktu Test</label>
                      <div class="col-sm-9">
                        {{ $detailmapel->waktu_test}}
                        <input type="time" name="waktu_test" class="form-control" value="{{ $detailmapel->waktu_test }}" hidden>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3">Keterangan</label>
                      <div class="col-sm-9">
                        {{ $detailmapel->keterangan }}
                      </div>
                    </div>
                  </div>
                </div>
                <center><button type="submit" class="btn btn-success">&emsp;&emsp;Mulai&emsp;&emsp;</button></center>
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
