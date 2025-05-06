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
                <h5 class="float-left">Cek Kartu Pasien</h5>
              </div>
              <!-- /.card-header -->
              <form action="{{route('registrasi.simpan')}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div class="card-body">
                  @include('template.alert')
                  <div id="qr-reader" style="width:500px"></div>
                  <div id="qr-reader-results"></div>

                  <hr>
                  <div class="row">
                    <div class="col-md-6">

                      <table id="example2" style="display: none;" class="table table-bordered table-striped">
                        <tr>
                          <th class="text-right">No. RM</th>
                          <td id="no_rm"></td>
                        </tr>
                        <tr>
                          <th class="text-right">Nama</th>
                          <td id="nm_pasien"></td>
                        </tr>
                        <tr>
                          <th class="text-right">Umur</th>
                          <td id="umur"></td>
                        </tr>
                        <tr>
                          <th class="text-right">JK</th>
                          <td id="jk"></td>
                        </tr>
                        <tr>
                          <th class="text-right">Tempat Lahir</th>
                          <td id="tmp_lahir"></td>
                        </tr>
                        <tr>
                          <th class="text-right">Tanggal Lahir</th>
                          <td id="tgl_lahir"></td>
                        </tr>
                        <tr>
                          <th class="text-right">Alamat</th>
                          <td id="alamat"></td>
                        </tr>
                        <tr>
                          <th class="text-right">Pekerjaan</th>
                          <td id="pekerjaan"></td>
                        </tr>
                        <tr>
                          <th class="text-right">Status Menikah</th>
                          <td id="stts_menikah"></td>
                        </tr>
                        <tr>
                          <th class="text-right">Agama</th>
                          <td id="agama"></td>
                        </tr>
                        <tr>
                          <th class="text-right">No. Telp</th>
                          <td id="no_tlp"></td>
                        </tr>                      
                      </table>

                      <h4 id="riwayatpasien" style="display: none;" class="pt-3">5 Riwayat Pasien</h4>
                      <table id="datapasien" class="table table-striped table-bordered table-list-search">
                      </table>
                    </div>
                    <div class="col-md-6" id="registrasi" style="display: none;">
                      <div class="form-group row">
                        
                        <label class="col-sm-3 col-form-label">Tanggal Registrasi<b class="text-danger">*</b></label>
                        <div class="col-sm-9">
                          <input type="date" name="tgl_registrasi" class="form-control form-control-sm" placeholder="tgl_perawatan" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <input type="text" class="form-control" name="no_rkm_medis" id="no_rkm_medis" hidden>
                        <label class="col-sm-3 col-form-label">Dokter<b class="text-danger">*</b></label>
                        <div class="col-sm-9">
                          <div class="form-group">
                            <select class="form-control select2" name="dokter" id="dokter">
                              @foreach ($dokter as $key)
                                <option value="{{ $key->kd_dokter }}">{{$key->nm_dokter}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Poliklinik<b class="text-danger">*</b></label>
                        <div class="col-sm-9">
                          <div class="form-group">
                            <select class="form-control select2" name="poliklinik" id="poliklinik">
                              @foreach ($poliklinik as $key)
                                <option value="{{ $key->kd_poli }}">{{$key->nm_poli}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      
                      <button type="submit" class="btn btn-info mt-5" name="action" value="simpan">Daftar Poli</button>
                    </div>
                  </div>

                  
                </div>
              </form>
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
<script src="{{asset('tampilan/html5-qrcode.min.js')}}"></script>
<script>
    function docReady(fn) {
        // see if DOM is already available
        if (document.readyState === "complete"
            || document.readyState === "interactive") {
            // call on next available tick
            setTimeout(fn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }

    docReady(function () {
        var resultContainer = document.getElementById('qr-reader-results');
        var lastResult, countResults = 0;
        function onScanSuccess(decodedText, decodedResult) {
            if (decodedText !== lastResult) {
                ++countResults;
                lastResult = decodedText;
                // Handle on success condition with the decoded message.
                console.log(`Scan result ${decodedText}`, decodedResult);

                $.ajax({
                  url: "{{route('getpasien')}}",
                  type: "GET",
                  // masukkan request ke data
                  data:{norm:`${decodedText}`}, 
                  dataType: "json",
                  success: function (data) {
                      var markup = '';
                      document.getElementById("example2").style.display = "block";
                      document.getElementById("registrasi").style.display = "block";
                      document.getElementById("riwayatpasien").style.display = "block";
                      $.each(data, function(key, value) {
                          // markup += '<tr> <td>' + value.no_rkm_medis	 + '</td> <td>' + value.nm_pasien + '</td> <td>' + value.pekerjaan+ ' '  + value.nip + '</td> <tr>';
                            document.getElementById("no_rm").textContent=value.no_rkm_medis;
                            $('#no_rkm_medis').val(value.no_rkm_medis);
                            document.getElementById("nm_pasien").textContent=value.nm_pasien;
                            document.getElementById("umur").textContent=value.umur;
                            document.getElementById("jk").textContent=value.jk;
                            document.getElementById("tmp_lahir").textContent=value.tmp_lahir;
                            document.getElementById("tgl_lahir").textContent=value.tgl_lahir;
                            document.getElementById("alamat").textContent=value.alamat;
                            document.getElementById("pekerjaan").textContent=value.pekerjaan;
                            document.getElementById("stts_menikah").textContent=value.stts_menikah;
                            document.getElementById("agama").textContent=value.agama;
                            document.getElementById("no_tlp").textContent=value.no_tlp;
                      });
                      // $('table[id="datapasien"]').html(markup);
                  },
                  error: function (xhr, status, error) {
                      // Handle errors, if any
                      console.log(error);
                  }
                });

                $.ajax({
                  url: "{{route('riwayatperiksa')}}",
                  type: "GET",
                  // masukkan request ke data
                  data:{norm:`${decodedText}`}, 
                  dataType: "json",
                  success: function (data) {
                      var markup = '';
                      document.getElementById("example2").style.display = "block";
                      $.each(data, function(key, value) {
                            for (i in value) 
                            {
                              x = value[i];
                              console.log(x.no_rawat);
                              markup += '<tr> <td>' + x.no_rawat + '</td> <td>' + x.tgl_registrasi + '</td> <td>'+ x.nm_poli + '</td> <td>' + x.nm_poli + '</td> <td>'+ x.status_lanjut + '</td> <tr>';
                            }

                            // document.getElementById("no_rm").textContent=value.no_rkm_medis;
                      });
                      $('table[id="datapasien"]').html(markup);
                  },
                  error: function (xhr, status, error) {
                      // Handle errors, if any
                      console.log(error);
                  }
                });
            }
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>
</body>
</html>
