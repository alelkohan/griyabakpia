<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="dark" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title>GriyaBakpia | Kasir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="<?= base_url() ?>approx-v1.0/dist/assets/images/kotak.png">

    <link href="<?= base_url() ?>approx-v1.0/dist/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= base_url() ?>approx-v1.0/dist/assets/css/icons.min.css" rel="stylesheet" />
    <link href="<?= base_url() ?>approx-v1.0/dist/assets/css/app.min.css" rel="stylesheet" />
    <link href="<?= base_url() ?>assets/libs/simple-datatables/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime&display=swap" rel="stylesheet">
    <style>
        @media print {
            @page {
                margin: 0;
            }

            body {
                margin: 0;
                font-size: 11px;
                font-family: 'Courier New', monospace; /* Font cocok untuk struk */
            }

            .container {
                padding: 0;
                margin: 0 auto;
                width: auto;
                max-width: 100%;
            }

            h5, p {
                margin: 0;
                padding: 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin: 0 0 5px 0;
            }

            table th,
            table td {
                padding: 2px 4px;
                font-size: 11px;
                word-break: break-word;
                vertical-align: top;
            }

            table th:nth-child(1),
            table td:nth-child(1) {
                width: 50%; /* Produk */
            }

            table th:nth-child(2),
            table td:nth-child(2) {
                width: 20%; /* Qty */
                text-align: right;
            }

            table th:nth-child(3),
            table td:nth-child(3) {
                width: 30%; /* Subtotal */
                text-align: right;
            }

            button, .btn, .text-end {
                display: none !important;
            }
            body {
                font-family: 'Courier Prime', monospace;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-end my-3">
            <a href="<?= site_url('kasir') ?>" class="btn btn-soft-primary">Kembali</a>
            <button class="btn btn-primary" onclick="window.print()">🖨 Cetak Struk</button>
        </div>

        <h5>Griya Bakpia</h5>
        <p>Cabang : <?= $transaksi->nama_outlet ?></p>
        <hr>
        <table class="table">
            <tr>
                <td>Kasir</td>
                <td> : </td>
                <td><?= $transaksi->nama_karyawan ?></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td> : </td>
                <td><?= $transaksi->waktu_transaksi ?></td>
            </tr>
            <tr>
                <td>Invoice</td>
                <td> : </td>
                <td><?= $transaksi->id_transaksi_kasir ?></td>
            </tr>
        </table>
        <hr>
        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detail_transaksi as $key): ?>
                    <tr>
                        <td><?= $key->nama_produk ?></td>
                        <td><?= $key->qty ?></td>
                        <td><?= number_format($key->subtotal, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <hr>
        <table class="table">
            <tr>
                <td>Total : </td>
                <td><?= number_format($transaksi->total_harga, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Bayar : </td>
                <td><?= number_format($transaksi->jumlah_bayar, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Kembalian : </td>
                <td><?= number_format($transaksi->jumlah_kembalian, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Diskon : </td>
                <td><?= number_format($transaksi->diskon, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Total Diskon : </td>
                <td><?= number_format($transaksi->total_diskon, 0, ',', '.') ?></td>
            </tr>
        </table>
        <p>Catatan : <?= $transaksi->catatan ?></p>
        <hr>
        <h5>Terimakasih</h5>
    </div>
</body>
</html>