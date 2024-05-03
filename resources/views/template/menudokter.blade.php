
<div class="col-sm-2 pt-2">
  <a href="{{ route('form-resume', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info">Resume</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('form-soap', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info">SOAP</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('form-awalmedis'. strtolower(Session::get('nm_sps')), ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info">Penilaian Awal Medis {{ Session::get('nm_sps')}}</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('form-permintaanlab', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info">Permintaan Lab</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('form-permintaanrad', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info">Permintaan Rad</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('form-penanganandokter', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info">Penanganan Dokter</a>
</div>
{{-- <div class="col-sm-2 pt-2">
  <a href="{{ route('form-diagnosa', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info">Diagnosa</a>
</div> --}}