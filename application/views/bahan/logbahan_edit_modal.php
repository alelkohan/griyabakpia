<form id="editLogBahan">
    <input type="hidden" name="id_log_bahan" value="<?= $log->id_log_bahan ?>">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="id_bahan" class="form-label">Nama Bahan</label>
                <select name="id_bahan" class="form-select" required disabled>
                    <?php foreach($bahan as $b): ?>
                        <option value="<?= $b->id_bahan ?>" <?= $b->id_bahan == $log->id_bahan ? 'selected' : '' ?>>
                            <?= $b->nama_bahan ?> (<?= $b->satuan ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d', strtotime($log->tanggal)) ?>" required disabled>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="jenis_aktivitas" class="form-label">Jenis Aktivitas</label>
                <input type="text" name="jenis_aktivitas" class="form-control" value="<?= ucfirst($log->jenis_aktivitas) ?>" disabled>
                <input type="hidden" name="jenis_aktivitas" value="<?= $log->jenis_aktivitas ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" name="jumlah" class="form-control" value="<?= $log->jumlah ?>" required>
            </div>
        </div>
    </div>

    <div class="row harga-wrapper" <?= $log->jenis_aktivitas == 'pemakaian' ? 'style="display: none;"' : '' ?>>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="harga_satuan" class="form-label">Harga Satuan</label>
                <input type="text" name="harga_satuan" class="form-control" value="<?= number_format($log->harga_satuan, 0, ',', '.') ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="harga_total" class="form-label">Harga Total</label>
                <input type="text" name="harga_total" class="form-control" value="<?= number_format($log->harga_total, 0, ',', '.') ?>">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control"><?= $log->keterangan ?></textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 text-end">
            <button type="submit" class="btn btn-primary px-4">Edit Aktivitas</button>
        </div>
    </div>
</form>

<!-- <script type="text/javascript">

    $(document).ready(function() {
        function formatRupiah(angka) {
            let number_string = angka.replace(/[^,\d]/g, '').toString();
            let split = number_string.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah ? 'Rp ' + rupiah : '';
        }

        $('input[name="harga_satuan"], input[name="jumlah"]').on('input', function() {
            let harga_satuan = $('input[name="harga_satuan"]').val().replace(/[^0-9]/g, '') || 0;
            let jumlah = $('input[name="jumlah"]').val() || 0;
            let total = parseInt(harga_satuan) * parseInt(jumlah);
            $('input[name="harga_total"]').val(formatRupiah(total.toString()));
        });

        $('input[name="harga_satuan"], input[name="harga_total"]').on('input', function() {
            let val = $(this).val();
            $(this).val(formatRupiah(val));
        });

        $('#editLogBahan').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: '<?= site_url("bahan/update_logbahan/") . $log->id_log_bahan ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    toastr.success('Aktivitas berhasil diupdate!', 'Sukses');
                    $('#modal_frame').modal('hide');
                    $('#tabelLogBahan').DataTable().ajax.reload();
                    $('#tabelBahan').DataTable().ajax.reload();
                },
                error: function() {
                    toastr.error('Gagal mengupdate Aktivitas.', 'Error');
                }
            });
        });
    });

</script> -->