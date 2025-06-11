<?php $this->load->view('templates/admin/head') ?>
<?php $this->load->view('templates/admin/topbar') ?>
<?php $this->load->view('templates/admin/sidebar') ?>

<div class="page-wrapper">

	<!-- Page Content-->
	<div class="page-content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<div class="page-title-box d-md-flex justify-content-md-between align-items-center">
						<h4 class="page-title">Bahan dan Aktivitas Bahan</h4>
						<div class="">
							<ol class="breadcrumb mb-0">
								<li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a>
								</li><!--end nav-item-->
								<li class="breadcrumb-item active">Data Bahan</li>
							</ol>
						</div>
					</div><!--end page-title-box-->
				</div><!--end col-->
			</div><!--end row-->
			<button class="btn btn-primary btn-sm mb-2 btn-bahan">Data Bahan</button>
			<div class="row justify-content-center">
				<div class="col-md-12 col-lg-12">
					<div class="card">
						<div class="card-header">
							<div class="row align-items-center">
								<div class="col">
									<div class="justify-content-between d-flex">
										<h4 class="card-title">Data Aktivitas Bahan</h4>
										<button type="submit" class="btn btn-primary btn-sm btn-logbahan-add">Buat Aktivitas Bahan</button>
									</div>
								</div><!--end col-->
							</div> <!--end row-->
						</div><!--end card-header-->
						<div class="card-body pt-0">
							<div class="table-responsive">
								<table class="table table-centered" id="tabelLogBahan">
									<thead class="table-light">
										<tr>
											<th>Nomor</th>
											<th>Nama Bahan</th>
											<th>Tanggal</th>
											<th>Jenis Aktivitas</th>
											<th>Jumlah</th>
											<th>Harga Satuan</th>
											<th>Harga Total</th>
											<th>Keterangan</th>
											<th class="text-end">Action</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table><!--end /table-->
							</div><!--end /tableresponsive-->
						</div><!--end card-body-->
					</div><!--end card-->
				</div> <!--end col-->
			</div><!--end row-->

		</div><!-- container -->

		<?php $this->load->view('templates/admin/foot') ?>

		<!--end footer-->
	</div>
<!-- end page content -->
</div>
<!-- end page-wrapper -->

<div class="modal fade bd-example-modal-lg" id="modal_frame" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title m-0" id="myLargeModalLabel"></h6>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div><!--end modal-header-->
			<div class="modal-body">

			</div><!--end modal-body-->
		</div><!--end modal-content-->
	</div><!--end modal-dialog-->
</div><!--end modal-->

<?php $this->load->view('templates/admin/link') ?>

<script>
	$(document).ready(function () {
	// --- Inisialisasi DataTable Log Bahan
		var table_logbahan = $("#tabelLogBahan").DataTable({
			ajax: "<?= base_url('bahan/get_all_logbahan'); ?>",
			paging: true,
			info: true,
			lengthChange: true,
			searching: true,
			ordering: false,
			pageLength: 10,
			lengthMenu: [5, 8, 10, 25],
			columnDefs: [{ targets: 8, className: 'text-end' }]
		});

	// --- Modal Tambah Log Bahan
		$(document).on("click", ".btn-logbahan-add", function () {
			let frame = $("#modal_frame");
			frame.find(".modal-title").html("Tambah Aktivitas Bahan");
			frame.modal("show");
			$.get("<?= site_url('bahan/modal_add_logbahan'); ?>", function (res) {
				frame.find(".modal-body").html(res);
			});
		});

	// --- Modal Edit Log Bahan
		$(document).on("click", ".btn-logbahan-edit", function () {
			let id_logbahan = $(this).data("id");
			let frame = $("#modal_frame");
			frame.find(".modal-title").html("Edit Aktivitas");
			frame.modal("show");
			$.get("<?= site_url('bahan/modal_edit_logbahan'); ?>/" + id_logbahan, function (res) {
				frame.find(".modal-body").html(res);
			});
		});

	// --- Modal Data Bahan
		$(document).on("click", ".btn-bahan", function () {
			let frame = $("#modal_frame");
			frame.find(".modal-title").html("Data Bahan");
			frame.modal("show");
			$.get("<?= site_url('bahan/modal_bahan'); ?>", function (res) {
				frame.find(".modal-body").html(res);

					// --- Inisialisasi DataTable Bahan
				var table_bahan = $("#tabelBahan").DataTable({
					ajax: "<?= base_url('bahan/get_bahan'); ?>",
					paging: true,
					info: true,
					lengthChange: true,
					searching: true,
					ordering: false,
				});

			});
		});

	// --- Hapus Log Bahan
		$(document).on("click", ".btn-delete", function () {
			let id = $(this).data("id");
			bootbox.confirm({
				title: "Konfirmasi Hapus bahan",
				message: "Apakah Anda yakin ingin menghapus bahan ini?",
				buttons: {
					confirm: { label: "Hapus", className: "btn-danger" },
					cancel: { label: "Batal", className: "btn-secondary" }
				},
				callback: function (result) {
					if (result) {
						$.post("<?= base_url('bahan/delete_logbahan'); ?>", { id_logbahan: id }, function (res) {
							if (res.status == "sukses") {
								toastr.success(res.pesan);
								table_logbahan.ajax.reload();
							} else {
								toastr.warning(res.pesan);
							}
						});
					}
				}
			});
		});

	// --- Modal Tambah Bahan
		$(document).on("click", ".btn-bahan-add", function () {
			let frame = $("#modal_frame");
			frame.find(".modal-title").html("Tambah Bahan");
			frame.one('hidden.bs.modal', function () {
				frame.modal("show");
				$.get("<?= site_url('bahan/modal_add_bahan'); ?>", function (res) {
					frame.find(".modal-body").html(res);
				});
			});
			frame.modal("hide");
		});

	// --- Modal Edit Bahan
		$(document).on("click", ".btn-bahan-edit", function () {
			let id = $(this).data("id");
			let frame = $("#modal_frame");
			frame.find(".modal-title").html("Edit Bahan");
			frame.one('hidden.bs.modal', function () {
				frame.modal("show");
				$.get("<?= site_url('bahan/modal_edit_bahan'); ?>/" + id, function (res) {
					frame.find(".modal-body").html(res);
				});
			});
			frame.modal("hide");
		});

	// --- Hapus Bahan
		$(document).on("click", ".btn-delete-bahan", function () {
			let id = $(this).data("id");
			$('#modal_frame').modal('hide');
			$('#modal_frame').off('hidden.bs.modal').on('hidden.bs.modal', function () {
				$(this).off('hidden.bs.modal');
				bootbox.confirm({
					title: "Konfirmasi Hapus Bahan",
					message: "Apakah Anda yakin ingin menghapus bahan ini?",
					buttons: {
						confirm: { label: "Hapus", className: "btn-danger" },
						cancel: { label: "Batal", className: "btn-secondary" }
					},
					callback: function (result) {
						if (result) {
							$.post("<?= base_url('bahan/delete_bahan'); ?>", { id_bahan: id }, function (res) {
								if (res.status == "sukses") {
									toastr.success(res.pesan);
									$('#tabelBahan').DataTable().ajax.reload();
									$('#tabelLogBahan').DataTable().ajax.reload();
									$(".btn-bahan").click();
								} else {
									toastr.warning(res.pesan);
								}
							});
						} else {
							setTimeout(function() {
								$(".btn-bahan").click(); // trigger buka ulang modal bahan
							}, 500);
						}
					}
				});
			});
		});

	// --- Submit Form Tambah Bahan
		$(document).on('submit', '#tambahBahan', function (e) {
			e.preventDefault();
			let formData = $(this).serialize();
			$.ajax({
				url: '<?= site_url("bahan/create_bahan") ?>',
				type: 'POST',
				data: formData,
				success: function (res) {
					toastr.success('Bahan berhasil ditambahkan!');
					$('#modal_frame').modal('hide');
					$('#tabelBahan').DataTable().ajax.reload();
					setTimeout(function() {
						$(".btn-bahan").click(); // trigger buka ulang modal bahan
					}, 500);

				},
				error: function (xhr) {
					let msg = 'Terjadi kesalahan saat menambahkan bahan.';
					try {
						msg = JSON.parse(xhr.responseText).message || msg;
					} catch (e) { }
					toastr.error(msg);
				}
			});
		});

	// --- Submit Form Edit Bahan
		$(document).on('submit', '#editBahan', function (e) {
			e.preventDefault();
			let formData = $(this).serialize();
			$.ajax({
				url: '<?= site_url("bahan/update_bahan") ?>',
				type: 'POST',
				data: formData,
				success: function (res) {
					toastr.success('Bahan berhasil diupdate!');
					$('#modal_frame').modal('hide');
					$('#tabelBahan').DataTable().ajax.reload();
					setTimeout(function() {
						$(".btn-bahan").click(); 
					}, 500);

				},
				error: function (xhr) {
					let msg = 'Terjadi kesalahan saat mengupdate bahan.';
					try {
						msg = JSON.parse(xhr.responseText).message || msg;
					} catch (e) { }
					toastr.error(msg);
				}
			});
		});

	// --- Submit Tambah Log Bahan
		$(document).on('submit', '#tambahLogBahan', function (e) {
			e.preventDefault();
			let formData = $(this).serialize();
			$.ajax({
				url: '<?= site_url("bahan/create_logbahan") ?>',
				type: 'POST',
				data: formData,
				success: function (response) {
					if (response.status === 'error') {
						toastr.error(response.message || 'Terjadi kesalahan.');
						return;
					}
					toastr.success('Aktivitas berhasil disimpan!');
					$('#modal_frame').modal('hide');
					table_logbahan.ajax.reload();
					$('#tabelBahan').DataTable().ajax.reload();
				},
				error: function (xhr) {
					let msg = 'Terjadi kesalahan saat menambahkan Aktivitas.';
					try {
						msg = JSON.parse(xhr.responseText).message || msg;
					} catch (e) { }
					toastr.error(msg);
				}
			});
		});

	// --- Submit Edit Log Bahan
		$(document).on('submit', '#editLogBahan', function (e) {
			e.preventDefault();
			let formData = $(this).serialize();
			$.ajax({
				url: '<?= site_url("bahan/update_logbahan") ?>',
				type: 'POST',
				data: formData,
				success: function () {
					toastr.success('Aktivitas berhasil diupdate!');
					$('#modal_frame').modal('hide');
					table_logbahan.ajax.reload();
					$('#tabelBahan').DataTable().ajax.reload();
				},
				error: function(xhr) {
					console.log(xhr.responseText);
					let msg = 'Terjadi kesalahan saat mengubah Aktivitas.';
					try {
						let res = JSON.parse(xhr.responseText);
						msg = res.message || msg;
					} catch (e) {}

					toastr.error(msg, 'Error');
				}
			});
		});

	// --- Modal Tutup dengan Target
		$(document).on('click', '.btn-tutup', function () {
			let target = $(this).data('target');
			$('#modal_frame').modal('hide');
			if (target) {
				$('#modal_frame').one('hidden.bs.modal', function () {
					$(target).click();
				});
			}
		});

	// --- Fungsi Format Rupiah
		function formatRupiah(angka) {
			let number_string = angka.replace(/[^,\d]/g, '').toString(),
			split = number_string.split(','),
			sisa = split[0].length % 3,
			rupiah = split[0].substr(0, sisa),
			ribuan = split[0].substr(sisa).match(/\d{3}/gi);

			if (ribuan) {
				let separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
			return split[1] !== undefined ? 'Rp ' + rupiah + ',' + split[1] : 'Rp ' + rupiah;
		}

	// --- Toggle Harga Saat Jenis Aktivitas Dipilih
		function toggleHargaFields() {
			let jenis = $('select[name="jenis_aktivitas"]').val();
			let hargaFields = $('.harga-wrapper');

			if (jenis === 'pemakaian') {
				hargaFields.hide().find('input').prop('required', false).prop('disabled', true);
			} else {
				hargaFields.show().find('input').prop('required', true).prop('disabled', false);
			}
		}

	// --- Event & Initial Load Harga
		$(document).on('change', 'select[name="jenis_aktivitas"]', toggleHargaFields);
		toggleHargaFields();

	// --- Kalkulasi Otomatis Total Harga
		$(document).on('input', 'input[name="harga_satuan"], input[name="jumlah"]', function () {
			let harga_satuan = $('input[name="harga_satuan"]').val().replace(/[^0-9]/g, '') || 0;
			let jumlah = $('input[name="jumlah"]').val() || 0;
			let total = parseInt(harga_satuan) * parseInt(jumlah);
			$('input[name="harga_total"]').val(formatRupiah(total.toString()));
		});

	// --- Format saat input harga
		$(document).on('input', 'input[name="harga_satuan"], input[name="harga_total"]', function () {
			let val = $(this).val();
			$(this).val(formatRupiah(val));
		});
	});

</script>

</body>
<!--end body-->

</html>