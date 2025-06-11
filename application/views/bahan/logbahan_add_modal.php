<form id="tambahLogBahan">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="id_bahan" class="form-label">Nama Bahan</label>
                <select name="id_bahan" class="form-select" required>
                    <option value="" disabled selected>Pilih Bahan</option>
                    <?php foreach($bahan as $b): ?>
                        <option value="<?= $b->id_bahan ?>"><?= $b->nama_bahan ?> (<?= $b->satuan ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="jenis_aktivitas" class="form-label">Jenis Aktivitas</label>
                <select name="jenis_aktivitas" class="form-select" required>
                    <option value="" disabled selected>Pilih Jenis</option>
                    <option value="pembelian">Pembelian</option>
                    <option value="pemakaian">Pemakaian</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" name="jumlah" class="form-control" required>
            </div>
        </div>
    </div>

    <div class="row harga-wrapper">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="harga_satuan" class="form-label">Harga Satuan</label>
                <input type="text" name="harga_satuan" class="form-control" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="harga_total" class="form-label">Harga Total</label>
                <input type="text" name="harga_total" class="form-control" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control"></textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 text-end">
            <button type="submit" class="btn btn-primary px-4">Buat Aktivitas</button>
        </div>
    </div>
</form>

<!-- <script>
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

        function toggleHargaFields() {
            let jenis = $('select[name="jenis_aktivitas"]').val();
            let hargaFields = $('.harga-wrapper');

            if (jenis === 'pemakaian') {
                hargaFields.hide();
                hargaFields.find('input').prop('required', false).prop('disabled', true);
            } else {
                hargaFields.show();
                hargaFields.find('input').prop('required', true).prop('disabled', false);
            }
        }

        $('select[name="jenis_aktivitas"]').on('change', toggleHargaFields);

        // Initial check pas load
        toggleHargaFields();

        // Harga otomatis dari jumlah * harga_satuan
        $('input[name="harga_satuan"], input[name="jumlah"]').on('input', function() {
            let harga_satuan = $('input[name="harga_satuan"]').val().replace(/[^0-9]/g, '') || 0;
            let jumlah = $('input[name="jumlah"]').val() || 0;

            let total = parseInt(harga_satuan) * parseInt(jumlah);

            $('input[name="harga_total"]').val(formatRupiah(total.toString()));
        });

        // Format rupiah pas ngetik
        $('input[name="harga_satuan"], input[name="harga_total"]').on('input', function() {
            let val = $(this).val();
            $(this).val(formatRupiah(val));
        });

        // Submit form via AJAX
        $('#tambahLogBahan').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: '<?= site_url("bahan/create_logbahan") ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status === 'error') {
                        toastr.error(response.message || 'Terjadi kesalahan.', 'Error');
                        return;
                    }

                    toastr.success('Aktivitas berhasil disimpan!', 'Sukses');
                    $('#modal_frame').modal('hide');
                    $('#tabelLogBahan').DataTable().ajax.reload();
                    $('#tabelBahan').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    let msg = 'Gagal menyimpan Aktivitas.';
                    try {
                        let res = JSON.parse(xhr.responseText);
                        msg = res.message || msg;
                    } catch (e) {}

                    toastr.error(msg, 'Error');
                }
            });
        });
    });
</script>
 -->