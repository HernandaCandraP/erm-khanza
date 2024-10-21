<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link bg-black">
      <img src="{{asset('image/logo-hst-kecil.png')}}" alt="RSBA" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text bold font-weight-light">SIMRS BUDI ASIH</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <!-- Petugas -->
          @if(Str::length(Auth::guard('pegawai')->user()) > 0)
          @if(Auth::guard('pegawai')->user()->level == "Petugas")
          <li class="nav-item menu-open">
            <a href="{{route('homepegawai')}}" class="nav-link {{ Request::is('pegawai') ? 'active' : '' }}">
              <i class="nav-icon fas fa-home"></i>
              <p>Pegawai</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('antrean')}}" class="nav-link {{ Request::is('antrean*') ? 'active' : '' }}">
              <i class="nav-icon  fas fa-laptop-house"></i>
              <p>Antrean</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('menu-ranap')}}" class="nav-link {{ Request::is('menu-ranap*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-stethoscope"></i>
              <p>Poli</p>
            </a>
          </li>
          @endif
          @endif
          <!-- End Petugas -->

          <!-- dokter -->
          @if(Str::length(Auth::guard('pegawai')->user()) > 0)
          @if(Auth::guard('pegawai')->user()->level == "Dokter")
          <li class="nav-item menu-open">
            <a href="{{route('homedokter')}}" class="nav-link {{ Request::is('dokter') ? 'active' : '' }}">
              <i class="nav-icon fas fa-hospital"></i>
              <p>Beranda</p>
            </a>
          </li>
          @if(Auth::guard('pegawai')->user()->nik == "D023" || Auth::guard('pegawai')->user()->nik == "0110")
          <li class="nav-item">
            <a href="{{route('menu-radiologi')}}" class="nav-link {{ Request::is('menu-radiologi*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-medical"></i>
              <p>Radiologi</p>
            </a>
          </li>
          @endif
          <li class="nav-item">
            <a href="{{route('menu-rajal')}}" class="nav-link {{ Request::is('form*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-book-medical"></i>
              <p>Rawat Jalan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('menu-ranap')}}" class="nav-link {{ Request::is('menu-ranap*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-medical"></i>
              <p>Rawat Inap</p>
            </a>
          </li>
          @endif
          @endif
          <!-- End dokter -->

          <hr>
          <!-- Admin -->
          @if(Str::length(Auth::guard('pegawai')->user()) > 0)
          @if(Auth::guard('pegawai')->user()->level == "Admin")
          <li class="nav-item menu-open">
            <a href="{{route('homeadmin')}}" class="nav-link {{ Request::is('admin') ? 'active' : '' }}">
              <i class="nav-icon fas fa-home"></i>
              <p>Admin</p>
            </a>
            <li class="nav-item">
              <a href="{{route('riwayatradiologi')}}" class="nav-link {{ Request::is('riwayatradiologi') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file"></i>
                <p>Riwayat Radiologi</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('persentasepoli')}}" class="nav-link {{ Request::is('persentasepoli') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file"></i>
                <p>Persentase Poli</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('kelolalogin')}}" class="nav-link {{ Request::is('admin/kelolalogin') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tasks"></i>
                <p>Kelola Login</p>
              </a>
            </li>
          @endif
          @endif
          <!-- End Guru -->
  
          <br>
          @if(Auth::guard('pegawai')->check() != null)
          <li class="nav-item">
            <a class="dropdown-item" href="{{ route('logout') }}" 
                onclick="event.preventDefault();                                              
                document.getElementById('logout-form').submit();">                                 
                {{ __('Logout') }}                             
            </a>                             
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">             
              @csrf
            </form>
          </li>
          @else
          <li class="nav-item">
            <a href="{{route('welcome')}}" class="nav-link text-light">
              <i class="nav-icon fa fa-home"></i>
              <p>
                Menu Utama
              </p>
            </a>
          </li><br>
          <li class="nav-item">
            <a href="{{route('login')}}" class="nav-link btn btn-sm btn-info text-light">
              <i class="nav-icon fa fa-sign-in-alt"></i>
              <p>
                Login
              </p>
            </a>
          </li>
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>