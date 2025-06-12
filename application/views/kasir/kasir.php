<?php $this->load->view('templates/kasir/head') ?>
<?php $this->load->view('templates/kasir/header') ?>
<div class="page-wrapper">

    <!-- Page Content-->
    <div class="page-content">
        <div class="container-fluid">
            <div class="mt-3">
                <form action="<?= site_url('kasir/simpan_transaksi') ?>" method="POST">
                    <div class="container-fluid">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" id="keranjang-tab" data-bs-toggle="tab" data-bs-target="#keranjang-tab-pane" type="button">Keranjang</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="transaksi-tab" data-bs-toggle="tab" data-bs-target="#transaksi-tab-pane" type="button">Transaksi</button>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="myTabContent">

                            <!-- KERANJANG -->
                            <div class="tab-pane fade show active" id="keranjang-tab-pane" role="tabpanel">
                                <div class="d-flex justify-content-between mt-3">
                                    <h4>Pilih Produk</h4>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="mb-3">
                                            <input type="search" id="search-produk" name="search" class="form-control" placeholder="Search here...">
                                        </div>
                                        <div id="produk-search"></div>
                                        <div class="row" id="produk-list">
                                            <?php foreach ($produk as $key): ?>
                                                <div class="col-lg-3 col-md-4 col-6">
                                                    <div class="card pilih-produk" 
                                                    data-id="<?= $key->id_produk ?>"
                                                    data-nama="<?= $key->nama_produk ?>"
                                                    data-harga="<?= $key->harga_outlet ?>"
                                                    data-harga-default="<?= $key->harga_default ?>"
                                                    data-stok="<?= $key->stok ?>"
                                                    data-jenis-pemilik="<?= $key->jenis_pemilik ?>"
                                                    data-id-pemilik="<?= $key->id_pemilik ?>">
                                                    <div class="card-body">
                                                        <h5><?= $key->nama_produk ?></h5>
                                                        <p>Rp <?= number_format($key->harga_outlet, 0, ',', '.') ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3>Keranjang</h3>
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 50%;">Produk</th>
                                                            <th style="width: 50%;">Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="keranjang-body">
                                                        <tr>
                                                            <td colspan="2"><em>Belum ada produk dipilih</em></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="bg-primary-subtle rounded p-3">
                                                <h3>Total: Rp <span id="total-harga">0</span></h3>
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-soft-danger btn-reset me-2">Reset</button>
                                                    <button type="button" class="btn btn-primary" id="btn-lanjut-transaksi">Lanjut Transaksi</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TRANSAKSI -->
                        <div class="tab-pane fade" id="transaksi-tab-pane" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card w-100">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 50%;">Produk</th>
                                                            <th style="width: 50%;">Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="detailKeranjang">
                                                        <tr>
                                                            <td colspan="2"><em>Belum ada produk dipilih</em></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="bg-primary-subtle rounded p-3 d-flex justify-content-between mb-3">
                                        <h2>Total:</h2>
                                        <h2>Rp <span id="total-diskon">0</span></h2>
                                        <input type="hidden" name="total_diskon" value="">
                                        <input type="hidden" name="total" value="">
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <label>Bayar</label>
                                            <input class="form-control mb-3 input-rupiah" type="text" min="0" name="bayar">
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Kembalian</label>
                                            <input class="form-control mb-3 input-rupiah" type="text" name="kembalian" readonly>
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Diskon (Rp)</label>
                                            <input class="form-control mb-3 input-rupiah" type="text" name="diskon" min="0" value="0">
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Metode Bayar</label>
                                            <select class="form-select" name="metode_bayar">
                                                <option value="cash">Cash</option>
                                                <option value="cashless">Cashless</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-12">
                                            <label>Catatan</label>
                                            <textarea class="form-control" name="catatan"></textarea>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-soft-danger me-2" id="btn-kembali" type="button">Kembali</button>
                                        <button class="btn btn-primary">Simpan & Cetak Struk</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('templates/kasir/foot') ?>
<script>
    $(document).ready(function () {
        $('#search-produk').on('input', function () {
            let keyword = $(this).val();

        if (keyword.length >= 2) { // hanya cari jika input ≥ 2 karakter
            // Sembunyikan produk lama
            $('#produk-list').hide();

            $.ajax({
                url: "<?= site_url('kasir/cari_produk') ?>", // sesuaikan endpoint kamu
                method: "GET",
                data: { keyword: keyword },
                success: function (data) {
                    $('#produk-search').html(data);
                },
                error: function () {
                    $('#produk-search').html('<p class="text-danger">Terjadi kesalahan saat pencarian.</p>');
                }
            });
        } else {
            // Kosongkan hasil pencarian & tampilkan produk lama
            $('#produk-search').empty();
            $('#produk-list').show();
        }
    });
    });
</script>
<script>    
    document.getElementById('btn-lanjut-transaksi').addEventListener('click', function () {
        const triggerTab = new bootstrap.Tab(document.querySelector('#transaksi-tab'));
        triggerTab.show();
    });

    document.getElementById('btn-kembali').addEventListener('click', function () {
        const triggerTab = new bootstrap.Tab(document.querySelector('#keranjang-tab'));
        triggerTab.show();
    });

    document.addEventListener("DOMContentLoaded", function () {
        const keranjangBody     = document.getElementById("keranjang-body");
        const detailKeranjang   = document.getElementById("detailKeranjang");
        const totalHargaElem    = document.getElementById("total-harga");
        const totalDiskonElem   = document.getElementById("total-diskon");
        const inputTotal        = document.querySelector('input[name="total"]');
        const inputTotalDiskon  = document.querySelector('input[name="total_diskon"]');

        // AutoNumeric instances
        const autoBayar = new AutoNumeric('input[name="bayar"]', {
            currencySymbol: 'Rp ',
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            unformatOnSubmit: true
        });

        const autoKembalian = new AutoNumeric('input[name="kembalian"]', {
            currencySymbol: 'Rp ',
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            readOnly: true
        });

        const autoDiskon = new AutoNumeric('input[name="diskon"]', {
            currencySymbol: 'Rp ',
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            unformatOnSubmit: true
        });

        let keranjang = [];

        function formatRupiah(angka) {
            return angka.toLocaleString('id-ID');
        }

        function getTotalTanpaDiskon() {
            return keranjang.reduce((acc, item) => acc + (item.harga * item.qty), 0);
        }

        function getDiskon() {
            return autoDiskon.getNumber() || 0;
        }

        function updateTotal() {
            let total = getTotalTanpaDiskon();
            let diskon = getDiskon();

            // Validasi diskon tidak lebih besar dari total
            if (diskon > total) {
                diskon = total;
                autoDiskon.set(total);
            }

            let finalTotal = total - diskon;

            // Update total di halaman keranjang
            totalHargaElem.textContent = formatRupiah(finalTotal);

            // Update total di halaman transaksi
            totalDiskonElem.textContent = formatRupiah(finalTotal);

            // Simpan nilai hidden input
            inputTotal.value = finalTotal;
            inputTotalDiskon.value = finalTotal;

            // Update kembalian jika bayar sudah diisi
            const bayar = autoBayar.getNumber() || 0;
            const kembali = bayar - finalTotal;
            autoKembalian.set(kembali > 0 ? kembali : 0);
        }

        function renderKeranjang() {
            keranjangBody.innerHTML = "";
            detailKeranjang.innerHTML = "";

            keranjang.forEach((item, index) => {
                keranjangBody.innerHTML += `
                    <tr>
                        <td>
                            <h5 class="mb-1">${item.nama}</h5>
                            <p>Rp <b>${formatRupiah(item.harga * item.qty)}</b></p>
                            <input type="hidden" name="id_produk[]" value="${item.id}">
                            <input type="hidden" name="nama_produk[]" value="${item.nama}">
                            <input type="hidden" name="subtotal[]" value="${item.harga * item.qty}">
                            <input type="hidden" name="qty[]" value="${item.qty}">
                            <input type="hidden" name="harga_satuan[]" value="${item.harga}">
                            <input type="hidden" name="harga_default[]" value="${item.hargaDefault}">
                            <input type="hidden" name="jenis_pemilik[]" value="${item.jenisPemilik}">
                            <input type="hidden" name="id_penitip[]" value="${item.idPemilik}">
                        </td>
                        <td class="text-end">
                            <button class="btn btn-soft-danger btn-sm btn-min-keranjang" data-index="${index}">-</button>
                            <span class="mx-2">${item.qty}</span>
                            <button class="btn btn-soft-primary btn-sm btn-plus-keranjang" data-index="${index}">+</button>
                        </td>
                </tr>`;

                detailKeranjang.innerHTML += `
                    <tr>
                        <td>
                            <h4 class="mb-1">${item.nama}</h4>
                            <p>Rp <b>${formatRupiah(item.harga * item.qty)}</b></p>
                        </td>
                        <td class="text-end">${item.qty}</td>
                </tr>`;
            });

            if (keranjang.length === 0) {
                keranjangBody.innerHTML = '<tr><td colspan="2"><em>Belum ada produk dipilih</em></td></tr>';
                detailKeranjang.innerHTML = '<tr><td colspan="2"><em>Belum ada produk dipilih</em></td></tr>';
            }

            updateTotal();
        }

        // Event: Tambah produk
        document.addEventListener("click", function (e) {
            if (e.target.closest(".pilih-produk")) {
                const card = e.target.closest(".pilih-produk");
                const id = card.dataset.id;
                const nama = card.dataset.nama;
                const stok = parseInt(card.dataset.stok);
                const harga = parseInt(card.dataset.harga);
                const hargaDefault = parseInt(card.dataset.hargaDefault);
                const jenisPemilik = card.dataset.jenisPemilik;
                const idPemilik = card.dataset.idPemilik;

                const existing = keranjang.find(item => item.id === id);

                if (existing) {
                    if (existing.qty < stok) {
                        existing.qty += 1;
                    } else {
                        alert(`Stok untuk ${nama} hanya ${stok}`);
                    }
                } else {
                    if (stok > 0) {
                        keranjang.push({ id, nama, harga, hargaDefault, qty: 1, stok, jenisPemilik, idPemilik });
                    } else {
                        alert(`Stok untuk ${nama} habis`);
                    }
                }

                renderKeranjang();
            }
        });

        // Event: Tambah/kurang qty
        keranjangBody.addEventListener("click", function (e) {
            const index = e.target.dataset.index;
            if (e.target.classList.contains("btn-plus-keranjang")) {
                const item = keranjang[index];
                if (item.qty < item.stok) {
                    item.qty += 1;
                } else {
                    alert(`Stok untuk ${item.nama} hanya ${item.stok}`);
                }
            }
            if (e.target.classList.contains("btn-min-keranjang")) {
                if (keranjang[index].qty > 1) {
                    keranjang[index].qty -= 1;
                } else {
                    keranjang.splice(index, 1);
                }
            }
            renderKeranjang();
        });

        // Reset
        document.querySelector('.btn-reset').addEventListener('click', function () {
            keranjang = [];
            renderKeranjang();
        });

        // Diskon & Bayar berubah
        document.querySelector('input[name="diskon"]').addEventListener('input', updateTotal);
        document.querySelector('input[name="bayar"]').addEventListener('input', updateTotal);

        // Cegah input minus
        document.querySelector('input[name="diskon"]').addEventListener('input', function () {
            if (autoDiskon.getNumber() < 0) {
                autoDiskon.set(0);
            }
            updateTotal();
        });

        document.querySelector('input[name="bayar"]').addEventListener('input', function () {
            if (autoBayar.getNumber() < 0) {
                autoBayar.set(0);
            }
            updateTotal();
        });

        // Validasi submit
        document.querySelector("form").addEventListener("submit", function(e) {
            const bayar = autoBayar.getNumber() || 0;
            const total = parseInt(inputTotal.value) || 0;

            if (total === 0) {
                e.preventDefault();
                alert("Tidak ada produk dalam keranjang.");
                return;
            }

            if (bayar < total) {
                e.preventDefault();
                alert("Uang bayar kurang dari total!");
            }

            const kembalianInput = document.querySelector('input[name="kembalian"]');
            kembalianInput.value = autoKembalian.getNumber();
        });
    });
</script>
</body>
</html>
