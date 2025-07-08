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
                <h5 class="float-left">Pendaftaran Pasien</h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                @include('template.alert')
                <form action="{{route('tambahpasien')}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-group row text-sm">
                  <div class="col-lg-6">
                    <div class="form-group row text-sm">
                      <div class="col-sm-6" hidden>
                        <div class="row">
                          <div class="col-sm-4 text-right" id="main-nav-menu">
                            No RM
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                              <input type="checkbox" class="custom-control-input" id="customSwitch3">
                              <label id="main-nav-toggler" class="custom-control-label" for="customSwitch3"></label>
                            </div>
                          </div>
                          <div class="col-sm-7 text-right">
                            <input id="rm_manual" type="text" name="rm_manual" class="form-control form-control-sm" autocomplete="off">
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Tgl. Daftar</div>
                          <div class="col-sm-7 text-right"><input type="date" name="tgl_daftar" class="form-control form-control-sm" value="{{Carbon\Carbon::now()->format('Y-m-d')}}"></div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row text-sm">
                      <div class="col-sm-12">
                        <div class="row">
                          <div class="col-sm-2 text-right">Nama</div>
                          <div class="col-sm-10 text-right"><input type="text" name="nm_pasien" class="form-control form-control-sm" autocomplete="off" required></div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row text-sm">
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Gender</div>
                          <div class="col-sm-7 text-right">
                            <select class="form-control form-control-sm " name="jk">
                              <option value="L" selected>Laki-Laki</option>
                              <option value="P">Perempuan</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Tgl. Lahir</div>
                          <div class="col-sm-7 text-right"><input type="date" name="tgl_lahir" class="form-control form-control-sm" required></div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row text-sm">
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Nama Ibu</div>
                          <div class="col-sm-7 text-right"><input type="text" name="nm_ibu" class="form-control form-control-sm" autocomplete="off" value="-"></div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Darah</div>
                          <div class="col-sm-7 text-right">
                            <select class="form-control form-control-sm " name="gol_darah">
                              <option value="O">O</option>
                              <option value="A">A</option>
                              <option value="B">B</option>
                              <option value="AB">AB</option>
                              <option value="-" selected>-</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row text-sm">
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Status Nikah</div>
                          <div class="col-sm-7 text-right">
                            <select class="form-control form-control-sm " name="stts_nikah">
                              <option value="MENIKAH">MENIKAH</option>
                              <option value="BELUM MENIKAH" selected>BELUM MENIKAH</option>
                              <option value="JANDA">JANDA</option>
                              <option value="DUDHA">DUDHA</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Agama</div>
                          <div class="col-sm-7 text-right">
                          <select class="form-control form-control-sm " name="agama">
                              <option value="ISLAM" selected>ISLAM</option>
                              <option value="KRISTEN">KRISTEN</option>
                              <option value="PROTESTAN">PROTESTAN</option>
                              <option value="HINDU">HINDU</option>
                              <option value="BUDHA">BUDHA</option>
                              <option value="KONGHUCU">KONGHUCU</option>
                              <option value="KEPERCAYAAN">KEPERCAYAAN</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row text-sm">
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Pekerjaan</div>
                          <div class="col-sm-7 text-right"><input type="text" name="pekerjaan" class="form-control form-control-sm" autocomplete="off" value="-"></div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">PND</div>
                          <div class="col-sm-7 text-right">
                            <select class="form-control form-control-sm " name="pnd">
                              <option value="TS">TS</option>
                              <option value="TK">TK</option>
                              <option value="SD">SD</option>
                              <option value="SMP">SMP</option>
                              <option value="SMA">SMA</option>
                              <option value="SLTA/SEDERAJAT">SLTA/SEDERAJAT</option>
                              <option value="D1">D1</option>
                              <option value="D2">D2</option>
                              <option value="D3">D3</option>
                              <option value="D4">D4</option>
                              <option value="S1">S1</option>
                              <option value="S2">S2</option>
                              <option value="S3">S3</option>
                              <option value="-" selected>-</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row text-sm">
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Penjamin</div>
                          <div class="col-sm-7 text-right">
                            <select class="form-control form-control-sm " name="kd_pj">
                              <option value="-" selected>-</option>
                              <option value="BPJ">BPJS</option>
                              <option value="A02">UMUM</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">No. Kartu</div>
                          <div class="col-sm-7 text-right"><input type="text" name="no_peserta" class="form-control form-control-sm" autocomplete="off" value="-"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group row text-sm">
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Keluarga</div>
                          <div class="col-sm-7 text-right">
                            <select class="form-control form-control-sm " name="keluarga">
                              <option value="AYAH" selected>AYAH</option>
                              <option value="IBU">IBU</option>
                              <option value="ISTRI">ISTRI</option>
                              <option value="SUAMI">SUAMI</option>
                              <option value="SAUDARA">SAUDARA</option>
                              <option value="ANAK">ANAK</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Nama</div>
                          <div class="col-sm-7 text-right"><input type="text" name="namakeluarga" class="form-control form-control-sm" autocomplete="off" value="-"></div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row text-sm">
                      <div class="col-sm-12">
                        <div class="row">
                          <div class="col-sm-2 text-right">Nomor KTP</div>
                          <div class="col-sm-10 text-right"><input type="text" name="no_ktp" class="form-control form-control-sm" autocomplete="off" value="{{old('no_ktp') }}" required></div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row text-sm">
                      <div class="col-sm-12">
                        <div class="row">
                          <div class="col-sm-2 text-right">Alamat</div>
                          <div class="col-sm-10 text-right">
                            <textarea type="text" name="alamat" rows="3" class="form-control" required>{{old('alamat') }}</textarea>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row text-sm" hidden>
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Propinsi</div>
                          <div class="col-sm-7 text-right"><input type="text" name="nm_prop" class="form-control form-control-sm" autocomplete="off" value="-"></div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Kota</div>
                          <div class="col-sm-7 text-right"><input type="text" name="nm_kab" class="form-control form-control-sm" autocomplete="off" value="-"></div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row text-sm" hidden>
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Kecamatan</div>
                          <div class="col-sm-7 text-right"><input type="text" name="nm_kec" class="form-control form-control-sm" autocomplete="off" value="-"></div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Desa</div>
                          <div class="col-sm-7 text-right"><input type="text" name="nm_kel" class="form-control form-control-sm" autocomplete="off" value="-"></div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row text-sm">
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-4 text-right">Telepon</div>
                          <div class="col-sm-7 text-right"><input type="text" name="no_tlp" class="form-control form-control-sm" autocomplete="off" value="-"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col text-center">
                  <button type="submit" class="btn btn-primary" name="action" value="simpan">Simpan Data</button>
                </div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="row">
              <div class="col-sm-6">
                <div class="card">
                  <div class="card-header">
                    <table class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th>No. RM</th>
                        <th>Nama Lengkap</th>
                        <th>No KTP</th>
                        <th>Tgl. Lahir</th>
                        <th>Gender</th>
                        <th>Alamat</th>
                        <th>Tgl Daftar</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach ($pasien as $no => $key)
                        <tr>
                          <td>{{$key->no_rkm_medis}}</td>
                          <td>{{$key->nm_pasien}}</td>
                          <td>{{$key->no_ktp}}</td>
                          <td>{{$key->tgl_lahir}}</td>
                          <td>{{$key->jk}}</td>
                          <td>{{$key->alamat}}</td>
                          <td>{{$key->tgl_daftar}}</td>
                        </tr> 
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="card">
                  <div class="card-header">
                    <form action="{{route('tambahpasien')}}" method="post" class="form-horizontal">
                      {{ csrf_field() }}
                      <div class="select2-purple input-group col-md-12" style="width: 100%">
                        <select class="select2 form-control form-control-sm" multiple="multiple" name="datapasien[]" id="datapasien" data-placeholder="Cari Nama Pasien" data-dropdown-css-class="select2-purple">
                          
                        </select>
                      </div>
                      <div class="d-grid gap-2 col-6 mx-auto p-3">
                        {{-- <button type="submit" class="btn btn-warning" name="action" value="update">Update Pasien</i></button> --}}
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah yakin ingin menghapus pasien?')" name="action" value="hapus">Hapus Pasien</i></button>
                      </div>
                    </form>
                  </div>
                </div>
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
  $("#main-nav-toggler").click(function() {
        $("#main-nav-menu").toggleClass("menu-show");
        $("#main-nav-menu").hasClass("menu-show") ? disableScroll() : enableScroll();
  });

  function disableScroll(){
    // console.log("disable scroll");
    // $("#rm_manual").prop("disabled",true);
    // $("#rm_auto").prop("disabled",false);
    $('#rm_manual').val('');
    $("#rm_manual").attr("hidden",true);
    $("#rm_auto").attr("hidden",false);
  }
  function enableScroll(){
    // console.log("enable scroll");
    // $("#rm_auto").prop("disabled",true);
    // $("#rm_manual").prop("disabled",false);
    $("#rm_auto").attr("hidden",true);
    $("#rm_manual").attr("hidden",false);
  }
  
  $(document).ready(function() {
    myFunction();
  });
  
  var myFunction = function(){
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
    $('#datapasien').select2({
        minimumInputLength: 3,  // bebas semau kita, tapi w saranin 3 angka lebih biar lebih cepat
        maximumSelectionLength: 4,
        allowClear: true,
        placeholder: 'Input nama',
        ajax: {
          dataType: 'json',
          url: '{{route("ambil_pasien")}}',  // ini data yang akan kita ambil untuk di load
          delay: 5,  // ini bebas mau di pake atau tidak
          data: function(params) {
            return {
              search: params.term
            }
          },
          processResults: function (data, page) {
          return {
            results: data
          };
        },
      }
      }).on('select2:select', function (evt) {
          var data = $("#datapasien option:selected").text();
        // alert("data yang anda pilih"+data+"");
        var element = evt.params.data.element;
        var $element = $(element);
        
        window.setTimeout(function () {  
        if ($("select").find(":selected").length > 1) {
          var $second = $("select").find(":selected").eq(-2);
          
          $element.detach();
          $second.after($element);
        } else {
          $element.detach();
          $("select").prepend($element);
        }
        
        $("select").trigger("change");
        }, 1);
      });

      $("select").on("select2:unselect", function (evt) {
        if ($("select").find(":selected").length) {
          var element = evt.params.data.element;
          var $element = $(element);
        $
        ("select").find(":selected").after($element); 
        }
      });
  }
</script>
</body>
</html>
