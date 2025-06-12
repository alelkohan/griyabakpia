<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('user')) {

            $user = $this->session->userdata('user');
            $allowed_roles = ['admin', 'manager'];

            if (!in_array($user->peran_karyawan, $allowed_roles)) {
                echo "Anda tidak punya akses ke halaman ini.";
                die();
            }

        } else {
            redirect('auth');
        }
    }

    public function index()
    {
        $this->load->view('dashboard');
    }
}
