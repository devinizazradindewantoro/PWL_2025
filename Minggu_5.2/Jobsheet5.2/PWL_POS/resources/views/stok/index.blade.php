@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('stok/create') }}">Tambah</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="stok_id" name="stok_id" required>
                            <option value="">- Semua Stok -</option>
                            @foreach ($stok as $item)
                            <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Pilih Stok</small>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jumlah Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@push('css')
@endpush
@push('js')
<script>
    $(document).ready(function() {
        var dataStok = $('#table_stok').DataTable({
            // jika ingin menggunakan server side processing serverSide: true
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{{ url('stok/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    d.level_id = $('#level_id').val();
                }
            },
            columns: [{
                // nomor urut dari laravel datatable addIndexColumn() 
                data: "DT_RowIndex",
                className: "text-center",
                orderable: false,
                searchable: false
            }, {
                data: "stok_tanggal",
                className: "",
                orderable: true, // jika ingin kolom ini bisa diurutkan orderable: true,
                searchable: true // searchable: true, jika ingin kolom ini bisa dicari
            }, {
                data: "stok_jumlah",
                className: "",
                orderable: true,
                searchable: true
            }, {
                data: "aksi",
                className: "",
                orderable: false,
                searchable: false
            }]
        });

        $('#level_id').on('change', function() {
           dataUser.ajax.reload(); 
        })
    });
</script> @endpush