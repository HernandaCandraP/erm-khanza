<script src="{{asset('tampilan/styles2.js')}}"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $("#example3").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "pageLength": 15
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $("#example4").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "pageLength": 15
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $("#example").dataTable().fnDestroy()
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>