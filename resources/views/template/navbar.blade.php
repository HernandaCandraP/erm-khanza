<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    @if(Auth::guard('siswa')->check() != null)
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-user-circle">
                {{ Auth::guard('siswa')->user()->username}}
            </i><i class="fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <div class="dropdown-divider"></div>
            <a href="{{route('homesiswa/profile')}}" class="dropdown-item">
                <i class="fas fa-info-circle mr-2"></i> Detail Profile
            </a>
            <div class="dropdown-divider"></div>
            <a href="{{route('logout')}}" class="dropdown-item">
                <i class="fas fa-power-off mr-2"></i> Logout
            </a>
        </div>
        </li>
    </ul>
    @elseif(Auth::guard('guru')->check() != null)
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-user-circle">
                {{ Auth::guard('guru')->user()->username}}
            </i> <i class="fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <div class="dropdown-divider"></div>
            @if(Auth::guard('guru')->user()->level == "Guru")
            <a href="{{route('homeguru/profile')}}" class="dropdown-item">
                <i class="fas fa-info-circle mr-2"></i> Detail Profile
            </a>
            @elseif(Auth::guard('guru')->user()->level == "Admin")
            <a href="{{route('homeadmin/profile')}}" class="dropdown-item">
                <i class="fas fa-info-circle mr-2"></i> Detail Profile
            </a>
            @endif
            <div class="dropdown-divider"></div>
            <a href="{{route('logout')}}" class="dropdown-item">
                <i class="fas fa-power-off mr-2"></i> Logout
            </a>
        </div>
        </li>
    </ul>
    @endif
</nav>