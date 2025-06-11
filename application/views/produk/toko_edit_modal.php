<form class="" id="editToko">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label for="username" class="form-label">Nama Toko</label>
                <input type="hidden" name="id_toko" value="<?= $toko->id_toko ?>">
                <input type="text" name="nama_toko" class="form-control" id="username" value="<?= $toko->nama_toko ?>" required="">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Status Toko</label>
                <select name="status_toko" class="form-select" required="">
                    <option value="" disabled selected>Pilih Status Toko</option>
                    <option value="aktif" <?= $toko->status == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= $toko->status == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-end">
            <button type="button" class="btn btn-secondary px-4 btn-tutup" data-target=".btn-outlet">Kembali</button>
            <button type="submit" class="btn btn-primary px-4">Edit Toko</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Submit form via AJAX
        $(document).off('submit', '#editToko').on('submit', '#editToko', function(e) {
            e.preventDefault(); // Prevent form from submitting normally

            var formData = $(this).serialize();

            $.ajax({
                url: '<?= site_url("produk/update_toko") ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    toastr.success('Toko berhasil dieditkan!', 'Sukses');
                    $('#tabelToko').DataTable().ajax.reload();

                    // Tutup modal dan reload konten kalau perlu
                    $('#modal_frame').modal('hide');

                    setTimeout(function() {
                        $(".btn-toko").click(); // Memanggil tombol toko untuk buka modal data toko
                    }, 500); // Tunggu sebentar sebelum buka modal lagi

                },
                error: function(xhr, status, error) {
                    toastr.error('Terjadi kesalahan saat menambahkan Toko.', 'Error');
                }
            });
        });

        $(document).on('click', '.btn-tutup', function() {
            let target = $(this).data('target'); // ambil tujuan dari data attribute

            $('#modal_frame').modal('hide');

            if (target) {
                $('#modal_frame').one('hidden.bs.modal', function() {
                    $(target).click(); // klik sesuai target tujuan
                });
            }
        });

    });

</script>

