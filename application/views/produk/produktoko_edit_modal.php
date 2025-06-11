<form class="" id="editProduk">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <input type="hidden" name="id_produk_toko" value="<?= $produk->id_produk_toko ?>">
                <input type="hidden" name="id_toko" value="<?= $produk->id_toko ?>">
                <label for="produk" class="form-label">Produk</label>
                <select name="id_produk" class="form-select" required>
                    <option value="" disabled>Pilih Produk</option>
                    <?php foreach ($all_produk as $p): ?>
                        <option value="<?= $p->id_produk ?>" <?= ($p->id_produk == $produk->id_produk) ? 'selected' : '' ?>>
                            <?= $p->nama_produk ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label" for="harga_produk">Harga Produk</label>
                <input type="text" name="harga_produk" class="form-control" id="harga_produk_edit" 
                       value="Rp <?= number_format($produk->harga_toko, 0, ',', '.') ?>" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-end">
            <button type="button" class="btn btn-secondary px-4 btn-tutup" 
                    data-target=".btn-toko-detail-<?= $produk->id_toko ?>">Kembali</button>
            <button type="submit" class="btn btn-primary px-4">Update Produk</button>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    // Format input harga
    document.getElementById('harga_produk_edit').addEventListener('input', function(e) {
        var value = e.target.value;
        value = value.replace(/[^0-9]/g, '');
        if (value) {
            value = 'Rp ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        e.target.value = value;
    });

    // Submit form edit
    $(document).off('submit', '#editProduk').on('submit', '#editProduk', function(e) {
        e.preventDefault();

        // Format harga sebelum submit (hapus Rp dan titik)
        var harga = $('#harga_produk_edit').val().replace('Rp ', '').replace(/\./g, '');
        $('#harga_produk_edit').val(harga);

        var formData = $(this).serialize();
        let id_toko = $('input[name="id_toko"]').val();

        $.ajax({
            url: '<?= site_url("produk/modal_edit_produktokosave") ?>',
            type: 'POST',
            data: formData,
            success: function(response) {
                toastr.success('Produk berhasil diupdate!', 'Sukses');
                $('#modal_frame').modal('hide');
                
                var activeTab = $('.tab-pane.active');
                var activeTable = activeTab.find('.tabelProduk'); // Temukan tabel di tab aktif
                var tableId = activeTable.attr('id');

                // Reload tabel jika ditemukan
                if (tableId && tableProdukInstances[tableId]) {
                    tableProdukInstances[tableId].ajax.reload();
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Terjadi kesalahan saat mengupdate Produk.', 'Error');
            }
        });
    });

});
</script>