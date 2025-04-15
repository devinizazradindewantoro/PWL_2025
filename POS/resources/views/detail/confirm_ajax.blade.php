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
<form action="{{ url('/detailPenjualan/' . $detail->detail_id . '/delete_ajax') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Detail Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi</h5>
                    Apakah Anda yakin ingin menghapus data berikut?
                </div>
                <table class="table table-bordered table-sm">
                    <tr><th width="30%">Kode Penjualan</th><td>{{ $detail->penjualan->penjualan_kode ?? '-' }}</td></tr>
                    <tr><th>Barang</th><td>{{ $detail->barang->barang_nama ?? '-' }}</td></tr>
                    <tr><th>Harga</th><td>{{ $detail->harga }}</td></tr>
                    <tr><th>Jumlah</th><td>{{ $detail->jumlah }}</td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        $('#form-delete').validate({
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                            tableDetail.ajax.reload();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: response.message });
                        }
                    }
                });
                return false;
            }
        });
    });
</script>
@endempty