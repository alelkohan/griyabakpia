<form class="" id="tambahToko">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label for="username" class="form-label">Nama Toko</label>
                <input type="text" name="nama_toko" class="form-control" id="username" required="">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Status Toko</label>
                <select name="status_toko" class="form-select" required="">
                    <option value="" disabled selected>Pilih Status Toko</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-end">
            <button type="submit" class="btn btn-primary px-4">Buat Toko</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Submit form via AJAX
        $(document).off('submit', '#tambahToko').on('submit', '#tambahToko', function(e) {
            e.preventDefault(); // Prevent form from submitting normally

            var formData = $(this).serialize();

            $.ajax({
                url: '<?= site_url("produk/create_toko") ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    toastr.success('Toko berhasil ditambahkan!', 'Sukses');
                    $('#tabelToko').DataTable().ajax.reload();

                    // Tutup modal dan reload konten kalau perlu
                    $('#modal_frame').modal('hide');

                },
                error: function(xhr, status, error) {
                    toastr.error('Terjadi kesalahan saat menambahkan Toko.', 'Error');
                }
            });
        });

    });

</script>

