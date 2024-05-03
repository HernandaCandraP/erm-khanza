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
                <h5 class="float-left">Detail pasien</h5><br><br>
                <div class="row">
                  <div class="col-3 text-right"><b>Nama</b></div>
                  <div class="col-9">&nbsp; {{$detailpasien[0]->nm_pasien}}</div>
                  <div class="col-3 text-right"><b>No. RM</b></div>
                  <div class="col-9">&nbsp; {{$detailpasien[0]->no_rkm_medis}}</div>
                  <div class="col-3 text-right"><b>No. Rawat</b></div>
                  <div class="col-9">&nbsp; {{$detailpasien[0]->no_rawat}}</div>
                  <div class="col-3 text-right"><b>Umur</b></div>
                  <div class="col-9">&nbsp; {{$detailpasien[0]->umur}}</div>
                </div>
                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus"></i> Tambah Data</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>Nomor Rawat</th>
                    <th>Klinik/Ruangan/Dokter</th>
                    <th>Keluhan</th>
                    <th>Pemeriksaan</th>
                    <th>Diagnosa</th>
                    <th>Obat</th>
                    <th>Laboratorium</th>
                    <th>Radiologi</th>
                  </tr>
                  </thead>
                  <tbody>
                @foreach ($riwayatpasien as $no =>$key)
                <tr>
                  <td scope="row"> {{$key->tgl_registrasi}}</td>
                  <td>{{$key->no_rawat}}</td>
                  <td>{{$key->nm_dokter}}</td>
                  <td>{{$key->keluhan}}</td>
                  <td>{{$key->pemeriksaan}}</td>
                  {{-- @if ($key->penyakit != "")
                  <td>
                    <ol>
                      @foreach(explode('.,', $key->penyakit) as $info) 
                        <li>{{$info}}</li>
                      @endforeach
                    </ol>
                  </td>
                  @else
                  <td>-</td>
                  @endif
                  @if ($key->obat != "")
                  <td>
                    <ol>
                      @foreach(explode('.,', $key->obat) as $info2) 
                        <li>{{$info2}}</li>
                      @endforeach
                    </ol>
                  </td>
                  @else
                  <td>-</td>
                  @endif --}}
                  @if ($key->lab != "")
                  <td>
                    <ol>
                      @foreach(explode('.,', $key->lab) as $info3) 
                        <li>{{$info3}}</li>
                      @endforeach
                    </ol>
                  </td>
                  @else
                  <td>-</td>
                  @endif
                  {{-- <td>{{$key->penyakit}}</td> --}}
                  {{-- <td>{{$key->no_rawat}}</td> --}}
                  <td>{{$key->nm_pasien}}</td>
                  <td>{{$key->nm_pasien}}</td>
                </tr> 
                @endforeach
                  
                  </tbody>
                </table>
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

@include('template.script_table')
</body>
</html>
