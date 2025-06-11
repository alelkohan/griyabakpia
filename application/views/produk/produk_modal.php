<div class="card">
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col">
				<div class="justify-content-between d-flex">
					<h4 class="card-title">Data Produk</h4>
					<button type="submit" class="btn btn-primary btn-sm btn-produk-add">Tambah Produk</button>
				</div>
			</div><!--end col-->
		</div> <!--end row-->
	</div><!--end card-header-->
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-centered" id="tabelProduk">
				<thead class="table-light">
					<tr>
						<th>Nomor</th>
						<th>Pemilik</th>
						<th>Nama Produk</th>
						<th>Harga Default</th>
						<th class="text-end">Action</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table><!--end /table-->
		</div><!--end /tableresponsive-->
	</div><!--end card-body-->
</div><!--end card-->

<script type="text/javascript">

	$(document).ready(function() {
		var table_produk = $("#tabelProduk");

		table_produk.DataTable({
			ajax: "<?= base_url('produk/get_all_produk'); ?>",
			paging: true,
			info: true,
			lengthChange: true,
			searching: true,
			ordering: false,
		});

		table_produk.on('draw.dt', function () {
			table_produk.columns.adjust().draw();
		});

		$(document).on("click", ".btn-delete-produk", function() {
			var id_produk = $(this).data("id");            
			var url = "<?= base_url('produk/delete'); ?>";

			// Tutup dulu modal_frame
			$('#modal_frame').modal('hide');

			// Tunggu sampai modal bener-bener tertutup baru munculin konfirmasi
			$('#modal_frame').off('hidden.bs.modal').on('hidden.bs.modal', function () {
				// Matikan event ini setelah jalan sekali biar nggak dobel
				$(this).off('hidden.bs.modal');

				bootbox.confirm({
					title: "Konfirmasi Hapus produk",
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
							$.post(url, {id_produk: id_produk}, function(res) {
								if(res.status == "sukses") {
									toastr.success(res.pesan);
									table_produk.DataTable().ajax.reload();
									$(".btn-produk").click();
								} else {
									toastr.warning(res.pesan);
								}
							});
						}	else {
							$('#modal_frame').modal('show');
							$('#modal_frame .modal-title').html("Data produk");

							$.get("<?= site_url('produk/modal_produk'); ?>", function(res) {
								$('#modal_frame .modal-body').html(res);
							});
						}
					}
				});
			});
		});

		// Konfigurasi tombol tambah produk
		$(document).on("click", ".btn-produk-add", function() {
			let frame = $("#modal_frame");

			frame.find(".modal-title").html("Tambah Produk");

    		// Menampilkan modal
			frame.modal("show");

    		// Mengambil data tabungan untuk diedit
			$.get("<?= site_url('produk/modal_add_produk'); ?>", function(res) {
				frame.find(".modal-body").html(res);
			});
		});

		$(document).on("click", ".btn-produk-edit", function() {
			let frame = $("#modal_frame");

    		// Mengambil id_tabungan dari atribut data-id tombol yang diklik
			let id_produk = $(this).data("id");

			frame.find(".modal-title").html("Edit Produk");

    		// Menampilkan modal
			frame.modal("show");

    		// Mengambil data tabungan untuk diedit
			$.get("<?= site_url('produk/modal_edit_produk'); ?>/" + id_produk, function(res) {
				frame.find(".modal-body").html(res);
			});
		});

	});

</script>