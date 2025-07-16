<nav
    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('dashboard/img/avatars/' . ($jenis_kelamin == 'L' ? 'L.png' : 'P.png')) }}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="/profil">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('dashboard/img/avatars/' . ($jenis_kelamin == 'L' ? 'L.png' : 'P.png')) }}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ $nama }}</span>
                                    <small class="text-muted">{{ $user->role }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    <li>
                        <a class="dropdown-item cursor-pointer" href="/profil">
                            <i class='bx bx-user-hexagon bx-fw'></i>
                            <span>Profil</span>
                        </a>
                    </li>

                    @if($user->role == 'hubin')
                    <li>
                        <a class="dropdown-item cursor-pointer" href="/pengaturan">
                            <i class="bx bx-cog bx-fw"></i>
                            <span>Pengaturan</span>
                        </a>
                    </li>
                    @endif

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item cursor-pointer" id="logoutButton">
                            <i class='bx bx-arrow-out-left-square-half bx-fw'></i>
                            <span>Keluar</span>
                        </button>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>