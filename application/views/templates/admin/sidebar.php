<!-- leftbar-tab-menu -->
<div class="startbar d-print-none">
    <!--start brand-->
    <div class="brand">
        <a href="<?= site_url('dashboard') ?>" class="logo">
            <span>
                <img src="<?= base_url('approx-v1.0/dist/') ?>assets/images/griyabakipa.png" alt="logo-small" class="logo-sm">
            </span>
            <span class="">
                <img src="<?= base_url('approx-v1.0/dist/') ?>assets/images/griyabakipa.png" alt="logo-large" class="logo-lg logo-light">
                <img src="assets/images/logo-dark.png" alt="logo-large" class="logo-lg logo-dark">
            </span>
        </a>
    </div>
    <!--end brand-->
    <!--start startbar-menu-->
    <div class="startbar-menu">
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-start flex-column w-100">
                <!-- Navigation -->
                <ul class="navbar-nav mb-auto w-100">
                    <li class="menu-label mt-2">
                        <span>Main</span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('dashboard') ?>">
                            <i class="iconoir-report-columns menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('kasir') ?>">
                            <i class="fa fa-money-bill menu-icon"></i>
                            <span>Kasir</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarTransactions" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarTransactions">
                        <i class="iconoir-cube-hole menu-icon"></i>
                        <span>Produk</span>
                    </a>
                    <div class="collapse " id="sidebarTransactions">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="<?= site_url('produk') ?>">Data Produk</a>
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="<?= site_url('produk/index_stok') ?>">Riwayat Stok</a>
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="<?= site_url('produk/index_toko') ?>">Toko</a>
                            </li><!--end nav-item-->
                        </ul><!--end nav-->
                    </div><!--end startbarTables-->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('bahan') ?>">
                            <i class="fas fa-boxes menu-icon"></i>
                            <span>Bahan</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('keuangan') ?>">
                            <i class="fas fa-money-bill-wave menu-icon"></i>
                            <span>Keuangan</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarKaryawan" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarKaryawan">
                        <i class="fas fa-users menu-icon"></i>
                        <span>Karyawan</span>
                    </a>
                    <div class="collapse " id="sidebarKaryawan">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="<?= site_url('karyawan') ?>">Data Karyawan</a>
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="<?= site_url('karyawan/index_absensi') ?>">Absensi Karyawan</a>
                            </li><!--end nav-item-->
                        </ul><!--end nav-->
                    </div><!--end startbarTables-->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('gaji') ?>">
                            <i class="fas fas fa-receipt menu-icon"></i>
                            <span>Gaji Karyawan</span>
                        </a>
                    </li><!--end nav-item-->
                </ul><!--end navbar-nav--->
            </div>
        </div><!--end startbar-collapse-->
    </div><!--end startbar-menu-->
</div><!--end startbar-->
<div class="startbar-overlay d-print-none"></div>
<!-- end leftbar-tab-menu-->