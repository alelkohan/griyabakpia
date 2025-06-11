<form class="" id="bayar-logsetor">
	<input type="hidden" value="<?= $id_log_setor_toko ?>" name="id_log_setor_toko">
	<div class="row">
		<div class="col-md-6">
			<div class="mb-3">
				<label for="tanggal_bayar" class="form-label">Tanggal Pembayaran</label>
				<input type="datetime-local" name="tanggal_bayar" class="form-control" id="tanggal_bayar" value="<?= date('Y-m-d\TH:i:s') ?>" required="">
			</div>
		</div>
		<div class="col-md-6">
			<div class="mb-3">
				<label for="nominal" class="form-label">Nominal</label>
				<input type="text" name="nominal" class="form-control" id="harga_produk2" required="">
			</div>
		</div>
	</div>
	<?php foreach ($detail as $detail): 
		$nama_produk = $this->Produk_model->get_nama_produk($detail->id_produk);?>
		<div class="row">
			<input type="hidden" class="harga-default" value="<?= $detail->harga_toko ?>">
			<div class="col-md-4">
				<div class="mb-3">
					<label for="nama_produk" class="form-label">Nama Produk</label>
					<input type="text" class="form-control" id="nama_produk" value="<?= $nama_produk ?>" readonly required="">
					<input type="hidden" name="id_produk[]" value="<?= $detail->id_produk ?>">
				</div>
			</div>
			<div class="col-md-4">
				<div class="mb-3">
					<label for="produk_terjual" class="form-label">Produk Terjual</label>
					<input type="number" name="produk_terjual[]" class="form-control produk-terjual" id="produk_terjual" >
				</div>
			</div>
			<div class="col-md-4">
				<div class="mb-3">
					<label for="produk_return" class="form-label">Retur Produk</label>
					<input type="number" name="produk_return[]" class="form-control" id="produk_return" required="">
				</div>
			</div>
		</div>
	<?php endforeach; ?>
	<div class="row">
		<div class="col-sm-12 text-end">
			<button type="button" class="btn btn-secondary px-4 m-1 btn-tutup" data-target=".btn-toko-bayar">Kembali</button>
			<button type="submit" class="btn btn-primary px-4 m-1">Simpan Pembayaran</button>
		</div>
	</div>
</form>

<script>
	$(document).ready(function() {

		$(document).off('submit', '#bayar-logsetor').on('submit', '#bayar-logsetor', function(e) {
			e.preventDefault();

			var formData = $(this).serialize();

			$.ajax({
				url: '<?= site_url("produk/modal_bayarform_tokosave") ?>',
				type: 'POST',
				data: formData,
				success: function(response) {
					toastr.success('Produk berhasil ditambahkan!', 'Sukses');

					$('#modal_frame').modal('hide');

					var activeTab = $('.tab-pane.active');
				var activeTable = activeTab.find('.tabelProduk'); // Temukan tabel di tab aktif
				var tableId = activeTable.attr('id');

				// Reload tabel jika ditemukan
				if (tableId && tableProdukInstances[tableId]) {
					tableProdukInstances[tableId].ajax.reload();
				}
			},
			error: function(xhr, status, error) {
				toastr.error('Terjadi kesalahan saat menambahkan Produk.', 'Error');
			}
		});
		});

	});

	$(document).on('click', '.btn-tutup', function() {
		let target = $(this).data('target');
		let id_toko = $(this).data('id_toko');
		
		$('#modal_frame').modal('hide');
		
		$('#modal_frame').one('hidden.bs.modal', function() {
                // Cari tombol yang sesuai dan trigger click
			$(target).filter('[data-id_toko="'+id_toko+'"]').click();
		});
	});


	document.getElementById('harga_produk2').addEventListener('input', function(e) {
		var value = e.target.value;

		value = value.replace(/[^0-9]/g, '');

		if (value) {
			value = 'Rp ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
		}

		e.target.value = value;
	});

	document.querySelectorAll('.produk-terjual').forEach(input => {
		input.addEventListener('input', hitungNominal);
	});

	function hitungNominal() {
		let total = 0;

		document.querySelectorAll('.produk-terjual').forEach((input, index) => {
			let jumlah = parseInt(input.value) || 0;
			let harga = parseInt(document.querySelectorAll('.harga-default')[index].value) || 0;

			total += jumlah * harga;
		});

		let formatted = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
		document.getElementById('harga_produk2').value = 'Rp ' + formatted;
	}

</script>