<form id="tambahLembur" method="POST"> 
    <table class="table table-striped mb-0">
        <thead class="table-light">
            <tr>
                <th>Nama</th>
                <th>Peran</th>
                <th>Lembur/jam</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($karyawan)) { ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">Hari ini sudah lembur semua</td>
                </tr>
            <?php } else { ?>

                <?php foreach ($karyawan as $k) { ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?= base_url('upload/karyawan/') . $k->foto_karyawan ?>" class="me-2 thumb-md align-self-center rounded" alt="foto ' . $key->nama_karyawan . '" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                                <div class="flex-grow-1 text-truncate"> 
                                    <h6 class="m-0"><?= $k->nama_karyawan ?></h6>
                                    <p class="fs-12 text-muted mb-0"><?= $k->kelamin_karyawan ?></p>                                                                                           
                                </div><!--end media body-->
                            </div>
                            <input type="hidden" name="id_karyawan[]" value="<?= $k->id_karyawan ?>">                        
                        </td>
                        <td><?= $k->peran_karyawan ?></td>
                        <td>
                            <input type="text" name="lembur[]" class="form-control lembur_input" placeholder="">
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
    <?php if (!empty($karyawan)) { ?>
        <div class="text-end mt-3">
            <button type="button" class="btn btn-secondary" id="isiSemuaLembur">Terapkan ke Semua</button>
            <button type="submit" class="btn btn-primary px-4">Submit</button>
        </div>
    <?php } ?>
</form>

<script type="text/javascript">

    $(document).ready(function() {
        $(document).off('submit', '#tambahLembur').on('submit', '#tambahLembur', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: '<?= site_url("karyawan/add_lembur") ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log("Sukses: ", response);
                    toastr.success('Berhasik menambahkan lembur!', 'Sukses');
                    $('#tabelAbsensi').DataTable().ajax.reload();
                    $('#modal_frame').modal('hide');
                },
                error: function(xhr) {
                    console.log("XHR ERROR:", xhr);
                    let msg = 'Gagal absen Karyawan.';
                    try {
                        let res = JSON.parse(xhr.responseText);
                        msg = res.message || msg;
                    } catch (e) {}
                    toastr.error(msg, 'Error');
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

        let nilaiTerakhirLembur = '';
        let sudahDiisiSemua = false;

        $('.lembur_input').on('input', function () {
            let angka = $(this).val().replace(/\D/g, '');
            if (angka.length > 3) {
                angka = angka.substring(0, 3);
            }

            nilaiTerakhirLembur = angka; // Simpan nilai terakhir
            $(this).val(angka ? angka + ' jam' : '');

            // Kalau user mulai nginput manual lagi, balikkan tombol ke mode isi
            if (sudahDiisiSemua) {
                $('#isiSemuaLembur').text('Terapkan ke Semua');
                sudahDiisiSemua = false;
            }
        });

        $('#isiSemuaLembur').on('click', function () {
            if (!sudahDiisiSemua) {
                // Mode: Terapkan ke semua
                if (!nilaiTerakhirLembur) {
                    toastr.error('Isi salah satu lembur dulu!');
                    return;
                }

                $('.lembur_input').each(function () {
                    $(this).val(nilaiTerakhirLembur + ' jam');
                });

                $(this).text('Reset Semua');
                sudahDiisiSemua = true;
            } else {
                // Mode: Reset semua
                $('.lembur_input').val('');
                $(this).text('Terapkan ke Semua');
                nilaiTerakhirLembur = '';
                sudahDiisiSemua = false;
            }
        });

    });

</script>