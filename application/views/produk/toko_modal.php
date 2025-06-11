<div class="card">
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col">
				<div class="justify-content-between d-flex">
					<h4 class="card-title">Data Toko</h4>
					<button type="submit" class="btn btn-primary btn-sm btn-toko-add">Tambah Toko</button>
				</div>
			</div><!--end col-->
		</div> <!--end row-->
	</div><!--end card-header-->
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-centered" id="tabelToko">
				<thead class="table-light">
					<tr>
						<th>Nomor</th>
						<th>Nama Toko</th>
						<th>Status Toko</th>
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
		var table_toko = $("#tabelToko");

		table_toko.DataTable({
			ajax: "<?= base_url('produk/get_all_toko'); ?>",
			paging: true,
			info: true,
			lengthChange: true,
			searching: true,
			ordering: false,
		});

		table_toko.on('draw.dt', function () {
			table_toko.columns.adjust().draw();
		});

		$(document).on("click", ".btn-delete-toko", function() {
			var id_toko = $(this).data("id");            
			var url = "<?= base_url('produk/delete_toko'); ?>";

			// Tutup dulu modal_frame
			$('#modal_frame').modal('hide');

			// Tunggu sampai modal bener-bener tertutup baru munculin konfirmasi
			$('#modal_frame').off('hidden.bs.modal').on('hidden.bs.modal', function () {
				// Matikan event ini setelah jalan sekali biar nggak dobel
				$(this).off('hidden.bs.modal');

				bootbox.confirm({
					title: "Konfirmasi Hapus toko",
					message: "Apakah Anda yakin ingin menghapus toko ini?",
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
							$.post(url, {id_toko: id_toko}, function(res) {
								if(res.status == "sukses") {
									toastr.success(res.pesan);
									table_toko.DataTable().ajax.reload();
									$(".btn-toko").click();
								} else {
									toastr.warning(res.pesan);
								}
							});
						}	else {
							$('#modal_frame').modal('show');
							$('#modal_frame .modal-title').html("Data toko");

							$.get("<?= site_url('produk/modal_toko'); ?>", function(res) {
								$('#modal_frame .modal-body').html(res);
							});
						}
					}
				});
			});
		});

		// Konfigurasi tombol tambah produk
		$(document).on("click", ".btn-toko-add", function() {
			let frame = $("#modal_frame");

			frame.find(".modal-title").html("Tambah toko");

    		// Menampilkan modal
			frame.modal("show");

			frame.one('hidden.bs.modal', function () {
				// Buka modal lagi pas udah bener-bener ketutup
				frame.modal("show");

				// Load isi kontennya
				$.get("<?= site_url('produk/modal_add_toko'); ?>", function(res) {
					frame.find(".modal-body").html(res);
				});
			});

			frame.modal("hide");			
		});

		$(document).on("click", ".btn-toko-edit", function() {
			let frame = $("#modal_frame");

    		// Mengambil id_tabungan dari atribut data-id tombol yang diklik
			let id_toko = $(this).data("id");

			frame.find(".modal-title").html("Edit toko");

    		// Menampilkan modal
			frame.modal("show");

			frame.one('hidden.bs.modal', function () {
				// Buka modal lagi pas udah bener-bener ketutup
				frame.modal("show");

				// Load isi kontennya
				$.get("<?= site_url('produk/modal_edit_toko'); ?>/" + id_toko, function(res) {
					frame.find(".modal-body").html(res);
				});
			});

			frame.modal("hide");			
		});

	});

</script>