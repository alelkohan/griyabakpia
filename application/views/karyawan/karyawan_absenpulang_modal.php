<form id="absenPulangKaryawan">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Peran</th>
                    <th>Waktu Pulang</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($karyawan)) { ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Hari ini sudah pulang semua</td>
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
                            <td><?= $k->nama_karyawan ?></td>
                            <td><?= $k->peran_karyawan ?></td>
                            <td>
                                <input type="time" name="waktu_pulang[]" value="<?= date('H:i') ?>" class="form-control waktu-input">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm hapusBaris">Hapus</button>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php if (!empty($karyawan)) { ?>
        <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary px-4">Submit</button>
        </div>
    <?php } ?>
</form>

<script type="text/javascript">

    $(document).ready(function() {
        $(document).off('submit', '#absenPulangKaryawan').on('submit', '#absenPulangKaryawan', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: '<?= site_url("karyawan/absen_pulang_karyawan") ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log("Sukses: ", response);
                    toastr.success('Karyawan berhasil diabsen!', 'Sukses');
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

        $(document).on('click', '.hapusBaris', function () {
            $(this).closest('tr').remove();
        });

    });

</script>