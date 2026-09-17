<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
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
                @include('template.alert')
                <h5 class="float-left">Formula Racikan</b></h5>
                <br><br><button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus"></i> Tambah Data </button> 
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="{{route('formularacikan')}}" method="post">
                {{ csrf_field() }}
                <table id="example1" class="table table-bordered text-sm">
                  <tbody>
                @foreach ($dataperformula as $key => $value)
                  <tr>
                    <th rowspan="{{$value->total +1}}"> {{$key+1}}. </th>
                    <th rowspan="{{$value->total +1}}"> {{$value->kd_poli}} <br> {{$value->nm_poli}} </th>
                    <th rowspan="{{$value->total +1}}">{{$value->kode_formula}}</th>
                    <th>Kode Obat</th>
                    <th>Nama Obat</th>
                    <th>Kandungan</th>
                    <th>Aksi</th>
                  </tr> 
                  @foreach ($formula as $keylengkap => $valuelengkap)
                    @if ($valuelengkap->kode_formula == $value->kode_formula)
                    <tr>
                      <td>{{$valuelengkap->kode_brng}}</td>
                      <td>{{$valuelengkap->letak_barang}} / {{$valuelengkap->nama_brng}}</td>
                      <td>{{$valuelengkap->kandungan}}</td>
                      <th>
                        <button type="submit" value="{{$valuelengkap->kode_brng}}***{{$value->kode_formula}}***{{$value->kd_poli}}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus?')" name="action[hapus]">Hapus</button>
                      </th>
                    </tr>
                    @endif
                  @endforeach
                @endforeach
                  </tbody>
                </table>
                </form>
              </div>
            </div>
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



<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Racikan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- form -->
      <form action="{{route('formularacikan')}}" method="post" class="form-horizontal">
      {{ csrf_field() }}
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Poli<b class="text-danger">*</b></label>
            <div class="col-sm-9">
              <div class="form-group">
                <select class="form-control select2" name="kd_poli" id="kd_poli" required>
                  @foreach ($poli as $key)
                    <option value="{{ $key->kd_poli }}"> {{$key->nm_poli}} </option>
                  @endforeach
                </select>
              </div>
              @error('kd_poli')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Kode Formula<b class="text-danger">*</b></label>
            <div class="col-sm-9">
              <input type="text" name="kode_formula" class="form-control" autocomplete="off" placeholder="Kode Formula" value="{{ old('kode_formula') }}" required>
              @error('kode_formula')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nama Obat</label>
            <div class="col-sm-9">
              <div class="form-group">
                <select style="min-width: 70%; max-width: 70%; font-size: 90%;" class="select2" id="kode_brng" name="kode_brng" required>
                  <option value="" disabled selected>Pilih Obat</option> 
                    @foreach ($databarang as $key)
                      <option value="{{ $key->kode_brng }}">{{$key->letak_barang}} / {{$key->nama_brng}} || stok: {{$key->stok}} || @money($key->ralan)</option>
                    @endforeach
                </select>

              </div>
              @error('kode_brng')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Kandungan</label>
            <div class="col-sm-9">
              <input type="number" min="0" step="any" name="kandungan" class="form-control" placeholder="kandungan..." value="{{ old('kandungan') }}"  required>
              @error('kandungan')<p class="text-danger">{{$message}}</p>@enderror
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm" name="action[tambah]">Tambah Formula</button>
      </div>
      </form>
      <!-- end form -->
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- ./wrapper -->

@include('template.script_table')
<script>
  $('.select2').select2();

  $(document).ready(function (){
    var table = $('#example').DataTable({
        pageLength: 5
    });
  });
  @if($errors->any() != null)
    $('#modal-lg').modal('show');
  @endif
</script>
</body>
</html>
