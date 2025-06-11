<div class="card">
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col">
				<div class="row">	
					<div class="col-md-3">
						<h4>Nama Toko:</h4>
					</div>
					<div class="col-md-3">
						<h4><?= $toko->nama_toko ?></h4>
					</div>
					<div class="col-md-3">
						<h4>Status Toko:</h4>
					</div>
					<div class="col-md-3">
						<h4><?= $toko->status ?></h4>
					</div>
				</div>
				<hr>	
				<div class="d-flex justify-content-between align-items-center">
					<h4 class="card-title mb-0">Data Produk Setor Toko</h4>
					<div class="d-flex gap-2 ms-auto">
						<button type="button" class="btn btn-primary btn-sm btn-toko-addProduk" data-id_toko="<?= $toko->id_toko ?>">Tambah Produk</button>
						<button type="button" class="btn btn-primary btn-sm btn-toko-addProduksetor" data-id_toko="<?= $toko->id_toko ?>">Setor</button>
						<button type="button" class="btn btn-primary btn-sm btn-toko-bayar" data-id_toko="<?= $toko->id_toko ?>">Bayar</button>
					</div>
				</div>
			</div><!--end col-->
		</div> <!--end row-->
	</div><!--end card-header-->
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-centered" id="tabelProduktoko">
				<thead class="table-light">
					<tr>
						<th>Nomor</th>
						<th class="border-top-0">Nama Produk</th>
						<th class="border-top-0">Harga</th>
						<th class="border-top-0">Stok</th>
						<th class="border-top-0">Jumlah Terjual</th>
						<th class="text-end">Action</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table><!--end /table-->
		</div><!--end /tableresponsive-->
	</div><!--end card-body-->
</div><!--end card-->

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

<script type="text/javascript">

	$(document).ready(function() {

		var table_tokoProduk = $("#tabelProduktoko");

		if ($.fn.DataTable.isDataTable("#tabelProduktoko")) {
			table_tokoProduk.DataTable().destroy();
		}

		table_tokoProduk.DataTable({
			ajax: "<?= base_url('produk/get_all_produk_by_toko/'). $id_toko ?>",
			paging: true,
			info: true,
			lengthChange: true,
			searching: true,
			ordering: false,
		});

		$(document).on("click", ".btn-toko-addProduk", function() {
			let frame = $("#modal_frame");
			let id_toko = $(this).data("id_toko");


			frame.find(".modal-title").html("Tambah Produk");

			frame.one('hidden.bs.modal', function () {
				// Buka modal lagi pas udah bener-bener ketutup
				frame.modal("show");

				// Load isi kontennya
				$.post("<?= site_url('produk/modal_add_produktoko'); ?>", 
					{ id_toko: id_toko }, 
					function(res) {
						frame.find(".modal-body").html(res);
					}
				);
			});

			frame.modal("hide"); // trigger nutup modal dulu
		});

		$(document).on("click", ".btn-toko-addProduksetor", function() {
			let frame = $("#modal_frame");
			let id_toko = $(this).data("id_toko");


			frame.find(".modal-title").html("Tambah Setor Stok Produk");

			frame.one('hidden.bs.modal', function () {
				// Buka modal lagi pas udah bener-bener ketutup
				frame.modal("show");

				// Load isi kontennya
				$.post("<?= site_url('produk/modal_add_produksetor'); ?>", 
					{ id_toko: id_toko }, 
					function(res) {
						frame.find(".modal-body").html(res);
					}
				);
			});

			frame.modal("hide"); // trigger nutup modal dulu
		});

		$(document).on("click", ".btn-toko-bayar", function() {
			let frame = $("#modal_frame");
			let id_toko = $(this).data("id_toko");


			frame.find(".modal-title").html("Data Setor Stok Produk");

			frame.one('hidden.bs.modal', function () {
				// Buka modal lagi pas udah bener-bener ketutup
				frame.modal("show");

				// Load isi kontennya
				$.post("<?= site_url('produk/modal_bayar_toko'); ?>", 
					{ id_toko: id_toko }, 
					function(res) {
						frame.find(".modal-body").html(res);
					}
				);
			});

			frame.modal("hide"); // trigger nutup modal dulu
		});

		$(document).on("click", ".btn-produktoko-edit", function() {
			let frame = $("#modal_frame");
			let id_produk_toko = $(this).data("id");


			frame.find(".modal-title").html("Edit Produk Toko");

			frame.one('hidden.bs.modal', function () {
				// Buka modal lagi pas udah bener-bener ketutup
				frame.modal("show");

				// Load isi kontennya
				$.post("<?= site_url('produk/modal_edit_produktoko'); ?>", 
					{ id_produk_toko: id_produk_toko }, 
					function(res) {
						frame.find(".modal-body").html(res);
					}
				);
			});

			frame.modal("hide"); // trigger nutup modal dulu
		});


	});

</script>