<?php $this->load->view('templates/admin/head') ?>
<div class="container-xxl">
    <div class="row vh-100 d-flex justify-content-center">
        <div class="col-12 align-self-center">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 mx-auto">
                        <div class="card">
                            <div class="card-body p-0 bg-black auth-header-box rounded-top">
                                <div class="text-center p-3">
                                    <a href="index.html" class="logo logo-admin">
                                        <img src="<?= base_url('approx-v1.0/dist/assets/images/griyabakipa.png') ?>" height="50" alt="logo" class="auth-logo">
                                    </a>
                                    <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Griya Bakpiya</h4>
                                    <p class="text-muted fw-medium mb-0">Login untuk melanjutkan.</p>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <form class="my-4" action="<?= site_url('auth/login') ?>" method="POST">
                                    <div class="form-group mb-2">
                                        <label class="form-label" for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="nama_karyawan" placeholder="Masukkan nama">
                                    </div><!--end form-group-->

                                    <div class="form-group">
                                        <label class="form-label" for="userpassword">Password</label>
                                        <input type="password" class="form-control" name="password" id="userpassword" placeholder="Masukkan password">
                                    </div><!--end form-group-->

                                    <?php if ($this->session->flashdata('error')): ?>
                                        <p class="text-danger"><em><?= $this->session->flashdata('error') ?></em></p>
                                    <?php endif ?>

                                    <div class="form-group mb-0 row">
                                        <div class="col-12">
                                            <div class="d-grid mt-3">
                                                <button class="btn btn-primary" type="submit">Log In <i class="fas fa-sign-in-alt ms-1"></i></button>
                                            </div>
                                        </div><!--end col-->
                                    </div> <!--end form-group-->
                                </form><!--end form-->
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end card-body-->
        </div><!--end col-->
    </div><!--end row-->
</div>
<?php $this->load->view('templates/admin/foot') ?>