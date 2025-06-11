<form class="" id="tambahProduk">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="username" class="form-label">Nama Produk</label>
                <input type="text" name="nama_produk" class="form-control" id="username" required="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label" for="subject">Harga Produk</label>
                <input type="text" name="harga_produk" class="form-control" id="harga_produk2" required="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="username" class="form-label">Pemilik Produk</label>
                <select name="pemilik" class="form-select" required="">
                    <option value="" disabled selected>Pilih Pemilik</option>
                    <?php foreach ($pemilik as $pemilik): ?>
                        <option value="<?= $pemilik->id_pemilik ?>"><?= $pemilik->nama_pemilik ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-end">
            <button type="submit" class="btn btn-primary px-4 btn-submit-produk">Buat Produk</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Submit form via AJAX
        $(document).off('submit', '#tambahProduk').on('submit', '#tambahProduk', function(e) {
            e.preventDefault(); // Prevent form from submitting normally

            // Serialize form data
            var formData = $(this).serialize();

            // AJAX request
            $.ajax({
                url: '<?= site_url("produk/create") ?>', // Change to your form action URL
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Jika berhasil
                    toastr.success('Produk berhasil ditambahkan!', 'Sukses');
                    
                    $('#modal_frame').modal('hide');
                    $('#tabelProduk').DataTable().ajax.reload(); // Memperbarui DataTable
                },
                error: function(xhr, status, error) {
                    // Jika terjadi error
                    toastr.error('Terjadi kesalahan saat menambahkan Produk.', 'Error');
                }
            });
        });
    });

    document.getElementById('harga_produk2').addEventListener('input', function(e) {
        var value = e.target.value;

        value = value.replace(/[^0-9]/g, '');

        if (value) {
            value = 'Rp ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        e.target.value = value;
    });

</script>

