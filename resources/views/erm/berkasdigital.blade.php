<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
    <link rel="stylesheet" href="{{asset('tampilan/plugins/daterangepicker/daterangepicker.css')}}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

@include('template.navbar')

@include('template.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="content-header"></div>

    <!-- Main content -->
    <div class="content">

      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="float-left">Pencarian NO Rekam Medis</h5>
              </div>
              @include('template.alert')
              <!-- /.card-header -->
              <form action="{{route('simrs.berkasdigital')}}" method="post" class="form-horizontal">
              {{ csrf_field() }}
              <div class="card-body row">
                <div class="col-sm-5"> 
                  <input type="text" name="no_rkm_medis" placeholder="No RM" class="form-control" value="{{old('no_rkm_medis', $pasien->no_rkm_medis ?? null) }}" style="width: 80%" required>
                </div>
                <div class="col-sm-3"> 
                  <button type="submit" class="btn btn-success" name="action" value="simpan">Cari Pasien</button>
                </div>
                
              </div>
              </form>
              
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="float-left">ERM IGD</h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="{{route('simrs.berkasdigitalsimpan')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="modal-body">
                    <div class="card-body">
                      <div class="form-group row">
                        @error('no_rkm_medis')<p class="text-danger">No Rekam Medis tidak Ditemukan</p>@enderror
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">NO RM</label>
                        <label class="col-sm-9 col-form-label">: {{$pasien->no_rkm_medis ?? null}}</label>
                        <input type="text" name="no_rkm_medis" class="form-control form-control-sm" hidden value={{ $pasien->no_rkm_medis ?? null}}>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama</label>
                        <label class="col-sm-9 col-form-label">: {{$pasien->nm_pasien ?? null}}</label>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">No Rawat</label>
                        <label class="col-sm-9 col-form-label">: {{$pasien->no_rawat ?? null}}</label>
                        <input type="text" name="no_rawat" class="form-control form-control-sm" hidden value={{ $pasien->no_rawat ?? null}}>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Lahir</label>
                        <label class="col-sm-9 col-form-label">: {{$pasien->tgl_lahir ?? null}}</label>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Alamat</label>
                        <label class="col-sm-9 col-form-label">: {{$pasien->almt_pj ?? null}}</label>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Berkas</label>
                        <div class="col-sm-9">
                          <div class="form-group">
                            <select class="form-control" name="kode" id="kode" required>
                              <option value="" disabled selected>== Pilih Berkas ==</option>
                              @foreach ($masterberkas as $key)
                                <option value="{{ $key->kode }}" {{ (collect(old('kode'))->contains($key->kode)) ? 'selected':''}}> {{$key->nama}} </option>
                              @endforeach
                            </select>
                            @error('kode')<p class="text-danger">{{$message}}</p>@enderror
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">File <b class="text-danger">(MAX 2 MB)</b></label>
                        <div class="col-sm-9">
                          <div id="list_file"></div>
                          <label for="image" class="btn" style="border:1px solid">Pilih File/Gambar</label>
                          <input type="file" id="image" name="image" style="display:none" >
                          <img src="" id="image-tag" width="400px" />
                          <div id="list_file"></div>
                          @error('image')<p class="text-danger">{{$message}}</p>@enderror
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary">Tambah Data</button>
                  </div>
                </form>

                <table id="example3" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Rawat</th>
                    <th>Data</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($listberkas as $no => $value)
                    <tr>
                        <td scope="row"> {{$no+1}}. </td>
                        <th scope="row"> {{$value->nama}} <br> {{$value->no_rawat}} <br> {{$pasien->nm_pasien ?? null}}</th>
                        <td>
                          <div class="row justify-content-center">
                            <iframe src="{{asset('file/'.$value->lokasi_file) ?? null}}" width="100%" height="350">
                                    This browser does not support PDFs. Please download the PDF to view it: <a href="{{asset('file/'.$value->lokasi_file) ?? 0}}">Download PDF</a>
                            </iframe>
                          </div>
                        </td>
                        <td>
                          {{-- <form action="{{ route('simrs.berkasdigitalhapus', 'aa') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')"><i class="fas fa-trash"></i></button>
                          </form> --}}

                          <form action="{{ route('simrs.berkasdigitalhapus', 'aa') }}" method="POST">
                            {{ csrf_field() }}
                            @method('DELETE')
                          <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
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
        </div>
      </div>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
  @include('template.footer')
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
@include('template.script_table')
<script src="{{asset('tampilan/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('tampilan/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('tampilan/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('tampilan/plugins/daterangepicker/daterangepicker.js')}}"></script>

<script>
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

  $(function () {
    $('.knob').knob({
      draw: function () {
      }
    })
  })

  var fileInput = document.getElementById('image');
  var listFile = document.getElementById('list_file');

  fileInput.onchange = function () {
    var files = Array.from(this.files);
    files = files.map(file => file.name);
    listFile.innerHTML = files.join('<br/>');
  }
</script>
</body>
</html>
