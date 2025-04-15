@empty($supplier)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/supplier') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>ID Supplier</label>
                    <input type="text" class="form-control" value="{{ $supplier->supplier_id }}" readonly>
                </div>
                <div class="form-group">
                    <label>Kode Supplier</label>
                    <input type="text" class="form-control" value="{{ $supplier->supplier_kode }}" readonly>
                </div>
                <div class="form-group">
                    <label>Nama Supplier</label>
                    <input type="text" class="form-control" value="{{ $supplier->supplier_nama }}" readonly>
                </div>
                <div class="form-group">
                    <label>Telepon</label>
                    <input type="text" class="form-control" value="{{ $supplier->supplier_telp }}" readonly>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea class="form-control" readonly>{{ $supplier->supplier_alamat }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@endempty