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

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="card card-primary">
              <!-- /.card-header -->
              <div class="card-header">
                <h3 class="card-title">Kerusakan & Pangajuan</h3>
              </div>
              <div class="card-body">
                @include('template.alert')
                <!-- form start -->
                <form action="{{route('kerusakan-tambah')}}" method="post" class="form-horizontal">
                  {{ csrf_field() }}
                  <div  class="card-body">
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label-sm">Ruangan</label>
                      <div class="col-sm-10">
                        <input type="text" name="ruangan" class="form-control form-control-sm" value="" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label-sm">Detail</label>
                      <div class="col-sm-10">
                          <select class="" name="detail">
                            <option value="-">-</option>
                            <option value="Medis">Medis</option>
                            <option value="Non-Medis">Non-Medis</option>
                            <option value="SIMRS">SIMRS</option>
                            <option value="Jaringan">JARINGAN</option>
                          </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label-sm">Deskripsi</label>
                      <div class="col-sm-10">
                        <textarea class="form-control form-control-sm" rows="5" name="deskripsi" autocomplete="off" required></textarea>
                      </div>
                    </div>
                    <div class="form-group row" hidden>
                      <label class="col-sm-2 col-form-label-sm">Tanggal Dilaporkan</label>
                      <div class="col-sm-10">
                        <input type="date" name="tgl_kerusakan" class="form-control-sm" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                      </div>
                    </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="submit" class="btn btn-info float-right ml-3" name="action" value="simpan">Simpan</button>
                  </div>
                  <!-- /.card-footer -->
                </form>
              </div>
            </div>
          </div>

          @if ($aksesdata === true)
          <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="card card-primary">
              <!-- /.card-header -->
              <div class="card-header">
                <h3 class="card-title">Pengerjaan</h3>
              </div>
              <div class="card-body">
                <table id="example3" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>Ruangan</th>
                    <th>Detail</th>
                    <th>Deskripsi</th>
                    <th>Tgl Dilaporkan</th>
                    <th>Tgl TL</th>
                    <th>Pengirim</th>
                    <th>Penindaklanjut</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($kerusakan_all as $key => $value)
                    <tr>
                      <th scope="row"> {{$key+1}}. </th>
                      <td>{{$value->ruangan}}</td>
                      <td>{{$value->detail}}</td>
                      <td>{{$value->deskripsi}}</td>
                      <td>{{$value->tgl_input}}</td>
                      <td>{{$value->ditindaklanjuti}}</td>
                      <td>{{$value->nama}}</td>
                      <td>
                        <form onsubmit="return confirm('Apakah Anda Yakin Update ?');" action="{{ route('kerusakan-update', $value->id) }}" method="POST">
                          {{ csrf_field() }}
                          <select class="form-control form-control-sm" name="action">
                            <option value="-" {{ old('saran', $value->saran) == '-' ? 'selected':'' }}>-</option>
                            <option value="poby" {{ old('saran', $value->saran) == 'poby' ? 'selected':'' }}>Septian Ganza Febryanto, S. Kom</option>
                            <option value="hernanda" {{ old('saran', $value->saran) == 'hernanda' ? 'selected':'' }}>Hernanda Candra P, S.Tr.Kom.</option>
                            <option value="dicky" {{ old('saran', $value->saran) == 'dicky' ? 'selected':'' }}>Dicky Nur Rohman</option>
                          </select>
                          <button><i class="nav-icon fas fa-save"></i></button>
                        </form>
                      </td>
                      <td>
                        <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('kerusakan-update', $value->id) }}" method="POST">
                          {{ csrf_field() }}
                          @if ($value->ditindaklanjuti == null)
                            <button class="btn btn-sm btn-warning" name="action" value="ditindaklanjuti">Ditindaklanjuti</button>
                          @elseif($value->selesai == null)
                            <button class="btn btn-sm btn-success" name="action" value="selesai">Selesaikan</button>
                          @else
                            Sudah selesai
                          @endif
                        </form>
                      </td>
                    </tr> 
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          @endif

          <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="card card-primary">
              <!-- /.card-header -->
              <div class="card-header">
                <h3 class="card-title">Riwayat Kerusakan</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>Ruangan</th>
                    <th>Detail</th>
                    <th>Deskripsi</th>
                    <th>Tgl Dilaporkan</th>
                    <th>Tgl TL</th>
                    <th>Yg Menindaklajuti</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($kerusakan as $key => $value)
                    <tr>
                      <th scope="row"> {{$key+1}}. </th>
                      <td>{{$value->ruangan}}</td>
                      <td>{{$value->detail}}</td>
                      <td>{{$value->deskripsi}}</td>
                      <td>{{$value->tgl_input}}</td>
                      <td>{{$value->ditindaklanjuti}}</td>
                      <td>{{$value->saran}}</td>
                      <td>
                        <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('kerusakan-hapus', $value->id) }}" method="POST">
                          {{ csrf_field() }}
                          @if ($value->ditindaklanjuti == null)
                            <span style="color: blue;">Belum</span>
                            <button><i class="fas fa-trash"></i></button>
                          @elseif($value->selesai == null)
                            <span style="color: red;">Sedang Ditindaklanjuti</span>
                          @else
                            <span style="color: green;">Selesai</span>
                          @endif
                        </form>
                      </td>
                    </tr> 
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
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

<!-- SCRIPTS -->
@include('template.script_table')
</body>
</html>
