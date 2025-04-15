@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/detailPenjualan/import') }}')" class="btn btn-info btn-sm">Import Data</button>
                <a href="{{ url('/detailPenjualan/export_excel') }}" class="btn btn-primary btn-sm"><i class="fa fa-file-excel"></i> Export Excel</a>
                <a href="{{ url('/detailPenjualan/export_pdf') }}" class="btn btn-warning btn-sm"><i class="fa fa-file-pdf"></i> Export PDF</a>
                <button onclick="modalAction('{{ url('/detailPenjualan/create_ajax/') }}')" class="btn btn-success btn-sm">Tambah Data</button>
            </div>
        </div>

        <div class="card-body">
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter-date" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_barang" class="form-control form-control-sm filter_barang">
                                    <option value="">- Semua -</option>
                                    @foreach($barang as $item)
                                        <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Nama Barang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-sm table-striped table-hover" id="table-detailPenjualan" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Penjualan</th>
                        <th>Nama Barang</th>
                        <th>Harga Barang</th>
                        <th>Jumlah Barang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        var tableDetail;
        $(document).ready(function () {
            tableDetail = $('#table-detailPenjualan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('detailPenjualan/list') }}",
                    dataType: "json",
                    type: "POST",
                    data: function (d) {
                        d.filter_barang = $('.filter_barang').val();
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex", 
                        className: "text-center", 
                        width: "5%", 
                        orderable: false, 
                        searchable: false 
                    },
                    {
                        data: "penjualan.penjualan_id", 
                        width: "20%", 
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "barang.barang_id", 
                        width: "25%", 
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "harga", 
                        className: "", 
                        width: "15%", 
                        orderable: true,
                        searchable: false,
                        render: function(data, type, row) {
                            return new Intl.NumberFormat('id-ID').format(data);
                        }
                    },
                    {
                        data: "jumlah", 
                        className: "", 
                        width: "10%" 
                    },
                    {
                        data: "aksi", 
                        className: "text-center", 
                        width: "15%", 
                        orderable: false, 
                        searchable: false 
                    }
                ]
            });

            $('#table-detailPenjualan_filter input').unbind().bind().on('keyup', function(e) {
                if (e.keyCode == 13) {
                    tableDetail.search(this.value).draw();
                }
            });

            $('.filter_barang').change(function () {
                tableDetail.draw();
            });
        });
    </script>
@endpush