<form class="" id="tambahPemilik">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label for="username" class="form-label">Nama Pemilik</label>
                <input type="text" name="nama_pemilik" class="form-control" id="username" required="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label for="username" class="form-label">Jenis Pemilik</label>
                <select name="jenis_pemilik" class="form-select" required="">
                    <option value="" disabled selected>Pilih Jenis Pemilik</option>
                    <option value="sendiri">Sendiri</option>
                    <option value="penitip">Penitip</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-end">
            <button type="button" class="btn btn-secondary px-4 btn-tutup" data-target=".btn-pemilik">Kembali</button>
            <button type="submit" class="btn btn-primary px-4">Buat Pemilik</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Submit form via AJAX
        $(document).off('submit', '#tambahPemilik').on('submit', '#tambahPemilik', function(e) {
            e.preventDefault(); // Prevent form from submitting normally

            var formData = $(this).serialize();

            $.ajax({
                url: '<?= site_url("produk/create_pemilik") ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    toastr.success('Pemilik berhasil ditambahkan!', 'Sukses');
                    $('#tabelPemilik').DataTable().ajax.reload();

                    // Tutup modal dan reload konten kalau perlu
                    $('#modal_frame').modal('hide');

                    setTimeout(function() {
                        $(".btn-pemilik").click(); // Memanggil tombol pemilik untuk buka modal data pemilik
                    }, 500); // Tunggu sebentar sebelum buka modal lagi

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

