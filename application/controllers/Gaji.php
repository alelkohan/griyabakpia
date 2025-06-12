<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gaji extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Gaji_model');
        $this->load->model('Karyawan_model');
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
        // $data['gaji_karyawan'] = $this->gaji_model->get_gaji_karyawan();
        $this->load->view('gaji/gaji_karyawan');
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

    public function get_all_gaji()
    {
        $daftar_gaji = $this->Gaji_model->get_gaji();
        $daftar_input = array();
        $no = 1;

        foreach ($daftar_gaji as $key) {
            // if ($key->jenis_aktivitas == 'pemakaian') {
            //     $harga_satuan = '-';
            //     $harga_total = '-';
            // } else {
            //     $harga_satuan = "Rp " . number_format($key->harga_satuan, 0, ',', '.');
            //     $harga_total = "Rp " . number_format($key->harga_total, 0, ',', '.');
            // }

            // $jenis_aktivitas_badge = ($key->jenis_aktivitas == "pembelian")
            // ? '<span class="badge bg-danger">' . $key->jenis_aktivitas .'</span>'
            // : '<span class="badge bg-success">' . $key->jenis_aktivitas . '</span>';

            $bahan_input = array(
                $no++,
                '<img src="' . base_url('upload/karyawan/' . $key->foto_karyawan) . '" alt="foto ' . $key->nama_karyawan . '" class="rounded-circle thumb-md me-1 d-inline">',
                $key->nama_karyawan,
                $key->peran_karyawan,
                $key->tanggal_pembayaran,
                "Rp " . number_format($key->jumlah_gaji, 0, ',', '.'),
                $key->keterangan,
                '<button class="btn btn-gaji-delete" data-id="' . $key->id_transaksi_gaji .'"><i class="fas fa-trash-alt text-danger"></i></button>',
            );
            array_push($daftar_input, $bahan_input);
        };

        // Kirim data dalam bentuk JSON
        $response = array(
            'data' => $daftar_input
        );
        $this->set_output($response);
    }

    public function create_gaji()
    {
        $id_karyawan = $this->input->post('id_karyawan');
        $tanggal     = $this->input->post('tanggal');
        $jumlah_gaji = $this->input->post('jumlah_gaji');
        $keterangan  = $this->input->post('keterangan');
        $saldo = $this->Keuangan_model->get_saldo();

        $jumlah_gaji = str_replace(['Rp', '.', ' '], '', $jumlah_gaji);

        if ($saldo < $jumlah_gaji) {
            $this->set_output(['status' => 'error', 'message' => 'Saldo tidak cukup untuk gaji']);
        } else {
            $this->Keuangan_model->kurangi_saldo($jumlah_gaji, $keterangan, 'Gaji Karyawan');

            $data = [
                'id_karyawan'        => $id_karyawan,
                'tanggal_pembayaran' => $tanggal,
                'jumlah_gaji'        => $jumlah_gaji,
                'keterangan'         => $keterangan,
            ];

            $this->Gaji_model->insert_gaji($data);

            $this->set_output(['status' => 'success']);
        }
    }

    public function delete_gaji()
    {
        $id_gaji = $this->input->post('id_gaji');

        $check_gaji = $this->Gaji_model->get_gaji_by_id($id_gaji);

        if (!$check_gaji) {
            $response = array(
                'status'    => 'gagal',
                'pesan'        => 'Tidak ditemukan',
            );
            $this->set_output($response);
        };

        $this->Keuangan_model->tambah_saldo($check_gaji->jumlah_gaji, 'Koreksi Gaji Karyawan', 'Gaji Karyawan');

        $this->Gaji_model->delete_gaji($id_gaji);

        $response = array(
            'status'    => 'sukses',
            'pesan'        => 'Berhasil dihapus',
        );
        $this->set_output($response);
    }

    public function modal_add_gaji()
    {
        $data['karyawan'] = $this->Gaji_model->get_karyawan_dengan_status_gaji();
        $this->load->view('gaji/gaji_add_modal', $data);
    }

    public function modal_detail_absensi($id_karyawan)
    {
        $data['karyawan'] = $this->Karyawan_model->get_karyawan_by_id($id_karyawan);
        $data['absensi'] = $this->Gaji_model->get_absensi_bulan_ini($id_karyawan);
        $data['rekap_status'] = $this->Gaji_model->get_rekap_status_absensi_bulan_ini($id_karyawan);

        $this->load->view('gaji/detail_absensi', $data);
    }

}