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
                <h3 class="float-left">Edit Soal</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <form action="{{url('guru/soal/update')}}/{{$soal->id_soal}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="modal-body">
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Soal</label>
                      <div class="col-sm-9">
                        <input type="text" name="id_detail" class="form-control" value="{{$soal->id_detail}}" hidden>
                        <textarea type="text" name="soal" rows="3" class="form-control" placeholder="Mata Pelajaean" required>{{ $soal->soal }}</textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Jawaban</label>
                      <div class="col-sm-9">
                        <textarea type="text" name="jawaban" rows="6" class="form-control" placeholder="Jawaban">{{ $soal->jawaban }}</textarea>
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
  <!-- /.content-wrapper -->
  <footer class="main-footer">
  @include('template.footer')
  </footer>

  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

@include('template.script')
</body>
</html>
