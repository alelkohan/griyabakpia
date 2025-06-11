<?php
$is_hadir = strtolower($absensi->status) === 'hadir' ? true : false;
?>

<form id="editAbsensi">
    <input type="hidden" name="id_absensi" value="<?= $absensi->id_absensi ?>">
    <div class="mb-3">
        <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
        <input type="text" class="form-control" id="nama_karyawan" value="<?= $absensi->nama_karyawan ?>" readonly>
    </div>
    <div class="row">
        <div class="col-4">
            <label for="waktu_masuk" class="form-label">Waktu Masuk</label>
            <input type="time" class="form-control" id="waktu_masuk" name="waktu_masuk"
            <?= !$is_hadir ? 'disabled' : '' ?>
            value="<?= date('H:i', strtotime($absensi->waktu_masuk)) ?>">

            <?php if (empty($absensi->waktu_pulang)) : ?>
                <small class="text-muted">input dinonaktifkan.</small>
            <?php endif; ?>
        </div>
        <div class="col-4">
            <label for="waktu_pulang" class="form-label">Waktu Pulang</label>
            <input type="time" class="form-control" id="waktu_pulang" name="waktu_pulang"
            <?= empty($absensi->waktu_pulang) ? 'disabled' : '' ?>
            value="<?= empty($absensi->waktu_pulang) ? '' : date('H:i', strtotime($absensi->waktu_pulang)) ?>">

            <?php if (empty($absensi->waktu_pulang)) : ?>
                <small class="text-muted">input dinonaktifkan.</small>
            <?php endif; ?>
        </div>
        <div class="col-4">
            <label for="lembur" class="form-label">Lembur/jam</label>
            <input type="text" class="form-control lembur_input" id="lembur" name="lembur"
            value="<?= $absensi->lembur ?>"

            <?= empty($absensi->lembur) ? 'disabled' : '' ?>
            value="<?= empty($absensi->lembur) ? '' : $absensi->lembur ?>">

            <?php if (empty($absensi->lembur)) : ?>
                <small class="text-muted">input dinonaktifkan.</small>
            <?php endif; ?>
        </div>
    </div>

    <div class="text-end mt-2">
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#editAbsensi').on('submit', function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: '<?= site_url("karyawan/update_absensi/") . $absensi->id_karyawan ?>',
                type: 'POST',
                data: formData,
                success: function (response) {
                    console.log("Response sukses:", response); // Debug log
                    toastr.success('Karyawan berhasil diedit!', 'Sukses');
                    $('#tabelAbsensi').DataTable().ajax.reload();
                    $('#modal_frame').modal('hide');
                },
                error: function (xhr, status, error) {
                    console.error("AJAX error:", error);
                    console.error("XHR response:", xhr.responseText);
                    toastr.error('Terjadi kesalahan saat mengedit absensi.', 'Error');
                }
            });
        });

        $('.lembur_input').on('input', function (e) {
            let input = $(this);
            let raw = input.val();

            // Ambil angka aja
            let angka = raw.replace(/\D/g, '');

            // Maksimal 3 digit
            if (angka.length > 3) {
                angka = angka.substring(0, 3);
            }

            // Simpan posisi kursor
            let start = this.selectionStart;

            // Update value-nya
            input.val(angka ? angka + ' jam' : '');

            // Balikin kursor ke posisi sebelumnya
            this.setSelectionRange(start, start);
        });

        $('.lembur_input').on('focus', function () {
            let angka = $(this).val().replace(/\D/g, '');
            if (angka) $(this).val(angka);
        });

        $('.lembur_input').on('blur', function () {
            let angka = $(this).val().replace(/\D/g, '');
            if (angka) $(this).val(angka + ' jam');
        });

    });

</script>

