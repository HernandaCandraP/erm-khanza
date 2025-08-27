<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

@include('template.navbar')

@include('template.sidebar')


@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Form Kepatuhan Identifikasi Pasien</h4>
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
            <label>Tahun</label>
            <input type="number" name="tahun" class="form-control" min="2020" max="2100" value="{{ old('tahun', date('Y')) }}" required>
        </div>
        <label>Bulan</label>
            <select name="bulan" class="form-control" required>
                <option value="">-- Pilih Bulan --</option>
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bulan)
                    <option value="{{ $bulan }}" {{ old('bulan') == $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
                @endforeach
            </select>
        <div class="form-group">
            <label>Numerator</label>
            <input type="numerator" name="numerator" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Denumerator</label>
            <input type="denumerator" name="denumerator" class="form-control" required>
        </div>

        <input type="text" name="detail_mutu" value="identifikasi-pasien" class="form-control" hidden>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>

    <div class="card-body">
                <table id="example3" class="table table-bordered table-striped">
                    <thead>
                  <tr>
                    <th>No</th>
                    <th>Tahun</th>
                    <th>Bulan</th>
                    <th>Numerator</th>
                    <th>Denumerator</th>
                    <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($datamutu as $no => $key)
                        <tr>
                            <th scope="row"> {{$no+1}}. </th>
                            <th scope="row"> {{$key->tahun}}</th>
                            <th scope="row"> {{$key->bulan}}</th>
                            <th scope="row"> {{$key->numerator}}</th>
                            <th scope="row"> {{$key->denumerator}}</th>
                            <th><a href="{{route('edit.mutu', $key->id) }}">Edit</a>
				                <a href="{{route('hapus.mutu' , $key->id)}}">Hapus</a>
                            </th> 
                        </tr> 
                    @endforeach
                  
                  </tbody>
                </table>
    </div>

</div>
  <footer class="main-footer">
  @include('template.footer')
  </footer>
</div>
<!-- ./wrapper -->

<!-- SCRIPTS -->
@include('template.script_table')
</body>
</html>
@endsection