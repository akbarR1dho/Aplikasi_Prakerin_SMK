@extends('layouts.dashboard')

@section('header')
@vite('resources/js/app.js')
@endsection

@section('content')
<div class="card">
    <div class="card-header d-grid gap-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Daftar Guru</h3>
            <a href="{{ route('akun-guru.tambah') }}" class="btn btn-primary">Tambah Guru</a>
        </div>
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
    </div>


    <div class="card-body">
        <table class="table table-bordered data-table" id="data-guru">
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th width="100px">Action</th>
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

        $('#data-guru').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('akun-guru.index') }}",
            columns: [{
                    data: 'nip',
                    name: 'nip'
                },
                {
                    data: 'nama',
                    name: 'nama'
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

    });
</script>
@endsection