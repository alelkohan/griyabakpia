<div class="card" data-id_toko="<?= $id_toko ?>">
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col">
				<div class="justify-content-between d-flex">
					<h4 class="card-title">Data Riwayat Setor Stok <?= $toko->nama_toko ?></h4>
				</div>
			</div><!--end col-->
		</div> <!--end row-->
	</div><!--end card-header-->
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-centered" id="tabelLogsetor">
				<thead class="table-light">
					<tr>
						<th>Nomor</th>
						<th>Tanggal Setor</th>
						<th>Status Bayar</th>
						<th class="text-end">Action</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table><!--end /table-->
		</div><!--end /tableresponsive-->
	</div><!--end card-body-->
</div><!--end card-->
<div class="row">
	<div class="col-sm-12 text-end">
		<button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function() {
		var table_logsetor = $("#tabelLogsetor");

		if ($.fn.DataTable.isDataTable("#tabelLogsetor")) {
			table_logsetor.DataTable().destroy();
		}

		table_logsetor.DataTable({
			ajax: "<?= base_url('produk/get_all_logsetor/'). $id_toko ?>",
			paging: true,
			info: true,
			lengthChange: true,
			searching: true,
			ordering: false,
		});

		table_logsetor.on('draw.dt', function () {
			table_logsetor.columns.adjust().draw();
		});

		$(document).on("click", ".btn-delete-logsetor", function() {
			var id_log_setor_toko = $(this).data("id");     
			var id_toko = $(this).data("id_toko");     
			var url = "<?= base_url('produk/delete_logsetor'); ?>";

			$('#modal_frame').modal('hide');

			$('#modal_frame').off('hidden.bs.modal').on('hidden.bs.modal', function () {
				$(this).off('hidden.bs.modal');

				bootbox.confirm({
					title: "Konfirmasi Hapus Log Setor Toko",
					message: "Apakah Anda yakin ingin menghapus log setor toko ini?",
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
							$.post(url, {id_log_setor_toko: id_log_setor_toko}, function(res) {
								if(res.status == "sukses") {
									toastr.success(res.pesan);
								} else {
									toastr.warning(res.pesan);
								}
							var activeTab = $('.tab-pane.active');
			                var activeTable = activeTab.find('.tabelProduk'); // Temukan tabel di tab aktif
			                var tableId = activeTable.attr('id');

			                // Reload tabel jika ditemukan
			                if (tableId && tableProdukInstances[tableId]) {
			                    tableProdukInstances[tableId].ajax.reload();
			                }
							});
						} else {
					var activeTab = $('.tab-pane.active');
	                var activeTable = activeTab.find('.tabelProduk'); // Temukan tabel di tab aktif
	                var tableId = activeTable.attr('id');

	                // Reload tabel jika ditemukan
	                if (tableId && tableProdukInstances[tableId]) {
	                    tableProdukInstances[tableId].ajax.reload();
	                }
						}
					}
				});
			});
		});

		$(document).on("click", ".btn-detail-logsetor", function() {
			let frame = $("#modal_frame");
			let id_log_setor_toko = $(this).data("id");


			frame.find(".modal-title").html("Data Detail Setor");

			frame.one('hidden.bs.modal', function () {
				// Buka modal lagi pas udah bener-bener ketutup
				frame.modal("show");

				// Load isi kontennya
				$.post("<?= site_url('produk/modal_detailbayar_toko'); ?>", 
					{ id_log_setor_toko: id_log_setor_toko }, 
					function(res) {
						frame.find(".modal-body").html(res);
					}
				);
			});

			frame.modal("hide"); // trigger nutup modal dulu
		});

		$(document).on("click", ".btn-bayar-logsetor", function() {
			let frame = $("#modal_frame");
			let id_log_setor_toko = $(this).data("id");


			frame.find(".modal-title").html("Pembayaran Toko");

			frame.one('hidden.bs.modal', function () {
				// Buka modal lagi pas udah bener-bener ketutup
				frame.modal("show");

				// Load isi kontennya
				$.post("<?= site_url('produk/modal_bayarform_toko'); ?>", 
					{ id_log_setor_toko: id_log_setor_toko }, 
					function(res) {
						frame.find(".modal-body").html(res);
					}
				);
			});

			frame.modal("hide"); // trigger nutup modal dulu
		});

		$(document).on('click', '.btn-tutup', function() {
            let target = $(this).data('target'); // ambil tujuan dari data attribute

            $('#modal_frame').modal('hide');

            if (target) {
            	$('#modal_frame').one('hidden.bs.modal', function() {
                    $(target).click(); // klik sesuai target tujuan
                });
            }
        });

	});

</script>