<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/home" class="app-brand-link">
            <span class="app-brand-logo demo me-2">
                <img src="{{ asset($pengaturan['app_icon']) }}" alt="Logo" width="35" height="35">
            </span>
            <span class="menu-text fw-bolder m-0 h5">{{ $pengaturan['app_name'] }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->is('home') ? 'active' : '' }}">
            <a href="/home" class="menu-link">
                <i class="menu-icon tf-icons bx bx-dashboard"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        @if($role == 'hubin')
        <!-- Inventory -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Inventory</span>
        </li>

        <!-- Daftar Akun -->
        <li class="menu-item {{ request()->is('akun-guru', 'akun-siswa', 'akun-industri') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon bx bx-group'></i>
                <div data-i18n="Account Settings">Daftar Akun</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('akun-guru') ? 'active' : '' }}">
                    <a href="/akun-guru" class="menu-link">
                        <div data-i18n="Account">Akun Guru</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('akun-siswa') ? 'active' : '' }}">
                    <a href="/akun-siswa" class="menu-link">
                        <div data-i18n="Notifications">Akun Siswa</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('akun-industri') ? 'active' : '' }}">
                    <a href="/akun-industri" class="menu-link">
                        <div data-i18n="Connections">Akun Pembimbing Industri</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->is('kelas', 'jurusan') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-whiteboard-alt"></i>
                <div data-i18n="Authentications">Daftar Kelas</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('kelas') ? 'active' : '' }}">
                    <a href="/kelas" class="menu-link">
                        <div data-i18n="Basic">Kelas</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('jurusan') ? 'active' : '' }}">
                    <a href="/jurusan" class="menu-link">
                        <div data-i18n="Basic">Jurusan</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif
    </ul>
</aside>