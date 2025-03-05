<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container py-4">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <h1>Selamat Datang di Dashboard Hubin</h1>
        <button class="btn btn-danger" id="logoutButton">Logout</button>
    </div>
</body>

</html>

<script>
    document.getElementById("logoutButton").addEventListener("click", function() {
        fetch("{{ route('logout') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            }
        }).then(response => {
            if (response.ok) {
                window.location.href = "/login"; // Redirect ke halaman login setelah logout
            }
        }).catch(error => console.error("Logout error:", error));
    });
</script>