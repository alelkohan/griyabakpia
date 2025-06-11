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
						<h4 class="page-title">Keuangan</h4>
						<div class="">
							<ol class="breadcrumb mb-0">
								<li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a>
								</li><!--end nav-item-->
								<li class="breadcrumb-item active">Keuangan</li>
							</ol>
						</div>
					</div><!--end page-title-box-->
				</div><!--end col-->
			</div><!--end row-->
			<!-- <button class="btn btn-primary btn-sm mb-2 btn-bahan">Oke</button> -->
			<div class="row justify-content-center">
				<div class="col-md-12 col-lg-12">
					<div class="card">
						<div class="card-header">
							<form id="formFilter">
								<div class="row mb-3">
									<div class="col-md-3">
										<label for="mulai_tanggal" class="form-label">Dari Tanggal</label>
										<input type="date" id="mulai_tanggal" name="mulai_tanggal" class="form-control" value="" max="<?= date('Y-m-d') ?>" required>
									</div>
									<div class="col-md-3">
										<label for="sampai_tanggal" class="form-label">Sampai Tanggal</label>
										<input type="date" id="sampai_tanggal" name="sampai_tanggal" class="form-control" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
									</div>
									<div class="col-md-3">
										<label for="asal" class="form-label">Asal Transaksi</label>
										<select id="filterAsal" class="form-control">
											<option value="">-- Semua asal --</option>
										</select>
									</div>
									<div class="col-md-3">
										<label class="form-label">Action</label>
										<div class="d-flex gap-2">
											<button type="submit" class="btn btn-primary w-100">Filter</button>
											<button type="button" id="reset" class="btn btn-danger w-100">Reset</button>
										</div>
									</div>
								</div>
							</form>

							<div class="row align-items-center">
								<div class="col">
									<div class="justify-content-between d-flex">
										<h4 class="card-title">Data Seluruh Riwayat Transaksi</h4>
										<button type="submit" class="btn btn-primary btn-pengeluaran-add">Pengeluaran Lain</button>
									</div>
								</div><!--end col-->
							</div> <!--end row-->
						</div><!--end card-header-->
						<div class="card-body pt-0">
							<div class="table-responsive">
								<table class="table table-centered" id="tabelKeuangan">
									<thead class="table-light">
										<tr>
											<th>Nomor</th>
											<th>Asal Transaksi </th>
											<th>Jenis Transaksi</th>
											<th>Tanggal</th>
											<th>Mutasi</th>
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
	$(document).ready(function() {
		var table_keuangan = $("#tabelKeuangan");

		table_keuangan.DataTable({
			ajax: "<?= base_url('keuangan/get_all_keuangan'); ?>",
			paging: true,
			info: true,
			lengthChange: true,
			searching: true,
			ordering: false,
			pageLength: 10,
			lengthMenu: [5, 8, 10, 25],
			columnDefs: [{ targets: 6, className: 'text-end' }]

		});

		table_keuangan.on('draw.dt', function () {
			table_keuangan.columns.adjust().draw();
		});

		$('#formFilter').on('submit', function(e) {
			e.preventDefault();

			var mulai_tanggal = $('#mulai_tanggal').val();
			var sampai_tanggal = $('#sampai_tanggal').val();
			var asal = $('#filterAsal').val();

			console.log("Dikirim:", mulai_tanggal, sampai_tanggal, asal);

			table_keuangan.DataTable().ajax.url(
				"<?= site_url('keuangan/get_all_keuangan') ?>" +
				"?mulai_tanggal=" + encodeURIComponent(mulai_tanggal) +
				"&sampai_tanggal=" + encodeURIComponent(sampai_tanggal) +
				"&asal=" + encodeURIComponent(asal)
				).
			load(function(json) {
				if (json.data.length === 0) {
					toastr.info("Data tidak ditemukan!", "Info");
				} else {
					toastr.success("Data berhasil difilter!", "Berhasil");
				}
			});
		});

		// Reset form
		$('#reset').on('click', function() {
			$('#mulai_tanggal').val('');
			$('#sampai_tanggal').val('');
			$('#filterAsal').val('');
			table_keuangan.DataTable().ajax.url("<?= site_url('keuangan/get_all_keuangan') ?>").load(function(json) {
				toastr.info("Data berhasil direset!", "Info");
			});
		});

	// 	$(document).on("click", ".btn-delete", function() {
	// 		var id_logbahan = $(this).data("id");            
	// 		var url = "<?= base_url('bahan/delete_logbahan'); ?>";

	// 		bootbox.confirm({
	// 			title: "Konfirmasi Hapus bahan",
	// 			message: "Apakah Anda yakin ingin menghapus bahan ini?",
	// 			buttons: {
	// 				confirm: {
	// 					label: "Hapus",
	// 					className: "btn-danger"
	// 				},
	// 				cancel: {
	// 					label: "Batal",
	// 					className: "btn-secondary"
	// 				}
	// 			},
	// 			callback: function(result) {
	// 				if(result) {
	// 					$.post(url, {id_logbahan: id_logbahan}, function(res) {
	// 						if(res.status == "sukses") {
	// 							toastr.success(res.pesan);
	// 							table_logbahan.DataTable().ajax.reload();
	// 						} else {
	// 							toastr.warning(res.pesan);
	// 						}
	// 					});
	// 				}
	// 			}
	// 		});
	// 	});

		$(document).on("click", ".btn-pengeluaran-add", function() {
			let frame = $("#modal_frame");

			frame.find(".modal-title").html("Tambah Pengeluaran lain");

            	// Menampilkan modal
			frame.modal("show");

            	// Mengambil data tabungan untuk diedit
			$.get("<?= site_url('keuangan/modal_add_non_operasional'); ?>", function(res) {
				frame.find(".modal-body").html(res);
			});
		});

		$(document).on('submit', '#tambahTransaksi', function (e) {
			e.preventDefault();
			let formData = $(this).serialize();
			$.ajax({
				url: '<?= site_url("keuangan/create_transaksi") ?>',
				type: 'POST',
				data: formData,
				success: function (res) {
					toastr.success('Transaksi berhasil ditambahkan!');
					$('#modal_frame').modal('hide');
					$('#tabelKeuangan').DataTable().ajax.reload();

				},
				error: function (xhr) {
					let msg = 'Terjadi kesalahan saat menambahkan Transaksi.';
					try {
						msg = JSON.parse(xhr.responseText).message || msg;
					} catch (e) { }
					toastr.error(msg);
				}
			});
		});

	// 	$(document).on("click", ".btn-logbahan-edit", function() {
	// 		let frame = $("#modal_frame");

    //         // Mengambil dari atribut data-id tombol yang diklik
	// 		let id_logbahan = $(this).data("id");

	// 		frame.find(".modal-title").html("Edit Aktivitas");

    //         // Menampilkan modal
	// 		frame.modal("show");

    //         // Mengambil data tabungan untuk diedit
	// 		$.get("<?= site_url('bahan/modal_edit_logbahan'); ?>/" + id_logbahan, function(res) {
	// 			frame.find(".modal-body").html(res);
	// 		});
	// 	});

	// 	$(document).on("click", ".btn-bahan", function() {
	// 		let frame = $("#modal_frame");

	// 		frame.find(".modal-title").html("Data Bahan");

    //         	// Menampilkan modal
	// 		frame.modal("show");

    //         	// Mengambil data tabungan untuk diedit
	// 		$.get("<?= site_url('bahan/modal_bahan'); ?>", function(res) {
	// 			frame.find(".modal-body").html(res);
	// 		});
	// 	});

	});

</script>
</body>
<!--end body-->

</html>