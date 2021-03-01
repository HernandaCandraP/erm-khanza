<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{asset('tampilan/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Ujian Esai</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Siswa -->
          @if(Str::length(Auth::guard('siswa')->user()) > 0)
          @if(Auth::guard('siswa')->user()->level == "Siswa")
          <li class="nav-item menu-open">
            <a href="{{route('homesiswa')}}" class="nav-link {{ Request::is('siswa') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Home</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('homesiswa/detailmapel')}}" class="nav-link {{ Request::is('siswa/detailmapel') ? 'active' : '' }}">
              <i class="nav-icon far fa-image"></i>
              <p>Kategori Soal</p>
            </a>
          </li>
          @endif
          @endif
          <!-- End Siswa -->

          <!-- Guru -->
          @if(Str::length(Auth::guard('guru')->user()) > 0)
          @if(Auth::guard('guru')->user()->level == "Guru")
          <li class="nav-item menu-open">
            <a href="{{route('homeguru')}}" class="nav-link {{ Request::is('guru') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Home</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('homeguru/detailmapel')}}" class="nav-link {{ Request::is('guru/detailmapel') ? 'active' : '' }}">
              <i class="nav-icon far fa-image"></i>
              <p>Kategori Soal</p>
            </a>
          </li>
          <li class="nav-item">
          <a href="{{route('homeguru/kelas')}}" class="nav-link {{ Request::is('guru/kelas') ? 'active' : '' }}">
              <i class="nav-icon fas fa-columns"></i>
              <p>Kelas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('homeguru/siswa')}}" class="nav-link {{ Request::is('guru/siswa') ? 'active' : '' }}">
              <i class="nav-icon fas fa-columns"></i>
              <p>Siswa</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-columns"></i>
              <p>Lihat Nilai</p>
            </a>
          </li>
          @endif
          @endif
          <!-- End Guru -->

          <!-- Admin -->
          @if(Str::length(Auth::guard('guru')->user()) > 0)
          @if(Auth::guard('guru')->user()->level == "Admin")
          <li class="nav-item menu-open">
            <a href="{{route('homeadmin')}}" class="nav-link {{ Request::is('admin') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Home</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('homeadmin/guru')}}" class="nav-link {{ Request::is('admin/guru') ? 'active' : '' }}">
              <i class="nav-icon far fa-image"></i>
              <p>Guru</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('homeadmin/siswa')}}" class="nav-link {{ Request::is('admin/siswa') ? 'active' : '' }}">
              <i class="nav-icon fas fa-columns"></i>
              <p>Siswa</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('homeadmin/kelas')}}" class="nav-link {{ Request::is('admin/kelas') ? 'active' : '' }}">
              <i class="nav-icon fas fa-columns"></i>
              <p>Kelas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('homeadmin/mapel')}}" class="nav-link {{ Request::is('admin/mapel') ? 'active' : '' }}">
              <i class="nav-icon fas fa-columns"></i>
              <p>Mata Pelajaran</p>
            </a>
          </li>
          @endif
          @endif
          <!-- End Guru -->
  
          <br>
          <li class="nav-item">
            <a href="{{route('logout')}}" class="nav-link btn btn-sm btn-danger">
              <i class="nav-icon fas fa-power-off"></i>
              <p>
                Logout
                <!-- <span class="right badge badge-danger">New</span> -->
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>