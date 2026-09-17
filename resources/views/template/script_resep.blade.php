<script>
  var i=1;
  $(".addmore").on('click',function(){
    if(i == 1){
      document.getElementById("testing").insertRow(-1).innerHTML = '<tr><th>Nama Obat</th><th>Jumlah</th><th>Aturan&emsp; Pakai</th><th>Aksi</th></tr>';
    }

    document.getElementById("testing").insertRow(-1).innerHTML = '<tr><td style="color: white; font-size: 90%;"><select style="min-width: 70%; max-width: 70%; font-size: 90%;" class="select2" id="resep'+i+'" name="resep[]" required><option value="" disabled selected>Pilih Obat</option> @foreach ($resepdokter as $key)<option value="{{ $key->kode_brng }}">{{$key->letak_barang}} / {{$key->nama_brng}} || stok: {{$key->stok}} || @money($key->ralan)</option>@endforeach</select></td><td><input style="width: 80px;" id="'+i+'" class="form-control total jumlah'+i+'" name="jumlah[]" value="" type="number"  min="1"  size="6" required/></td><td><textarea  id="aturan-pakai'+i+'" class="form-control" name="aturan_pakai[]" type="text" cols="25"></textarea></td><td><text type="text" class="btn btn-danger" id="hapusresep'+i+'">X</text></td></tr>';

    $('.select2').select2();

    $('#hapusresep'+i).on('click', function() {
      $(this).parents("tr").remove();
      return false;
    });
    i++;

    myFunction();
  });

  $(".cekharga").on('click',function(){
    var totalHarga = 0;
    var cekKelengkapanObat = 1;
    var notif = "";
    $('#testing tr').each(function(i) {
      var customerId = $(this).find(".select2-selection__rendered").text();  
      var total = $(this).find('.total').val();
      // cek apakah ada isi pada kolom obat
      if(customerId){
        // cek apakah obat & total tidak kosong
        if(customerId && total && customerId != 'Pilih Obat'){
          
          // console.log( customerId ); 
          // console.log( total ); 

          var bagian = customerId.split("||"); 
          var bagianHarga = bagian[2].trim(); // Hasil: "Rp. 1100"
          var harga = bagianHarga.split("Rp.")[1].trim().replace(/,/g, ''); // Hasil: "1100"

          namaobat = bagian[0].split("/"); 
          notif += namaobat[0].trim() +" ( "+ harga +" X "+ total +" ) = "+ commaSeparateNumber(harga*total) +"\n";
          totalHarga+= harga*total;
        }else{
          alert("Obat Belum Lengkap");
          cekKelengkapanObat = 0;
          return false;
        }        
      }
      
    });
    if(cekKelengkapanObat == 1){
      alert(notif+"\nTotal : "+ commaSeparateNumber(totalHarga));
    }

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
        document.getElementById("testing").insertRow(-1).innerHTML = '<tr><td class="select2-selection__rendered"><input class="form-control" id="resep'+i+'" name="resep[]" value='+element["kode_brng"]+' type="text" hidden />'+element["letak_barang"] +' / '+element["nama_brng"]+' || stok: '+element["stok"]+' || Rp. '+element["ralan"]+' </td><td><input id="'+i+'" class="form-control total jumlah'+i+'" value="'+element["jml"]+'" name="jumlah[]" value="" type="number"  min="1"  size="6" required/></td><td><input  id="aturan-pakai'+i+'" class="form-control" name="aturan_pakai[]" value="'+element["aturan_pakai"]+'" type="text"  size="8"/></td><td><text type="text" class="btn btn-danger" id="hapusresep'+i+'">X</text></td></tr>';

        $('.select2').select2();

        $('#hapusresep'+i).on('click', function() {
          $(this).parents("tr").remove();
          return false;
        });
        cekStok(element["jml"], element["kode_brng"], i);
        i++;
      }
    });
    myFunction();
    $('#modal-lg').modal('hide');
  });

  // obat racikan
  var r=1;
  $(".addmore-racikan").on('click',function(){
    if(r == 1){
      document.getElementById("racikan").insertRow(-1).innerHTML = '<tr><th>Nama Obat</th><th>Dosis (ex: 0.5)</th><th hidden>Aturan&emsp; Pakai</th><th>Aksi</th></tr>';
    }

    document.getElementById("racikan").insertRow(-1).innerHTML = '<tr><td><select class="select2" id="resepracik'+r+'" name="resepracik[]" required><option value="" disabled selected>Pilih Obat</option> @foreach ($resepdokter as $key)<option value="{{ $key->kode_brng }}">{{$key->nama_brng}} || stok: {{$key->stok}} || @money($key->ralan)</option>@endforeach</select></td><td><input id="'+r+'" type="number" min="0" step="any" class="form-control jumlahracik'+r+'" name="jumlahracik[]" value="" size="6" required/></td><td><text type="text" class="btn btn-danger" id="hapusresepracikan'+r+'">X</text></td></tr>';

    $('.select2').select2();

    $('#hapusresepracikan'+r).on('click', function() {
      $(this).parents("tr").remove();
      return false;
    });
    r++;

    myFunction();
  });

  // copy resep racikan
  $(".copyresep-racikan").click(function() {
    var str = $(this).val();
    var index = str.substring(0, str.indexOf("#*#"));
    var collectdata = str.split("#*#")[1];
    collectdata= JSON.parse(collectdata)
    // console.log(collectdata);
    
    collectdata.forEach(function(element){
      console.log(element, index)
      if(element['kode_formula'] == index){
        var $kode_brng = element["kode_brng"];
        if(r == 1){
          document.getElementById("racikan").insertRow(-1).innerHTML = '<tr><th>Nama Obat</th><th>Dosis (ex: 0.5)</th><th hidden>Aturan&emsp; Pakai</th><th>Aksi</th></tr>';
        }
        document.getElementById("racikan").insertRow(-1).innerHTML = '<tr><td><input class="form-control" id="resepracik'+r+'" name="resepracik[]" value='+element["kode_brng"]+' type="text" hidden />'+element["nama_brng"]+' || stok: '+element["stok"]+' || Rp. '+element["ralan"]+' </td><td><input id="'+r+'" class="form-control jumlahracik'+r+'" value="'+element["kandungan"]+'" name="jumlahracik[]" value="" type="number" min="0" step="any" size="6" required/></td><td><text type="text" class="btn btn-danger" id="hapusresepracikan'+r+'">X</text></td></tr>';

        $('.select2').select2();

        $('#hapusresepracikan'+r).on('click', function() {
          $(this).parents("tr").remove();
          return false;
        });
        r++;
      }
    });
    myFunction();
    $('#modal-racikan').modal('hide');
  });

  // check obat kosong atau tidak
  $('form').on('keyup change paste', 'input, select, textarea', function(){
    if (!isNaN(this.id) && this.name == 'jumlah[]') {
      // console.log( this.id );
      // console.log( this.value );
      var resepid = "resep" + this.id;
      var resep = document.getElementById(resepid);
      cekStok(this.value, resep.value, this.id);
    }
  });
  
  $(document).ready(function() {
    myFunction();
  });

  function cekStok(jumlah, id, idinput) {
    console.log(jumlah, id, idinput);
    if (id.trim()) {
      $.ajax({
        url: "{{route('cekstokobat')}}",
        type: "GET",
        // masukkan request ke data
        data:{jumlah, id, idinput}, 
        dataType: "json",
        success: function (data) {
            // jika jumlah input melebihi stok
            if(data.msg != ""){
              alert(data.msg);
              var idinput = "#" + data.idinput;
              $(idinput).val(data.stok);
              // console.log(data.stok);
            }
        },
        error: function (xhr, status, error) {
            // Handle errors, if any
            console.log(error);
        }
      });
    }else{
      alert("Obat Masih Kosong");
    }
  }

  function commaSeparateNumber(val){
    while (/(\d+)(\d{3})/.test(val.toString())){
      val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
    }
    return val;
  }

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