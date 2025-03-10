@extends('layouts.dashboard')

@section('header')
@vite('resources/js/app.js')
@endsection

@section('content')
<div class="card">
    <div class="card-header d-grid gap-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Daftar Jurusan</h3>
            <button
                type="button"
                id="btnTambah"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modalFormTambah">
                Tambah
            </button>
        </div>

        <x-flash-message />
    </div>


    <div class="card-body">
        <table class="table table-bordered" id="data-guru">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kaprog</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@include('dashboard.jurusan.modal-form')

@endsection

@section('script')
<script>
    $(document).ready(function() {

        $('#data-guru').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('jurusan.index') }}",
            columns: [{
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'kaprog.nama',
                    name: 'kaprog.nama',
                    defaultContent: '-',
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#btnTambah').click(function() {
            $('#modalForm').modal('show');

            // Ambil data guru
            axios.get('{{ route("jurusan.get-guru") }}')
                .then(response => {
                    let dataGuru = response.data;
                    let $guruSelect = $('#guruSelect');

                    // Kosongkan select sebelum diisi ulang
                    $guruSelect.empty().append('<option selected disabled>Pilih Guru</option>');

                    // Isi select dengan data guru
                    dataGuru.forEach(guru => {
                        $guruSelect.append(`<option value="${guru.id}">${guru.nama}</option>`);
                    })
                })
                .catch(error => {
                    console.error('Gagal mengambil data guru:', error);
                });
        })

        $(document).on('click', '#btnEdit', function() {
            let id = $(this).data('id'); // Ambil ID dari tombol yang diklik

            $('#modalForm').modal('show');

            // Ambil data guru
            axios.get('/jurusan/get-data/' + id)
                .then(response => {
                    let data = response.data;
                    let $guruSelect = $('#guruSelect');

                    // Kosongkan select sebelum diisi ulang
                    $guruSelect.empty().append('<option selected disabled>Pilih Guru</option>');

                    // Isi select dengan data guru
                    data.guru.forEach(guru => {
                        $guruSelect.append(`<option value="${guru.id}">${guru.nama}</option>`);
                    });

                    // Isi form dengan data jurusan 
                    $('#id').val(data.id);
                    $('#nama').val(data.nama);
                })
                .catch(error => {
                    alert(error.response.data.message);
                    console.error('Gagal mengambil data guru:', error);
                });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#id').val('');
            $('#nama').val('');
        });

        $('#form').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            axios.post('{{ route("jurusan.simpan") }}', formData)
                .then(response => {
                    alert(response.data.message);
                    $('#modalForm').modal('hide');
                    $('#data-guru').DataTable().ajax.reload();
                })
                .catch(error => {
                    alert(error.response.data.message);
                    console.error('Gagal menambahkan jurusan:', error);
                });
        });

        $(document).on('click', '.delete', function() {
            let id = $(this).data('id');
            if (confirm('Yakin ingin menghapus data ini?')) {
                axios.delete('/jurusan/' + id)
                    .then(function(response) {
                        alert(response.data.message);
                        $('#data-guru').DataTable().ajax.reload();
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