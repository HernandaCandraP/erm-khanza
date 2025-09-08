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
        <div class="col-md-12">

          <!-- Form Edit -->
          <form id="editForm" action="{{ route('update.mutu', $editmutu->id) }}" method="post">
            @csrf
            <!-- Simpan URL halaman sebelumnya -->
            <input type="hidden" name="redirect_url" value="{{ request('redirect', url()->previous()) }}">

            <!-- <div class="form-group">
              <label for="tahun">Tahun</label>
              <input type="number" name="tahun" class="form-control" min="2020" max="2100"
                     value="{{ old('tahun', $editmutu->tahun) }}" required>
            </div> -->

            <div class="form-group">
            <label for="tahun">Tahun</label>
                <input type="number"
                    class="form-control"
                    value="{{ date('Y') }}"
                    disabled>
                <input type="hidden" name="tahun" value="{{ date('Y') }}">
        </div>
            <div class="form-group">
              <label for="bulan">Bulan</label>
              <select name="bulan" class="form-control" required>
                <option value="">-- Pilih Bulan --</option>
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bulan)
                  <option value="{{ $bulan }}" {{ old('bulan', $editmutu->bulan) == $bulan ? 'selected' : '' }}>
                    {{ $bulan }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group"> 
              <label for="numerator">Numerator</label>
              <input type="number" class="form-control" id="numerator" name="numerator" 
              value="{{ old('numerator', $editmutu->numerator ?? '') }}" placeholder="Numerator">
            </div>

            <div class="form-group"> 
              <label for="denumerator">Denumerator</label>
              <input type="number" class="form-control" id="denumerator" name="denumerator" 
              value="{{ old('denumerator', $editmutu->denumerator ?? '') }}" placeholder="Denumerator">
            </div>

            <input type="hidden" name="id" value="{{ $editmutu->id }}">
            <input type="hidden" name="detail_mutu" value="{{ $editmutu->detail_mutu }}">

            <!-- Tombol untuk buka modal konfirmasi -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmEditModal">
              Submit
            </button>
          </form>
          <!-- End Form -->

        </div>
      </div>
    </div>
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmEditModal" tabindex="-1" role="dialog" aria-labelledby="confirmEditLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="confirmEditLabel">Konfirmasi Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin mengedit data ini? Periksa kembali semua input sebelum menyimpannya.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="confirmEditBtn">Ya, Simpan Perubahan</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->

<!-- Main Footer -->
<footer class="main-footer">
  @include('template.footer')
</footer>
</div>
<!-- ./wrapper -->

<!-- SCRIPTS -->
@include('template.script_table')

<!-- Script untuk aksi modal -->
<script>
  document.getElementById('confirmEditBtn').addEventListener('click', function () {
    document.getElementById('editForm').submit();
  });
</script>

</body>
</html>