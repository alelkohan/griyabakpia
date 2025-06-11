<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-pembayaran-tab" data-bs-toggle="tab" data-bs-target="#nav-pembayaran" type="button" role="tab" aria-controls="nav-pembayaran" aria-selected="true">Pembayaran</button>
        <button class="nav-link" id="nav-riwayat-pembayaran-tab" data-bs-toggle="tab" data-bs-target="#nav-riwayat-pembayaran" type="button" role="tab" aria-controls="nav-riwayat-pembayaran" aria-selected="false">Riwayat pembayaran</button>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-pembayaran" role="tabpanel" aria-labelledby="nav-pembayaran-tab">
        <?php if (count($titipan_toko) > 0): ?>
            <div class="container">
                <form id="pembayaran_penitip">
                    <select class="form-control mb-3 mt-3" name="id_titipan_toko" id="select_titipan">
                        <option value="">Pilih Tanggal</option>
                        <?php foreach ($titipan_toko as $key): ?>
                            <option value="<?= $key->id_titipan_toko ?>" data-total="<?= $key->total_bayar ?>">
                                pembayaran tanggal : <?= date('d-m-Y', strtotime($key->waktu)) ?>
                            </option>
                        <?php endforeach ?>
                    </select>

                    <input type="hidden" name="bayar" id="input_bayar" value="">
                    <input type="hidden" name="id_pemilik" value="<?= $id_pemilik ?>">
                    <label>Keterangan</label>
                    <textarea class="form-control mb-3" name="keterangan" required></textarea>
                    <h4>Total bagi hasil : <span id="total_display">Rp ...</span></h4>
                    <div class="alert alert-warning">
                        <p>Dengan membayar bagi hasil kepada penitip berarti produk sisa yang belum terjual pada setoran ini akan dijadikan produk retur kepada penitip.</p>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Bayar</button>
                    </div>
                </form>
                <script>
                    $(document).ready(function() {
                        $('#select_titipan').on('change', function() {
                            let selected = $(this).find('option:selected');
                            let total = parseInt(selected.data('total')) || 0;

                            let totalFormatted = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(total);

                            $('#total_display').text(totalFormatted);
                            $('#input_bayar').val(total);
                        });
                    });
                </script>
            </div>
        <?php else: ?>
            <p class="text-center mt-3"><em>Belum ada data bagi hasil terbaru yang bisa ditampilkan atau sudah lunas semua.</em></p>
        <?php endif ?>
    </div>
    <div class="tab-pane fade" id="nav-riwayat-pembayaran" role="tabpanel" aria-labelledby="nav-riwayat-pembayaran-tab">
        <div class="table-responsive mt-3">
            <table class="table riwayat_pembayaran_table">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nominal</th>
                        <th>Waktu</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($bayar_titipan_toko as $key): ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td>Rp <?= number_format($key->nominal,0,',','.') ?></td>
                            <td><?= $key->waktu ?></td>
                            <td><?= $key->keterangan ?></td>
                        </tr>
                        <?php $no++; ?>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
