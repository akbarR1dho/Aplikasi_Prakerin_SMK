@extends('layouts.dashboard')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Tambah Guru</h3>

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    <div class="card-body">
        <form action="{{ route('akun-guru.tambah') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">NIP</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-id-card'></i></span>
                    <input
                        type="text"
                        class="form-control"
                        id="nip"
                        placeholder="Masukkan NIP"
                        name="nip" required value="{{ old('nip') }}" />
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Nama</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-user'></i></span>
                    <input
                        type="text"
                        class="form-control"
                        id="nama"
                        placeholder="Masukkan Nama"
                        name="nama" required value="{{ old('nama') }}" />
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Email</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        placeholder="Masukkan Email"
                        name="email" required value="{{ old('email') }}" />

                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">No. Telp</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-phone'></i></span>
                    <input
                        type="text"
                        class="form-control"
                        id="no_telp"
                        placeholder="Masukkan No. Telp"
                        name="no_telp" required value="{{ old('no_telp') }}" />

                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Jenis Kelamin</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-body'></i></span>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required value="{{ old('jenis_kelamin') }}">
                        <option selected disabled value>Pilih...</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
    </div>
</div>
@endsection