<div class="card">
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col">
				<div class="justify-content-between d-flex">
					<h4 class="card-title">Data Outlet</h4>
					<button type="submit" class="btn btn-primary btn-sm btn-outlet-add">Tambah Outlet</button>
				</div>
			</div><!--end col-->
		</div> <!--end row-->
	</div><!--end card-header-->
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-centered" id="tabelOutlet">
				<thead class="table-light">
					<tr>
						<th>Nomor</th>
						<th>Nama Outlet</th>
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
		var table_outlet = $("#tabelOutlet");

		table_outlet.DataTable({
			ajax: "<?= base_url('produk/get_all_outlet'); ?>",
			paging: true,
			info: true,
			lengthChange: true,
			searching: true,
			ordering: false,
		});

		table_outlet.on('draw.dt', function () {
			table_outlet.columns.adjust().draw();
		});

		$(document).on("click", ".btn-delete-outlet", function() {
			var id_outlet = $(this).data("id");            
			var url = "<?= base_url('produk/delete_outlet'); ?>";

			// Tutup dulu modal_frame
			$('#modal_frame').modal('hide');

			// Tunggu sampai modal bener-bener tertutup baru munculin konfirmasi
			$('#modal_frame').off('hidden.bs.modal').on('hidden.bs.modal', function () {
				// Matikan event ini setelah jalan sekali biar nggak dobel
				$(this).off('hidden.bs.modal');

				bootbox.confirm({
					title: "Konfirmasi Hapus outlet",
					message: "Apakah Anda yakin ingin menghapus outlet ini?",
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
							$.post(url, {id_outlet: id_outlet}, function(res) {
								if(res.status == "sukses") {
									toastr.success(res.pesan);
									table_outlet.DataTable().ajax.reload();
									$(".btn-outlet").click();
								} else {
									toastr.warning(res.pesan);
								}
							});
						}	else {
							$('#modal_frame').modal('show');
							$('#modal_frame .modal-title').html("Data outlet");

							$.get("<?= site_url('produk/modal_outlet'); ?>", function(res) {
								$('#modal_frame .modal-body').html(res);
							});
						}
					}
				});
			});
		});

		// Konfigurasi tombol tambah produk
		$(document).on("click", ".btn-outlet-add", function() {
			let frame = $("#modal_frame");

			frame.find(".modal-title").html("Tambah Outlet");

    		// Menampilkan modal
			frame.modal("show");

    		// Mengambil data tabungan untuk diedit
			$.get("<?= site_url('produk/modal_add_outlet'); ?>", function(res) {
				frame.find(".modal-body").html(res);
			});
		});

		$(document).on("click", ".btn-outlet-edit", function() {
			let frame = $("#modal_frame");

    		// Mengambil id_tabungan dari atribut data-id tombol yang diklik
			let id_outlet = $(this).data("id");

			frame.find(".modal-title").html("Edit Outlet");

    		// Menampilkan modal
			frame.modal("show");

    		// Mengambil data tabungan untuk diedit
			$.get("<?= site_url('produk/modal_edit_outlet'); ?>/" + id_outlet, function(res) {
				frame.find(".modal-body").html(res);
			});
		});

	});

</script>