<div class="col-sm-2 pt-2">
  <a href="{{ route('formri-soap', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info {{ Request::is('menu-ranap/soap') ? 'active' : '' }}">SOAP RI</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('formri-penanganandokter', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info {{ Request::is('menu-ranap/penanganandokter') ? 'active' : '' }}">Penanganan Dokter</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('formri-medisumum', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info {{ Request::is('menu-ranap/medisumum') ? 'active' : '' }}">Awal Medis</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('formri-mediskebidanan', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info {{ Request::is('menu-ranap/mediskebidanan') ? 'active' : '' }}">Medis Kebidanan & Kandungan</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('formri-permintaanlab', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info {{ Request::is('menu-ranap/permintaanlab') ? 'active' : '' }}">Permintaan Lab</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('formri-permintaanrad', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info {{ Request::is('menu-ranap/permintaanrad') ? 'active' : '' }}">Permintaan Rad</a>
</div>
<div class="col-sm-2 pt-2">
  <a href="{{ route('formri-resep', ['no_rawat' => $no_rawat]) }}" class="btn btn-sm btn-block btn-outline-info {{ Request::is('menu-ranap/resep') ? 'active' : '' }}">Resep Dokter</a>
</div>