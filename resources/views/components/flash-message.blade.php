@if (session()->has('success'))
<div class="alert alert-success" id="flash-message">
    {{ session('success') }}
</div>

@elseif (session()->has('error'))
<div class="alert alert-danger" id="flash-message">
    {{ session('error') }}
</div>

@elseif (session()->has('warning'))
<div class="alert alert-warning" id="flash-message">
    {{ session('warning') }}
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

@if (session()->has('import_errors'))
<div class="alert alert-danger">
    <ul class="mb-0" id="import-error-list">
        @foreach (session('import_errors') as $index => $error)
        <li class="import-error-item {{ $index >= 20 ? 'd-none' : '' }}">{{ $error }}</li>
        @endforeach
    </ul>

    @if (count(session('import_errors')) > 20)
    <button type="button" class="btn btn-sm btn-link mt-2" id="show-more-errors">Lihat Selengkapnya</button>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showMoreBtn = document.getElementById('show-more-errors');
        const items = document.querySelectorAll('.import-error-item');
        let visibleCount = 20;

        showMoreBtn?.addEventListener('click', function() {
            const nextVisible = visibleCount + 20;
            for (let i = visibleCount; i < nextVisible && i < items.length; i++) {
                items[i].classList.remove('d-none');
            }
            visibleCount = nextVisible;

            if (visibleCount >= items.length) {
                showMoreBtn.remove(); // Sembunyikan tombol jika semua item sudah terlihat
            }
        });
    });
</script>
@endif

<script>
    setTimeout(function() {
        document.getElementById('flash-message')?.remove();
    }, 5000);
</script>