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
                <form action="{{url('siswa/jawaban/')}}/{{$tampilsoal->id_detail}}/{{$tampilsoal->id_soal}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                  <div class="form-group row">
                    <div class="col-sm-9">
                      <textarea class="form-control" rows="5" disabled>{{$tampilsoal->soal}}</textarea><br>
                        <textarea class="form-control" name="jawaban_siswa" rows="5" placeholder="jawaban">{{$jawaban['jawaban_siswa']}}</textarea><br>
                    </div>
                    <div class="col-sm-3">
                      <div class="card card-success">
                        <div class="card-header">
                          <h3 class="card-title">Different Width</h3>
                        </div>
                        <div class="card-body row text-center">
                        @foreach ($getsoal as $key => $value)
                          <div class="col-3">
                            <!-- kondisi untuk menampilkan soal yang dipilih -->
                            @if($value->id_soal == $tampilsoal->id_soal)
                              <!-- mengambil id_detail dan id_soal untuk fungsi pidah soal lainnya -->
                              <button type="submit" class="btn btn-primary" name="redirect_id_soal" value="{{ $value->id_soal }}">{{$key+1}}</button>
                            @elseif($value->jawaban_siswa == null)
                              <button type="submit" class="btn btn-default" name="redirect_id_soal" value="{{ $value->id_soal }}">{{$key+1}}</button>
                            @else
                              <button type="submit" class="btn btn-secondary" name="redirect_id_soal" value="{{ $value->id_soal }}">{{$key+1}}</button>
                            @endif
                            <br></br>
                          </div>
                        @endforeach
                        <button class="btn btn-danger form-control" disabled>Simpan</button>
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
