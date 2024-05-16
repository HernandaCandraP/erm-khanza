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
          {{-- <li class="nav-item {{ Request::is('form-resume') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('form-resume') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Charts
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('form-resume')}}" class="nav-link {{ Request::is('form-resume') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Test link</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/flot.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Flot</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/inline.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inline</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/uplot.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>uPlot</p>
                </a>
              </li>
            </ul>
          </li> --}}
          {{-- <li class="nav-item">
            <a href="{{route('homeadmin/kelas')}}" class="nav-link {{ Request::is('admin/kelas') ? 'active' : '' }}">
              <i class="nav-icon fas fa-building"></i>
              <p>Kelas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('homeadmin/mapel')}}" class="nav-link {{ Request::is('admin/mapel') ? 'active' : '' }}">
              <i class="nav-icon fas fa-book"></i>
              <p>Mata Pelajaran</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-envelope"></i>
              <p>Mailbox<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
              <li class="nav-item">
                <a href="pages/mailbox/mailbox.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inbox</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/mailbox/compose.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Compose</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/mailbox/read-mail.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Read</p>
                </a>
              </li>
            </ul>
          </li> --}}
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
          </li>
          {{-- <li class="nav-item">
            <a href="{{route('homeadmin/guru')}}" class="nav-link {{ Request::is('admin/guru') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chalkboard-teacher"></i>
              <p>Guru</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('homeadmin/siswa')}}" class="nav-link {{ Request::is('admin/siswa') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>Siswa</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('homeadmin/kelas')}}" class="nav-link {{ Request::is('admin/kelas') ? 'active' : '' }}">
              <i class="nav-icon fas fa-building"></i>
              <p>Kelas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('homeadmin/mapel')}}" class="nav-link {{ Request::is('admin/mapel') ? 'active' : '' }}">
              <i class="nav-icon fas fa-book"></i>
              <p>Mata Pelajaran</p>
            </a>
          </li> --}}
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