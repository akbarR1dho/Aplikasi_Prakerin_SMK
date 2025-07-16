<!DOCTYPE html>
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title', 'Dashboard')</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset($pengaturan['app_icon']) }}" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/fonts/basic/boxicons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/fonts/basic/transformations.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/fonts/brands/boxicons-brands.min.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('dashboard/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('dashboard/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('dashboard/js/config.js') }}"></script>

    @vite(['resources/js/app.js', 'resources/js/libs/select2-custom.js'])
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Sidebar -->

            @include('layouts.sidebar')
            <!-- / Sidebar -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                @include('layouts.navbar')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assetsvdashboard/endor/js/core.js -->
    <script src="{{ asset('dashboard/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('dashboard/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('dashboard/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('dashboard/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('dashboard/js/dashboards-analytics.js') }}"></script>

    @yield('script')

    <script>
        document.getElementById("logoutButton").addEventListener("click", function() {
            isConfirmed = confirm("Apakah anda yakin ingin keluar?");

            if (isConfirmed) {
                fetch("{{ route('logout') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    }
                }).then(response => {
                    if (response.ok) {
                        alert("Logout berhasil");
                        window.location.href = "/login"; // Redirect ke halaman login setelah logout
                    }
                }).catch(error => alert("Logout gagal"));
            }
        });
    </script>
</body>

</html>