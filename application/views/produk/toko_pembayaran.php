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
						<h4 class="page-title">Data Pembayaran Toko</h4>
						<div class="">
							<ol class="breadcrumb mb-0">
								<li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a>
								</li><!--end nav-item-->
								<li class="breadcrumb-item active">Data Pembayaran Toko</li>
							</ol>
						</div>
					</div><!--end page-title-box-->
				</div><!--end col-->
			</div><!--end row-->

			<div class="row">
				<div class="col-sm-12">
					<div class="page-title-box d-md-flex justify-content-md-between align-items-center">
						<a href="<?= site_url('produk/index_toko') ?>" class="btn btn-secondary">Back</a>						
					</div><!--end page-title-box-->
				</div><!--end col-->
			</div><!--end row-->

			<div class="row justify-content-center">
				<div class="col-md-12 col-lg-12">
					<div class="card">
						<div class="card-header">
							<form id="formFilter">
								<div class="row mb-3">
									<div class="col-md-3">
										<label for="mulai_tanggal" class="form-label">Dari Tanggal</label>
										<input type="date" id="mulai_tanggal" name="mulai_tanggal" class="form-control" value="" max="<?= date('Y-m-d') ?>">
									</div>
									<div class="col-md-3">
										<label for="sampai_tanggal" class="form-label">Sampai Tanggal</label>
										<input type="date" id="sampai_tanggal" name="sampai_tanggal" class="form-control" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>">
									</div>
									<div class="col-md-3">
										<label for="toko" class="form-label">Toko</label>
										<select id="filterToko" class="form-select" required="">
											<option value="" disabled selected>Pilih Toko</option>
											<?php foreach   ($toko as $toko): ?>
												<option value="<?= $toko->id_toko ?>"><?= $toko->nama_toko ?></option>
											<?php endforeach; ?>
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
							<hr>
						</div><!--end card-header-->
						<div class="card-body pt-0">
							<div class="table-responsive">
								<table class="table mb-0" id="tabel-pembayaran-toko">
									<thead class="table-light">
										<tr>
											<th class="border-top-0">Nomor</th>
											<th class="border-top-0">Tanggal Bayar</th>
											<th class="border-top-0">Nama Toko</th>
											<th class="border-top-0">Nominal</th>
											<th class="border-top-0">Action</th>
										</tr><!--end tr-->
									</thead>
									<tbody>

									</tbody>
								</table> <!--end table-->
							</div><!--end /div-->
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

<div class="modal fade bd-example-modal-xl" id="modal_frame" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
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

<script type="text/javascript">
	$(document).ready(function() {
		var table_pembayaran = $("#tabel-pembayaran-toko");

		table_pembayaran.DataTable({
			ajax: "<?= base_url('produk/get_all_pembayaran_toko'); ?>",
			paging: true,
			info: true,
			lengthChange: true,
			searching: true,
			ordering: false,
			pageLength: 10,
			lengthMenu: [5, 8, 10, 25]
		});

		$('#formFilter').on('submit', function(e) {
			e.preventDefault();

			var mulai_tanggal = $('#mulai_tanggal').val();
			var sampai_tanggal = $('#sampai_tanggal').val();
			var toko = $('#filterToko').val();

			// Kirim request AJAX dengan parameter yg diisi (boleh kosong juga)
			table_pembayaran.DataTable().ajax.url(
				"<?= site_url('produk/get_all_pembayaran_toko') ?>" +
				"?mulai_tanggal=" + encodeURIComponent(mulai_tanggal) +
				"&sampai_tanggal=" + encodeURIComponent(sampai_tanggal) +
				"&toko=" + encodeURIComponent(toko)
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
			$('#filterToko').val('');
			table_pembayaran.DataTable().ajax.url("<?= site_url('produk/get_all_pembayaran_toko') ?>").load(function(json) {
				toastr.info("Data berhasil direset!", "Info");
			});
		});

		$(document).on("click", ".btn-pembayarantoko-detail", function() {
			let frame = $("#modal_frame");

			// Mengambil dari atribut data-id tombol yang diklik
			let id_pembayaran_toko = $(this).data("id");

			frame.find(".modal-title").html("Detail Pembayaran");

			// Menampilkan modal
			frame.modal("show");

			// Mengambil data tabungan untuk diedit
			$.get("<?= site_url('produk/modal_detail_pembayaran_toko'); ?>/" + id_pembayaran_toko, function(res) {
				frame.find(".modal-body").html(res);
			});
		});

		$(document).on("click", ".btn-pembayarantoko-edit", function() {
			let frame = $("#modal_frame");

			// Mengambil dari atribut data-id tombol yang diklik
			let id_pembayaran_toko = $(this).data("id");

			frame.find(".modal-title").html("Edit Pembayaran");

			// Menampilkan modal
			frame.modal("show");

			// Mengambil data tabungan untuk diedit
			$.get("<?= site_url('produk/modal_edit_pembayaran_toko'); ?>/" + id_pembayaran_toko, function(res) {
				frame.find(".modal-body").html(res);
			});
		});


	});

</script>
