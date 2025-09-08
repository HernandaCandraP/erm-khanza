<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    @include('template.navbar')
    @include('template.sidebar')

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header"></div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                
                <!-- Pesan sukses jika update berhasil -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Mutu Nasional</h3>
                    </div>
                    <div class="card-body">
                        <table id="mutuTable" class="table table-bordered table-striped">
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
                                @foreach($data as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $row->tahun }}</td>
                                        <td>{{ $row->bulan }}</td>
                                        <td>{{ $row->numerator }}</td>
                                        <td>{{ $row->denumerator }}</td>
                                        <td>
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('edit.mutu', ['id' => $row->id, 'redirect' => url()->current()]) }}" 
                                               class="btn btn-warning btn-sm">
                                                Edit
                                            </a>

                                            <!-- Tombol Hapus dengan konfirmasi -->
                                            <form action="{{ route('delete.mutu', $row->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class ="fas fa-trash">  </i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('template.footer')

</div>
<!-- ./wrapper -->

@include('template.script_table')
<script>
    $(function () {
        $('#mutuTable').DataTable();
    });
</script>

</body>
</html>