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
          @include('template.menudokterranap', ['no_rawat' => $no_rawat, 'specialis' => Session::get('getspecialis')])

          <div class="col-md-12 mt-4">
            <!-- Horizontal Form -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">::[ Resep Dokter ]::</h3>
              </div>
              <!-- /.card-header -->
              <form class="form-horizontal">
                <div class="card-header">
                  <div class="row text-sm"><div class="col-sm-6">
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
                </div>
              </form>
              @include('template.alert')
              <!-- form start -->
              <form action="{{route('formri-tambah')}}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div  class="card-body">
                  <div class="form-group row text-sm">
                    <div class="form-inline row">
                      {{-- <div class="col-xs-6 pt-1">&emsp;&emsp; Dokter Perujuk &emsp;: {{$penilaianralanparu->nama}}</div> --}}
                      <input type="text" name="no_rawat" class="form-control form-control-sm" value="{{$detailpasien[0]->no_rawat}}" hidden>
                      <input type="text" name="nip" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik}}" hidden>
                      <input type="text" name="niplama" class="form-control form-control-sm" value="{{ Auth::guard('pegawai')->user()->nik }}" hidden>
                      <input type="text" name="tabel" class="form-control form-control-sm" value="ResepDokterRawatInap" hidden>
                    </div>

                    <div class="form-inline row text-sm pb-2">
                      <div class="col-xs-3 pt-1">&emsp;&emsp; Tanggal &emsp;</div>
                      <div class="col-xs-3 pt-1">
                        <input type="date" name="tgl_peresepan" class="form-control form-control-sm" placeholder="tgl_peresepan" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                      </div>
                      <div class="col-xs-3 pt-1">&emsp;&emsp; Jam &emsp;</div>
                      <div class="col-xs-3 pt-1">
                        <input type="time" name="jam_peresepan" class="form-control form-control-sm" placeholder="jam_peresepan" value="{{Carbon\Carbon::now()->format('H:i:s')}}">
                      </div>
                    </div>
                  </div>

                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h6 class="card-title text-sm">
                        <b>Data Pemeriksaan</b>
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="form-group row "> 
                        <div class="col-sm-12">
                          <table id="testing" class="table table-bordered table-striped text-sm">
                            <tr>
                              {{-- <th><input class="check_all" type="checkbox" onclick="select_all()"/></th> --}}
                              {{-- <th>No.</th> --}}
                              <th>Jumlah</th>
                              <th>Aturan Pakai</th>
                              <th>Total</th>
                              <th>Aksi</th>
                            </tr>
                            <tr>
                              {{-- <td><input type="checkbox" class="case"/></td> --}}
                              {{-- <td>1.</td> --}}
                              <td><input type="number" min="1" id="jumlah" class="form-control" name="jumlah[]" required/></td>
                              <td><input type="text" id="aturan-pakai" class="form-control" name="aturan_pakai[]"/></td>
                              <td>
                                <select class="select2" id="resep" class="form-control" name="resep[]" required>
                                  <option value="" disabled selected>Pilih Obat</option>
                                  @foreach ($resepdokter as $key)
                                    <option value="{{ $key->kode_brng }}"> {{ $key->kode_brng }} - {{$key->nama_brng}} </option>
                                  @endforeach
                                </select>
                              </td>
                              <td><text type="text" class="btn btn-danger" id="hapusresep">X</text></td>
                            </tr>
                          </table>
                      
                          {{-- <div type="text" class="btn btn-warning delete">- Delete</div> --}}
                          <div type="text" class="btn btn-warning addmore float-right">+ Tambah Obat</div>
                         
                        </div>
                      </div>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-info float-right ml-3" name="action" value="simpan">Simpan</button>
                </div>
                <!-- /.card-body -->
              </form>
              <div  class="card-body">
                <div class="card card-primary card-outline">
                  <div class="card-header">
                    <h6 class="card-title text-sm">
                      <b>Riwayat Resep Dokter</b>
                    </h6>
                  </div>
                  <div class="card-footer">
                    <table id="example1" class="table table-bordered table-striped text-sm">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>No Resep</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Aturan Pakai</th>
                        <th>Dokter Peresep</th>
                        {{-- <th>Aksi</th> --}}
                      </tr>
                      </thead>
                      <tbody>
                        @foreach ($listresep as $no => $key)
                        <tr>
                            <th scope="row"> {{$no+1}}. </th>
                            <td>{{$key->no_resep}}</td>
                            <td>{{$key->kode_brng}}</td>
                            <td>{{$key->nama_brng}}</td>
                            <td>{{$key->jml}}</td>
                            <td>{{$key->aturan_pakai}}</td>
                            <td>{{$key->nama}}</td>
                            {{-- <td><a href="{{ route('form/hapus', ['no_rawat' => $key->no_rawat, 'tabel' => 'PenangananDokter', 'niplama' => $key->kd_dokter, 'tgl_perawatan_lama' => $key->tgl_perawatan, 'jam_rawat_lama' => $key->jam_rawat, 'kd_jenis_prw' => $key->kd_jenis_prw]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah yakin ingin menghapus data?')"><i class="fas fa-trash"></i></a></td> --}}
                        </tr> 
                        @endforeach
                      
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
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


  var i=2;
  $(".addmore").on('click',function(){
    document.getElementById("testing").insertRow(-1).innerHTML = '<tr><td><input id="jumlah'+i+'" class="form-control" name="jumlah[]" type="number"  min="1" required/></td><td><input  id="aturan-pakai'+i+'" class="form-control" name="aturan_pakai[]" type="text"/></td><td><select class="select2" name="resep[]" required><option value="" disabled selected>Pilih Obat</option> @foreach ($resepdokter as $key)<option value="{{ $key->kode_brng }}"> {{ $key->kode_brng }} - {{$key->nama_brng}} </option>@endforeach</select></td><td><text type="text" class="btn btn-danger" id="hapusresep'+i+'">X</text></td></tr>';
    // document.getElementById("testing").insertRow(-1).innerHTML = '<tr><td><input type="checkbox" class="case"/></td><td>'+i+'.</td><td><input id="jumlah'+i+'" class="form-control" name="jumlah[]" type="number"  min="1" /></td><td><input  id="aturan-pakai'+i+'" class="form-control" name="aturan_pakai[]" type="text"/></td><td><select class="select2" name="resep[]"><option value="" disabled selected>Pilih Obat</option> @foreach ($resepdokter as $key)<option value="{{ $key->kode_brng }}"> {{ $key->kode_brng }} - {{$key->nama_brng}} </option>@endforeach</select></td><td><text type="text" class="btn btn-danger" id="hapusresep'+i+'">X</text></td></tr>';
    // var data="<tr><td><input type='checkbox' class='case'/></td><td>"+i+".</td>";
    //     data +="<td><input type='text' id='jumlah"+i+"' name='jumlah[]'/></td> <td><input type='text' id='aturan-pakai"+i+"' name='aturan_pakai[]'/></td><td><input type='text' id='tamil"+i+"' name='tamil[]'/></td><td><input type='text' id='english"+i+"' name='english[]'/></td><td><input type='text' id='computer"+i+"' name='computer[]'/></td>";
    //     data +="<td><select class='select2' id='total"+i+"' name='total[]' ><option   selected='selected'>Cat</option><option selected='selected'>Dog</option> </select></td></tr>"
        
    //     $('table#testing').append(data);
    $('.select2').select2();

    $('#hapusresep'+i).on('click', function() {
      $(this).parents("tr").remove();
      return false;
    });
    i++;
  });

  // $(document).on('click', 'div#hapusresep', function () {
  //   $(this).closest('tr').remove();
  //   
  // });

  $("#hapusresep").on('click', function() {
    $(this).parents("tr").remove();
    return false;
  });


  // $(".delete").on('click', function() {
  //   $('.case:checkbox:checked').parents("tr").remove();
  //     $('.check_all').prop("checked", false); 
  //   check();
  // });
  
  $(document).ready(function (){
    var table = $('#example').DataTable({
        pageLength: 4
    });
  });
</script>
</body>
</html>


