<form class="" id="tambahKaryawan" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
            <input type="text" name="nama_karyawan" class="form-control" id="nama_karyawan" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="alamat_karyawan" class="form-label">Alamat</label>
            <input type="text" name="alamat_karyawan" class="form-control" id="alamat_karyawan" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
            <input type="text" name="nomor_telepon" class="form-control" id="nomor_telepon" placeholder="08xx-xxxx-xxx" maxlength="15" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="peran_karyawan" class="form-label">Peran</label>
            <select type="text" name="peran_karyawan" class="form-select" id="peran_karyawan" required>
                <option value="" disabled selected>Pilih Peran</option>
                <option value="admin">Admin</option>
                <option value="packing">Packing</option>
                <option value="produksi">Produksi</option>
                <option value="sales">Sales</option>
                <option value="kasir">Kasir</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" class="form-control" id="tanggal_masuk" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="status_tempat_tinggal" class="form-label">Status Tempat Tinggal</label>
            <select name="status_tempat_tinggal" class="form-select" id="status_tempat_tinggal" required>
                <option value="" disabled selected>Pilih Tempat Tinggal</option>
                <option value="Menetap">Menetap</option>
                <option value="Tidak Menetap">Tidak Menetap</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="kelamin_karyawan" class="form-label">Jenis Kelamin</label>
            <select name="kelamin_karyawan" class="form-select" id="kelamin_karyawan" required>
                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="status_karyawan" class="form-label">Status</label>
            <select name="status_karyawan" class="form-select" id="status_karyawan" required>
                <option value="aktif" selected>Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password"
            pattern="(?=.*[A-Z])(?=.*[0-9]).{6,}" 
            title="Password minimal 6 karakter, harus ada huruf kapital dan angka" 
            oninvalid="this.setCustomValidity('Password minimal 6 karakter, harus ada huruf kapital dan angka')" 
            oninput="this.setCustomValidity('')" >
        </div>
        <div class="col-md-6 mb-3">
            <label for="foto_karyawan" class="form-label">Foto Karyawan</label>
            <input type="file" name="foto_karyawan" class="form-control" id="foto_karyawan" accept="image/*">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-end">
            <button type="button" class="btn btn-secondary px-4 btn-tutup" data-target=".btn-karyawan">Kembali</button>
            <button type="submit" class="btn btn-primary px-4">Tambah Karyawan</button>
        </div>
    </div>
</form>

<script type="text/javascript">

    $(document).ready(function() {
        $(document).off('submit', '#tambahKaryawan').on('submit', '#tambahKaryawan', function(e) {
            e.preventDefault();

            var form = $('#tambahKaryawan')[0];
            var formData = new FormData(form); // ini biar bisa ngirim file foto

            $.ajax({
                url: '<?= site_url("karyawan/create_karyawan") ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    toastr.success('Karyawan berhasil ditambahkan!', 'Sukses');
                    $('#tabelKaryawan').DataTable().ajax.reload();
                    $('#modal_frame').modal('hide');

                    setTimeout(function() {
                        $(".btn-karyawan").click();
                    }, 500);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    let msg = 'Gagal menambah Karyawan.';
                    try {
                        let res = JSON.parse(xhr.responseText);
                        msg = res.message || msg;
                    } catch (e) {}

                    toastr.error(msg, 'Error');
                }
            });
        });

        const inputTelepon = document.getElementById('nomor_telepon');
        if (inputTelepon) {
            inputTelepon.addEventListener('input', function () {
                let value = this.value.replace(/\D/g, '');
                if (value.startsWith('0')) {
                    if (value.length > 4 && value.length <= 8) {
                        value = value.replace(/^(\d{4})(\d+)/, '$1-$2');
                    } else if (value.length > 8) {
                        value = value.replace(/^(\d{4})(\d{4})(\d+)/, '$1-$2-$3');
                    }
                }
                this.value = value;
            });
        }

        $(document).on('click', '.btn-tutup', function() {
            let target = $(this).data('target');
            $('#modal_frame').modal('hide');

            if (target) {
                $('#modal_frame').one('hidden.bs.modal', function() {
                    $(target).click();
                });
            }
        });

    });

</script>