<!-- Top Bar Start -->
<div class="topbar d-print-none w-100">
    <div class="container-fluid">
        <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">


            <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                <li>
                    <a class="navbar-brand" href="<?= site_url('') ?>">
                        <img src="<?= base_url() ?>approx-v1.0/dist/assets/images/kotak.png" class="img-fluid" style="height: 30px;">
                    </a>
                    <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0 mx-3">
                        <li class="topbar-item mx-2">
                            <a class="nav-link" href="<?= site_url('kasir') ?>">Kasir</a>
                        </li>
                        <li class="topbar-item mx-2">
                            <a class="nav-link" href="<?= site_url('kasir/setoran') ?>">Setoran</a>
                        </li>
                        <?php if ($this->session->userdata('user')->peran_karyawan === 'admin' || $this->session->userdata('user')->peran_karyawan === 'manager'): ?>
                        <li class="topbar-item mx-2">
                            <a class="nav-link" href="<?= site_url('admin') ?>">Admin</a>
                        </li>
                        <?php endif ?>
                    </ul>
                </li>
            </ul>
            <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">

                <li class="topbar-item">
                    <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                        <i class="iconoir-half-moon dark-mode"></i>
                        <i class="iconoir-sun-light light-mode"></i>
                    </a>
                </li>

                <li class="topbar-item dropdown topbar-item">
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
                            </div>
                        </div>
                        <div class="dropdown-divider mb-0"></div>
                        <a class="dropdown-item text-danger" href="<?= site_url('auth/logout') ?>"><i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Logout</a>
                    </div>
                </li>
            </ul><!--end topbar-nav-->
        </nav>
        <!-- end navbar-->
    </div>
</div>
<!-- Top Bar End -->