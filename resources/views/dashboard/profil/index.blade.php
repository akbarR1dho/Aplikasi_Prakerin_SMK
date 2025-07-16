@extends('layouts.dashboard')

@section('title', 'Profil - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Profil</h2>

        <x-flash-message />
    </div>

    <div class="card-body">
        <form action="{{ route('profil.edit') }}" method="POST" id="formEditProfil">
            @csrf
            @method('PUT')

            <div class="container-fluid p-0">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">Nama</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-user'></i></span>
                            <input
                                type="text"
                                class="form-control"
                                id="nama"
                                placeholder="Masukkan Nama"
                                name="nama" value="{{ old('nama') ? old('nama') : $data->nama }}"
                                required />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">Username</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-user'></i></span>
                            <input
                                type="text"
                                class="form-control"
                                id="nama"
                                placeholder="Masukkan Username"
                                name="username" value="{{ old('username') ? old('username') : $user->username }}"
                                required />
                        </div>
                    </div>
                    @if ($data->nip != null && $user->role == 'hubin')
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">NIP</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-user-id-card'></i></span>
                            <input
                                type="text"
                                class="form-control"
                                id="nip"
                                placeholder="Masukkan NIP"
                                name="nip" value="{{ old('nip') ? old('nip') : $data->nip }}"
                                required />
                        </div>
                    </div>
                    @endif
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">Email</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-envelope-alt'></i></span>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                placeholder="Masukkan Email"
                                required
                                name="email" value="{{ old('email') ? old('email') : $user->email }}" />

                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">No. Telp</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-phone'></i></span>
                            <input
                                type="text"
                                class="form-control"
                                id="no_telp"
                                placeholder="Masukkan No. Telp"
                                required
                                name="no_telp" value="{{ old('no_telp') ? old('no_telp') : $data->no_telp }}" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">Jenis Kelamin</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-man-woman'></i></span>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                                <option selected disabled value>Pilih...</option>
                                <option value="L" @if (old('jenis_kelamin')=='L' || $data->jenis_kelamin == 'L') selected @endif>Laki-laki</option>
                                <option value="P" @if (old('jenis_kelamin')=='P' || $data->jenis_kelamin == 'P') selected @endif>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    @if ($data->tempat_lahir != null && $data->tanggal_lahir != null && $user->role == 'siswa')
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">Tempat Lahir</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-location-alt-2'></i></span>
                            <input
                                type="text"
                                class="form-control"
                                id="tempat_lahir"
                                placeholder="Masukkan Tempat Lahir"
                                name="tempat_lahir" value="{{ old('tempat_lahir') ? old('tempat_lahir') : $data->tempat_lahir }}" required />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">Tanggal Lahir</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-calendar-alt'></i></span>
                            <input
                                type="date"
                                class="form-control"
                                id="tanggal_lahir"
                                placeholder="Masukkan Tanggal Lahir"
                                name="tanggal_lahir" value="{{ old('tanggal_lahir') ? old('tanggal_lahir') : $data->tanggal_lahir }}" required />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-fullname">Alamat</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-map'></i></span>
                            <textarea
                                name="alamat"
                                id="alamat"
                                cols="30"
                                rows="4"
                                class="form-control"
                                placeholder="Masukkan Alamat" required>{{ old('alamat') ? old('alamat') : $data->alamat }}</textarea>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('home') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#formEditProfil').submit(function(e) {
            e.preventDefault();

            const isConfirmed = confirm('Yakin ingin ubah data profil?');
            if (isConfirmed) {
                this.submit();
            }
        });
    })
</script>
@endsection