<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="<?= base_url() ?>approx-v1.0/dist/assets/images/kotak.png" class="img-fluid" style="height: 30px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?= site_url('kasir') ?>">Kasir</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('kasir/setoran') ?>">Setor</a>
                </li>
                <li class="topbar-item">
                </ul>
            </div>
        </div>
    </nav>