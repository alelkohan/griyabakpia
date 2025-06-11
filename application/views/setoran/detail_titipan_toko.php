<div class="table-responsive">
	<table class="table produk_penitip_table">
		<thead class="table-light">
			<tr>
				<th>No</th>
				<th>Nama Produk</th>
				<th>Jumlah</th>
				<th>Laku</th>
				<th>Retur</th>
			</tr>
		</thead>
		<tbody>
			<?php $no = 1 ?>
			<?php foreach ($detail_titipan_toko as $key): ?>
				<tr>
					<td><?= $no ?></td>
					<td><?= $key->nama_produk ?></td>
					<td><?= $key->jumlah ?></td>
					<td><?= $key->laku ?></td>
					<td><?= $key->retur ?></td>
				</tr>
				<?php $no++; ?>
			<?php endforeach ?>
		</tbody>
	</table>
</div>
<div class="d-flex justify-content-end">
	<button type="button" class="btn btn-secondary btn-setoran-penitip" data-id="<?= $id_pemilik ?>">Kembali</button>
</div>