@if (session()->has('success'))
<div class="alert alert-success" id="flash-message">
    {{ session('success') }}
</div>

@elseif (session()->has('error'))
<div class="alert alert-danger" id="flash-message">
    {{ session('error') }}
</div>

@elseif (session()->has('errors') && $errors->any())
<div class="alert alert-danger" id="flash-message">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>

@endif

<script>
    setTimeout(function() {
        document.getElementById('flash-message')?.remove();
    }, 5000);
</script>