<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <button class="nav-link active" id="nav-setoran-tab" data-bs-toggle="tab" data-bs-target="#nav-setoran" type="button" role="tab" aria-controls="nav-setoran" aria-selected="true">Setoran</button>
    <button class="nav-link" id="nav-riwayat-tab" data-bs-toggle="tab" data-bs-target="#nav-riwayat" type="button" role="tab" aria-controls="nav-riwayat" aria-selected="false">Riwayat</button>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-setoran" role="tabpanel" aria-labelledby="nav-setoran-tab">
    <select id="produkSelect" class="form-control mb-3 mt-3">
        <option value="">Pilih produk</option>
        <?php foreach ($produk_penitip as $key): ?>
            <option value="<?= $key->id_produk ?>" data-nama="<?= $key->nama_produk ?>">
                <?= $key->nama_produk ?>
            </option>
        <?php endforeach ?>
    </select>

    <form id="setoran_penitip">
        <input type="hidden" name="id_titipan_toko" value="<?= $id_pemilik ?>">
        <input type="hidden" name="id_outlet" value="<?= $id_outlet ?>">

        <div id="produkContainer"></div>

        <label>Keterangan</label>
        <textarea class="form-control mb-3" name="keterangan" required></textarea>
        <input type="hidden" name="id_pemilik" value="<?= $id_pemilik ?>">
        <div class="alert alert-warning">
            <p>Dengan menyetorkan produk baru maka produk sisa pada setoran sebelumnya akan dijadikan produk retur kepada penitip.</p>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <button type="submit" class="btn btn-primary">Simpan Setoran</button>
        </div>
    </form>
  </div>
  <div class="tab-pane fade" id="nav-riwayat" role="tabpanel" aria-labelledby="nav-riwayat-tab">
      <table class="riwayat_titipan_table table">
        <thead class="table-light">
            <tr>
                <th>No.</th>
                <th class="text-end">Waktu</th>
                <th class="text-end">Lunas</th>
                <th class="text-end">Keterangan</th>
                <th class="text-end">Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($titipan_toko as $key): ?>
                <tr>
                    <td><?= $no ?></td>
                    <td class="text-end"><?= $key->waktu ?></td>
                    <td class="text-end"><span class="badge bg-<?= ($key->lunas === 'true')? 'success' : 'danger'?>"><?= ($key->lunas === 'true')? 'Lunas' : 'Belum lunas'?></span></td>
                    <td class="text-end"><?= $key->keterangan ?></td>
                    <td class="text-end">
                        <button type="button" class="btn btn-soft-info btn-detail-titipan" data-id="<?= $key->id_titipan_toko ?>">Detail</button>
                    </td>
                </tr>
                <?php $no++; ?>
            <?php endforeach ?>
        </tbody>
    </table>
  </div>
</div>
<script>
$(document).ready(function() {
    var frame = $('#modal_frame');
    
    $('#produkSelect').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var idProduk = selectedOption.val();
        var namaProduk = selectedOption.data('nama');

        if (!idProduk) return; // Jika "Pilih produk" yang dipilih

        // Cek apakah produk sudah ditambahkan
        if ($('#produkContainer input[name="id_produk[]"][value="' + idProduk + '"]').length) {
            alert("Produk sudah ditambahkan.");
            return;
        }

        var html = `
            <div class="row produk align-items-center mb-2">
                <input type="hidden" name="id_produk[]" value="${idProduk}">
                <div class="col-lg-6 mb-1">
                    <input type="text" name="nama_produk[]" class="form-control" value="${namaProduk}" readonly>
                </div>
                <div class="col-lg-6 mb-1 d-flex justify-content-between">
                    <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" min="1" required>
                    <button type="button" class="btn btn-danger btn-hapus" style="margin-left:10px;">Hapus</button>
                </div>
            </div>
        `;

        $('#produkContainer').append(html);

        // Reset pilihan
        $(this).val('');
    });

    // Event hapus
    $(document).on('click', '.btn-hapus', function() {
        $(this).closest('.row.produk').remove();
    });
});
</script>