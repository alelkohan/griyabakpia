<form id="tambahTransaksi">
    <div class="row">
        <div class="col">
            <div class="mb-3">
                <label for="jenis_transaksi" class="form-label">Jenis Transaksi</label>
                <select name="jenis_transaksi" class="form-select" id="jenis_transaksi" required>
                    <option value="" disabled selected>Pilih Jenis Transaksi</option>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </div>
        </div>
        <div class="col">
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal Transaksi</label>
                <input type="date" name="tanggal" class="form-control" id="tanggal" value="<?= date('Y-m-d') ?>" required readonly>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="mb-3">
                <label for="nilai" class="form-label">Nilai</label>
                <input type="text" name="nilai" class="form-control" id="nilai" required>
            </div>
        </div>
        <div class="col">
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <input type="text" name="keterangan" class="form-control" id="keterangan">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col text-end">
            <button type="submit" class="btn btn-primary px-4">Simpan Transaksi</button>
        </div>
    </div>
</form>

<script>
    document.getElementById('nilai').addEventListener('input', function(e) {
        var value = e.target.value;

        value = value.replace(/[^0-9]/g, '');

        if (value) {
            value = 'Rp ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        e.target.value = value;
    });

</script>