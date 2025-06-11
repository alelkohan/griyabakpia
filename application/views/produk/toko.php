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
						<h4 class="page-title">Data Produk Toko</h4>
						<div class="">
							<ol class="breadcrumb mb-0">
								<li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a>
								</li><!--end nav-item-->
								<li class="breadcrumb-item active">Data Produk Toko</li>
							</ol>
						</div>
					</div><!--end page-title-box-->
				</div><!--end col-->
			</div><!--end row-->

			<div class="justify-content-between d-flex mb-3">
				<button type="button" class="btn btn-primary btn-sm btn-toko">Data Toko</button>
				<a href="<?= site_url('produk/index_pembayaran_toko') ?>" class="btn btn-primary btn-sm">Riwayat Pembayaran</a>
			</div>

			<div class="row justify-content-center">
				<div class="col-md-12 col-lg-12">
					<div class="card">
						<div class="card-header">
							<div class="row align-items-center">
								<div class="col">                      
									<nav>
										<div class="nav nav-tabs" id="nav-tab" role="tablist">
											<?php $i = 0; foreach ($toko as $item): ?>
											<a class="nav-link py-2 <?= $i == 0 ? 'active' : '' ?> <?= $item->status == 'nonaktif' ? 'disabled' : '' ?>"
												id="tab<?= $i ?>-tab"
												data-bs-toggle="<?= $item->status == 'aktif' ? 'tab' : '' ?>"
												href="#tab<?= $i ?>"
												aria-selected="<?= $i == 0 ? 'true' : 'false' ?>"
												role="tab"
												<?= $item->status == 'nonaktif' ? 'aria-disabled="true" tabindex="-1"' : '' ?>>
												<?= $item->nama_toko ?>
												<?php if($item->status == 'nonaktif'): ?>
													<span class="badge bg-danger ms-1">Nonaktif</span>
												<?php endif; ?>
											</a>
											<?php $i++; endforeach; ?>
										</div>
									</nav>
								</div><!--end col-->
							</div>  <!--end row-->                                  
						</div><!--end card-header-->
						<div class="card-body pt-0">
							<div class="tab-content" id="nav-tabContent">
								<?php $i = 0; foreach ($toko as $item): ?>
								<div class="tab-pane <?= $i == 0 ? 'active' : '' ?>"
									id="tab<?= $i ?>"
									role="tabpanel"
									aria-labelledby="tab<?= $i ?>-tab">
									<div class="justify-content-between d-flex mt-4">
										<h5 class="card-title">Data Produk <?= $item->nama_toko ?></h5>
										<div class="d-flex gap-2 ms-auto">
											<button type="button" data-id_toko="<?= $item->id_toko ?>" class="btn btn-primary btn-sm btn-toko-addProduk">Tambah Produk</button>
											<button type="button" class="btn btn-primary btn-sm btn-toko-addProduksetor" data-id_toko="<?= $item->id_toko ?>">Setor</button>
											<button type="button" class="btn btn-primary btn-sm btn-toko-bayar" data-id_toko="<?= $item->id_toko ?>">Bayar</button>
										</div>
									</div>
									<div class="table-responsive mt-3">
										<table class="table table-centered tabelProduk" id="tabelProduk<?= $i ?>" data-id="<?= $item->id_toko ?>" style="width: 100%;">
											<thead class="table-light">
												<tr>
													<th>Nomor</th>
													<th>Nama Produk</th>
													<th>Harga</th>
													<th>Stok</th>
													<th>Jumlah Terjual</th>
													<th class="text-end">Action</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>
									<!-- Konten toko bisa ditambahkan di sini -->
								</div>
								<?php $i++; endforeach; ?>
							</div>
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
	var tableProdukInstances = {};

	$(document).ready(function() {
		$('.tabelProduk').each(function () {
			var tableId = $(this).attr('id');
			var id_toko = $(this).data('id');

			tableProdukInstances[tableId] = $('#' + tableId).DataTable({
				ajax: {
					url: "<?= base_url('produk/get_all_produk_by_toko'); ?>",
					type: "GET",
					data: {
						id_toko: id_toko
					}
				},
				paging: true,
				info: true,
				lengthChange: true,
				searching: true,
				ordering: false,
				pageLength: 10,
				lengthMenu: [5, 8, 10, 25],
				columnDefs: [{ targets: 5, className: 'text-end' }]
			});
		});

        // Tambahkan event ketika tab dibuka
		$('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            // Ambil ID tabel di dalam tab yang aktif
            var targetTab = $(e.target).attr("href"); // misal: #tab-toko-solo
            var table = $(targetTab).find('.tabelProduk'); // cari tabel di dalam tab itu
            var tableId = table.attr('id');

            // Adjust kolom DataTables
            if (tableId && tableProdukInstances[tableId]) {
            	tableProdukInstances[tableId].columns.adjust().draw();
            }
        });

		$(document).on("click", ".btn-delete-produktoko", function() {
			var id_produk_toko = $(this).data("id");            
			var url = "<?= base_url('produk/delete_produktoko'); ?>";

			bootbox.confirm({
				title: "Konfirmasi Hapus Produk",
				message: "Apakah Anda yakin ingin menghapus produk ini?",
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
						$.post(url, {id_produk_toko: id_produk_toko}, function(res) {
							if(res.status == "sukses") {
								toastr.success(res.pesan);
								$(".tabelProduk").DataTable().ajax.reload(null, false);
							} else {
								toastr.warning(res.pesan);
							}
						}, 'json');
					}
				}
			});
		});

		$(document).on("click", ".btn-toko", function() {
			let frame = $("#modal_frame");

			frame.find(".modal-title").html("Data Toko");

			// Menampilkan modal
			frame.modal("show");

			// Mengambil data tabungan untuk diedit
			$.get("<?= site_url('produk/modal_toko'); ?>", function(res) {
				frame.find(".modal-body").html(res);
			});
		});

		$(document).on("click", ".btn-toko-addProduk", function() {
			let frame = $("#modal_frame");

    		// Mengambil id_tabungan dari atribut data-id tombol yang diklik
			let id_toko = $(this).data("id_toko");

			frame.find(".modal-title").html("Tambah Produk");

    		// Menampilkan modal
			frame.modal("show");

    		// Mengambil data tabungan untuk diedit
			$.get("<?= site_url('produk/modal_add_produktoko'); ?>",
				{ id_toko: id_toko },
				function(res) {
					frame.find(".modal-body").html(res);
				});
		});

		$(document).on("click", ".btn-produktoko-edit", function() {
			let frame = $("#modal_frame");

			let id_produk_toko = $(this).data("id");

			frame.find(".modal-title").html("Edit Produk Toko");

    		// Menampilkan modal
			frame.modal("show");

			$.post("<?= site_url('produk/modal_edit_produktoko'); ?>", 
				{ id_produk_toko: id_produk_toko }, 
				function(res) {
					frame.find(".modal-body").html(res);
				}
				);
		});

		$(document).on("click", ".btn-toko-bayar", function() {
			let frame = $("#modal_frame");

			let id_toko = $(this).data("id_toko");

			frame.find(".modal-title").html("Data Setor Stok Produk");

    		// Menampilkan modal
			frame.modal("show");

			$.post("<?= site_url('produk/modal_bayar_toko'); ?>", 
				{ id_toko: id_toko }, 
				function(res) {
					frame.find(".modal-body").html(res);
				}
				);
		});

		$(document).on("click", ".btn-toko-addProduksetor", function() {
			let frame = $("#modal_frame");

			let id_toko = $(this).data("id_toko");

			frame.find(".modal-title").html("Data Setor Stok Produk");

    		// Menampilkan modal
			frame.modal("show");

			$.post("<?= site_url('produk/modal_add_produksetor'); ?>", 
				{ id_toko: id_toko }, 
				function(res) {
					frame.find(".modal-body").html(res);
				}
				);
		});

		$(document).on("click", ".btn-toko-detail", function() {
			let frame = $("#modal_frame");

            // Mengambil id_tabungan dari atribut data-id tombol yang diklik
			let id_toko = $(this).data("id");

			frame.find(".modal-title").html("Detail Toko");

            // Menampilkan modal
			frame.modal("show");

			$.get("<?= site_url('produk/modal_detail_toko'); ?>/" + id_toko, function(res) {
				frame.find(".modal-body").html(res);

				// Eksekusi ulang semua tag <script> di dalam res
				frame.find(".modal-body").find("script").each(function() {
					$.globalEval(this.text || this.textContent || this.innerHTML || '');
				});
			});

		});

	});

</script>
