<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
    <style>
        /* Hover warna merah untuk link hapus */
        .hapus-link {
            color: #dc3545;
            text-decoration: none;
            transition: color 0.3s, font-weight 0.3s;
        }
        .hapus-link:hover {
            color: #ff0000;
            font-weight: bold;
            cursor: pointer;
        }

        /* Hover biru untuk link edit */
        .edit-link {
            color: #0d6efd;
            text-decoration: none;
            transition: color 0.3s;
        }
        .edit-link:hover {
            color: #0b5ed7;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

@include('template.navbar')
@include('template.sidebar')

@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Form Kepuasan Pasien</h4>

    @if(session('hasil'))
        <div class="alert alert-success">
            <b>Hasil Input:</b><br>
            Tahun: {{ session('hasil.tahun') }}<br>
            Bulan: {{ session('hasil.bulan') }}<br>
            Numerator: {{ session('hasil.numerator') }}<br>
            Denumerator: {{ session('hasil.denumerator') }}
        </div>
    @endif

    <form method="POST" action="{{ route('indikatormutu.simpan-mutu') }}">
        @csrf
        <div class="form-group">
            <label for="tahun">Tahun</label>
            <input type="number" class="form-control" value="{{ date('Y') }}" disabled>
            <input type="hidden" name="tahun" value="{{ date('Y') }}">
        </div>

        <div class="form-group">
            <label>Bulan</label>
            <select name="bulan" class="form-control" required>
                <option value="">-- Pilih Bulan --</option>
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bulan)
                    <option value="{{ $bulan }}" {{ old('bulan') == $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Numerator</label>
            <input type="number" name="numerator" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Denumerator</label>
            <input type="number" name="denumerator" class="form-control" required>
        </div>

        <input type="text" name="detail_mutu" value="kepuasan-pasien" class="form-control" hidden>

        <button type="button" id="btn-simpan" class="btn btn-success mt-3">Simpan</button>
    </form>

    <div class="card-body mt-4">
        <table id="example3" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun</th>
                    <th>Bulan</th>
                    <th>Numerator</th>
                    <th>Denumerator</th>
                    <th>Hasil Dalam%</th>
                    <th>Target</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datamutu as $no => $key)
                    <tr>
                        <td>{{ $no+1 }}</td>
                        <td>{{ $key->tahun }}</td>
                        <td>{{ $key->bulan }}</td>
                        <td>{{ $key->numerator }}</td>
                        <td>{{ $key->denumerator }}</td>
                        <td>{{ round (($key->numerator/$key->denumerator) * 100,2) }}</td>
                        <td> ≤76,61%</td>
                        <td>
                            <!-- Tombol Edit -->
                            <a href="javascript:void(0);" 
                               class="edit-link" 
                               data-url="{{ route('edit.mutu', $key->id) }}">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <span class="mx-2 text-muted">|</span>

                            <!-- Tombol Hapus -->
                            <form id="delete-form-{{ $key->id }}" action="{{ route('hapus.mutu', $key->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="hapus-link btn btn-link p-0" data-id="{{ $key->id }}">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <footer class="main-footer">
        @include('template.footer')
    </footer>
</div>
</div>

@include('template.script_table')

<!-- SweetAlert2 Script -->
<script>
    // Pop-up konfirmasi untuk Hapus
    document.querySelectorAll('.hapus-link').forEach(function(button) {
        button.addEventListener('click', function () {
            let id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        });
    });

    // Pop-up konfirmasi untuk Edit
    document.querySelectorAll('.edit-link').forEach(function(button) {
        button.addEventListener('click', function () {
            let url = this.getAttribute('data-url');
            Swal.fire({
                title: 'Konfirmasi Edit',
                text: "Apakah Anda yakin ingin mengedit data ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Edit',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });

    // Tangkap tombol simpan
    document.getElementById('btn-simpan').addEventListener('click', function() {
        Swal.fire({
            title: 'Konfirmasi Simpan',
            text: 'Apakah Anda yakin ingin menyimpan data ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form jika user klik Ya
                this.closest('form').submit();
            }
        });
    });
</script>
</body>
</html>
@endsection