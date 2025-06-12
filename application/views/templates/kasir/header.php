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
                    <a class="nav-link" href="<?= site_url('kasir') ?>">Kasir</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('kasir/setoran') ?>">Setor</a>
                </li>
                <li class="topbar-item">
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('admin') ?>">Admin</a>
                </li>
                <li class="dropdown topbar-item">
                    <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false" data-bs-offset="0,19">
                        <img src="<?= base_url('upload/karyawan/').$this->session->userdata('user')->foto_karyawan ?>" alt="" class="thumb-md rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0">
                        <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                            <div class="flex-shrink-0">
                                <img src="<?= base_url('upload/karyawan/').$this->session->userdata('user')->foto_karyawan ?>" alt="" class="thumb-md rounded-circle">
                            </div>
                            <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                <h6 class="my-0 fw-medium text-dark fs-13"><?= $this->session->userdata('user')->nama_karyawan ?></h6>
                                <small class="text-muted mb-0"><?= $this->session->userdata('user')->peran_karyawan ?></small>
                            </div><!--end media-body-->
                        </div>
                        <div class="dropdown-divider mb-0"></div>
                        <a class="dropdown-item text-danger" href="<?= site_url('auth/logout') ?>"><i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>