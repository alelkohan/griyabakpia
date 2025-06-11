<form class="" id="editOutlet">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label for="username" class="form-label">Nama Outlet</label>
                <input type="hidden" name="id_outlet" value="<?= $outlet->id_outlet ?>">
                <input type="text" name="nama_outlet" class="form-control" id="username" value="<?= $outlet->nama_outlet ?>" required="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-end">
            <button type="button" class="btn btn-secondary px-4 btn-tutup" data-target=".btn-outlet">Kembali</button>
            <button type="submit" class="btn btn-primary px-4">Buat Outlet</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Submit form via AJAX
        $(document).off('submit', '#editOutlet').on('submit', '#editOutlet', function(e) {
            e.preventDefault(); // Prevent form from submitting normally

            var formData = $(this).serialize();

            $.ajax({
                url: '<?= site_url("produk/update_outlet") ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    toastr.success('Outlet berhasil dieditkan!', 'Sukses');
                    $('#tabelOutlet').DataTable().ajax.reload();

                    // Tutup modal dan reload konten kalau perlu
                    $('#modal_frame').modal('hide');

                    setTimeout(function() {
                        $(".btn-outlet").click(); // Memanggil tombol outlet untuk buka modal data outlet
                    }, 500); // Tunggu sebentar sebelum buka modal lagi

                },
                error: function(xhr, status, error) {
                    toastr.error('Terjadi kesalahan saat menambahkan Outlet.', 'Error');
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

