
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
              
              <div class="card-body">
              @include('template.alert')
                @foreach ($soal as $no => $key)
                <hr><div class="row">
                  <div class="col-1 col-sm-1 col-md-1">
                    {{$no+1}}
                  </div>
                  <div class="col-11 col-sm-11 col-md-11">
                    @if($key->image != null)
                    <img style="border:1px solid;" src="{{asset('image/'. $key->image)}}" class="img-fluid" width="300px" /><br>
                    @endif
                    <b>Soal:</b>
                    <div style="background-color:transparent; text-align:justify; height:auto" class="form-control">@nl2br($key->soal)</div>
                    <b>Jawaban:</b>
                    <div style="background-color:transparent; text-align:justify; height:auto" class="form-control">@nl2br($key->jawaban)</div>
                    <b>Aksi:</b>
                    <a href="{{url('guru/soal/edit')}}/{{$key->id_soal}}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                    <a href="{{url('guru/soal/hapus')}}/{{$key->id_soal}}/{{$key->id_detail}}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus Ujian nomer {{$no+1}} ?')"><i class="fas fa-trash"></i></a>
                  </div>
                </div>
                @endforeach
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
      <form action="{{url('guru/soal/tambah')}}/{{$detailmapel->id_detail}}" method="post" class="form-horizontal" enctype="multipart/form-data">
      {{ csrf_field() }}
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Image</label>
            <div class="col-sm-9">
              <label for="image" class="btn" style="border:1px solid">Pilih Gambar</label>
              <input type="file" id="image" name="image" style="display:none">
              <img src="" id="image-tag" width="200px" />
              @error('image')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Soal<b class="text-danger">*</b></label>
            <div class="col-sm-9">
              <textarea type="text" onkeyup="txtarea(this)" name="soal" class="form-control" placeholder="Soal">{{ old('soal') }}</textarea>
              @error('soal')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Jawaban<b class="text-danger">*</b></label>
            <div class="col-sm-9">
              <textarea type="text" onkeyup="txtarea(this)" name="jawaban" class="form-control" placeholder="Jawaban">{{ old('jawaban') }}</textarea>
              @error('jawaban')<p class="text-danger">{{$message}}</p>@enderror
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
  function txtarea(element){
    element.style.height = '1px';
    element.style.height = (5+element.scrollHeight)+"px";
  }
  function readURL(input){
    if(input.files && input.files[0]){
      var reader = new FileReader();
      reader.onload = function(e){
        $('#image-tag').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
  // $("image").change(function(){
  //   filename = this.file[0].name;
  // });
  $('#image').change(function(){
      readURL(this);
  });
  @if($errors->any() != null)
    $('#modal-lg').modal('show');
  @endif
</script>
</body>
</html>
