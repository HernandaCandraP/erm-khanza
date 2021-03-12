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
              <form action="{{url('guru/soal/update')}}/{{$soal->id_soal}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="modal-body">
                @include('template.alert')
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Image</label>
                      <div class="col-sm-5">
                        <img style="border:1px solid;" id="image-tag" src="{{$soal->image != null ? asset('image/'. $soal->image) : ''}}" class="img-fluid" width="300px" />
                        @if($soal->image == null)
                        <label for="image" class="btn btn-sm btn-outline-success" style="border:1px solid" onclick="return confirm('Apakah yakin ingin menambahkan gambar ?')"><i class="fas fa-plus"></i>Tambah Gambar</label><br>
                        @endif
                        @error('soal')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                      <div class="col-sm-4">
                        @if($soal->image != null)
                        <label for="image" class="btn btn-sm btn-outline-warning" style="border:1px solid" onclick="return confirm('Apakah yakin ingin mengganti dengan gambar baru ?')"><i class="fas fa-edit"></i>Ubah Gambar</label><br>
                        <a href="{{url('guru/deleteimage')}}/{{$soal->id_soal}}" class="btn btn-sm btn-outline-danger" style="border:1px solid" onclick="return confirm('Apakah yakin ingin menghapus gambar ?')"><i class="fas fa-trash"></i>Hapus Gambar</a>
                        @endif
                        <input type="file" id="image" name="image" style="display:none">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Soal<b class="text-danger">*</b></label>
                      <div class="col-sm-9">
                        <input type="text" name="id_detail" class="form-control" value="{{$soal->id_detail}}" hidden>
                        <textarea type="text" onkeyup="txtarea(this)" name="soal" rows="3" class="form-control" placeholder="Mata Pelajaean">{{old('soal', $soal->soal) }}</textarea>
                        @error('soal')<p class="text-danger">{{$message}}</p>@enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Jawaban<b class="text-danger">*</b></label>
                      <div class="col-sm-9">
                        <textarea type="text" onkeyup="txtarea(this)" name="jawaban" rows="6" class="form-control" placeholder="Jawaban">{{old('jawaban', $soal->jawaban) }}</textarea>
                        @error('jawaban')<p class="text-danger">{{$message}}</p>@enderror
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
  $('#image').change(function(){
      readURL(this)
  });
</script>
</body>
</html>
