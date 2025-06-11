<div class="card">
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col">
				<div class="justify-content-between d-flex">
					<h4 class="card-title">Pemilik Produk</h4>
					<button type="submit" class="btn btn-primary btn-sm btn-pemilik-add">Tambah Pemilik</button>
				</div>
			</div><!--end col-->
		</div> <!--end row-->
	</div><!--end card-header-->
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-centered" id="tabelPemilik">
				<thead class="table-light">
					<tr>
						<th>Nomor</th>
						<th>Nama Pemilik</th>
						<th>Jenis</th>
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
		var table_pemilik = $("#tabelPemilik");

		table_pemilik.DataTable({
			ajax: "<?= base_url('produk/get_all_pemilik'); ?>",
			paging: true,
			info: true,
			lengthChange: true,
			searching: true,
			ordering: false,
		});

		table_pemilik.on('draw.dt', function () {
			table_pemilik.columns.adjust().draw();
		});

		$(document).on("click", ".btn-delete-pemilik", function() {
			var id_pemilik = $(this).data("id");            
			var url = "<?= base_url('produk/delete_pemilik'); ?>";

			// Tutup dulu modal_frame
			$('#modal_frame').modal('hide');

			// Tunggu sampai modal bener-bener tertutup baru munculin konfirmasi
			$('#modal_frame').off('hidden.bs.modal').on('hidden.bs.modal', function () {
				// Matikan event ini setelah jalan sekali biar nggak dobel
				$(this).off('hidden.bs.modal');

				bootbox.confirm({
					title: "Konfirmasi Hapus Pemilik",
					message: "Apakah Anda yakin ingin menghapus Pemilik ini?",
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
							$.post(url, {id_pemilik: id_pemilik}, function(res) {
								if(res.status == "sukses") {
									toastr.success(res.pesan);
									table_pemilik.DataTable().ajax.reload();
									$(".btn-pemilik").click();
								} else {
									toastr.warning(res.pesan);
								}
							});
						}	else {
							$('#modal_frame').modal('show');
							$('#modal_frame .modal-title').html("Data Pemilik");

							$.get("<?= site_url('produk/modal_pemilik'); ?>", function(res) {
								$('#modal_frame .modal-body').html(res);
							});
						}
					}
				});
			});
		});

		// Konfigurasi tombol tambah produk
		$(document).on("click", ".btn-pemilik-add", function() {
			let frame = $("#modal_frame");

			frame.find(".modal-title").html("Tambah Pemilik");

    		// Menampilkan modal
			frame.modal("show");

    		// Mengambil data tabungan untuk diedit
			$.get("<?= site_url('produk/modal_add_pemilik'); ?>", function(res) {
				frame.find(".modal-body").html(res);
			});
		});

		$(document).on("click", ".btn-pemilik-edit", function() {
			let frame = $("#modal_frame");

    		// Mengambil id_tabungan dari atribut data-id tombol yang diklik
			let id_pemilik = $(this).data("id");

			frame.find(".modal-title").html("Edit Pemilik");

    		// Menampilkan modal
			frame.modal("show");

    		// Mengambil data tabungan untuk diedit
			$.get("<?= site_url('produk/modal_edit_pemilik'); ?>/" + id_pemilik, function(res) {
				frame.find(".modal-body").html(res);
			});
		});

	});

</script>