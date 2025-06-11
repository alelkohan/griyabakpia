<table class="table produk_penitip_table">
	<thead class="table-light">
		<tr>
			<th>No</th>
			<th>Nama Produk</th>
			<th>Harga Penitip</th>
			<th>Harga Jual Outlet</th>
			<th>Laba Satuan</th>
			<th>Stok</th>
		</tr>
	</thead>
	<tbody>
		<?php $no = 1 ?>
		<?php foreach ($produk as $key): ?>
			<tr>
				<td><?= $no ?></td>
				<td><?= $key->nama_produk ?></td>
				<td>Rp <?= number_format($key->harga_default,0,',','.') ?></td>
				<td>Rp <?= number_format($key->harga_outlet,0,',','.') ?></td>
				<td><?= number_format($key->harga_outlet - $key->harga_default) ?></td>
				<td><?= $key->stok ?></td>
			</tr>
			<?php $no++; ?>
		<?php endforeach ?>
	</tbody>
</table>