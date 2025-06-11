<form class="" id="editBahan">
    <input type="hidden" name="id_bahan" value="<?= $bahan->id_bahan ?>">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label for="username" class="form-label">Nama Bahan</label>
                <input type="text" name="nama_bahan" class="form-control" id="username" value="<?= $bahan->nama_bahan ?>" required="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label for="username" class="form-label">Satuan</label>
                <input type="text" name="satuan" class="form-control" id="username" value="<?= $bahan->satuan ?>" required="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-end">
            <button type="button" class="btn btn-secondary px-4 btn-tutup" data-target=".btn-bahan">Kembali</button>
            <button type="submit" class="btn btn-primary px-4">Edit Bahan</button>
        </div>
    </div>
</form>

<!-- <script>
    $(document).ready(function() {
        // Submit form via AJAX
        $(document).off('submit', '#tambahBahan').on('submit', '#tambahBahan', function(e) {
            e.preventDefault(); // Prevent form from submitting normally

            var formData = $(this).serialize();

            $.ajax({
                url: '<?= site_url("bahan/update_bahan/") . $bahan->id_bahan ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    toastr.success('Bahan berhasil ditambahkan!', 'Sukses');
                    $('#tabelBahan').DataTable().ajax.reload();

                    // Tutup modal dan reload konten kalau perlu
                    $('#modal_frame').modal('hide');

                    setTimeout(function() {
                        $(".btn-bahan").click(); 
                    }, 500);

                },
                error: function(xhr, status, error) {
                    toastr.error('Terjadi kesalahan saat menambahkan Pemilik.', 'Error');
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
-->
