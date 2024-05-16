
<div class="col-sm-2 pt-2">
  <a href="{{ route('form-resume', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info {{ Request::is('form/resume') ? 'active' : '' }}">Resume</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('form-soap', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info {{ Request::is('form/soap') ? 'active' : '' }}">SOAP</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('form-awalmedis'. strtolower(Session::get('nm_sps')), ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info {{ Request::is('form/awalmedis'. strtolower(Session::get('nm_sps'))) ? 'active' : '' }}">Penilaian Awal Medis {{ Session::get('nm_sps')}}</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('form-permintaanlab', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info {{ Request::is('form/permintaanlab') ? 'active' : '' }}">Permintaan Lab</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('form-permintaanrad', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info {{ Request::is('form/permintaanrad') ? 'active' : '' }}">Permintaan Rad</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('form-penanganandokter', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info {{ Request::is('form/penanganandokter') ? 'active' : '' }}">Penanganan Dokter</a>
</div>
{{-- <div class="col-sm-2 pt-2">
  <a href="{{ route('form-diagnosa', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info">Diagnosa</a>
</div> --}}