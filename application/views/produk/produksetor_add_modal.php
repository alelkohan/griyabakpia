<form id="formSetorProduk" method="POST"> 
	<div id="produkContainer">
		<input type="hidden" name="id_toko" value="<?= $id_toko ?>">
		<div class="row produk-item">
			<div class="col-md-6">
				<div class="mb-3">
					<select name="produk[]" class="form-select produk-select" required>
						<option value="" disabled selected>Pilih Produk</option>
						<?php foreach ($produk as $p): ?>
							<option value="<?= $p->id_produk ?>"><?= $p->nama_produk ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-5">
				<div class="mb-3">
					<input type="number" name="jumlah[]" placeholder="Jumlah" class="form-control" required>
				</div>
			</div>
			<div class="col-md-1 d-flex align-items-center">
				<button type="button" class="btn btn-danger btn-sm hapusProduk">🗑️</button>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="d-flex justify-content-between align-items-center">
			<button type="button" id="tambahProdukstok" class="btn btn-primary m-1">+ Tambah Produk</button>
			<div class="d-flex gap-2 ms-auto">
				<button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
				<button type="submit" class="btn btn-primary px-4">Simpan Setoran</button>
			</div>
		</div>
	</div>
</form>

<script>
	$(document).ready(function () {
		// Simpan daftar produk awal
		let allProduk = <?= json_encode($produk) ?>;
		let maxProduk = allProduk.length;
		let selectedProdukIds = new Set();

		$('#tambahProdukstok').click(function () {
			let currentCount = $('.produk-item').length;
			if (currentCount >= maxProduk) {
				toastr.warning('Maksimal produk yang bisa ditambahkan adalah ' + maxProduk);
				return;
			}
				// Filter produk yang belum dipilih
			let availableProduk = allProduk.filter(p => !selectedProdukIds.has(p.id_produk.toString()));

				// Buat opsi dropdown
			let options = '<option value="" disabled selected>Pilih Produk</option>';
			availableProduk.forEach(p => {
				options += `<option value="${p.id_produk}">${p.nama_produk}</option>`;
			});

			let produkItem = `
				<div class="row produk-item">
						<div class="col-md-6">
								<div class="mb-3">
										<select name="produk[]" class="form-select produk-select" required>
												${options}
										</select>
								</div>
						</div>
						<div class="col-md-5">
								<div class="mb-3">
										<input type="number" name="jumlah[]" placeholder="Jumlah" class="form-control" required>
								</div>
						</div>
						<div class="col-md-1 d-flex align-items-center">
								<button type="button" class="btn btn-danger btn-sm hapusProduk">🗑️</button>
						</div>
				</div>`;
				
				$('#produkContainer').append(produkItem);
			});

		// Update daftar produk terpilih saat ada perubahan
		$(document).on('change', '.produk-select', function() {
			updateSelectedProduk();
		});

		// Fungsi untuk update daftar produk terpilih
		function updateSelectedProduk() {
			selectedProdukIds.clear();
			$('.produk-select').each(function() {
				let val = $(this).val();
				if (val) selectedProdukIds.add(val);
			});

				// Update opsi di semua dropdown
			$('.produk-select').each(function() {
				let currentVal = $(this).val();
				let options = '<option value="" disabled selected>Pilih Produk</option>';

				allProduk.forEach(p => {
								// Tampilkan produk jika belum dipilih atau produk ini yang sedang dipilih
					if (!selectedProdukIds.has(p.id_produk.toString()) || p.id_produk.toString() === currentVal) {
						let selected = p.id_produk.toString() === currentVal ? 'selected' : '';
						options += `<option value="${p.id_produk}" ${selected}>${p.nama_produk}</option>`;
					}
				});

				$(this).html(options);
			});
		}

		// Saap menghapus produk
		$(document).on('click', '.hapusProduk', function() {
			$(this).closest('.produk-item').remove();
			updateSelectedProduk();
		});

		$('#formSetorProduk').submit(function (e) {
			e.preventDefault();
			let formData = $(this).serialize();

			$.ajax({
				url: '<?= site_url("produk/modal_add_produksetorsave") ?>',
				method: 'POST',
				data: formData,
				success: function (res) {
					toastr.success('Setoran berhasil disimpan!');

					let id_toko = $('input[name="id_toko"]').val();

					$('#modal_frame').modal('hide');

					var activeTab = $('.tab-pane.active');
					var activeTable = activeTab.find('.tabelProduk'); // Temukan tabel di tab aktif
					var tableId = activeTable.attr('id');

					// Reload tabel jika ditemukan
					if (tableId && tableProdukInstances[tableId]) {
							tableProdukInstances[tableId].ajax.reload();
					}

					
				},
				error: function () {
					toastr.error('Gagal menyimpan setoran.');
				}
			});
		});
	});
</script>
