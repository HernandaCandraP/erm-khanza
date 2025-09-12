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
            <a href="{{route('absensi.rekap')}}" class="nav-link {{ Request::is('absensi*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-clock"></i>
              <p>Riwayat Absensi</p>
            </a>
          </li>
          {{-- <li class="nav-item">
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
          </li> --}}
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

          <!-- apotek -->
          @if(Str::length(Auth::guard('pegawai')->user()) > 0)
          @if(Auth::guard('pegawai')->user()->level == "Apotek")
          <li class="nav-item">
            <a href="{{route('homeapotek')}}" class="nav-link {{ Request::is('apotek*') ? 'active' : '' }}">
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
              <a href="{{route('penilaianperawat')}}" class="nav-link {{ Request::is('admin/penilaianperawat') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-nurse"></i>
                <p>Penilaian Perawat</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('riwayatradiologi')}}" class="nav-link {{ Request::is('riwayatradiologi') ? 'active' : '' }}">
                <i class="nav-icon fas fa-x-ray"></i>
                <p>Riwayat Radiologi</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('persentasepoli')}}" class="nav-link {{ Request::is('persentasepoli') ? 'active' : '' }}">
                <i class="nav-icon fas fa-hospital-user"></i>
                <p>Persentase Poli</p>
              </a>
            </li>
            {{-- <li class="nav-item">
              <a href="{{route('persentaseranap')}}" class="nav-link {{ Request::is('persentaseranap') ? 'active' : '' }}">
                <i class="nav-icon fa fa-notes-medical"></i>
                <p>Persentase Ranap</p>
              </a>
            </li> --}}
            <li class="nav-item">
              <a href="{{route('pemesananobat')}}" class="nav-link {{ Request::is('admin/pemesananobat') ? 'active' : '' }}">
                <i class="nav-icon fas fa-capsules"></i>
                <p>Pemesanan Obat</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('admin-biayarajal')}}" class="nav-link {{ Request::is('admin/biayarajal') ? 'active' : '' }}">
                <i class="nav-icon fas fa-hourglass-half"></i>
                <p>Pembayaran Pasien</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('tutupbilling')}}" class="nav-link {{ Request::is('admin/tutupbilling') ? 'active' : '' }}">
                <i class="nav-icon fas fa-money-bill"></i>
                <p>Tutup Billing</p>
              </a>
            </li>
            @if(Auth::guard('pegawai')->user()->nik == "0108")
            <li class="nav-item">
              <a href="{{route('kelolalogin')}}" class="nav-link {{ Request::is('admin/kelolalogin') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tasks"></i>
                <p>Kelola Login</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('absensi.rekapadmin')}}" class="nav-link {{ Request::is('absensi*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>Pegawai</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('simrs.berkasdigital')}}" class="nav-link {{ Request::is('berkasdigital*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-book"></i>
                <p>Berkas Digital</p>
              </a>
            </li>
            <li class="nav-item {{ Request::is('erm/*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ Request::is('erm/*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>
                  Pengisian SIMRS
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('simrs.igd') }}" class="nav-link {{ Request::is('erm/pengisian-igd*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>ERM IGD</p>
                  </a>
                </li> 
                <li class="nav-item">
                  <a href="{{  route('simrs.rawatinap') }}" class="nav-link {{ Request::is('erm/pengisian-rawatinap*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>ERM Rawat Inap</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{  route('simrs.farmasi') }}" class="nav-link {{ Request::is('erm/pengisian-farmasi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>ERM Farmasi</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('simrs.gizi') }}" class="nav-link {{ Request::is('erm/pengisian-gizi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>ERM Gizi</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('indikatormutu.waktu_tunggu_rajal') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>ERM Laboratorium</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('simrs.operasi') }}" class="nav-link {{ Request::is('erm/pengisian-operasi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>ERM Operasi</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-envelope"></i>
              <p>
                Mutu
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('indikatormutu.kebersihan_tangan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kepatuhan Kebersihan Tangan</p>
                </a>
              </li> 
              <li class="nav-item">
                <a href="{{  route('indikatormutu.apd') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kepatuhan Penggunaan alat Pelindung diri (APD)</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{  route('indikatormutu.identifikasi_pasien') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kepatuhan Identifikasi Pasien</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('indikatormutu.seksio_emergensi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Waktu Tanggap Operasi Seksio Seksarea Emergensi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('indikatormutu.waktu_tunggu_rajal') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Waktu Tunggu Rawat Jalan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('indikatormutu.penundaan_operasi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Penundaan Operasi Elektif</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('indikatormutu.visite_dokter') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kepatuhan Waktu Visite Dokter</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('indikatormutu.hasil_kritis_lab') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pelaporan Hasil Kritis Laboratorium</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{  route('indikatormutu.formularium') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kepatuhan Penggunaan Formularium Nasional</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('indikatormutu.clinical_pathway') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kepatuhan terhadap Alur Klinis (Clinical Pathway)</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('indikatormutu.pencegahan_jatuh') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kepatuhan Upaya Pencegahan Resiko Pasien Jatuh</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('indikatormutu.tanggap_komplain') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kecepatan Waktu Tanggap Komplain</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('indikatormutu.kepuasan_pasien') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kepuasan Pasien</p>
                </a>
              </li>
            </ul>
            </li>
            @endif
            @if(Auth::guard('pegawai')->user()->nik == "D009")
            <li class="nav-item">
              <a href="{{route('tambahpasien')}}" class="nav-link {{ Request::is('tambahpasien*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-plus "></i>
                <p>Tambah Pasien</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('barcode')}}" class="nav-link {{ Request::is('barcode*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-credit-card"></i>
                <p>Barcode</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('cetakkartu')}}" class="nav-link {{ Request::is('cetakkartu*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-print"></i>
                <p>Cetak Kartu</p>
              </a>
            </li>
            @endif
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