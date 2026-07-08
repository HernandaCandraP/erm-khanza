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
          {{-- <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-info"><i class="far fa-hospital"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">TOTAL KERUSAKAN</span>
                <span class="info-box-number">{{$total['total']}}</span>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-success"><i class="fas fa-user-clock"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">BELUM DITINDAKLANJUTI</span>
                <span class="info-box-number">{{$total['belum']}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="fa fa-users"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">SEDANG DITINDAKLANJUTI</span>
                <span class="info-box-number">{{$total['ditindaklanjuti']}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-danger"><i class="fa fa-user-plus"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">SELESAI DITINDAKLANJUTI</span>
                <span class="info-box-number">{{$total['selesai']}}</span>
              </div>
            </div>
          </div> --}}

          <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="card card-primary">
              <!-- /.card-header -->
              <div class="card-header">
                <h3 class="card-title">List Kerusakan</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>Ruangan</th>
                    <th>Deskripsi</th>
                    <th>Tgl Dilaporkan</th>
                    <th>Tgl TL</th>
                    <th>Pengirim</th>
                    <th>Status</th>
                    <th>Penindaklanjut</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($kerusakan_all as $key => $value)
                    <tr>
                      <th scope="row"> {{$key+1}}. </th>
                      <td>{{$value->ruangan}}</td>
                      <td>{{$value->deskripsi}}</td>
                      <td>{{$value->tgl_kerusakan}}</td>
                      <td>{{$value->ditindaklanjuti}}</td>
                      <td>{{$value->nama}}</td>
                      <td>
                        <form onsubmit="return confirm('Apakah Anda Yakin Update ?');" action="{{ route('kerusakan-update-admin', $value->id) }}" method="POST">
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
                          @if ($value->ditindaklanjuti == null)
                            <span style="color: blue;">Belum</span>
                          @elseif($value->selesai == null)
                            <span style="color: red;">Sedang Ditindaklanjuti</span>
                          @else
                            <span style="color: green;">Selesai</span>
                          @endif
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
