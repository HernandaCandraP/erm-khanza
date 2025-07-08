<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
    <style>
      span.foo
      {
        font-size: 0.575em;;
        background-color: green;
        line-height: 0;
        font-family: Arial;
      }
      
      span.bar
      {
        position: relative;
        bottom: -0.92em;
      }
    </style>

  <style type="text/css" media="print">
    @page {
        size: auto;   /* auto is the initial value */
        margin: 0;  /* this affects the margin in the printer settings */
    }
  </style>
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
              <div class="card-header d-print-none">
                <h5 class="float-left">Cek Kartu Pasien</h5>
              </div>
              {{-- .d-print-none --}}
              <!-- /.card-header -->
              <div class="card-body">
                <div class="barcode-container pb-4">
                  <input
                    type="text"
                    placeholder="No RM..."
                    class="barcode-input d-print-none"
                  />
                  <button class="generate-btn d-print-none btn btn-primary btn-sm">Cetak Barcode</button>
                </div>
                <div class="row">
                  <div class="col-sm-1">
                    <img src="{{asset('image/logo-hst-kecil.png')}}" alt="RSBA" class="brand-image " style="height: 60px;">
                  </div>
                  <div class="col-sm-6">
                    <b style="line-height: 0;height: 0; font-size: 1.440em;">RS. BUDI ASIH TRENGGALEK</b><br>
                    <b style="line-height: 0;height: 0; margin-top: 0;font-size: 0.675em;">Jl. Mayjen Sungkono No. 80 Telp. (0355) 794690 Fax. 794680 </b><br>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <table id="example2" style="display: none;" class="table table-sm">
                      <tr>
                        <td id="nm_pasien"></td>
                      </tr>
                      <tr>
                        <td id="no_rm"></td>
                      </tr>
                      <tr>
                        <td id="tgl_lahir"></td>
                      </tr>        
                      <tr>
                        <th><svg class="barcode"></svg></th>
                      </tr>        
                    </table>
                  </div>
                  {{-- <div class="col-sm-6">
                    <svg class="barcode"></svg>
                  </div> --}}
                </div>
              </div>
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

</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
@include('template.script_table')
<script
src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.6/JsBarcode.all.min.js"
integrity="sha512-k2wo/BkbloaRU7gc/RkCekHr4IOVe10kYxJ/Q8dRPl7u3YshAQmg3WfZtIcseEk+nGBdK03fHBeLgXTxRmWCLQ=="
crossorigin="anonymous"
referrerpolicy="no-referrer"
></script>
<script>
  const generateBtn = document.querySelector(".generate-btn");

  const generateBarcode = () => {
    const input = document.querySelector(".barcode-input").value;
    if (input.trim() !== "") {
      JsBarcode(".barcode", input, {
        height: 45,
        displayValue: false,
      });
    } else {
      alert("Silakan masukan No RM...");
    }
    $.ajax({
      url: "{{route('getpasien')}}",
      type: "GET",
      // masukkan request ke data
      data:{norm:input}, 
      dataType: "json",
      success: function (data) {
          var markup = '';
          document.getElementById("example2").style.display = "block";
          $.each(data, function(key, value) {
              // markup += '<tr> <td>' + value.no_rkm_medis	 + '</td> <td>' + value.nm_pasien + '</td> <td>' + value.pekerjaan+ ' '  + value.nip + '</td> <tr>';
                document.getElementById("no_rm").textContent="RM : "+ value.no_rkm_medis;
                document.getElementById("nm_pasien").textContent=value.nm_pasien;
                document.getElementById("tgl_lahir").textContent=value.tgl_lahir;
          });
          // $('table[id="datapasien"]').html(markup);
      },
      error: function (xhr, status, error) {
          // Handle errors, if any
          console.log(error);
      }
    });
  };

  generateBtn.addEventListener("click", generateBarcode);
</script>

</body>
</html>
