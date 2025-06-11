<form class="" id="editPemilik">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label for="username" class="form-label">Nama Pemilik</label>
                <input type="text" name="nama_pemilik" class="form-control" id="username" value="<?= $pemilik->nama_pemilik ?>" required="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label for="username" class="form-label">Jenis Pemilik</label>
                <select name="jenis_pemilik" class="form-select" required="">
                    <option value="sendiri" <?= $pemilik->jenis_pemilik == 'sendiri' ? 'selected' : '' ?>>Sendiri</option>
                    <option value="penitip" <?= $pemilik->jenis_pemilik == 'penitip' ? 'selected' : '' ?>>Penitip</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-end">
            <button type="submit" class="btn btn-primary px-4">Edit Pemilik</button>
        </div>
    </div>
</form>

<script type="text/javascript">

    $(document).ready(function() {
        // Submit form via AJAX
        $('#editPemilik').on('submit', function(e) {
            e.preventDefault(); // Prevent form from submitting normally

            // Serialize form data
            var formData = new FormData(this); // 'this' refers to the form element

            // AJAX request
            $.ajax({
                url: '<?= site_url("produk/update_pemilik/") . $pemilik->id_pemilik ?>', // Change to your form action URL
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    $('#tabelPemilik').DataTable().ajax.reload(); // Memperbarui DataTable
                    $('#tabelProdukJogja').DataTable().ajax.reload(); // Memperbarui DataTable
                    $('#tabelProdukBantul').DataTable().ajax.reload(); // Memperbarui DataTable

                    // Jika berhasil
                    toastr.success('Pemilik berhasil diubah!', 'Sukses');

                    // Tutup modal dan reload konten kalau perlu
                    $('#modal_frame').modal('hide');

                    setTimeout(function() {
                        $(".btn-pemilik").click(); // Memanggil tombol pemilik untuk buka modal data pemilik
                    }, 500); // Tunggu sebentar sebelum buka modal lagi

                },
                error: function(xhr, status, error) {
                    // Jika terjadi error
                    toastr.error('Terjadi kesalahan saat mengubah Pemilik.', 'Error');
                }
            });
        });

    });


</script>