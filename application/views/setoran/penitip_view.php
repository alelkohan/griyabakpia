<?php $this->load->view('templates/kasir/head') ?>
<?php $this->load->view('templates/kasir/header') ?>
<div class="page-wrapper">

    <!-- Page Content-->
    <div class="page-content">
        <div class="container-fluid">
            <div class="mt-3">
				<div class="table-responsive">
					<table class="table" id="penitip_table">
						<thead class="table-light">
							<tr>
								<th>No</th>
								<th>Penitip</th>
								<th>Setoran produk & Pembayaran</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
			<div class="modal fade" tabindex="-1" id="modal_frame">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Modal title</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</button>
					</div>
					<div class="modal-body">
						<p>Modal body text goes here.</p>
					</div>
					<div class="modal-footer">
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
<?php $this->load->view('templates/kasir/foot') ?>

<script>
	$(document).ready(function () {
		var table_penitip = $('#penitip_table');
		var pesan_loading = '<p class="text-center"><em>Work in progress...</em></p>';
		var frame = $('#modal_frame');

		table_penitip.DataTable({
			ajax: "<?= site_url('kasir/penitip_daftar') ?>",
			responsive: true,
			language: {
	          // url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' // Bahasa Indonesia
			}
		});

		// PRODUK PENITIP
		$(document).on("click",".btn-produk-penitip",function(){
			let id_pemilik = $(this).data('id');
			frame.find(".modal-title").html("Produk Pemilik");
			frame.find(".modal-body").html(pesan_loading);
			frame.modal("show");
			$.post('<?= site_url('kasir/produk_penitip/') ?>' + id_pemilik, function(res){
				frame.find(".modal-body").html(res);

				$('.produk_penitip_table').DataTable({
					responsive: true,
					language: {
		                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' // bahasa indonesia opsional
		            }
		        });
			});
		});

		// SETORAN PENITIP
		$(document).on("click",".btn-setoran-penitip",function(){
			let id_pemilik = $(this).data('id');
			frame.find(".modal-title").html("Setoran Penitip");
			frame.find(".modal-body").html(pesan_loading);
			frame.modal("show");
			$.post('<?= site_url('kasir/setoran_penitip/') ?>' + id_pemilik, function(res){
				frame.find(".modal-body").html(res);

				$('.riwayat_titipan_table').DataTable({
					responsive: true,
					language: {
		                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' // bahasa indonesia opsional
		            }
		        });
			});
		});

		//PENITIP MENYETOR
		$(document).on('submit', '#setoran_penitip', function(event) {
			event.preventDefault();

			let isValid = true;
			let jumlahInputs = $('input[name="jumlah[]"]');

			if (jumlahInputs.length === 0) {
				toastr.warning('Silakan pilih minimal satu produk untuk disetorkan.');
				return;
			}

			jumlahInputs.each(function() {
				let val = $(this).val();
				if (val === '' || isNaN(val) || Number(val) <= 0) {
					$(this).addClass('is-invalid');
					isValid = false;
				} else {
					$(this).removeClass('is-invalid');
				}
			});

			if (!isValid) {
				toastr.warning('Pastikan jumlah produk diisi dengan angka lebih dari 0.');
				return;
			}

			$.ajax({
				url: '<?= site_url('kasir/simpan_setoran_penitip') ?>',
				type: 'POST',
				data: $(this).serialize()
			})
			.done(function(respon) {
				if (respon.status === 'success') {
					frame.modal('hide');
					toastr.success(respon.message, 'Success');
				} else {
					toastr.error(respon.message, 'Error');
				}
			})
			.fail(function() {
				toastr.error('Terjadi error di server!', 'Error');
			});
		});

		// DETAIL SETORAN PENITIP
		$(document).on("click",".btn-detail-titipan",function(){
			let id_titipan_toko = $(this).data('id');
			frame.find(".modal-title").html("Detail Titipan Toko");
			frame.find(".modal-body").html(pesan_loading);
			frame.modal("show");
			$.post('<?= site_url('kasir/detail_setoran_penitip/') ?>' + id_titipan_toko, function(res){
				frame.find(".modal-body").html(res);

				$('.detail_titipan_toko_table').DataTable({
					responsive: true,
					language: {
	                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' // bahasa indonesia opsional
	                }
	            });
			});
		});

		// PEMBAYARAN PENITIP
		$(document).on("click",".btn-pembayaran-penitip",function(){
			let id_pemilik = $(this).data('id');
			frame.find(".modal-title").html("Pembayaran Penitip");
			frame.find(".modal-body").html(pesan_loading);
			frame.modal("show");
			$.post('<?= site_url('kasir/pembayaran_penitip/') ?>' + id_pemilik, function(res){
				frame.find(".modal-body").html(res);
				$('.riwayat_pembayaran_table').DataTable({
					responsive: true,
					language: {
	                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' // bahasa indonesia opsional
	                }
	            });
			});
		});


		$(document).on('submit', '#pembayaran_penitip', function(event) {
			event.preventDefault();

			let idTitipan = $('#select_titipan').val();
			let bayar = parseInt($('#input_bayar').val()) || 0;

			if (!idTitipan) {
				toastr.warning('Silakan pilih tanggal titipan terlebih dahulu.', 'Peringatan');
				return;
			}

			if (bayar <= 0) {
				toastr.warning('Total bayar tidak valid. Mungkin belum ada bagi hasil.', 'Peringatan');
				return;
			}

			$.ajax({
				url: '<?= site_url('kasir/simpan_pembayaran_penitip') ?>',
				type: 'POST',
				data: $(this).serialize()
			})
			.done(function(respon) {
				if (respon.status === 'success') {
					frame.modal('hide');
					toastr.success(respon.message, 'Success');
				} else {
					toastr.error(respon.message, 'Error');
				}
			})
			.fail(function() {
				toastr.error('Terjadi error di server!', 'Error');
			});
		});
	});
</script>
</body>
</html>