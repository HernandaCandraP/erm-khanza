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
                <h3 class="card-title">::[ SOAP ]::</h3>
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
                    <div class="col-sm-3 pt-3">
                      <a href="{{ route('form/listdata', ['no_rawat' => $detailpasien[0]->no_rawat, 'no_rkm_medis' => $detailpasien[0]->no_rkm_medis, 'tabel' => 'DataPemeriksaanRawatJalan']) }}" class="btn btn-warning btn-sm">List Data Soap Sekarang</a>
                    </div>
                    <div class="col-sm-3 pt-3">
                      <a href="{{ route('form/listdata', ['no_rawat' => $detailpasien[0]->no_rawat, 'no_rkm_medis' => $detailpasien[0]->no_rkm_medis, 'tabel' => 'DataKontrolRawatJalan']) }}" class="btn btn-success btn-sm">Riwayat Kontrol Terakhir</a>
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
                    <div class="col-sm-6">
                      <div class="form-inline">
                        Dilakukan Oleh : &emsp; <b>{{ $data->nama }}</b>
                        <input type="text" name="no_rawat" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rawat}}" hidden>
                        <input type="text" name="nip" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                        <input type="text" name="niplama" class="form-control form-control-sm" value="{{ $data->nip }}" hidden>
                        <input type="text" name="tgl_perawatan" class="form-control form-control-sm" value="{{$detailpasien[0]->tgl_registrasi}}" hidden>
                        <input type="text" name="tgl_perawatan_lama" class="form-control form-control-sm" value="{{$data->tgl_perawatan_lama ?? null}}" hidden>
                        <input type="text" name="no_rm" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rkm_medis ?? null}}" hidden>
                        <input type="text" name="jam_rawat_lama" class="form-control form-control-sm" value="{{$data->jam_rawat_lama ?? null}}" hidden>
                        <input type="text" name="tabel" class="form-control form-control-sm" value="DataPemeriksaanRawatJalan" hidden>
                      </div>
                      <div class="form-inline"><b>Subject :</b>
                        <textarea class="form-control form-control-sm" rows="2" name="keluhan" autocomplete="off" style="width: 100%" required>{{ old('keluhan', $data->keluhan) }}</textarea>
                      </div>
                      <div class="form-inline pt-3"><b>Object : </b>
                        <textarea class="form-control form-control-sm" rows="2" name="pemeriksaan" autocomplete="off" style="width: 100%" required>{{ old('pemeriksaan', $data->pemeriksaan) }}</textarea>
                        {{-- <div class="col-sm-10"> 
                          <div class="form-inline">
                            <img src="{{asset($getimage)}}" class="mx-auto d-block"/><br>
                          </div>
                        </div> --}}
                      </div>
                      <div class="row text-sm"> 
                        <div class="col-sm-4"> 
                          Suhu
                          <div class="input-group-append" >
                            <input type="text" name="suhu_tubuh" class="form-control form-control-sm" style="width: 80%" value="{{ old('suhu_tubuh', $data->suhu_tubuh) }}" required>
                            <span class="input-group-text text-sm">°C</span>
                          </div>
                        </div>
                        <div class="col-sm-4"> 
                          Tensi
                          <div class="input-group-append" >
                            <input type="text" name="tensi" class="form-control form-control-sm" style="width: 80%" value="{{ old('tensi', $data->tensi) }}" required>
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
                            <input type="text" name="respirasi" class="form-control form-control-sm" style="width: 80%" value="{{ old('respirasi', $data->respirasi) }}" required>
                            <span class="input-group-text text-sm">/menit</span>
                          </div>
                        </div>
                        <div class="col-sm-4"> 
                          Nadi
                          <div class="input-group-append" >
                            <input type="text" name="nadi" class="form-control form-control-sm" style="width: 80%" value="{{ old('nadi', $data->nadi) }}" required>
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
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group row">
                        <div class="col-sm-2" hidden>L.P. (cm)</div>
                        <div class="col-sm-3" hidden>
                          <input type="text" name="lingkar_perut" class="form-control form-control-sm" autocomplete="off" value="{{ old('lingkar_perut', $data->lingkar_perut) }}">
                        </div>
                        <div class="col-sm-2">Alergi</div>
                        <div class="col-sm-9">
                          <input type="text" name="alergi" class="form-control form-control-sm" autocomplete="off" value="{{ old('alergi', $data->alergi) }}">
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <hr>
                      <div class="form-inline"><b>Asesmen :</b>
                        <textarea class="form-control form-control-sm" rows="3" name="penilaian" autocomplete="off" style="width: 100%" required>{{ old('penilaian', $data->penilaian) }}</textarea>
                        {{-- <div class="select2-purple" style="width: 100%">
                          <select class="select2 form-control form-control-sm" multiple="multiple" name="diagnosa_utama[]" id="diagnosa_utama" data-placeholder="ICD - Diagnosa" data-dropdown-css-class="select2-purple">
                            @foreach ($jnspenyakit as $key)
                              <option value="{{ $key->kd_penyakit }}">{{ $key->kd_penyakit }} - {{ $key->nm_penyakit }}</option>
                            @endforeach
                          </select>
                        </div> --}}
                        <div class="select2-purple input-group col-md-12" style="width: 100%">
                          <select class="select2 form-control form-control-sm" multiple="multiple" name="diagnosa_utama[]" id="diagnosa_utama" data-placeholder="ICD - Diagnosa" data-dropdown-css-class="select2-purple">
                            
                          </select>
                        </div>
                      </div>
                      <div class="pt-3"><b>Plan : </b> &emsp;<button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-lg">Riwayat Obat &nbsp;<i class="fas fa-exclamation"></i> </button>
                        <textarea class="form-control form-control-sm" rows="4" name="rtl" autocomplete="off" style="width: 100%">{{ old('rtl', $data->rtl) }}</textarea>
                          <table id="testing" class="table table-bordered table-striped text-sm">
                          </table>
                          <div class="row text-sm"> 
                            <div class="col-sm-10">
                              <div type="text" class="form-control btn btn-warning btn-sm addmore">+ Tambah Obat Rawat Jalan</div>
                            </div>
                            <div class="col-sm-2">
                              <div type="text" class="form-control btn btn-success btn-sm cekharga">Cek Harga</div>
                            </div>
                          </div>
                      </div>

                      <div class="p-3">
                        {{-- <a href="{{route('resepracikan')}}?no_rawat= {!!$detailpasien[0]->no_rawat!!}" type="button" class="btn btn-danger" target="_blank">Obat Racikan</a> --}}
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="card card-info collapsed-card">
                          <div class="card-header">
                            <div type="button" class="btn btn-tool col-sm-11" data-card-widget="collapse"><h3 class="card-title">Obat Racikan</h3>&emsp;&emsp;<i class="fas fa-plus"></i></div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                            <div class="form-group row text-sm"> 
                              <div class="col-sm-6" hidden>
                                No Racik
                                <div class="input-group-append" >
                                  <input type="text" name="no_racik" class="form-control form-control-sm" style="width: 100%" value="1">
                                </div>
                              </div>
                              <div class="col-sm-6">
                                Nama Racikan
                                <div class="input-group-append" >
                                  <input type="text" name="nama_racik" class="form-control form-control-sm" style="width: 100%" value="-" required>
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
                                  <input type="number" name="jml_dr" min="1" class="form-control form-control-sm" style="width: 100%" value="1" required>
                                </div>
                              </div>
                              <div class="col-sm-6 pt-3">
                                Aturan Pakai
                                <div class="input-group-append" >
                                  <input type="text" name="aturan_pakai_racikan" class="form-control form-control-sm" style="width: 100%" value="3x1" required>
                                </div>
                              </div>
                              <div class="col-sm-6 pt-3">
                                Keterangan
                                <div class="input-group-append" >
                                  <input type="text" name="keterangan" class="form-control form-control-sm" style="width: 100%" value="-" required>
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
                        </div>
                        <!-- /.card -->
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group pt-3"><b>Instruksi :</b>
                        <textarea class="form-control form-control-sm" rows="5" name="instruksi" autocomplete="off" style="width: 100%" required>{{ old('instruksi', $data->instruksi) }}</textarea>
                      </div>
                      <div class="form-group">
                        <div class="card card-primary collapsed-card">
                          <div class="card-header">
                            <div type="button" class="btn btn-tool col-sm-12" data-card-widget="collapse"><h3 class="card-title">Permintaan Radiologi</h3>&emsp;&emsp;<i class="fas fa-plus"></i></div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                            <div class="form-group row text-sm"> 
                              <div class="col-sm-12"> 
                                <div class=" pt-2">Indikasi Pemeriksaan / Diagnosis Klinis
                                  <input type="text" name="diagnosa_klinisrad" class="form-control form-control-sm" autocomplete="off" style="width: 100%">
                                </div>
                                <div class="">Informasi Tambahan Permintaan Foto 
                                  <input type="text" name="informasi_tambahanrad" class="form-control form-control-sm" autocomplete="off" style="width: 100%">
                                </div>
                              </div>
                              <div class="col-sm-12 pt-2 select2-purple">
                                Pemeriksaan
                                <select class="select2 form-control form-control-sm" multiple="multiple" name="pemeriksaanrad[]" id="pemeriksaanrad" data-placeholder="Pilih Pemeriksaan" data-dropdown-css-class="select2-purple">
                                  @foreach ($jnsperawatanrad as $key)
                                    <option value="{{ $key->kd_jenis_prw }}">{{$key->nm_perawatan}} || @money($key->total_byr)</option>
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
                                    <option value="{{ $key->kd_jenis_prw }}***{{$key->id_template}}"> {{$key->nm_perawatan}} - {{$key->Pemeriksaan}} || @money($key->total_byr)</option>
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
                  {{-- <a href="{{ route('form/hapus', ['no_rawat' => $detailpasien[0]->no_rawat, 'tabel' => 'DataPemeriksaanRawatJalan', 'niplama' => $data->nip, 'tgl_perawatan_lama' => $data->tgl_perawatan_lama ?? null, 'jam_rawat_lama' => $data->jam_rawat_lama ?? null ]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')"><i class="fas fa-trash"></i></a> --}}
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
  @include('template.formulaobat')
  @include('template.footer')
  </footer>
</div>

<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Riwayat Resep</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body justify-content-between">
        <table id="example1" class="table table-bordered text-sm">
          <tbody>
        @foreach ($datalistnorawatperresep as $key => $value)
          <tr>
            <th rowspan="{{$value->total +1}}"> {{$key+1}}. </th>
            <th rowspan="{{$value->total +1}}">{{$value->no_resep}}<br><a class="text-danger">{{$value->nm_dokter}}</a><br>
              <button type="button" value="{{$value->no_resep}}#*#{{$datalistnorawatlengkap}}" class="btn btn-success btn-sm copyresep">
                Copy Resep
              </button>
            </th>
            <th>Kode Obat</th>
            <th>Nama Obat</th>
            <th>Jumlah</th>
            <th>Pakai</th>
          </tr> 
          @foreach ($datalistnorawatlengkap as $keylengkap => $valuelengkap)
            @if ($valuelengkap->no_resep == $value->no_resep)
            <tr>
              <td>{{$valuelengkap->kode_brng}}</td>
              <td>{{$valuelengkap->letak_barang}} / {{$valuelengkap->nama_brng}}</td>
              <td>{{$valuelengkap->jml}}</td>
              <td>{{$valuelengkap->aturan_pakai}}</td>
            </tr>
            @endif
          @endforeach
        @endforeach
          </tbody>
        </table>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- end form -->
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- ./wrapper -->

<!-- SCRIPTS -->
@include('template.script_table')
@include('template.script_resep')
</body>
</html>
