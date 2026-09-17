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

          @include('template.menudokter', ['no_rawat' => $no_rawat])
          <div class="col-md-12 mt-4">
          <!-- Horizontal Form -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">::[ Resep Racikan ]::</h3>
              </div>
              <!-- /.card-header -->
              <form class="form-horizontal">
                <div class="card-header">
                  <div class="row text-sm">
                    <div class="col-sm-6">
                      <div class="row">
                        <div class="col-5 text-right"><b>Nama</b></div>
                        <div class="col-7">&nbsp; {{$detailpasien[0]->nm_pasien}}  
                          @if ($detailpasien[0]->internal)
                            <a href="{{ route('form-resumegabung', ['no_rawat' => $no_rawat]) }}" class="btn btn-danger btn-sm">Resume Internal</a>
                          @endif
                        </div>
                        <div class="col-5 text-right"><b>No. RM</b></div>
                        <div class="col-7">&nbsp; {{$detailpasien[0]->no_rkm_medis}}</div>
                        <div class="col-5 text-right"><b>No. Rawat</b></div>
                        <div class="col-7">&nbsp; {{$detailpasien[0]->no_rawat}}</div>
                        <div class="col-5 text-right"><b>Umur</b></div>
                        <div class="col-7">&nbsp; {{$detailpasien[0]->umur}}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              @include('template.alert')
              <!-- form start -->
              <form action="{{route('form/tambah')}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div  class="card-body">
                  <div class="form-inline row text-sm pb-2">
                    <div class="col-xs-3 pt-1">&emsp;&emsp; Tanggal &emsp;</div>
                    <div class="col-xs-3 pt-1">
                      <input type="date" name="tgl_perawatan2" class="form-control form-control-sm" placeholder="tgl_perawatan" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                    </div>
                    <div class="col-xs-3 pt-1">&emsp;&emsp; Jam &emsp;</div>
                    <div class="col-xs-3 pt-1">
                      <input type="time" name="jam_rawat" class="form-control form-control-sm" placeholder="jam_rawat" value="{{Carbon\Carbon::now()->format('H:i:s')}}">
                    </div>
                  </div>
                  <div class="form-group row text-sm">
                    <div class="col-sm-12">
                      <div class="form-inline">
                        {{-- Dilakukan Oleh : &emsp; <b>{{ $data->nama }}</b> --}}
                        <input type="text" name="no_rawat" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rawat}}" hidden>
                        <input type="text" name="nip" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                        <input type="text" name="niplama" class="form-control form-control-sm" value="{{ $data->nip }}" hidden>
                        <input type="text" name="tgl_perawatan" class="form-control form-control-sm" value="{{$detailpasien[0]->tgl_registrasi}}" hidden>
                        <input type="text" name="tgl_perawatan_lama" class="form-control form-control-sm" value="{{$data->tgl_perawatan_lama ?? null}}" hidden>
                        <input type="text" name="no_rm" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rkm_medis ?? null}}" hidden>
                        <input type="text" name="jam_rawat_lama" class="form-control form-control-sm" value="{{$data->jam_rawat_lama ?? null}}" hidden>
                        <input type="text" name="tabel" class="form-control form-control-sm" value="ResepRacikanDokter" hidden>
                      </div>
                    </div>
                    <div class="col-sm-6" hidden>
                      No Racik
                      <div class="input-group-append" >
                        <input type="text" name="no_racik" class="form-control form-control-sm" style="width: 100%" value="1">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      Nama Racikan
                      <div class="input-group-append" >
                        <input type="text" name="nama_racik" class="form-control form-control-sm" style="width: 100%" required>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      Metode Racik
                      <div class="form-group">
                        <select class="form-control select2" name="kd_racik" id="id_kelas" @required(true)>
                          @foreach ($metoderacik as $key)
                            <option value="{{ $key->kd_racik }}"> {{$key->nm_racik}} </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      Jml Racik
                      <div class="input-group-append" >
                        <input type="number" name="jml_dr" min="1" class="form-control form-control-sm" style="width: 100%" required>
                      </div>
                    </div>
                    <div class="col-sm-6 pt-3">
                      Aturan Pakai
                      <div class="input-group-append" >
                        <input type="text" name="aturan_pakai_racikan" class="form-control form-control-sm" style="width: 100%" required>
                      </div>
                    </div>
                    <div class="col-sm-6 pt-3">
                      Keterangan
                      <div class="input-group-append" >
                        <input type="text" name="keterangan" class="form-control form-control-sm" style="width: 100%" required>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="pt-3"><b>List Obat</b> <div class="btn btn-sm btn-danger ml-3" data-toggle="modal" data-target="#modal-racikan">Formula</div>
                        <table id="racikan" class="table table-bordered table-striped text-sm">
                        </table>
                        <div class="col-sm-12">
                          <div type="text" class="form-control btn btn-warning btn-sm addmore-racikan">+ Tambah Obat Rawat Jalan</div>
                        </div>
                      </div>
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

<!-- SCRIPTS -->
@include('template.formulaobat')
@include('template.script_table')
@include('template.script_resep')
</body>
</html>
