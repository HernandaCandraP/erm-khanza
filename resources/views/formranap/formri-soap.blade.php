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

          @include('template.menudokterranap', ['no_rawat' => $no_rawat])
          <div class="col-md-12 mt-4">
          <!-- Horizontal Form -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">::[ SOAP Rawat Inap ]::</h3>
              </div>
              <!-- /.card-header -->
              <form class="form-horizontal">
                <div class="card-header">
                  <div class="row text-sm">
                    <div class="col-sm-6">
                      <div class="row">
                        <div class="col-5 text-right"><b>Nama</b></div>
                        <div class="col-7">&nbsp; {{$detailpasien[0]->nm_pasien}}</div>
                        <div class="col-5 text-right"><b>No. RM</b></div>
                        <div class="col-7">&nbsp; {{$detailpasien[0]->no_rkm_medis}}</div>
                        <div class="col-5 text-right"><b>No. Rawat</b></div>
                        <div class="col-7">&nbsp; {{$detailpasien[0]->no_rawat}}</div>
                        <div class="col-5 text-right"><b>Umur</b></div>
                        <div class="col-7">&nbsp; {{$detailpasien[0]->umur}}</div>
                      </div>
                    </div>
                    <div class="col-sm-6 pt-3">
                      <a href="{{ route('formri-listdata', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'DataPemeriksaanRawatInap']) }}" class="btn btn-warning btn-sm">List Data Soap Sebelumnya</a>
                    </div>
                  </div>
                </div>
              </form>
              @include('template.alert')
              <!-- form start -->
              <form action="{{route('formri-tambah')}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div  class="card-body">
                  <div class="form-inline row text-sm pb-2">
                    <div class="col-xs-3 pt-1">&emsp;&emsp; Tanggal &emsp;</div>
                    <div class="col-xs-3 pt-1">
                      <input type="date" name="tgl_perawatan" class="form-control form-control-sm" placeholder="tgl_perawatan" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                    </div>
                    <div class="col-xs-3 pt-1">&emsp;&emsp; Jam &emsp;</div>
                    <div class="col-xs-3 pt-1">
                      <input type="time" name="jam_rawat" class="form-control form-control-sm" placeholder="jam_rawat" value="{{Carbon\Carbon::now()->format('H:i:s')}}">
                    </div>
                  </div>
                  <div class="form-group row text-sm">
                    <div class="col-sm-6">
                      <div class="form-inline">
                        Dilakukan Oleh : &emsp; <b>{{ $data->nama }}</b>
                        <input type="text" name="no_rawat" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rawat}}" hidden>
                        <input type="text" name="nip" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                        <input type="text" name="niplama" class="form-control form-control-sm" value="{{ $data->nip }}" hidden>
                        {{-- <input type="text" name="tgl_perawatan" class="form-control form-control-sm" value="{{$detailpasien[0]->tgl_registrasi}}" hidden> --}}
                        <input type="text" name="tgl_perawatan_lama" class="form-control form-control-sm" value="{{$data->tgl_perawatan_lama ?? null}}" hidden>
                        <input type="text" name="jam_rawat_lama" class="form-control form-control-sm" value="{{$data->jam_rawat_lama ?? null}}" hidden>
                        <input type="text" name="tabel" class="form-control form-control-sm" value="DataPemeriksaanRawatInap" hidden>
                      </div>
                      <div class="form-inline">Subject :
                        <textarea class="form-control form-control-sm" rows="2" name="keluhan" autocomplete="off" style="width: 100%">{{ old('keluhan', $data->keluhan) }}</textarea>
                      </div>
                      <div class="row text-sm"> 
                        <div class="col-sm-4"> 
                          Suhu
                          <div class="input-group-append" >
                            <input type="text" name="suhu_tubuh" class="form-control form-control-sm" style="width: 80%" value="{{ old('suhu_tubuh', $data->suhu_tubuh) }}">
                            <span class="input-group-text text-sm">°C</span>
                          </div>
                        </div>
                        <div class="col-sm-4"> 
                          Tensi
                          <div class="input-group-append" >
                            <input type="text" name="tensi" class="form-control form-control-sm" style="width: 80%" value="{{ old('tensi', $data->tensi) }}">
                            <span class="input-group-text text-sm">mmHg</span>
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          Berat
                          <div class="input-group-append" >
                            <input type="text" name="berat" class="form-control form-control-sm" style="width: 80%" value="{{ old('berat', $data->berat) }}">
                            <span class="input-group-text text-sm">Kg</span>
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          TB
                          <div class="input-group-append" >
                            <input type="text" name="tinggi" class="form-control form-control-sm" style="width: 80%" value="{{ old('tinggi', $data->tinggi) }}">
                            <span class="input-group-text text-sm">cm</span>
                          </div>
                        </div>
                        <div class="col-sm-3"> 
                          RR
                          <div class="input-group-append" >
                            <input type="text" name="respirasi" class="form-control form-control-sm" style="width: 80%" value="{{ old('respirasi', $data->respirasi) }}">
                            <span class="input-group-text text-sm">/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-4"> 
                          Nadi
                          <div class="input-group-append" >
                            <input type="text" name="nadi" class="form-control form-control-sm" style="width: 80%" value="{{ old('nadi', $data->nadi) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-4"> 
                          SpO2
                          <div class="input-group-append" >
                            <input type="text" name="spo2" class="form-control form-control-sm" style="width: 80%" value="{{ old('spo2', $data->spo2) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-4"> 
                          GCS (E, V, M)
                          <div class="input-group-append" >
                            <input type="text" name="gcs" class="form-control form-control-sm" style="width: 80%" value="{{ old('gcs', $data->gcs) }}">
                            <span class="input-group-text text-sm">x/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-4"> 
                          Kesadaran&emsp;
                          <select class="form-control form-control-sm" name="kesadaran">
                            <option value="Compos Mentis" {{ old('kesadaran', $data->kesadaran) == 'Compos Mentis' ? 'selected':'' }}>Compos Mentis</option>
                            <option value="Somnolence" {{ old('kesadaran', $data->kesadaran) == 'Somnolence' ? 'selected':'' }}>Somnolence</option>
                            <option value="Sopor" {{ old('kesadaran', $data->kesadaran) == 'Sopor' ? 'selected':'' }}>Sopor</option>
                            <option value="Coma" {{ old('kesadaran', $data->kesadaran) == 'Coma' ? 'selected':'' }}>Coma</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-inline pt-2">Object : 
                        <textarea class="form-control form-control-sm" rows="2" name="pemeriksaan" autocomplete="off" style="width: 100%">{{ old('pemeriksaan', $data->pemeriksaan) }}</textarea>
                        <div class="col-sm-10"> 
                          <div class="form-inline">
                            <img src="{{asset($getimage)}}" class="mx-auto d-block"/><br>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group row">
                        <div class="col-sm-3">Alergi :</div>
                        <div class="col-sm-12">
                          <input type="text" name="alergi" class="form-control form-control-sm" autocomplete="off" value="{{ old('alergi', $data->alergi) }}">
                        </div>
                      </div>
                      <div class="form-inline">Asesmen :
                        <textarea class="form-control form-control-sm" rows="2" name="penilaian" autocomplete="off" style="width: 100%">{{ old('penilaian', $data->penilaian) }}</textarea>
                      </div>
                      <div class="form-inline pt-2">Plan : 
                        <textarea class="form-control form-control-sm" rows="2" name="rtl" autocomplete="off" style="width: 100%">{{ old('rtl', $data->rtl) }}</textarea>
                        <table id="testing" class="table table-bordered table-striped text-sm">
                            
                        </table>
                        <div class="col-sm-12">
                          <div type="text" class="btn btn-warning addmore float-right">+ Tambah Obat</div>
                        </div>
                      </div>
                      <div class="form-inline">Instruksi :
                        <textarea class="form-control form-control-sm" rows="2" name="instruksi" autocomplete="off" style="width: 100%">{{ old('instruksi', $data->instruksi) }}</textarea>
                      </div>
                      <div class="form-group pt-2">
                        <div class="card card-primary collapsed-card">
                          <div class="card-header">
                            <div type="button" class="btn btn-tool col-sm-12" data-card-widget="collapse"><h3 class="card-title">Permintaan Radiologi</h3>&emsp;&emsp;<i class="fas fa-plus"></i></div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                            <div class="form-group row text-sm"> 
                              <div class="col-sm-12"> 
                                <div class="">Informasi Tambahan Permintaan Foto 
                                  <input type="text" name="informasi_tambahanrad" class="form-control form-control-sm" autocomplete="off" style="width: 100%">
                                </div>
                                <div class=" pt-2">Indikasi Pemeriksaan / Diagnosisi Klinis
                                  <input type="text" name="diagnosa_klinisrad" class="form-control form-control-sm" autocomplete="off" style="width: 100%">
                                </div>
                              </div>
                              <div class="col-sm-12 pt-2 select2-purple">
                                Pemeriksaan
                                <select class="select2 form-control form-control-sm" multiple="multiple" name="pemeriksaanrad[]" id="pemeriksaanrad" data-placeholder="Pilih Pemeriksaan" data-dropdown-css-class="select2-purple">
                                  @foreach ($jnsperawatanrad as $key)
                                    <option value="{{ $key->kd_jenis_prw }}"> {{ $key->kd_jenis_prw }} - {{$key->nm_perawatan}} </option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                      </div>
                      <div class="form-group">
                        <div class="card card-primary collapsed-card">
                          <div class="card-header">
                            <div type="button" class="btn btn-tool col-sm-12" data-card-widget="collapse"><h3 class="card-title">Permintaan Laboratorium</h3><i class="fas fa-plus"></i></div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                            <div class="form-group row text-sm"> 
                              <div class="col-sm-12"> 
                                Indikasi / Klinis :  <input type="text" name="diagnosa_klinislab" class="form-control form-control-sm" autocomplete="off" style="width: 100%">
                              </div>
                              <div class="col-sm-12">
                                Informasi Tambahan : <input type="text" name="informasi_tambahanlab" class="form-control form-control-sm" autocomplete="off" style="width: 100%">
                              </div>
                              <div class="col-sm-12 pt-2 select2-purple">
                                pemeriksaan
                                <select class="select2 form-control form-control-sm" multiple="multiple" name="pemeriksaanlab[]" id="pemeriksaanlab" data-placeholder="Pilih Pemeriksaan" data-dropdown-css-class="select2-purple">
                                  @foreach ($jnsperawatanlab as $key)
                                    <option value="{{ $key->kd_jenis_prw }}***{{$key->id_template}}"> {{$key->nm_perawatan}} - {{$key->Pemeriksaan}} </option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                      </div>
                      <div class="form-inline pt-2" hidden>Evaluasi : 
                        <textarea class="form-control form-control-sm" rows="2" name="evaluasi" autocomplete="off" style="width: 100%">{{ old('evaluasi', $data->evaluasi) }}</textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  {{-- <a href="{{ route('form/hapus', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'DataPemeriksaanRawatInap', 'niplama' => $data->nip, 'tgl_perawatan_lama' => $data->tgl_perawatan_lama ?? null, 'jam_rawat_lama' => $data->jam_rawat_lama ?? null ]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')"><i class="fas fa-trash"></i></a> --}}
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')" name="action" value="hapus"><i class="fas fa-trash"></i></button>
                  {{-- <button type="submit" class="btn btn-info float-right">Simpan Data</button> --}}
                  <button type="submit" class="btn btn-info float-right ml-3" name="action" value="simpan">Simpan</button>
                  <button type="submit" class="btn btn-warning float-right ml-3" name="action" value="update_with_time">Ganti</button>
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
<!-- ./wrapper -->

<!-- SCRIPTS -->
@include('template.script_table')
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  })


  var i=1;
  $(".addmore").on('click',function(){
    if(i == 1){
      document.getElementById("testing").insertRow(-1).innerHTML = '<tr><th>Jumlah</th><th>Aturan&emsp; Pakai</th><th>Total</th><th>Aksi</th></tr>';
    }

    document.getElementById("testing").insertRow(-1).innerHTML = '<tr><td><input id="jumlah'+i+'" class="form-control" name="jumlah[]" type="number"  min="1"  size="6" required/></td><td><input  id="aturan-pakai'+i+'" class="form-control" name="aturan_pakai[]" type="text"  size="8"/></td><td><select class="select2" name="resep[]" required><option value="" disabled selected>Pilih Obat</option> @foreach ($resepdokter as $key)<option value="{{ $key->kode_brng }}"> {{ $key->kode_brng }} - {{$key->nama_brng}} </option>@endforeach</select></td><td><text type="text" class="btn btn-danger" id="hapusresep'+i+'">X</text></td></tr>';

    $('.select2').select2();

    $('#hapusresep'+i).on('click', function() {
      $(this).parents("tr").remove();
      return false;
    });
    i++;
  });

  $("#hapusresep").on('click', function() {
    $(this).parents("tr").remove();
    return false;
  });
</script>
</body>
</html>
