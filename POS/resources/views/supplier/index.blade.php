@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
            <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-info">Import Barang</button>
                <a href="{{ url('/stok/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Barang</a>
                <a href="{{ url('/stok/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Barang</a>
                <button onclick="modalAction('{{ url('/stok/create_ajax/') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Nama User</th>
                        <th>Tanggal Stok</th>
                        <th>Jumlah Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div> 
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data- backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div> 
@endsection

@push('css') 
@endpush

@push('js')
    <script>
        function modalAction(url = ''){
            $('#myModal').load(url,function(){
                $('#myModal').modal('show');
            });
        }
        
        $(document).ready(function() {
            var dataStok = $('#table_stok').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('stok/list') }}", 
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.user_id = $('#user_id').val(); // Filter berdasarkan user_id
                        d._token = "{{ csrf_token() }}"; // Tambahkan CSRF token
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false, 
                        searchable: false
                    },
                    {
                        data: "barang.barang_nama", // Mengambil nama barang dari relasi
                        className: "",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "user.nama", // Mengambil nama user dari relasi
                        className: "",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "stok_tanggal",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "stok_jumlah",
                        className: "",
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#user_id').on('change', function() {
                dataStok.ajax.reload();
            });
        });
    </script> 
@endpush