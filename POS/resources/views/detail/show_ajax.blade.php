@empty($detail)
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">Data tidak ditemukan.</div>
        </div>
    </div>
</div>
@else
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Data Penjualan</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-sm">
                <tr><th>Kode Penjualan</th><td>{{ $detail->penjualan->penjualan_kode ?? '-' }}</td></tr>
                <tr><th width="30%">Barang</th><td>{{ $detail->barang->barang_nama ?? '-' }}</td></tr>
                <tr><th>Harga</th><td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td></tr>
                <tr><th>Jumlah</th><td>{{ $detail->jumlah }}</td></tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
@endempty