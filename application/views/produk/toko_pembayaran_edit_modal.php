<form id="edit-pembayaran-form">
    <input type="hidden" name="id_pembayaran_toko" value="<?= $pembayaran->id_pembayaran_toko ?>">
    <div class="row">
        <div class="col-md-6">
            <label for="tanggal_bayar">Tanggal Pembayaran</label>
            <input type="datetime-local" class="form-control" name="tanggal_bayar" value="<?= date('Y-m-d\TH:i:s', strtotime($pembayaran->tanggal_bayar)) ?>" required>
        </div>
        <div class="col-md-6">
            <label for="nominal">Nominal</label>
            <input type="text" class="form-control" id="harga_produk_edit" name="nominal" value="<?= 'Rp ' . number_format($pembayaran->jumlah_uang, 0, ',', '.') ?>" required>
        </div>
    </div>
    <hr>
    <?php foreach ($detail as $row): 
        $nama_produk = $this->Produk_model->get_nama_produk($row->id_produk); ?>
        <div class="row">
            <input type="hidden" class="harga-default" value="<?= $row->harga_toko ?>">
            <div class="col-md-4">
                <label>Produk</label>
                <input type="text" class="form-control" value="<?= $nama_produk ?>" readonly>
                <input type="hidden" name="id_produk[]" value="<?= $row->id_produk ?>">
            </div>
            <div class="col-md-4">
                <label>Produk Terjual</label>
                <input type="number" class="form-control produk-terjual" name="produk_terjual[]" value="<?= $row->jumlah_terjual ?>" required>
            </div>
            <div class="col-md-4">
                <label>Retur Produk</label>
                <input type="number" class="form-control" name="produk_return[]" value="<?= $row->jumlah_return ?>" required>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="text-end mt-3">
        <button type="button" class="btn btn-secondary btn-tutup" data-target=".btn-kembali-edit">Kembali</button>
        <button type="submit" class="btn btn-primary">Update Pembayaran</button>
    </div>
</form>

<script>
    $(document).on('submit', '#edit-pembayaran-form', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: '<?= site_url("produk/modal_edit_pembayaran_tokosave") ?>',
            type: 'POST',
            data: formData,
            success: function(response) {
                toastr.success('Pembayaran berhasil diperbarui!', 'Sukses');
                $('#tabel-pembayaran-toko').DataTable().ajax.reload();
                $('#modal_frame').modal('hide');
            },
            error: function() {
                toastr.error('Gagal memperbarui pembayaran.', 'Error');
            }
        });
    });

    document.getElementById('harga_produk_edit').addEventListener('input', function(e) {
        var value = e.target.value;

        value = value.replace(/[^0-9]/g, '');

        if (value) {
            value = 'Rp ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        e.target.value = value;
    });

    document.querySelectorAll('.produk-terjual').forEach(input => {
        input.addEventListener('input', hitungNominal);
    });

    function hitungNominal() {
        let total = 0;

        document.querySelectorAll('.produk-terjual').forEach((input, index) => {
            let jumlah = parseInt(input.value) || 0;
            let harga = parseInt(document.querySelectorAll('.harga-default')[index].value) || 0;

            total += jumlah * harga;
        });

        let formatted = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        document.getElementById('harga_produk_edit').value = 'Rp ' + formatted;
    }
</script>