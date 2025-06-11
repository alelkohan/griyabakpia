<form class="" id="tambahStok">
    <div class="row">
        <div class="mb-3">
            <label class="form-label">Stok Produk</label>
            <input type="number" name="stok" class="form-control" required>
            <input type="hidden" name="id_produk_outlet" value="<?= $produk_outlet->id_produk_outlet ?>" class="form-control" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" placeholder="Contoh: Stok outlet jogja" rows="1" required></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-end">
            <button type="submit" class="btn btn-primary px-4">Tambah Stok</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
    // Submit form via AJAX
        $(document).off('submit', '#tambahStok').on('submit', '#tambahStok', function(e) {
        e.preventDefault(); // Prevent form from submitting normally

        // Serialize form data
        var formData = $(this).serialize();

        // AJAX request
        $.ajax({
            url: '<?= site_url("produk/add_stok") ?>', // Change to your form action URL
            type: 'POST',
            data: formData,
            success: function(response) {
                // Jika berhasil
                toastr.success('Produk berhasil ditambahkan!', 'Sukses');
                
                $('#modal_frame').modal('hide');
                // Cari tab yang sedang aktif
                    var activeTab = $('.tab-pane.active');
                    var activeTable = activeTab.find('.tabelProduk'); // Temukan tabel di tab aktif
                    var tableId = activeTable.attr('id');

                    // Reload tabel jika ditemukan
                    if (tableId && tableProdukInstances[tableId]) {
                        tableProdukInstances[tableId].ajax.reload();
                    }
            },
            error: function(xhr, status, error) {
                // Jika terjadi error
                toastr.error('Terjadi kesalahan saat menambahkan Produk.', 'Error');
            }
        });
    });
    });

</script>

