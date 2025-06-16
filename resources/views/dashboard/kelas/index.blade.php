@extends('layouts.dashboard')

@section('header')
@vite('resources/js/app.js')
@endsection

@section('content')
<div class="card">
    <div class="card-header d-grid gap-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="m-0">Daftar Kelas</h3>
            <div class="d-flex gap-2 d-lg-block d-none">
                <a
                    href="{{ route('kelas.form-tambah') }}"
                    class="btn btn-primary">
                    Tambah
                </a>
                <button
                    type="button" id="btnFilterData" class="filterData btn btn-info"
                    data-bs-toggle="modal" data-bs-target="#modalFilterData">
                    Filter
                </button>
            </div>

            <div class="d-lg-none dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/kelas/tambah">Tambah</a></li>
                    <li><a class="dropdown-item" id="btnFilterData" data-bs-toggle="modal" data-bs-target="#modalFilterData">Filter</a></li>
                </ul>
            </div>
        </div>

        <x-flash-message />
    </div>


    <div class="card-body">
        <table class="table table-bordered" id="data-kelas">
            <thead>
                <tr>
                    <th>Jurusan</th>
                    <th class="text-start">Tingkat</th>
                    <th>Kelompok</th>
                    <th class="text-start">Angkatan</th>
                    <th>Walas</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@include('dashboard.kelas.modal-filter-data')
@include('dashboard.kelas.modal-ganti-walas')

@endsection

@section('script')
<script src="{{ asset('js/select2-init-custom.js') }}"></script>
<script>
    $(document).ready(function() {
        const table = $('#data-kelas').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('kelas.index') }}",
                data: function(d) {
                    d.angkatan = $('#filter-angkatan').val();
                    d.tingkat = $('#filter-tingkat').val();
                    d.kelompok = $('#filter-kelompok').val();
                }
            },
            columns: [{
                    data: 'jurusan.nama',
                    name: 'jurusan.nama',
                },
                {
                    data: 'tingkat',
                    name: 'tingkat',
                    class: 'text-start',
                    searchable: false

                },
                {
                    data: 'kelompok',
                    name: 'kelompok',
                    searchable: false

                },
                {
                    data: 'angkatan',
                    name: 'angkatan',
                    class: 'text-start',
                    searchable: false

                },
                {
                    data: 'walas.nama',
                    name: 'walas.nama',
                    defaultContent: '-',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        // Event handler untuk tombol Terapkan Filter
        $('#btnTerapkanFilter').on('click', function() {
            // Menutup modal setelah filter diterapkan
            $('#modalFilterData').modal('hide');

            // Memaksa DataTable untuk me-refresh data berdasarkan filter yang dipilih
            table.draw();
        });

        select2Custom('#walasSelect', '/kelas/load-walas', '#modalGantiWalas');

        $(document).on('click', '#btnGantiWalas', function() {
            let id = $(this).data('id'); // Ambil ID dari tombol yang diklik

            $('#modalGantiWalas').modal('show');

            // Ambil data walas
            axios.get('/kelas/data-walas/' + id)
                .then(response => {
                    let data = response.data;

                    $('#id').val(data.id);
                    $('#currentWalas strong').text(data.walas.nama || 'Belum ditentukan');
                })
                .catch(error => {
                    alert(error.response.data.message);
                    console.error('Gagal mengambil data guru:', error);
                });
        });

        $(document).on('click', '#btnHapus', function() {
            const id = $(this).data('id');
            if (confirm('Yakin ingin menghapus data ini?') == true) {
                axios.delete('/kelas/' + id)
                    .then(function(response) {
                        alert(response.data.message);
                        $('#data-kelas').DataTable().ajax.reload();
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert(error.response.data.message);
                    })
            }
        });

        $('#formGantiWalas').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            const isConfirmed = confirm('Yakin ingin mengganti walas kelas ini?');

            if (isConfirmed) {
                axios.put('/kelas/ganti-walas', formData)
                    .then(response => {
                        alert(response.data.message);
                        $('#modalGantiWalas').modal('hide');
                        $('#data-kelas').DataTable().ajax.reload();
                    })
                    .catch(error => {
                        alert(error.response.data.message);
                        console.error('Gagal mengganti walas:', error);
                    });
            }
        });
    });
</script>
@endsection