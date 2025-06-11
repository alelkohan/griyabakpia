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
						<h4 class="page-title">Gaji Karyawan</h4>
						<div class="">
							<ol class="breadcrumb mb-0">
								<li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a>
								</li><!--end nav-item-->
								<li class="breadcrumb-item active">Gaji Karyawan</li>
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
										<h4 class="card-title">Data Karyawan</h4>
										<div class="d-flex gap-2">
											<button type="" class="btn btn-primary btn-sm btn-gaji-add">Gaji Karyawan</button>
										</div>
									</div>
								</div><!--end col-->
							</div> <!--end row-->
						</div><!--end card-header-->
						<div class="card-body pt-0">
							<div class="table-responsive">
								<table class="table table-centered" id="tabelGaji">
									<thead class="table-light">
										<tr>
											<th>Nomor</th>
											<th>Foto</th>
											<th>Nama Karyawan</th>
											<th>Peran</th>
											<th>Tanggal Gaji</th>
											<th>Jumlah Gaji</th>
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

		var tabel_gaji = $("#tabelGaji");

		tabel_gaji.DataTable({
			ajax: "<?= base_url('gaji/get_all_gaji'); ?>",
			paging: true,
			info: true,
			lengthChange: true,
			searching: true,
			ordering: false,
			pageLength: 10,
			lengthMenu: [5, 8, 10, 25],
			columnDefs: [{ targets: 7, className: 'text-end' }]
		});

		tabel_gaji.on('draw.dt', function () {
			tabel_gaji.columns.adjust().draw();
		});

		$(document).on("click", ".btn-gaji-delete", function() {
			var id_gaji = $(this).data("id");            
			var url = "<?= base_url('gaji/delete_gaji'); ?>";

			bootbox.confirm({
				title: "Konfirmasi Hapus gaji",
				message: "Apakah Anda yakin ingin menghapus gaji ini?",
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
						$.post(url, {id_gaji: id_gaji}, function(res) {
							if(res.status == "sukses") {
								toastr.success(res.pesan);
								tabel_gaji.DataTable().ajax.reload();
							} else {
								toastr.warning(res.pesan);
							}
						});
					}
				}
			});
		});

		$(document).on("click", ".btn-gaji-add", function() {
			let frame = $("#modal_frame");

			frame.find(".modal-title").html("Data Karyawan");

            	// Menampilkan modal
			frame.modal("show");

            	// Mengambil data karyawan untuk diedit
			$.get("<?= site_url('gaji/modal_add_gaji'); ?>", function(res) {
				frame.find(".modal-body").html(res);
			});
		});

		$(document).off('submit', '#gajiKaryawan').on('submit', '#gajiKaryawan', function(e) {
			e.preventDefault();

			var formData = new FormData(this);

			$.ajax({
				url: '<?= site_url("gaji/create_gaji") ?>',
				type: 'POST',
				data: formData,
				contentType: false,
				processData: false,
				success: function(response) {
					console.log("Sukses: ", response);

					if (response.status === 'error') {
						toastr.error(response.message || 'Terjadi kesalahan.', 'Error');
						return;
					}

					toastr.success('Karyawan berhasil digaji!', 'Sukses');
					$('#tabelGaji').DataTable().ajax.reload();
					$('#tab-detail').tab('show');
				}
			});
		});

        // $(document).on('click', '.hapusBaris', function () {
        //     $(this).closest('tr').remove();
        // });

		$(document).on("click", ".btn-detail", function() {
			let frame = $("#modal_frame");

            // Mengambil dari atribut data-id tombol yang diklik
			let id_karyawan = $(this).data("id");

			frame.find(".modal-title").html("Detail Absensi Karyawan");

			// Nunggu modal bener-bener ketutup dulu
			frame.one('hidden.bs.modal', function () {
				// Buka modal lagi
				frame.modal("show");

				// Load isi konten edit bahan
				$.get("<?= site_url('gaji/modal_detail_absensi'); ?>/" + id_karyawan, function(res) {
					frame.find(".modal-body").html(res);

					const ctx = document.getElementById('doughnut');
					if (ctx) {
						new Chart(ctx, {
							type: 'doughnut',
							data: {
								labels: ['Hadir', 'Sakit', 'Izin', 'Alpha'],
								datasets: [{
									data: [
										parseInt($('#doughnut').data('hadir')), 
										parseInt($('#doughnut').data('sakit')), 
										parseInt($('#doughnut').data('izin')), 
										parseInt($('#doughnut').data('alpha'))
									],
									backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545']
								}]
							},
							options: {
								responsive: true,
								plugins: {
									legend: { position: 'bottom' }
								}
							}
						});
					}

				});
			});

			frame.modal("hide"); // Trigger nutup modal dulu
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
</body>
<!--end body-->

</html>