<ul class="nav nav-tabs nav-justified mb-3" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link py-2 active" id="tab-detail" data-bs-toggle="tab" data-bs-target="#tab-pane-detail" type="button" role="tab">
            Detail Absensi
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link py-2" id="tab-gaji" data-bs-toggle="tab" data-bs-target="#tab-pane-gaji" type="button" role="tab">
            Form Gaji
        </button>
    </li>
</ul>
<div class="tab-content" id="pills-tabContent">
    <!-- Tab 1: Detail Absensi -->
    <div class="tab-pane fade show active" id="tab-pane-detail" role="tabpanel">
        <div class="row d-flex align-items-start">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body d-flex gap-3"> <!-- Biar foto & tabel sejajar -->
                        <div class="col-auto">
                            <img src="upload/karyawan/<?= $karyawan->foto_karyawan ?>" alt="Foto <?= $karyawan->nama_karyawan ?>" class="rounded" style="width: 150px; height: auto; object-fit: cover;"> 
                        </div>
                        <div class="col">
                            <table class="table table-striped table-bordered h-100">
                                <tbody>
                                    <tr>
                                        <th>Nama Karyawan</th>
                                        <td><?= $karyawan->nama_karyawan ?></td>
                                    </tr>
                                    <tr>
                                        <th>Peran Karyawan</th>
                                        <td><?= $karyawan->peran_karyawan ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Hadir Bulan ini</th>
                                        <td><?= $rekap_status['hadir'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Sakit Bulan ini</th>
                                        <td><?= $rekap_status['sakit'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Izin Bulan ini</th>
                                        <td><?= $rekap_status['izin'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Alpha Bulan ini</th>
                                        <td><?= $rekap_status['alpha'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom 2: Chart -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Chart Absensi</h4>
                    </div>
                    <div class="card-body">
                        <canvas 
                        id="doughnut" 
                        height="600"
                        data-hadir="<?= $rekap_status['hadir'] ?>"
                        data-sakit="<?= $rekap_status['sakit'] ?>"
                        data-izin="<?= $rekap_status['izin'] ?>"
                        data-alpha="<?= $rekap_status['alpha'] ?>"
                        ></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-centered" id="tabelGaji">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Status Kehadiran</th>
                        <th>Tanggal & Waktu Masuk</th>
                        <th>Tanggal & Waktu Pulang</th>
                        <th>Lembur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1 ?>
                    <?php foreach ($absensi as $row) { ?>
                        <tr>
                            <td><?= $row->created_at ?></td>
                            <td><?= $row->status ?></td>
                            <td>
                                <?= (!empty($row->waktu_masuk) && date('H:i', strtotime($row->waktu_masuk)) !== '00:00') 
                                ? date('H:i', strtotime($row->waktu_masuk)) 
                                : '-' ?>
                            </td>
                            <td>
                                <?= (!empty($row->waktu_pulang) && date('H:i', strtotime($row->waktu_pulang)) !== '00:00') 
                                ? date('H:i', strtotime($row->waktu_pulang)) 
                                : '-' ?>
                            </td>
                            <td><?= !empty($row->lembur) ? $row->lembur . ' jam' : '-' ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table><!--end /table-->
        </div>
        <button type="button" class="btn btn-secondary px-4 btn-tutup" data-target=".btn-gaji-add">Kembali</button>
    </div>
    <div class="tab-pane fade" id="tab-pane-gaji" role="tabpanel">
        <form id="gajiKaryawan">
            <input type="hidden" name="id_karyawan" value="<?= $karyawan->id_karyawan ?>">

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Karyawan</label>
                <input type="text" class="form-control" readonly value="<?= $karyawan->nama_karyawan ?>">
            </div>

            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" name="tanggal" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="mb-3">
                <label for="jumlah_gaji" class="form-label">Jumlah Gaji</label>
                <input type="text" class="form-control" name="jumlah_gaji" id="jumlah_gaji" required>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" name="keterangan" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Gaji</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('jumlah_gaji').addEventListener('input', function(e) {
        var value = e.target.value;

        value = value.replace(/[^0-9]/g, '');

        if (value) {
            value = 'Rp ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        e.target.value = value;
    });
</script>