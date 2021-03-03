
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
    @include('template.head')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  @include('template.navbar')
  @include('template.sidebar')

  <div class="content-wrapper">
    <section class="content-header">
      <!-- Main content -->
      <div class="container-fluid">
        <div class="row">
          <div class="col-6 col-sm-6 col-md-4">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Perhitungan Cosine</span>
                <span class="info-box-number">{{$tampilnilai->sum('nilai_cosine')/$tampilnilai->count('nilai_cosine')}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-6 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Perhitungan Jaccard</span>
                <span class="info-box-number">{{$tampilnilai->sum('nilai_jaccard')/$tampilnilai->count('nilai_jaccard')}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-6 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Penialian Guru</span>
                <span class="info-box-number">{{$tampilnilai->sum('nilai_guru')/$tampilnilai->count('nilai_guru')}}</span>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
          @foreach($tampilnilai as $no => $value )
            <div class="card card-primary card-outline">
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-9">
                    <h5 class="card-title">{{$no+1}}. {{$value->soal}}</h5>
                    <!-- <textarea class="form-control" name="jawaban_siswa" rows="5" placeholder="jawaban">soal</textarea> -->
                    <textarea class="form-control" style="background: #ffff" name="jawaban_siswa" rows="5" placeholder="jawaban kosong" disabled>{{$value->jawaban_siswa}}</textarea><br>
                  </div>
                  <div class="col-lg-3 data-row">
                    <a class="btn bg-primary">
                      Cosine Similarity&emsp;: &nbsp; <b>{{$value->nilai_cosine}}</b> 
                    </a>
                    <a class="btn bg-info">
                      Jaccard Similarity &ensp;: &nbsp; <b>{{$value->nilai_jaccard}}</b> 
                    </a>
                    <a class="btn bg-lightblue ">
                      @if($value->nilai_guru == null)
                        Penilaian Guru &emsp;&ensp;&nbsp;: &nbsp; <b>-</b> 
                      @else
                        Penilaian Guru &emsp;&ensp;&nbsp;: &nbsp; <b>{{$value->nilai_guru}}</b> 
                      @endif
                    </a>
                    <!-- mendapatkan nilai guru untuk proses edit data  -->
                    <a class="nilai-guru" hidden>{{$value->nilai_guru}}</a>
                    <button type="button" class="btn btn-default" data-toggle="modal" id="edit-item" data-item-id="{{$value->id_jawaban}}">Edit Penilaian Guru</button>
                  </div>  
                </div>
              </div>
            </div><!-- /.card -->
          @endforeach
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  

      <div class="modal fade" id="edit-modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Default Modal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{url('guru/penilaian/update')}}" id="edit-form" method="post" class="form-horizontal">
            {{ csrf_field() }}
            <div class="modal-body">
              <div class="form-group" hidden>
                <label class="col-form-label">Id</label>
                <input type="text" name="modal_input_id" class="form-control" id="modal_input_id" required>
                <input type="text" id="id_detail" name="id_detail" value="{{$id_detail}}">
                <input type="text" id="id_siswa" name="id_siswa" value="{{$id_siswa}}">
              </div>
              <div class="form-group">
                <label for="modal_input_nilai">Nilai</label>
                <input type="text" name="modal_input_nilai" class="form-control" id="modal_input_nilai" autocomplete="off" required autofocus>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Edit Penilaian</button>
            </div>
            <form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->



  <footer class="main-footer">
  @include('template.footer')
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
@include('template.script')
<script>
$(document).ready(function() {

$(document).on('click', "#edit-item", function() {
    $(this).addClass('edit-item-trigger-clicked'); //useful for identifying which trigger was clicked and consequently grab data from the correct row and not the wrong one.

    var options = {
      'backdrop': 'static'
    };
    $('#edit-modal').modal(options)
  })

  // on modal show
  $('#edit-modal').on('show.bs.modal', function() {
    var el = $(".edit-item-trigger-clicked"); // See how its usefull right here? 
    var row = el.closest(".data-row");

    // get the data
    var id = el.data('item-id');
    var nilai = row.children(".nilai-guru").text();

    // fill the data in the input fields
    $("#modal_input_id").val(id);
    $("#modal_input_nilai").val(nilai);
  })

  // on modal hide
  $('#edit-modal').on('hide.bs.modal', function() {
    $('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked')
    $("#edit-form").trigger("reset");
  })
});

</script>
</body>
</html>
