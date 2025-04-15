@empty($stok)
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
                <a href="{{ url('/stok') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Stok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>ID Stok</label>
                    <input type="text" class="form-control" value="{{ $stok->stok_id }}" readonly>
                </div>
                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" class="form-control" value="{{ $stok->barang->barang_nama }}" readonly>
                </div>
                <div class="form-group">
                    <label>Nama User</label>
                    <input type="text" class="form-control" value="{{ $stok->user->nama }}" readonly>
                </div>
                <div class="form-group">
                    <label>Tanggal Stok</label>
                    <input type="text" class="form-control" value="{{ $stok->stok_tanggal }}" readonly>
                </div>
                <div class="form-group">
                    <label>Jumlah Stok</label>
                    <input type="text" class="form-control" value="{{ $stok->stok_jumlah }}" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@endempty