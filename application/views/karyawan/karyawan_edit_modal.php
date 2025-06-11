<form class="" id="editKaryawan" enctype="multipart/form-data">
    <input type="hidden" name="id_karyawan" id="id_karyawan" value="<?= $karyawan->id_karyawan ?>">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
            <input type="text" name="nama_karyawan" class="form-control" id="nama_karyawan" value="<?= $karyawan->nama_karyawan ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="alamat_karyawan" class="form-label">Alamat</label>
            <input type="text" name="alamat_karyawan" class="form-control" id="alamat_karyawan" value="<?= $karyawan->alamat_karyawan ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
            <input type="text" name="nomor_telepon" class="form-control" id="nomor_telepon" value="<?= $karyawan->nomor_telepon ?>" placeholder="08xx-xxxx-xxx" maxlength="15" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="peran_karyawan" class="form-label">Peran</label>
            <select name="peran_karyawan" class="form-select" id="peran_karyawan" required>
                <option value="Admin" <?= $karyawan->peran_karyawan == 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Packing" <?= $karyawan->peran_karyawan == 'Packing' ? 'selected' : '' ?>>Packing</option>
                <option value="produksi" <?= $karyawan->peran_karyawan == 'produksi' ? 'selected' : '' ?>>Produksi</option>
                <option value="Sales" <?= $karyawan->peran_karyawan == 'Sales' ? 'selected' : '' ?>>Sales</option>
                <option value="kasir" <?= $karyawan->peran_karyawan == 'kasir' ? 'selected' : '' ?>>Kasir</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" class="form-control" id="tanggal_masuk" value="<?= $karyawan->tanggal_masuk ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="status_tempat_tinggal" class="form-label">Status Tempat Tinggal</label>
            <select name="status_tempat_tinggal" class="form-select" id="status_tempat_tinggal" required>
                <option value="Menetap" <?= $karyawan->status_tempat_tinggal == 'Menetap' ? 'selected' : '' ?>>Menetap</option>
                <option value="Tidak Menetap" <?= $karyawan->status_tempat_tinggal == 'Tidak Menetap' ? 'selected' : '' ?>>Tidak Menetap</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="kelamin_karyawan" class="form-label">Jenis Kelamin</label>
            <select name="kelamin_karyawan" class="form-select" id="kelamin_karyawan" required>
                <option value="Laki-laki" <?= $karyawan->kelamin_karyawan == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="Perempuan" <?= $karyawan->kelamin_karyawan == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="status_karyawan" class="form-label">Status</label>
            <select name="status_karyawan" class="form-select" id="status_karyawan" required>
                <option value="aktif" <?= $karyawan->status_karyawan == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="nonaktif" <?= $karyawan->status_karyawan == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="password" class="form-label">Password (opsional)</label>
            <input type="password" name="password" class="form-control" id="password"
            pattern="(?=.*[A-Z])(?=.*[0-9]).{6,}" 
            title="Password minimal 6 karakter, harus ada huruf kapital dan angka" 
            oninvalid="this.setCustomValidity('Password minimal 6 karakter, harus ada huruf kapital dan angka')" 
            oninput="this.setCustomValidity('')">
        </div>
        <div class="col-md-6 mb-3">
            <label for="foto_karyawan" class="form-label">Foto Karyawan (opsional)</label>
            <input type="file" name="foto_karyawan" class="form-control" id="foto_karyawan" accept="image/*">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-end">
            <button type="button" class="btn btn-secondary px-4 btn-tutup" data-target=".btn-karyawan">Kembali</button>
            <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
        </div>
    </div>
</form>

<script type="text/javascript">

    $(document).ready(function() {
        $(document).off('submit', '#editKaryawan').on('submit', '#editKaryawan', function(e) {
            e.preventDefault();

            var form = $('#editKaryawan')[0];
            var formData = new FormData(form);


            $.ajax({
                url: '<?= site_url("karyawan/update_karyawan/" . $karyawan->id_karyawan) ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    toastr.success('Data karyawan berhasil diupdate!', 'Sukses');
                    $('#tabelKaryawan').DataTable().ajax.reload();
                    $('#modal_frame').modal('hide');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    let msg = 'Gagal update data karyawan.';
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
