<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    // asu
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
    }

    public function index()
    {
        $this->load->view('auth/login');
    }

    public function login()
    {
        $nama_karyawan = $this->input->post('nama_karyawan', TRUE);
        $password = $this->input->post('password', TRUE);


        $karyawan = $this->auth_model->login($nama_karyawan, $password);

        // var_dump($karyawan);
        // die();

        if ($karyawan) {

            //KARYAWAN
            if ($karyawan->peran_karyawan === 'kasir') {
                $this->session->set_userdata('user',$karyawan);
                redirect('kasir');

            //ADMIN
            } elseif ($karyawan->peran_karyawan === 'admin') {
                $this->session->set_userdata('user',$karyawan);
                redirect('admin');

            //MANAGER
            } elseif ($karyawan->peran_karyawan === 'manager') {
                $this->session->set_userdata('user',$karyawan);
                redirect('admin');

            //PRODUKSI
            } else {
                echo "anda belum punya akses ke sistem, Coming Soon...";
            }

        } else {
            $this->session->set_flashdata('error','Tidak ditemukan data karyawan');
            redirect('auth');
        }
    } 

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }   
}