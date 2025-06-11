<div class="modal-body">
    <div class="mb-3">
        <label class="form-label">Tanggal Setor</label>
        <input type="text" class="form-control" value="<?= date('d M Y H:i', strtotime($logsetor->tanggal_setor)) ?>" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Status Bayar</label>
        <input type="text" class="form-control" value="<?= ucfirst($logsetor->status_bayar) ?>" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Detail Produk Disetor</label>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($detail as $item): 
                        // Asumsi kamu punya fungsi atau method untuk ambil nama produk dari id_produk
                        $nama_produk = $this->Produk_model->get_nama_produk($item->id_produk);
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $nama_produk ?></td>
                            <td><?= $item->jumlah_produk ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($detail)): ?>
                        <tr>
                            <td colspan="3" class="text-center">Belum ada data produk disetor.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 text-end">
        <button type="button" class="btn btn-secondary px-4 btn-tutup" data-target=".btn-toko-bayar">Kembali</button>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() {

        $(document).on('click', '.btn-tutup', function() {
            let target = $(this).data('target');
            let id_toko = $(this).data('id_toko');
            
            $('#modal_frame').modal('hide');
            
            $('#modal_frame').one('hidden.bs.modal', function() {
                // Cari tombol yang sesuai dan trigger click
                $(target).filter('[data-id_toko="'+id_toko+'"]').click();
            });
        });

    });

</script>
