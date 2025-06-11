<form class="" id="tambahProduk">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <input type="hidden" name="id_outlet" value="">
                <label for="username" class="form-label">Produk</label>
                <select name="id_produk" class="form-select" required="">
                    <option value="" disabled selected>Pilih Produk</option>
                    <?php foreach   ($produk as $produk): ?>
                        <option value="<?= $produk->id_produk ?>"><?= $produk->nama_produk ?> (<?= $produk->nama_pemilik ?>/<?= $produk->jenis_pemilik ?>)</option>
                    <?php endforeach; ?>
                </select>
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
        <div class="col-sm-12 text-end">
            <button type="submit" class="btn btn-primary px-4">Buat Produk</button>
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
                url: '<?= site_url("produk/modal_add_produkoutletsave") ?>', // Change to your form action URL
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

    document.getElementById('harga_produk2').addEventListener('input', function(e) {
        var value = e.target.value;

        value = value.replace(/[^0-9]/g, '');

        if (value) {
            value = 'Rp ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        e.target.value = value;
    });

</script>

