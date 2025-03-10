@extends('layouts.dashboard')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Pengaturan Aplikasi</h2>

        <x-flash-message />
    </div>

    <div class="card-body">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="app_name" class="form-label">Nama Aplikasi</label>
                <input type="text" id="app_name" name="app_name" class="form-control" value="{{ $app_name }}" required>
            </div>

            <div class="mb-3">
                <label for="app_icon" class="form-label">Logo Aplikasi</label>
                <div class="my-2">
                    <img src="{{ asset($app_icon) }}" alt="App Icon" width="100">
                </div>
                <input type="file" id="app_icon" name="app_icon" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection