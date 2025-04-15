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
<form action="{{ url('/detailPenjualan/' . $detail->detail_id . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Detail Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Penjualan</label>
                    <select name="penjualan_id" class="form-control">
                        <option value="">- Pilih Kode Penjualan -</option>
                        @foreach($penjualan as $p)
                            <option value="{{ $p->penjualan_id }}" {{ $p->penjualan_id == $detail->penjualan_id ? 'selected' : '' }}>{{ $p->penjualan_kode }}</option>
                        @endforeach
                    </select>
                    <small id="error-penjualan_id" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Barang</label>
                    <select name="barang_id" class="form-control">
                        <option value="">- Pilih Barang -</option>
                        @foreach($barang as $b)
                            <option value="{{ $b->barang_id }}" {{ $b->barang_id == $detail->barang_id ? 'selected' : '' }}>{{ $b->barang_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-barang_id" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" value="{{ $detail->harga }}" class="form-control">
                    <small id="error-harga" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah" value="{{ $detail->jumlah }}" class="form-control">
                    <small id="error-jumlah" class="error-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('#form-edit').validate({
            rules: {
                barang_id: { required: true },
                penjualan_id: { required: true },
                harga: { required: true, digits: true },
                jumlah: { required: true, digits: true }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if(response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                            tableDetail.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: response.message });
                        }
                    }
                });
                return false;
            }
        });
    });
</script>
@endempty