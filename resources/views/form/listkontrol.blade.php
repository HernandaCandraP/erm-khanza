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
          <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="card card-primary">
              <!-- /.card-header -->
              <div class="card-header">
                <h3 class="card-title"><a href="{{ url()->previous() }}" class="btn btn-primary"><i class="fa fa-arrow-left nav-icon"></i></a>Riwayat SOAP</h3>
              </div>
              <div class="card-body">
                <div class="row text-sm">
                  <div class="col-3 text-right"><b>Nama</b></div>
                  <div class="col-9">&nbsp; {{$detailpasien->nm_pasien}}</div>
                  <div class="col-3 text-right"><b>No. RM</b></div>
                  <div class="col-9">&nbsp; {{$detailpasien->no_rkm_medis}}</div>
                  <div class="col-3 text-right"><b>Umur</b></div>
                  <div class="col-9">&nbsp; {{$detailpasien->umur}}</div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                @foreach ($data as $key => $value)
                  <div class="col-sm-12 pt-3">
                    <div class="form-inline bg-info p-1">No Rawat : &emsp; <b>{{ $value->no_rawat }}</b></div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-inline">
                      Dilakukan Oleh : &emsp; <b>{{ $value->nama }}</b>
                    </div>
                    <div class="form-inline pt-2">Subject :
                      <textarea disabled class="form-control form-control-sm" rows="2" name="keluhan" autocomplete="off" style="width: 100%">{{ $value->keluhan }}</textarea>
                    </div>
                    <div class="form-inline pt-2"> 
                      <div class="col-sm-6"> 
                        Suhu : <u>{{$value->suhu_tubuh}} °C </u>
                      </div>
                      <div class="col-sm-6"> 
                        Tensi : <u>{{$value->tensi}} mmHg </u>
                      </div>
                      <div class="col-sm-6"> 
                        Berat : <u>{{$value->berat}} Kg </u>
                      </div>
                      <div class="col-sm-6"> 
                        TB : <u>{{$value->tinggi}} Cm </u>
                      </div>
                      <div class="col-sm-6"> 
                        RR : <u>{{$value->respirasi}} /mnt </u>
                      </div>
                      <div class="col-sm-6"> 
                        Nadi : <u>{{$value->nadi}} x/mnt </u>
                      </div>
                      <div class="col-sm-6"> 
                        SpO2 : <u>{{$value->spo2}} x/mnt </u>
                      </div>
                      <div class="col-sm-6"> 
                        GCS : <u>{{$value->gcs}} x/mnt </u>
                      </div>
                      <div class="col-sm-12"> 
                        Kesadaran : <u>{{$value->kesadaran}}</u>
                      </div>
                    </div>
                    <div class="form-inline pt-2">Object : 
                      <textarea disabled class="form-control form-control-sm" rows="2" name="pemeriksaan" autocomplete="off" style="width: 100%">{{$value->pemeriksaan}}</textarea>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group row">
                      <div class="col-sm-12">Alergi : <u>{{$value->alergi}}</u></div>
                    </div>
                    <div class="form-inline">Asesmen :
                      <textarea disabled class="form-control form-control-sm" rows="2" name="penilaian" autocomplete="off" style="width: 100%">{{$value->penilaian}}</textarea>
                    </div>
                    <div class="form-inline pt-2">Plan : 
                      <textarea disabled class="form-control form-control-sm" rows="4" name="rtl" autocomplete="off" style="width: 100%">{{ $value->rtl}}</textarea>
                    </div>
                    <div class="form-group">Instruksi :
                      <textarea disabled class="form-control form-control-sm" rows="2" name="instruksi" autocomplete="off" style="width: 100%">{{$value->instruksi}}</textarea>
                    </div>
                    <div class="form-inline pt-2" hidden>Evaluasi : 
                      <textarea disabled class="form-control form-control-sm" rows="2" name="evaluasi" autocomplete="off" style="width: 100%">{{$value->evaluasi}}</textarea>
                    </div>
                  </div>
                  <div class="col-sm-12 text-center">
                    <a href="{{ route('form-rincianbiaya', ['no_rawat' => $value->no_rawat]) }}" class="btn btn-success btn-sm">Rincian Total Biaya</a>
                  </div>
                @endforeach
                </div>
              </div>
              <hr>
              <!-- /.card-body -->
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
