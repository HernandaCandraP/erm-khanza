<div class="modal fade modal-riwayat-lab" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
              <div class="modal-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Pemeriksaan</th>
                    <th>Hasil</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($riwayatlab as $no => $riwayatlab)
                    <tr>
                        <td><b>{{$riwayatlab->nm_perawatan}}</b><br>
                          {{$riwayatlab->tgl_periksa}} {{$riwayatlab->jam}}
                        </td>
                        <td>
                          <table id="example" class="table table-sm table-bordered table-striped">
                            <thead>
                            <tr>
                              <th>Pemeriksaan</th>
                              <th>Hasil</th>
                              <th>Satuan</th>
                              <th>Nilai Rujukan</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach ($riwayatdelatillab as $no => $value)
                                @if ($riwayatlab->kd_jenis_prw == $value->kd_jenis_prw )
                                  <tr>
                                      <td>{{$value->pemeriksaan}}</td>
                                      <td>{{$value->nilai}}</td>
                                      <td>{{$value->satuan}}</td>
                                      <td>{{$value->nilai_rujukan}}</td>
                                  </tr>
                                @endif
                              @endforeach
                            </tbody>
                          </table>


                        </td>
                    </tr> 
                    @endforeach
                  </tbody>
                </table>
        </div>
    </div>
  </div>
</div>


<div class="modal fade modal-riwayat-rad" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
              <div class="modal-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Pemeriksaan</th>
                    <th>Gambar</th>
                    <th>Hasil</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($grouprad as $no => $grouprad)
                    <tr>
                        <td>
                          {{$grouprad->tgl_periksa}} {{$grouprad->jam}}
                            
                        </td>
                        <td>
                            @foreach ($pasienrad as $no => $pasienrad1)
                                @if ($pasienrad1->tgl_periksa == $grouprad->tgl_periksa && $pasienrad1->jam == $grouprad->jam )
                                    {{$pasienrad1->nm_perawatan}}
                                    {{$pasienrad1->kd_jenis_prw}}<br>
                                @endif
                            @endforeach

                            @foreach ($getgambar as $nogambar => $getgambar1)
                                @if ($getgambar1->tgl_periksa == $grouprad->tgl_periksa && $getgambar1->jam == $grouprad->jam )
                                    {{-- {{$getgambar1->lokasi_gambar}} --}}
                                    {{-- <div class="col-sm-6"> --}}
                                        <img src="{{$getgambar1->lokasi_gambar}}" class="img-fluid w-100"/><br>
                                    {{-- </div> --}}
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($hasilrad as $no => $hasilrad1)
                                @if ($hasilrad1->tgl_periksa == $grouprad->tgl_periksa && $hasilrad1->jam == $grouprad->jam )
                                    {!! nl2br($hasilrad1->hasil) !!}
                                @endif
                            @endforeach
                        </td>
                    </tr> 
                    @endforeach
                  </tbody>
                </table>
        </div>
    </div>
  </div>
</div>
