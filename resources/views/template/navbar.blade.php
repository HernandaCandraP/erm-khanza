<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    @if(Auth::guard('pegawai')->check() != null)
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-user-circle">
                {{ Auth::guard('pegawai')->user()->nama}}
            </i> <i class="fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <div class="dropdown-divider"></div>
            @if(Auth::guard('pegawai')->user()->level == "Petugas")
            <a href="{{route('homepegawai/profile')}}" class="dropdown-item">
                <i class="fas fa-info-circle mr-2"></i> Detail Profile
            </a>
            @elseif(Auth::guard('pegawai')->user()->level == "Dokter")
            <a href="{{route('homedokter/profile')}}" class="dropdown-item">
                <i class="fas fa-info-circle mr-2"></i> Detail Profile
            </a>
            @elseif(Auth::guard('pegawai')->user()->level == "Admin")
            <a href="{{route('homeadmin/profile')}}" class="dropdown-item">
                <i class="fas fa-info-circle mr-2"></i> Detail Profile
            </a>
            @endif
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('logout') }}" 
                onclick="event.preventDefault();                                              
                document.getElementById('logout-form').submit();">                                 
                {{ __('Logout') }}                             
            </a>                             
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">             
              @csrf
            </form>
        </div>
        </li>
    </ul>
    @endif
</nav>