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
                        <input type="text" name="jml_dr" class="form-control form-control-sm" style="width: 100%" required>
                      </div>
                    </div>
                    <div class="col-sm-6 pt-3">
                      Aturan Pakai
                      <div class="input-group-append" >
                        <input type="text" name="aturan_pakai" class="form-control form-control-sm" style="width: 100%" required>
                      </div>
                    </div>
                    <div class="col-sm-6 pt-3">
                      Keterangan
                      <div class="input-group-append" >
                        <input type="text" name="keterangan" class="form-control form-control-sm" style="width: 100%" required>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="pt-3"><b>List Obat</b> 
                        <table id="testing" class="table table-bordered table-striped text-sm">
                        </table>
                        <div class="col-sm-12">
                          <div type="text" class="form-control btn btn-warning btn-sm addmore">+ Tambah Obat Rawat Jalan</div>
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
            <th rowspan="{{$value->total +1}}">{{$value->no_resep}}<br>
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
              <td>{{$valuelengkap->nama_brng}}</td>
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
<script>
  var i=1;
  $(".addmore").on('click',function(){
    if(i == 1){
      document.getElementById("testing").insertRow(-1).innerHTML = '<tr><th>Nama Obat</th><th>Jumlah</th><th hidden>Aturan&emsp; Pakai</th><th>Aksi</th></tr>';
    }

    document.getElementById("testing").insertRow(-1).innerHTML = '<tr><td><select class="select2" id="resep'+i+'" name="resep[]" required><option value="" disabled selected>Pilih Obat</option> @foreach ($resepdokter as $key)<option value="{{ $key->kode_brng }}">{{$key->nama_brng}} || stok: {{$key->stok}} || @money($key->ralan)</option>@endforeach</select></td><td><input id="'+i+'" class="form-control jumlah'+i+'" name="jumlah[]" value="" size="6" required/></td><td><text type="text" class="btn btn-danger" id="hapusresep'+i+'">X</text></td></tr>';

    $('.select2').select2();

    $('#hapusresep'+i).on('click', function() {
      $(this).parents("tr").remove();
      return false;
    });
    i++;

    myFunction();
  });

  $(".copyresep").click(function() {
    var str = $(this).val();
    var index = str.substring(0, str.indexOf("#*#"));
    var collectdata = str.split("#*#")[1];
    collectdata= JSON.parse(collectdata)
    
    collectdata.forEach(function(element){
      // console.log(element)
      if(element['no_resep'] == index){
        var $kode_brng = element["kode_brng"];
        if(i == 1){
          document.getElementById("testing").insertRow(-1).innerHTML = '<tr><th>Nama Obat</th><th>Jumlah</th><th>Aturan&emsp; Pakai</th><th>Aksi</th></tr>';
        }
        document.getElementById("testing").insertRow(-1).innerHTML = '<tr><td><input class="form-control" id="resep'+i+'" name="resep[]" value='+element["kode_brng"]+' type="text" hidden />'+element["nama_brng"]+' || stok: '+element["stok"]+' || Rp. '+element["ralan"]+' </td><td><input id="'+i+'" class="form-control jumlah'+i+'" value="'+element["jml"]+'" name="jumlah[]" value="" type="number"  min="1"  size="6" required/></td><td><input  id="aturan-pakai'+i+'" class="form-control" name="aturan_pakai[]" value="'+element["aturan_pakai"]+'" type="text"  size="8"/></td><td><text type="text" class="btn btn-danger" id="hapusresep'+i+'">X</text></td></tr>';

        $('.select2').select2();

        $('#hapusresep'+i).on('click', function() {
          $(this).parents("tr").remove();
          return false;
        });
        i++;
      }
    });
    myFunction();
    $('#modal-lg').modal('hide');
  });

  // check obat kosong atau tidak
  $('form').on('keyup change paste', 'input, select, textarea', function(){
    if (!isNaN(this.id)) {
      console.log( this.id );
      console.log( this.value );
      var resepid = "resep" + this.id;
      var resep = document.getElementById(resepid);
      console.log(resep.value);
      console.log(resepid);
      $.ajax({
        url: "{{route('cekstokobat')}}",
        type: "GET",
        // masukkan request ke data
        data:{jumlah:$(this).val(), id:$(resep).val(), idinput:$(this).attr('id')}, 
        dataType: "json",
        success: function (data) {
            // jika jumlah input melebihi stok
            if(data.msg != ""){
              alert(data.msg);
              var idinput = "#" + data.idinput;
              $(idinput).val(data.stok);
              console.log(data.stok);
            }
        },
        error: function (xhr, status, error) {
            // Handle errors, if any
            console.log(error);
        }
      });
    }
  });
  
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
    $('#diagnosa_utama').select2({
        minimumInputLength: 3,  // bebas semau kita, tapi w saranin 3 angka lebih biar lebih cepat
        maximumSelectionLength: 4,
        allowClear: true,
        placeholder: 'Input nama',
        ajax: {
          dataType: 'json',
          url: '{{route("ambil_icd")}}',  // ini data yang akan kita ambil untuk di load
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
          var data = $("#diagnosa_utama option:selected").text();
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
