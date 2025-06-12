<?php
defined('BASEPATH') or exit('No direct script access allowed');

class keuangan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Keuangan_model');

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
        $data['keuangan'] = $this->Keuangan_model->get_keuangan();
        $this->load->view('keuangan/keuangan', $data);
    }

    private function set_output($data)
    {
        $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data, JSON_PRETTY_PRINT))
        ->_display();
        exit;
    }

    public function get_all_keuangan()
    {
        $tanggal_from = $this->input->get('mulai_tanggal');
        $tanggal_to = $this->input->get('sampai_tanggal');
        $asal = $this->input->get('asal');

        if (empty($tanggal_from) && empty($tanggal_to) && empty($asal)) {
            $daftar_keuangan = $this->Keuangan_model->get_keuangan();
        } else {
            $daftar_keuangan = $this->Keuangan_model->get_keuangan_filter($tanggal_from, $tanggal_to, $asal);
        }

        $daftar_input = array();
        $no = 1;

        foreach ($daftar_keuangan as $key) {
            $nilai_mutasi = "Rp " . number_format($key->nilai_mutasi, 0, ',', '.');
            // $harga_total = "Rp " . number_format($key->harga_total, 0, ',', '.');

            $jenis = ($key->jenis == "Pengeluaran")
            ? '<span class="badge rounded text-danger bg-danger-subtle">' . $key->jenis .'</span>'
            : '<span class="badge rounded text-success bg-success-subtle">' . $key->jenis . '</span>';

            $bahan_input = array(
                $no++,
                $key->asal,
                $jenis,
                $key->tanggal,
                $nilai_mutasi,
                $key->keterangan,
                '<button class="btn btn-keuangan-edit" data-id="' . $key->id_keuangan .'"><i class="fas fa-pencil-alt text-warning"></i></button>',
            );
            array_push($daftar_input, $bahan_input);
        };

        // Kirim data dalam bentuk JSON
        $response = array(
            'data' => $daftar_input
        );
        $this->set_output($response);
    }

    public function create_transaksi()
    {
        $nilai = $this->input->post('nilai');
        $saldo = $this->Keuangan_model->get_saldo();
        $jenis_transaksi = $this->input->post('jenis_transaksi');
        $keterangan = $this->input->post('keterangan');

        $nilai = str_replace(['Rp', '.', ' '], '', $nilai);

        if ($jenis_transaksi == 'pengeluaran') {

            if ($saldo < $nilai) {
                $this->set_output(['status' => 'error', 'message' => 'Saldo tidak cukup untuk transaksi']);
                return;
            }

            $this->Keuangan_model->kurangi_saldo($nilai, $keterangan, 'Pengeluaran Non-Operasional');

        } else { 
            $this->Keuangan_model->tambah_saldo($nilai, $keterangan, 'Pemasukan Non-Operasional');
        }

    }

    public function modal_add_non_operasional()
    {
        $this->load->view('keuangan/non_operasional_add_modal');
    }

}
