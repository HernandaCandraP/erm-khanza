<div class="modal fade" id="modal-racikan">
  <div class="modal-dialog modal-dialog-scrollable modal-lg modal-racikan">
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
        @foreach ($dataperformula as $key => $value)
          <tr>
            <th rowspan="{{$value->total +1}}"> {{$key+1}}. </th>
            <th rowspan="{{$value->total +1}}">{{$value->kode_formula}}<br>
              <button type="button" value="{{$value->kode_formula}}#*#{{$formula}}" class="btn btn-success btn-sm copyresep-racikan">
                Copy Resep
              </button>
            </th>
            <th>Kode Obat</th>
            <th>Nama Obat</th>
            <th>Kandungan</th>
          </tr> 
          @foreach ($formula as $keylengkap => $valuelengkap)
            @if ($valuelengkap->kode_formula == $value->kode_formula)
            <tr>
              <td>{{$valuelengkap->kode_brng}}</td>
              <td>{{$valuelengkap->letak_barang}} / {{$valuelengkap->nama_brng}}</td>
              <td>{{$valuelengkap->kandungan}}</td>
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