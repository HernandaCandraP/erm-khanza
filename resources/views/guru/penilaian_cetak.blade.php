<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
    <style type="text/css">
      @media print{
        .hide-print{
          display: none;
        }
        .page-break{
          clear: both;
          page-break-after: always;
        }
        @page{
          size: auto;
          size: A4 portrait;
          margin: 1.5cm 1.5cm 1.5cm 2cm;
          border: 1px solid red;
        }
      }
      body{
        height:297mm; width:210mm; margin-left:auto; margin-right:auto;
      }
      .content-left{
        float: left;
        position: absolute;
        width: 13%;
      }
      .content-right{
        float: left;
        position: absolute;
        width: 97%;
        margin-left: 5%;
      }
    </style>
</head>
<body>
<div class="wrapper">

  <div class="">
    <section class="content-header"></section>
    <button id="print" class="hide-print btn btn-warning" onclick="window.print()">Cetak Laporan Penilaian</button>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <div class="row">
          <div class="col-md-12">
            <!-- <div class="card">
              <div class="card-header"> -->
                @foreach($kls as $value)
                  <div class="card page-break">
                    <div class="card-header">
                      <div>
                        <div class="content-left">
                          <img src="{{asset('image/logo_trenggalek.png')}}" class="img-fluid"/>
                        </div>
                        <div class="content-right text-center">
                          <h4>PEMERINTAH KABUPATEN TRENGGALEK</h4>
                          <h4>DINAS PENDIDIKAN DAN KEBUDAYAAN</h4>
                          <h3>SMP NEGERI 1 DURENAN</h3>
                          <h6> Jl. Raya Durenan No. 10 Trenggalek Kode Pos. 66381 Phone (0355)879508</h6>
                        </div>
                      </div><hr style="margin-top:155px; border:2px solid">
                      <h4 class="card-title">
                        <h6>Periksa Hasil Penilaian</h6>
                        <h6>Mata Pelajaran {{$namamapel->nama_mapel}} ({{$namamapel->waktu_mulai}} - {{$namamapel->waktu_selesai}})</h6> 
                        <hr>
                        Kelas {{$value->nama_kelas}}
                      </h4>
                    </div>
                      <div class="card-body">
                        <table class="table table-bordered table-striped">
                          <thead>
                          <tr>
                            <th>No.</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Nilai Cosine</th>
                            <th>Nilai Jaccard</th>
                            <th>Penilaian Guru</th>
                          </tr>
                          </thead>
                          <tbody>
                          @foreach ($value->getsiswa as $no =>$key)
                          <tr>
                              <th scope="row"> {{$no+1}}. </th>
                              <td>{{$key->nis}}</td>
                              <td>{{$key->nama_siswa}}</td>
                              <td>@if(empty($avg_cosine[$key->id_siswa]))
                                    0
                                  @else {{$avg_cosine[$key->id_siswa]}}
                                  @endif 
                              </td>
                              <td>
                                @if(empty($avg_jaccard[$key->id_siswa]))
                                    0
                                  @else {{$avg_jaccard[$key->id_siswa]}}
                                  @endif 
                              </td>
                              <td>{{number_format($key->getjawaban->whereIn('id_soal',$getsoal_arr)->sum('nilai_guru') / ($count_soal), 0, '.', '')}}</td>
                          </tr> 
                          @endforeach
                          </tbody>
                        </table>
                      </div>
                  </div>
                @endforeach
              <!-- </div> -->
              <!-- /.card-body -->
            <!-- </div> -->
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
</div>

@include('template.script_table')
<script type="text/javascript">
  function showOverlay() {
  // $('#myModal').show()
  document.getElementById("print").click();
}
setTimeout(showOverlay, 1000)
</script>
</body>
</html>
