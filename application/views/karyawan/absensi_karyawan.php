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
						<h4 class="page-title">Karyawan</h4>
						<div class="">
							<ol class="breadcrumb mb-0">
								<li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a>
								</li><!--end nav-item-->
								<li class="breadcrumb-item active">Absensi Karyawan</li>
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
							<div class="row align-items-center">
								<div class="col">
									<div class="justify-content-between d-flex">
										<h4 class="card-title">Data Absen Karyawan</h4>
										<div class="d-flex gap-2">
											<button type="submit" class="btn btn-primary btn-sm btn-absen-masuk">Absen Masuk Karyawan</button>
											<button type="submit" class="btn btn-primary btn-sm btn-absen-lembur">Absen Lembur</button>
											<button type="submit" class="btn btn-primary btn-sm btn-absen-pulang">Absen Pulang Karyawan</button>
										</div>
									</div>
								</div><!--end col-->
							</div> <!--end row-->
						</div><!--end card-header-->
						<div class="card-body pt-0">
							<div class="table-responsive">
								<table class="table table-centered" id="tabelAbsensi">
									<thead class="table-light">
										<tr>
											<tr>
												<th>Nomor</th>
												<th>Nama Karyawan</th>
												<th>Peran</th>
												<th>Waktu Masuk</th>
												<th>Lembur</th>
												<th>Waktu Pulang</th>
												<th>Status Kehadiran</th>
												<th class="text-end">Action</th>
											</tr>
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
		var table_absensi = $("#tabelAbsensi");

		table_absensi.DataTable({
			ajax: "<?= base_url('karyawan/get_all_absensi'); ?>",
			paging: true,
			info: true,
			lengthChange: true,
			searching: true,
			ordering: false,
			pageLength: 10,
			lengthMenu: [5, 8, 10, 25],
			columnDefs: [
				{ targets: 2, className: 'align-items-center' },
				{ targets: 7, className: 'text-end' },
				{ targets: 6, className: 'text-center' }
			]
		});

		table_absensi.on('draw.dt', function () {
			table_absensi.columns.adjust().draw();
		});

		$(document).on("click", ".btn-absensi-delete", function() {
			var id_absensi = $(this).data("id");            
			var url = "<?= base_url('karyawan/delete_absensi'); ?>";

			bootbox.confirm({
				title: "Konfirmasi Hapus Absensi",
				message: "Apakah Anda yakin ingin menghapus absensi ini?",
				buttons: {
					confirm: {
						label: "Hapus",
						className: "btn-danger"
					},
					cancel: {
						label: "Batal",
						className: "btn-secondary"
					}
				},
				callback: function(result) {
					if(result) {
						$.post(url, {id_absensi: id_absensi}, function(res) {
							if(res.status == "sukses") {
								toastr.success(res.pesan);
								table_absensi.DataTable().ajax.reload();
							} else {
								toastr.warning(res.pesan);
							}
						});
					}
				}
			});
		});

		$(document).on("click", ".btn-absensi-edit", function() {
			let frame = $("#modal_frame");

			let id_absensi = $(this).data("id");

			frame.find(".modal-title").html("Edit Absensi");

            // Menampilkan modal
			frame.modal("show");


			$.get("<?= site_url('karyawan/modal_edit_absensi'); ?>/" + id_absensi, function(res) {
				frame.find(".modal-body").html(res);
			});

		});

		$(document).on("click", ".btn-absen-lembur", function() {
			let frame = $("#modal_frame");

			frame.find(".modal-title").html("Tambah Lembur");

            // Menampilkan modal
			frame.modal("show");

			$.get("<?= site_url('karyawan/modal_lembur'); ?>", function(res) {
				frame.find(".modal-body").html(res);
			});

		});

		$(document).on("click", ".btn-absen-masuk", function() {
			let frame = $("#modal_frame");

			frame.find(".modal-title").html("Absen Karyawan");

            	// Menampilkan modal
			frame.modal("show");

            	// Mengambil data karyawan untuk diedit
			$.get("<?= site_url('karyawan/modal_absenmasuk_karyawan'); ?>", function(res) {
				frame.find(".modal-body").html(res);
			});
		});

		$(document).on("click", ".btn-absen-pulang", function() {
			let frame = $("#modal_frame");

			frame.find(".modal-title").html("Absen Karyawan");

            	// Menampilkan modal
			frame.modal("show");

            	// Mengambil data karyawan untuk diedit
			$.get("<?= site_url('karyawan/modal_absenpulang_karyawan'); ?>", function(res) {
				frame.find(".modal-body").html(res);
			});
		});

	// 	$(document).on("click", ".btn-karyawan-edit", function() {
	// 		let frame = $("#modal_frame");

    //         // Mengambil dari atribut data-id tombol yang diklik
	// 		let id_karyawan = $(this).data("id");

	// 		frame.find(".modal-title").html("Edit Karyawan");

    //         // Menampilkan modal
	// 		frame.modal("show");

    //         // Mengambil data karyawan untuk diedit
	// 		$.get("<?= site_url('karyawan/modal_edit_karyawan'); ?>/" + id_karyawan, function(res) {
	// 			frame.find(".modal-body").html(res);
	// 		});
	// 	});

	// 	$(document).on("click", ".btn-bahan", function() {
	// 		let frame = $("#modal_frame");

	// 		frame.find(".modal-title").html("Data Bahan");

    //         	// Menampilkan modal
	// 		frame.modal("show");

    //         	// Mengambil data karyawan untuk diedit
	// 		$.get("<?= site_url('bahan/modal_bahan'); ?>", function(res) {
	// 			frame.find(".modal-body").html(res);
	// 		});
	// 	});

	});

</script>
</body>
<!--end body-->

</html>