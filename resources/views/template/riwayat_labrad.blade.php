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
                              <th>Keterangan</th>
                            </tr>
                            </thead>
                            <tbody>
                              @foreach ($riwayatdelatillab as $no => $value)
                                @if ($riwayatlab->kd_jenis_prw == $value->kd_jenis_prw && $riwayatlab->tgl_periksa == $value->tgl_periksa && $riwayatlab->jam == $value->jam )
                                  @if ($value->keterangan = NULL)
                                  <tr class="bg-danger bg-gradient">
                                      <td>{{$value->pemeriksaan}}</td>
                                      <td>{{$value->nilai}}</td>
                                      <td>{{$value->satuan}}</td>
                                      <td>{{$value->nilai_rujukan}}</td>
                                      <td>{{$value->keterangan}}</td>
                                  </tr>
                                  @else
                                  <tr>
                                      <td>{{$value->pemeriksaan}}</td>
                                      <td>{{$value->nilai}}</td>
                                      <td>{{$value->satuan}}</td>
                                      <td>{{$value->nilai_rujukan}}</td>
                                      <td>{{$value->keterangan}}</td>
                                  </tr>
                                  @endif
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


<div class="modal fade modal-riwayat-ekg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
              <div class="modal-body">
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="row">
                            <div class="col-4 text-right">Kiriman Dari</div>
                            <div class="col-8">{{$riwayatekg->kiriman_dari ?? '' ?? ''}}</div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-4 text-right">Irama</div>
                            <div class="col-8">{{$riwayatekg->irama ?? ''}}</div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-4 text-right">Gelombang P</div>
                            <div class="col-8">{{$riwayatekg->gelombangp ?? ''}}</div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-4 text-right">Axis</div>
                            <div class="col-8">{{$riwayatekg->axis ?? ''}}</div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-4 text-right">Segmen ST</div>
                            <div class="col-8">{{$riwayatekg->segmenst ?? ''}}</div>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="row">
                            <div class="col-4 text-right">Diagnosa Klinis</div>
                            <div class="col-8">{{$riwayatekg->diagnosa_klinis ?? ''}}</div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-4 text-right">Laju Jantung</div>
                            <div class="col-8">{{$riwayatekg->laju_jantung ?? ''}}</div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-4 text-right">Interval PR</div>
                            <div class="col-8">{{$riwayatekg->intervalpr ?? ''}}</div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-4 text-right">Kompleks QRS</div>
                            <div class="col-8">{{$riwayatekg->kompleksqrs ?? ''}}</div>
                            <div class="w-100 pt-2"></div>
                            <div class="col-4 text-right">Gelombang T</div>
                            <div class="col-8">{{$riwayatekg->gelombangt ?? ''}}</div>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          Kesimpulan
                          <textarea class="form-control" rows="9" name="kesimpulan" autocomplete="off" style="width: 100%" readonly>{{ $riwayatekg->kesimpulan ?? ''}}</textarea>
                        </div>
                      </div>
        </div>
    </div>
  </div>
</div>