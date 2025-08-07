@extends('layouts.dashboard')

@section('title', 'Form Edit Siswa - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Edit Siswa</h3>

        <x-flash-message />
    </div>

    <div class="card-body">
        <form action="{{ route('akun-siswa.edit', $siswa->nis) }}" method="POST" id="formEditSiswa">
            @csrf
            @method('PUT')

            <div class="container-fluid p-0">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">NIS</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-user-id-card'></i></span>
                            <input
                                type="text"
                                class="form-control"
                                id="nis"
                                placeholder="Masukkan NIS"
                                name="nis" value="{{ old('nis') ? old('nis') : $siswa->nis }}" required />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">NISN</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-user-id-card'></i></span>
                            <input
                                type="text"
                                class="form-control"
                                id="nisn"
                                placeholder="Masukkan NISN"
                                name="nisn" value="{{ old('nisn') ? old('nisn') : $siswa->nisn }}" required />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="kelas">Kelas</label>
                        <small class="ms-2 text-muted">Kelas saat ini: <strong>{{ $siswa->kelas->id_kelas }}</strong></small>
                        <div class="d-flex">
                            <span class="input-group-text"><i class='bx bx-reading'></i></span>
                            <div class="d-flex align-items-center border px-1 py-0" style="height: 38.95px; width: 100%;">
                                <select name="id_kelas" id="kelas" class="form-select border-0" style="box-shadow: none;"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">Nama</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-user'></i></span>
                            <input
                                type="text"
                                class="form-control"
                                id="nama"
                                placeholder="Masukkan Nama"
                                name="nama" value="{{ old('nama') ? old('nama') : $siswa->nama }}" required />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">Username</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-user'></i></span>
                            <input
                                type="text"
                                class="form-control"
                                id="username"
                                placeholder="Masukkan Username"
                                name="username" value="{{ old('email') ? old('email') : $siswa->akun->username }}" />

                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">Email</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-envelope-alt'></i></span>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                placeholder="Masukkan Email"
                                name="email" value="{{ old('email') ? old('email') : $siswa->akun->email }}" />

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
                                name="no_telp" value="{{ old('no_telp') ? old('no_telp') : $siswa->no_telp }}" required />

                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">Jenis Kelamin</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-man-woman'></i></span>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
                                <option selected disabled value>Pilih...</option>
                                <option value="L" {{ $siswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $siswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">Tempat Lahir</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-location-alt-2'></i></span>
                            <input
                                type="text"
                                class="form-control"
                                id="tempat_lahir"
                                placeholder="Masukkan Tempat Lahir"
                                name="tempat_lahir" value="{{ old('tempat_lahir') ? old('tempat_lahir') : $siswa->tempat_lahir }}" required />
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
                                name="tanggal_lahir" value="{{ old('tanggal_lahir') ? old('tanggal_lahir') : $siswa->tanggal_lahir }}" required />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <label class="form-label" for="basic-icon-default-fullname">Tahun Masuk</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class='bx bx-calendar-plus'></i></span>
                            <input
                                type="number"
                                class="form-control yearpicker"
                                id="tahun_masuk"
                                placeholder="Masukkan Tahun Masuk"
                                name="tahun_masuk" value="{{ old('tahun_masuk', date('Y')) ? old('tahun_masuk', date('Y')) : $siswa->tahun_masuk }}"
                                required />
                        </div>
                    </div>
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
                        placeholder="Masukkan Alamat" required>{{ old('alamat') ? old('alamat') : $siswa->alamat }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ URL::previous() !== URL::current() ? URL::previous() : route('akun-siswa.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#formEditSiswa').submit(function(e) {
            e.preventDefault();

            const isConfirmed = confirm('Yakin ingin merubah data ini?');
            if (isConfirmed) {
                this.submit();
            }
        });

        select2Custom('#kelas', '/akun-siswa/load-kelas', 'id_kelas', 'Pilih Kelas...');
    })
</script>
@endsection