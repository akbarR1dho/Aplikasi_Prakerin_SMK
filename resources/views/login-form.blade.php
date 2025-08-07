<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset($pengaturan['app_icon']) }}" />
    <title>Login - {{ $pengaturan['app_name'] }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <section class="bg-light vh-100">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <div class="card border border-light-subtle rounded-4">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="text-center mb-3">
                                <img src="{{ asset($pengaturan['app_icon']) }}" alt="Logo" width="100" height="95">
                            </div>
                            <p class="text-center mb-4">Silahkan Masuk</p>

                            <!-- Alert Message -->
                            <x-flash-message />

                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                class="form-control"
                                                name="login"
                                                id="login"
                                                oninput="if(!this.value.includes('@')) this.value = this.value.toLowerCase().replace(/[^a-z0-9\_.]/g, '')"
                                                placeholder="Username atau Email"
                                                required value="{{ old('login') }}">
                                            <label for="login" class="form-label">Username/Email</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3 position-relative">
                                            <input
                                                type="password"
                                                class="form-control password-input"
                                                name="password"
                                                id="password"
                                                value="{{ old('password') }}"
                                                placeholder="Password"
                                                required>
                                            <label for="password">Password</label>
                                            <span class="position-absolute top-50 end-0 translate-middle-y pe-3 toggle-password" style="cursor: pointer">
                                                <i class="bi bi-eye-slash-fill"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-primary btn-lg" type="submit">Masuk</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

<script>
    document.querySelector('.toggle-password').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');

        // Toggle password visibility
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle icon
        icon.classList.toggle('bi-eye-slash-fill');
        icon.classList.toggle('bi-eye-fill');
    });
</script>

</html>