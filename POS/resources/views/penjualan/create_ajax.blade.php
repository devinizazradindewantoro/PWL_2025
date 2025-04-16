<form action="{{ url('/penjualan/ajax') }}" method="POST" id="form-transaksi">
    @csrf
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-white text-dark">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title"><i class="fas fa-cash-register mr-2 text-primary"></i>Transaksi</h5>
                <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <!-- KIRI: Pembeli & Barang -->
                    <div class="col-md-6">
                        <!-- Data Pembeli -->
                        <div class="card shadow-sm mb-4 border-light">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0 text-primary"><i class="fas fa-user mr-2"></i>Data Pembeli</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nama Pembeli <span class="text-danger">*</span></label>
                                    <input type="text" name="pembeli" class="form-control" placeholder="Masukkan nama pembeli" required>
                                    <small class="text-danger" id="error-pembeli"></small>
                                </div>
                            </div>
                        </div>

                        <!-- Data Barang -->
                        <div class="card shadow-sm mb-4 border-light">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0 text-primary"><i class="fas fa-box mr-2"></i>Data Barang</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Pilih Barang <span class="text-danger">*</span></label>
                                    <select id="select-barang" class="form-control select2">
                                        <option value="">- Pilih Barang -</option>
                                        @foreach ($barang as $b)
                                            <option value="{{ $b->barang_id }}"
                                                    data-harga="{{ $b->harga_jual }}"
                                                    data-stok="{{ $b->stok ? $b->stok->stok_jumlah : 0 }}"
                                                    data-nama="{{ $b->barang_nama }}">
                                                {{ $b->barang_nama }} (Rp {{ number_format($b->harga_jual, 0, ',', '.') }})
                                                - Stok: {{ $b->stok ? $b->stok->stok_jumlah : 0 }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addItem()">
                                        <i class="fas fa-plus mr-1"></i>Tambahkan ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KANAN: Keranjang -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4 border-light h-100">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0 text-primary"><i class="fas fa-shopping-cart mr-2"></i>Keranjang Belanja</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                                    <table class="table table-sm table-hover table-bordered mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Nama Barang</th>
                                                <th>Harga</th>
                                                <th>Jumlah</th>
                                                <th>Subtotal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="items-list" class="small"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="mt-3 p-3 bg-light border rounded d-flex justify-content-between align-items-center shadow-sm">
                    <h4 class="mb-0">Total: <span id="total" class="font-weight-bold text-primary">Rp 0</span></h4>
                </div>
            </div>

            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#select-barang').select2({
            dropdownParent: $('#myModal')
        });
    });

    let items = [];

    function addItem() {
        const select = document.getElementById('select-barang');
        const selectedOption = select.options[select.selectedIndex];
        const stok = parseInt(selectedOption.dataset.stok) || 0;

        if (!selectedOption.value) {
            Swal.fire('Peringatan', 'Pilih barang terlebih dahulu!', 'warning');
            return;
        }

        if (stok < 1) {
            Swal.fire('Error', 'Stok barang habis!', 'error');
            return;
        }

        // Cek apakah barang sudah ada di keranjang
        const existingItem = items.find(item => item.barang_id === selectedOption.value);
        if (existingItem) {
            Swal.fire('Peringatan', 'Barang sudah ada di keranjang!', 'warning');
            return;
        }

        const item = {
            barang_id: selectedOption.value,
            nama: selectedOption.dataset.nama,
            harga: parseInt(selectedOption.dataset.harga),
            jumlah: 1
        };

        items.push(item);
        renderItems();
        calculateTotal();
    }

    function removeItem(index) {
        items.splice(index, 1);
        renderItems();
        calculateTotal();
    }

    function renderItems() {
        const tbody = document.getElementById('items-list');
        tbody.innerHTML = '';

        items.forEach((item, index) => {
            const subtotal = item.harga * item.jumlah;
            tbody.innerHTML += `
            <tr>
                <td>
                    ${item.nama}
                    <input type="hidden" name="items[${index}][barang_id]" value="${item.barang_id}">
                </td>
                <td>Rp ${item.harga.toLocaleString()}</td>
                <td>
                    <input type="number" name="items[${index}][jumlah]"
                           value="${item.jumlah}" min="1"
                           class="form-control form-control-sm"
                           onchange="updateQty(${index}, this.value)">
                </td>
                <td>Rp ${subtotal.toLocaleString()}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm"
                            onclick="removeItem(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        });
    }

    function updateQty(index, value) {
        // Validasi jumlah tidak boleh melebihi stok
        const maxStok = parseInt($('#select-barang option[value="' + items[index].barang_id + '"]').data('stok')) || 0;

        if (parseInt(value) > maxStok) {
            Swal.fire('Error', 'Jumlah melebihi stok tersedia!', 'error');
            value = items[index].jumlah; // Kembalikan ke nilai sebelumnya
        }

        // Update jumlah di array items
        items[index].jumlah = parseInt(value) || 1;

        // Render ulang item dan hitung total
        renderItems();
        calculateTotal();
    }

    function calculateTotal() {
        const total = items.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
        document.getElementById('total').textContent = 'Rp ' + total.toLocaleString();
    }

    $("#form-transaksi").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                if (response.status) {
                    $('#myModal').modal('hide');
                    Swal.fire('Sukses', response.message, 'success');
                    tablePenjualan.ajax.reload();
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                    // Tampilkan error validasi
                    $('.text-danger').text('');
                    $.each(response.errors, function (field, messages) {
                        $('#error-' + field).text(messages[0]);
                    });
                }
            },
            error: function (xhr) {
                Swal.fire('Error', 'Terjadi kesalahan teknis', 'error');
            }
        });
    });
</script>