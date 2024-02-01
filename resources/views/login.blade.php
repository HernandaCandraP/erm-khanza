
<!DOCTYPE html>
<html lang="en">
<head>
  @include('template.head')
</head>
<body class="hold-transition login-page" style='background-color:#98d9a3'>
  <div class="card-header text-center">
    <img src="{{asset('image/logo-hst.png')}}" class="pb-2 img-fluid" width="150px" />
    <p class="h4">RUMAH SAKIT BUDI ASIH</p>
    <p class="login-box-msg">JL MAYJEN SUNGKONO NO 80 - TRENGGALEK</p>
  </div>
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-body">
      <p class="login-box-msg">Silahkan login dulu untuk memulai</p>
      @error('msg')<p class="text-danger">{{$message}}</p>@enderror
      <form action="{{route('postlogin')}}" method="post">
      {{ csrf_field() }}
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" autocomplete="off" placeholder="Username" value="{{ old('username') }}">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        @error('username')<p class="text-danger">{{$message}}</p>@enderror
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        @error('password')<p class="text-danger">{{$message}}</p>@enderror
        <div class="row">
          <div class="col-7">
          </div>
          <div class="col-5">
            <button type="submit" class="btn btn-primary btn-block">Login</button>
          </div>
        </div>
      </form>
      <!-- /.social-auth-links -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

@include('template.script')
</body>
</html>
