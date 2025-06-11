<form id="gajiKaryawan">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Peran</th>
                    <th>Status Gaji Bulan ini</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($karyawan as $k) { ?>
                    <tr>
                        <td>
                            <img src="<?= base_url('upload/karyawan/') . $k->foto_karyawan ?>" alt="Foto <?= $k->nama_karyawan ?>" class="rounded-circle thumb-md me-1 d-inline">
                            <input type="hidden" name="id_karyawan[]" value="<?= $k->id_karyawan ?>">
                        </td>
                        <td><?= $k->nama_karyawan ?></td>
                        <td><?= $k->peran_karyawan ?></td>
                        <td>
                            <?php if ($k->sudah_digaji_bulan_ini > 0): ?>
                                <span class="badge rounded-pill bg-success-subtle text-success">Sudah</span>
                            <?php else: ?>
                                <span class="badge rounded-pill bg-danger-subtle text-danger">Belum</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm btn-detail" data-id="<?= $k->id_karyawan ?>">Detail Absensi</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="text-end mt-3">
        <button type="submit" class="btn btn-primary px-4">Submit</button>
    </div>
</form>

<!-- <script type="text/javascript">

    $(document).ready(function() {

    });

</script> -->