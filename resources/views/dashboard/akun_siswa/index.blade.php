@extends('layouts.dashboard')

@section('title', 'Daftar Siswa - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header d-grid gap-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="m-0">Daftar Siswa</h3>
            <div class="d-flex gap-2 d-none d-lg-block">
                <a href="/akun-siswa/tambah" class="btn btn-primary">Tambah</a>
                <a href="/akun-siswa/import" class="btn btn-primary">Import</a>
            </div>

            <div class="d-lg-none dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/akun-siswa/tambah">Tambah</a></li>
                    <li><a class="dropdown-item" href="/akun-siswa/import">Import</a></li>
                </ul>
            </div>
        </div>

        <x-flash-message />
    </div>


    <div class="card-body">
        <table class="table table-bordered" id="data-siswa">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Id Kelas</th>
                    <th>Email</th>
                    <th class="text-start">NIS</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {

        $('#data-siswa').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('akun-siswa.index') }}",
            columns: [{
                    data: 'nama',
                    name: 'nama',
                },
                {
                    data: 'kelas.id_kelas',
                    name: 'kelas.id_kelas',
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'nis',
                    name: 'nis',
                    class: 'text-start',
                    defaultContent: '-'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $(document).on('click', '#btnHapus', function() {
            const nis = $(this).data('id');

            if (confirm('Yakin ingin menghapus data ini?') == true) {
                axios.delete('/akun-siswa/' + nis)
                    .then(function(response) {
                        alert(response.data.message);
                        $('#data-siswa').DataTable().ajax.reload();
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert(error.response.data.message);
                    })
            }
        });

        $(document).on('click', '.btnResetPassword', function() {
            let nis = $(this).data('id');

            isConfirmed = confirm('Yakin ingin mereset password?');

            if (isConfirmed) {
                axios.post('/akun-siswa/reset-password/' + nis)
                    .then(function(response) {
                        alert(response.data.message);
                        $('#data-siswa').DataTable().ajax.reload();
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert(error.response.data.message);
                    })
            }
        });

    });
</script>
@endsection