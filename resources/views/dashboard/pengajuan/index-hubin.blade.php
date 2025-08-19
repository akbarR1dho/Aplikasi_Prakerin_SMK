@extends('layouts.dashboard')

@section('title', 'Daftar Pengajuan - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header d-grid gap-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="m-0">Daftar Pengajuan</h3>

            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownPilihJurusan" data-bs-toggle="dropdown" aria-expanded="false">
                    Pilih Jurusan
                </button>
                <ul class="dropdown-menu overflow-auto" aria-labelledby="dropdownPilihJurusan" style="max-height: 200px;">
                    @foreach($data_jurusan as $j)
                    <li><button class="dropdown-item pilih-jurusan" type="button" data-jurusan="{{ $j->id }}">{{ $j->nama }}</button></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <x-flash-message />
    </div>


    <div class="card-body">
        <table class="table table-bordered" id="data-pengajuan">
            <thead>
                <tr>
                    <th>Id Pengajuan</th>
                    <th>Nama Siswa</th>
                    <th>Status</th>
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
        let jurusanSekarang = null;

        const table = $('#data-pengajuan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('pengajuan.index') }}",
                data: function(d) {
                    d.jurusan = jurusanSekarang;
                }
            },
            columns: [{
                    data: 'id_pengajuan',
                    name: 'id_pengajuan',
                },
                {
                    data: 'siswa.nama',
                    name: 'siswa.nama',
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        // Tentukan kelas CSS berdasarkan status
                        const status = data.toUpperCase();
                        let badgeClass = '';
                        switch (data.toLowerCase()) {
                            case 'disetujui':
                                badgeClass = 'bg-success';
                                break;
                            case 'ditolak':
                                badgeClass = 'bg-danger';
                                break;
                            default:
                                badgeClass = 'bg-warning';
                                break;
                        }

                        return `<span class="badge ${badgeClass}">${status}</span>`;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });


        // Handle dropdown tombol pilih jurusan
        $(document).on('click', '.pilih-jurusan', function(e) {
            e.preventDefault();
            const jurusanDipilih = $(this).data('jurusan');

            if (jurusanSekarang != jurusanDipilih) {
                jurusanSekarang = jurusanDipilih;

                // Hapus class active dari semua opsi
                $('.pilih-jurusan').removeClass('active');
                
                // Tambahkan class active ke opsi yang dipilih
                $(this).addClass('active');

                // Reload DataTables dengan filter baru
                table.ajax.reload();
            }

            // // Update teks tombol dropdown
            // $('#dropdownjurusanPengajuan').html(`Ubah jurusan (${jurusanSekarang})`);
        });

        $(document).on('click', '.btnSetuju', function() {
            const id = $(this).data('id');
            if (confirm('Yakin ingin menyetujui pengajuan ini?')) {
                axios.post('/pengajuan/setujui/' + id)
                    .then(function(response) {
                        alert(response.data.message);
                        $('#data-pengajuan').DataTable().ajax.reload();
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