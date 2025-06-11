<div class="modal-body">
    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>Toko:</strong> <?= $toko->nama_toko ?></li>
        <li class="list-group-item"><strong>Tanggal Setor:</strong> <?= $log_setor_toko->tanggal_setor ?></li>
        <li class="list-group-item"><strong>Status Bayar:</strong> <?= ucfirst($log_setor_toko->status_bayar) ?></li>
        <li class="list-group-item"><strong>Tanggal Bayar:</strong> <?= $pembayaran_toko->tanggal_bayar ?></li>
        <li class="list-group-item"><strong>Jumlah Uang Dibayar:</strong> Rp <?= number_format($pembayaran_toko->jumlah_uang, 0, ',', '.') ?></li>
    </ul>

    <h6 class="mb-2">Detail Produk yang Dibayar</h6>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah Terjual</th>
                <th>Jumlah Return</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($detail_pembayaran_toko): ?>
                <?php foreach ($detail_pembayaran_toko as $detail): 
                    $nama_produk = $this->Produk_model->get_nama_produk($detail->id_produk); ?>
                    <tr>
                        <td><?= $nama_produk ?></td>
                        <td><?= $detail->jumlah_terjual ?></td>
                        <td><?= $detail->jumlah_return ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data detail pembayaran.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>
