<form class="" id="editProdukBantul">
    <input type="hidden" name="id_produk" id="id_produk" value="<?= $produk->id_produk ?>">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="username" class="form-label">Nama Produk</label>
                <input type="text" name="nama_produk" class="form-control" id="username" value="<?= $produk->nama_produk ?>" required="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label" for="subject">Harga Produk</label>
                <input type="text" name="harga_produk" class="form-control" id="harga_produk4" value="<?= number_format($produk->harga_default, 0, ',', '.') ?>" required="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="username" class="form-label">Pemilik Produk</label>
                <select name="pemilik" class="form-select" required="">
                    <option value="" disabled selected>Pilih Pemilik</option>
                    <?php foreach ($pemilik as $p): ?>
                        <option value="<?= $p->id_pemilik ?>" <?= $produk->id_pemilik == $p->id_pemilik ? 'selected' : '' ?>>
                            <?= $p->nama_pemilik ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-end">
            <button type="submit" class="btn btn-primary px-4">Edit Produk</button>
        </div>
    </div>
</form>

<script type="text/javascript">

    $(document).ready(function() {
        // Submit form via AJAX
        $('#editProdukBantul').on('submit', function(e) {
            e.preventDefault(); // Prevent form from submitting normally

            // Serialize form data
            var formData = new FormData(this); // 'this' refers to the form element

            // AJAX request
            $.ajax({
                url: '<?= site_url("produk/update/") . $produk->id_produk ?>', // Change to your form action URL
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    // Jika berhasil
                    toastr.success('Produk berhasil diubah!', 'Sukses');

                    $('#modal_frame').modal('hide');
                    $('#tabelProdukBantul').DataTable().ajax.reload(); // Memperbarui DataTable
                    $('#tabelProdukJogja').DataTable().ajax.reload(); // Memperbarui DataTable
                },
                error: function(xhr, status, error) {
                    // Jika terjadi error
                    toastr.error('Terjadi kesalahan saat mengubah produk.', 'Error');
                }
            });
        });
    });

    document.getElementById('harga_produk4').addEventListener('input', function(e) {
        var value = e.target.value;

        value = value.replace(/[^0-9]/g, '');

        if (value) {
            value = 'Rp ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        e.target.value = value;
    });

</script>