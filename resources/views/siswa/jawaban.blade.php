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
                <h4 class="float-left">Ujian Mata Palajaran ....</h4>
                <h4 class="float-right">ini waktu </h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="modal-body">
                  <div class="form-group row">
                    <div class="col-sm-9">
                    @foreach ($soal as $key)
                      <textarea class="form-control" rows="5" disabled>{{$key->soal}}</textarea><br>
                      <textarea class="form-control" rows="5" placeholder="jawaban"></textarea><br>
                    @endforeach
                    </div>
                    <div class="col-sm-3">
                      <div class="card card-success">
                        <div class="card-header">
                          <h3 class="card-title">Different Width</h3>
                        </div>
                        <div class="card-body row text-center">
                        @foreach ($soal as $key)
                          <div class="col-3">
                            <button class="btn btn-primary">{{$no++}}</button>
                          </div>
                        @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
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
  <footer class="main-footer">
  @include('template.footer')
  </footer>

  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

@include('template.script')
</body>
</html>
