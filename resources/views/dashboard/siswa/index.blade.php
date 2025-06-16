@extends('layouts.dashboard')

@section('header')
@vite('resources/js/app.js')
@endsection

@section('content')
<div class="card">
    <div class="card-header d-grid gap-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="m-0">Daftar Guru</h3>
            <div class="d-flex gap-2 d-none d-lg-block">
                <a href="/siswa/tambah" class="btn btn-primary">Tambah</a>
                <a href="/siswa/import" class="btn btn-primary">Import</a>
            </div>

            <div class="d-lg-none dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/akun-guru/tambah">Tambah</a></li>
                    <li><a class="dropdown-item" href="/akun-guru/import">Import</a></li>
                </ul>
            </div>
        </div>

        <x-flash-message />
    </div>


    <div class="card-body">
        <table class="table table-bordered" id="data-guru">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th class="text-start">NIP</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection