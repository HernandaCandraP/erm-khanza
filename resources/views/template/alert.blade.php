@if($message = Session::get('success'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <div><i class="icon fas fa-check"></i> {{$message}}</div>
</div>
@elseif($message = Session::get('error'))
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <div><i class="icon fas fa-times"></i> {{$message}}</div>
</div>
@elseif($message = Session::get('warning'))
<div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <div><i class="icon fas fa-check"></i> {{$message}}</div>
</div>
@endif
<script>
    setTimeout(function(){
      $("div.alert").remove();
    }, 5000);
</script>