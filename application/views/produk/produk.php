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
                        <h4 class="page-title">Produk</h4>
                        <div class="">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a>
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item active">Produk</li>
                                <li class="breadcrumb-item active">Data Produk</li>
                            </ol>
                        </div>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div><!--end row-->
            <button class="btn btn-primary btn-sm mb-2 btn-pemilik">Data Pemilik</button>
            <button class="btn btn-primary btn-sm mb-2 btn-produk">Data Produk</button>
            <button class="btn btn-primary btn-sm mb-2 btn-outlet">Data Outlet</button>
            
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">                      
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <?php $i = 0; foreach ($outlets as $outlet): ?>
                                            <a class="nav-link py-2 <?= $i == 0 ? 'active' : '' ?>"
                                                id="tab<?= $i ?>-tab"
                                                data-bs-toggle="tab"
                                                href="#tab<?= $i ?>"
                                                aria-selected="<?= $i == 0 ? 'true' : 'false' ?>"
                                                role="tab">
                                                <?= $outlet->nama_outlet ?>
                                            </a>
                                            <?php $i++; endforeach; ?>
                                        </div>
                                    </nav>
                                </div><!--end col-->
                            </div>  <!--end row-->                                  
                        </div><!--end card-header-->
                        <div class="card-body pt-0">
                            <div class="tab-content" id="nav-tabContent">
                                <?php $i = 0; foreach ($outlets as $outlet): ?>
                                <div class="tab-pane <?= $i == 0 ? 'active' : '' ?>"
                                    id="tab<?= $i ?>"
                                    role="tabpanel"
                                    aria-labelledby="tab<?= $i ?>-tab">
                                    <div class="justify-content-between d-flex">
                                        <h5 class="card-title">Data Produk <?= $outlet->nama_outlet ?></h5>
                                        <button type="button" data-id_outlet="<?= $outlet->id_outlet ?>" class="btn btn-primary btn-sm btn-produkoutlet-add">Tambah Produk</button>
                                    </div>
                                    <div class="table-responsive mt-3">
                                        <table class="table table-centered tabelProduk" id="tabelProduk<?= $i ?>" data-id="<?= $outlet->id_outlet ?>" style="width: 100%;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nomor</th>
                                                    <th>Nama Produk</th>
                                                    <th>Pemilik</th>
                                                    <th>Harga</th>
                                                    <th>Stok</th>
                                                    <th class="text-end">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <!-- Konten outlet bisa ditambahkan di sini -->
                                </div>
                                <?php $i++; endforeach; ?>
                            </div>
                        </div><!--end card-body--> 
                    </div><!--end card--> 
                </div>

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
    var tableProdukInstances = {};

    $(document).ready(function () {
        $('.tabelProduk').each(function () {
            var tableId = $(this).attr('id');
            var id_outlet = $(this).data('id');

            tableProdukInstances[tableId] = $('#' + tableId).DataTable({
                ajax: {
                    url: "<?= base_url('produk/get_all_produk_by_outlet'); ?>",
                    type: "GET",
                    data: {
                        id_outlet: id_outlet
                    }
                },
                paging: true,
                info: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                pageLength: 10,
                lengthMenu: [5, 8, 10, 25],
                columnDefs: [{ targets: 5, className: 'text-end' }]
            });
        });

        $(document).on("click", ".btn-produkoutlet-add", function() {
            let frame = $("#modal_frame");
            let id_outlet = $(this).data("id_outlet");

            frame.find(".modal-title").html("Tambah Produk");

            frame.modal("show");

            $.get("<?= site_url('produk/modal_add_produkoutlet'); ?>", { id_outlet: id_outlet }, function(res) {
                frame.find(".modal-body").html(res);

                // Set nilai input hidden setelah form dimuat
                frame.find("input[name='id_outlet']").val(id_outlet);
            });
        });

        // Tambahkan event ketika tab dibuka
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            // Ambil ID tabel di dalam tab yang aktif
            var targetTab = $(e.target).attr("href"); // misal: #tab-outlet-solo
            var table = $(targetTab).find('.tabelProduk'); // cari tabel di dalam tab itu
            var tableId = table.attr('id');

            // Adjust kolom DataTables
            if (tableId && tableProdukInstances[tableId]) {
                tableProdukInstances[tableId].columns.adjust().draw();
            }
        });

        $(document).on("click", ".btn-delete-produk-outlet", function() {
            var id_produk = $(this).data("id");            
            var url = "<?= base_url('produk/delete_produkoutlet'); ?>";

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
                        $.post(url, {id_produk: id_produk}, function(res) {
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

        $(document).on("click", ".btn-pemilik", function() {
            let frame = $("#modal_frame");

            frame.find(".modal-title").html("Data Pemilik");

            // Menampilkan modal
            frame.modal("show");

            // Mengambil data tabungan untuk diedit
            $.get("<?= site_url('produk/modal_pemilik'); ?>", function(res) {
                frame.find(".modal-body").html(res);
            });
        });

        $(document).on("click", ".btn-outlet", function() {
            let frame = $("#modal_frame");

            frame.find(".modal-title").html("Data Outlet");

            // Menampilkan modal
            frame.modal("show");

            // Mengambil data tabungan untuk diedit
            $.get("<?= site_url('produk/modal_outlet'); ?>", function(res) {
                frame.find(".modal-body").html(res);
            });
        });

        $(document).on("click", ".btn-produk", function() {
            let frame = $("#modal_frame");

            frame.find(".modal-title").html("Data Produk");

            // Menampilkan modal
            frame.modal("show");

            // Mengambil data tabungan untuk diedit
            $.get("<?= site_url('produk/modal_produk'); ?>", function(res) {
                frame.find(".modal-body").html(res);
            });
        });
    });

</script>
</body>
<!--end body-->

</html>