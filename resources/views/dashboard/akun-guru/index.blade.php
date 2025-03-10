@extends('layouts.dashboard')

@section('header')
@vite('resources/js/app.js')
@endsection

@section('content')
<div class="card">
    <div class="card-header d-grid gap-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="m-0">Daftar Guru</h3>
            <a href="{{ route('akun-guru.tambah') }}" class="btn btn-primary">Tambah</a>
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

@include('dashboard.akun-guru.modal-ganti-password')

@endsection

@section('script')
<script>
    $(document).ready(function() {

        $('#data-guru').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('akun-guru.index') }}",
            columns: [{
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'nip',
                    name: 'nip',
                    class: 'text-start',
                    defaultContent: '-'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $(document).on('click', '.delete', function() {
            let id = $(this).data('id');
            if (confirm('Yakin ingin menghapus data ini?')) {
                axios.delete('/akun-guru/' + id)
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

        $(document).on('click', '.btnGantiPassword', function() {
            let id = $(this).data('id');
            $('#modalForm').modal('show');
            $('#id').val(id);
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#form')[0].reset();
        });

        $('#form').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            axios.post('{{ route("akun-guru.ganti-password") }}', formData)
                .then(response => {
                    alert(response.data.message);
                    $('#modalForm').modal('hide');
                    $('#data-guru').DataTable().ajax.reload();
                })
                .catch(error => {
                    alert(error.response.data.message);
                    console.error('Gagal menambahkan jurusan:', error);
                })
        })

    });
</script>
@endsection