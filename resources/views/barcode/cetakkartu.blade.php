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
              <div class="card-body">
                <div class="barcode-container">
                  <h2>Barcode Generator</h2>
                  <input
                    type="text"
                    placeholder="Enter text or number"
                    class="barcode-input"
                  />
                  <button class="generate-btn">Generate Barcode</button>
                  <svg class="barcode"></svg>
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

  <!-- Main Footer -->
  <footer class="main-footer">
  @include('template.footer')
  </footer>
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
        height: 100,
        displayValue: true,
      });
    } else {
      alert("Please enter a valid text or number to generate a barcode.");
    }
  };

  generateBtn.addEventListener("click", generateBarcode);
</script>

</body>
</html>
