{{-- <html>
<head>
    <title>Html-Qrcode Demo</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<body>
    <div id="qr-reader" style="width:500px"></div>
    <div id="qr-reader-results"></div>
    <input type="text" name="nameOfTheInputElement"/>

    <button>Set text content for all p elements</button>

<p>This is a paragraph.</p>
<p>This is another paragraph.</p>

<table id="datapasien" class="table table-striped table-bordered table-list-search">
</table>
</body>

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
                $("p").text(`Scan result ${decodedText}`);

                $.ajax({
                  url: "{{route('getpasien')}}",
                  type: "GET",
                  // masukkan request ke data
                  data:{norm:`${decodedText}`}, 
                  dataType: "json",
                  success: function (data) {
                      var markup = '';
                      $.each(data, function(key, value) {
                          markup += '<tr> <td>' + value.no_rkm_medis	 + '</td> <td>' + value.nm_pasien + '</td> <td>' + value.pekerjaan+ ' '  + value.nip + '</td> <tr>';
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
</head>
</html> --}}

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
                <h5 class="float-left">Data Akses</h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Jenis Kelamin</th>
                    <th>Username</th>
                    <th>Level</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($datalogin as $no => $key)
                    <tr>
                        <th scope="row"> {{$no+1}}. </th>
                        <td>{{$key->nik}}</td>
                        <td>{{$key->nama}}</td>
                        <td>{{$key->alamat}}</td>
                        <td>{{$key->jk}}</td>
                        <td>{{$key->username}}</td>
                        <td>{{$key->level}}</td>
                        <td><th><a href="{{ route('resetpassword', ['nik' => $key->nik]) }}" class="btn btn-block btn-primary btn-xs">Reset Password</a></th></td>
                    </tr> 
                    @endforeach
                  
                  </tbody>
                </table>
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
<script src="{{asset('tampilan/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<script>
  $(function () {
    $('.knob').knob({
      draw: function () {
      }
    })
  })
</script>
</body>
</html>
