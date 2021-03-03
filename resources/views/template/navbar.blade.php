<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    @if(Auth::guard('siswa')->check() != null || Auth::guard('guru')->check() != null)
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell">
                @if( Auth::guard('siswa')->check() != null)
                    {{ Auth::guard('siswa')->user()->username}}
                @elseif( Auth::guard('guru')->check() != null)
                    {{ Auth::guard('guru')->user()->username}}
                @endif
            </i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
                <i class="fas fa-envelope mr-2"></i> Detail Profile
            </a>
            <div class="dropdown-divider"></div>
            <a href="{{route('logout')}}" class="dropdown-item">
                <i class="fas fa-users mr-2"></i> Logout
            </a>
        </div>
        </li>
    </ul>
    @endif
</nav>