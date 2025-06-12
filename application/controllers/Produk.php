<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Produk_model');

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
        $data['outlets'] = $this->Produk_model->get_outlet();
        $this->load->view('produk/produk', $data);
    }

    public function index_stok()
    {
        $data['outlet'] = $this->Produk_model->get_outlet();

        $this->load->view('produk/stok', $data);
    }

    public function index_toko()
    {
        $data['toko'] = $this->Produk_model->get_toko();
        $this->load->view('produk/toko', $data);
    }

    public function index_pembayaran_toko()
    {
        $data['toko'] = $this->Produk_model->get_toko();
        $this->load->view('produk/toko_pembayaran', $data);
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

    public function get_all_pemilik()
    {
        $daftar_pemilik = $this->Produk_model->get_pemilik();
        $daftar_input = array();
        $no = 1;

        foreach ($daftar_pemilik as $key) {

            // Format data untuk dimasukkan ke dalam DataTable
            $bahan_input = array(
                $no++,
                $key->nama_pemilik,
                $key->jenis_pemilik,
                '<div class="dropdown d-inline-block">
                <a class="dropdown-toggle arrow-none" id="dLabel" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dLabel' . $key->id_pemilik . '">
                <button class="dropdown-item btn-pemilik-edit" data-id="' . $key->id_pemilik .'">Edit</button>
                <button class="dropdown-item btn-delete-pemilik" data-id="' . $key->id_pemilik .'">Hapus</button>
                </div>
                </div>',
            );
            array_push($daftar_input, $bahan_input);
        };

        // Kirim data dalam bentuk JSON
        $response = array(
            'data' => $daftar_input
        );
        $this->set_output($response);
    }

    public function get_all_pembayaran_toko()
    {
        $tanggal_from = $this->input->get('mulai_tanggal');
        $tanggal_to = $this->input->get('sampai_tanggal');
        $toko = $this->input->get('toko');

        if (empty($tanggal_from) && empty($tanggal_to) && empty($toko)) {
            $daftar_pembayaran_toko = $this->Produk_model->get_pembayaran_toko();
        } else {
            $daftar_pembayaran_toko = $this->Produk_model->get_pembayaran_toko_filter($tanggal_from, $tanggal_to, $toko);
        }

        $daftar_input = array();
        $no = 1;

        foreach ($daftar_pembayaran_toko as $key) {

            // Format data untuk dimasukkan ke dalam DataTable
            $bahan_input = array(
                $no++,
                $key->tanggal_bayar,
                $key->nama_toko,
                'Rp ' . number_format($key->jumlah_uang, 0, ',', '.'),
                '<div class="dropdown d-inline-block">
                <a class="dropdown-toggle arrow-none" id="dLabel" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dLabel' . $key->id_pembayaran_toko . '">
                <button class="dropdown-item btn-pembayarantoko-detail" data-id="' . $key->id_pembayaran_toko .'">Detail</button>
                <button class="dropdown-item btn-pembayarantoko-edit" data-id="' . $key->id_pembayaran_toko .'">Edit</button>
                </div>
                </div>',
            );
            array_push($daftar_input, $bahan_input);
        };

        // Kirim data dalam bentuk JSON
        $response = array(
            'data' => $daftar_input
        );
        $this->set_output($response);
    }

    public function get_all_toko()
    {
        $daftar_toko = $this->Produk_model->get_toko();
        $daftar_input = array();
        $no = 1;

        foreach ($daftar_toko as $key) {
            $status_badge = $key->status == 'aktif'
            ? '<span class="badge bg-success-subtle text-success fs-11 fw-medium px-2">Aktif</span>'
            : '<span class="badge bg-danger-subtle text-danger fs-11 fw-medium px-2">Nonaktif</span>';

            // Cek status toko dan atur tombol Hapus
            $btn_delete = $key->status == 'aktif'
            ? '<button class="dropdown-item text-muted" disabled>Hapus</button>'
            : '<button class="dropdown-item btn-delete-toko" data-id="' . $key->id_toko . '">Hapus</button>';

            $dropdown = '
            <div class="dropdown d-inline-block">
            <a class="dropdown-toggle arrow-none" id="dLabel' . $key->id_toko . '" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="las la-ellipsis-v fs-20 text-muted"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dLabel' . $key->id_toko . '">
            <button class="dropdown-item btn-toko-edit" data-id="' . $key->id_toko . '">Edit</button>
            ' . $btn_delete . '
            </div>
            </div>';

            $bahan_input = array(
                $no++,
                $key->nama_toko,
                $status_badge,
                $dropdown,
            );

            array_push($daftar_input, $bahan_input);
        }

        $response = array(
            'data' => $daftar_input
        );
        $this->set_output($response);
    }

    public function get_all_outlet()
    {
        $daftar_outlet = $this->Produk_model->get_outlet();
        $daftar_input = array();
        $no = 1;

        foreach ($daftar_outlet as $key) {

            // Format data untuk dimasukkan ke dalam DataTable
            $bahan_input = array(
                $no++,
                $key->nama_outlet,
                '<div class="dropdown d-inline-block">
                <a class="dropdown-toggle arrow-none" id="dLabel" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dLabel' . $key->id_outlet . '">
                <button class="dropdown-item btn-outlet-edit" data-id="' . $key->id_outlet .'">Edit</button>
                <button class="dropdown-item btn-delete-outlet" data-id="' . $key->id_outlet .'">Hapus</button>
                </div>
                </div>',
            );
            array_push($daftar_input, $bahan_input);
        };

        // Kirim data dalam bentuk JSON
        $response = array(
            'data' => $daftar_input
        );
        $this->set_output($response);
    }

    public function get_all_produk()
    {
        $daftar_produk = $this->Produk_model->get_produk();
        $daftar_input = array();
        $no = 1;

        foreach ($daftar_produk as $key) {

            // Format data untuk dimasukkan ke dalam DataTable
            $bahan_input = array(
                $no++,
                $key->nama_pemilik,
                $key->nama_produk,
                'Rp ' . number_format($key->harga_default, 0, ',', '.'),
                '<div class="dropdown d-inline-block">
                <a class="dropdown-toggle arrow-none" id="dLabel" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dLabel' . $key->id_produk . '">
                <button class="dropdown-item btn-produk-edit" data-id="' . $key->id_produk .'">Edit</button>
                <button class="dropdown-item btn-delete-produk" data-id="' . $key->id_produk .'">Hapus</button>
                </div>
                </div>',
            );
            array_push($daftar_input, $bahan_input);
        };

        // Kirim data dalam bentuk JSON
        $response = array(
            'data' => $daftar_input
        );
        $this->set_output($response);
    }
    

    public function get_all_produk_by_outlet()
    {
        $id_outlet = $this->input->get('id_outlet');
        $daftar_produk = $this->Produk_model->get_produk_by_outlet($id_outlet);

        $daftar_input = array();
        $no = 1;

        foreach ($daftar_produk as $key) {
            $stok = ($key->stok == 0)
            ? '<span class="badge rounded text-danger bg-danger-subtle">0</span>'
            : '<span class="badge rounded text-success bg-success-subtle">' . $key->stok . '</span>';

            $bahan_input = array(
                $no++,
                $key->nama_produk,
                $key->nama_pemilik ?? '-', // tampilkan "-" jika null
                'Rp ' . number_format($key->harga_outlet, 0, ',', '.'),
                $stok,
                '<div class="dropdown d-inline-block">
                <a class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#">
                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                <button class="dropdown-item btn-produk-addstok" data-id="' . $key->id_produk_outlet . '">Tambah Stok</button>
                <button class="dropdown-item btn-delete-produk-outlet" data-id="' . $key->id_produk_outlet . '">Hapus</button>
                </div>
                </div>',
            );
            array_push($daftar_input, $bahan_input);
        }

        // Kirim data dalam bentuk JSON
        $response = array(
            'data' => $daftar_input
        );
        $this->set_output($response);
    }

    public function get_all_produk_by_toko()
    {
        $id_toko = $this->input->get('id_toko');
        $daftar_produk = $this->Produk_model->get_produk_by_toko($id_toko);

        $daftar_input = array();
        $no = 1;

        foreach ($daftar_produk as $key) {
            $bahan_input = array(
                $no++,
                $key->nama_produk,
                'Rp ' . number_format($key->harga_toko, 0, ',', '.'),
                $key->stok,
                $key->jumlah_terjual,
                '<div class="dropdown d-inline-block">
                <a class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#">
                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                <button class="dropdown-item btn-produktoko-edit" data-id="' . $key->id_produk_toko . '">Edit</button>
                <button class="dropdown-item btn-delete-produktoko" data-id="' . $key->id_produk_toko . '" data-id_toko="' . $id_toko . '">Hapus</button>
                </div>
                </div>',
            );
            array_push($daftar_input, $bahan_input);
        }

        // Kirim data dalam bentuk JSON
        $response = array(
            'data' => $daftar_input
        );
        $this->set_output($response);
    }

    public function get_all_logstok()
    {
        $tanggal_from = $this->input->get('mulai_tanggal');
        $tanggal_to = $this->input->get('sampai_tanggal');
        $outlet = $this->input->get('outlet');

        if (empty($tanggal_from) && empty($tanggal_to) && empty($outlet)) {
            $daftar_logstok = $this->Produk_model->get_logstok();
        } else {
            $daftar_logstok = $this->Produk_model->get_logstok_filter($tanggal_from, $tanggal_to, $outlet);
        }

        $daftar_input = array();
        $no = 1;

        foreach ($daftar_logstok as $key) {
            $bahan_input = array(
                $no++,
                $key->nama_produk,
                $key->outlet,
                $key->tanggal,
                $key->jumlah,
                $key->keterangan,
                '<button class="btn btn-delete-stok" data-id="' . $key->id_log_stok_outlet . '">
                <i class="fas fa-trash-alt text-danger"></i>
                </button>',
            );
            array_push($daftar_input, $bahan_input);
        }

        $response = array('data' => $daftar_input);
        $this->set_output($response);
    }

    public function get_all_logsetor($id_toko)
    {
        $daftar_log_setor_toko = $this->Produk_model->get_log_setor_toko_by_toko($id_toko);
        $daftar_input = array();
        $no = 1;

        foreach ($daftar_log_setor_toko as $key) {
            // Format status bayar dengan badge
            $status_bayar = ($key->status_bayar == 'lunas') 
            ? '<span class="badge bg-success-subtle text-success fs-11 fw-medium px-2">Lunas</span>'
            : '<span class="badge bg-danger-subtle text-danger fs-11 fw-medium px-2">Belum</span>';

            // Tambahkan atribut disabled jika status lunas
            $btn_bayar = ($key->status_bayar == 'lunas') 
            ? '<button class="dropdown-item btn-bayar-logsetor" data-id="' . $key->id_log_setor_toko . '" disabled>Bayar</button>'
            : '<button class="dropdown-item btn-bayar-logsetor" data-id="' . $key->id_log_setor_toko . '">Bayar</button>';

            $bahan_input = array(
                $no++,
                $key->tanggal_setor,
                $status_bayar,
                '<div class="dropdown d-inline-block">
                <a class="dropdown-toggle arrow-none" id="dLabel" data-bs-toggle="dropdown" href="#" role="button">
                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                <button class="dropdown-item btn-detail-logsetor" data-id="' . $key->id_log_setor_toko .'">Detail</button>'
                . $btn_bayar .
                '<button class="dropdown-item btn-delete-logsetor" data-id="' . $key->id_log_setor_toko .'" data-target=".btn-toko-bayar[data-id_toko=\'' . $id_toko . '\']">Hapus</button>
                </div>
                </div>',
            );

            $daftar_input[] = $bahan_input;
        }

        echo json_encode(['data' => $daftar_input]);
    }

    public function create()
    {
        $harga = $this->input->post('harga_produk');
        $harga = preg_replace("/[^0-9]/", "", $harga);

        $nama_produk = $this->input->post('nama_produk');

        $data = [
            'id_pemilik' => $this->input->post('pemilik'),
            'nama_produk' => $nama_produk,
            'harga_default' => $harga
        ];
        $this->Produk_model->insert($data);
        echo json_encode(['status' => 'success']);
    }

    public function update($id_produk)
    {
        $harga = $this->input->post('harga_produk');
        $harga = preg_replace("/[^0-9]/", "", $harga);

        $data = [
            'id_pemilik' => $this->input->post('pemilik'),
            'nama_produk' => $this->input->post('nama_produk'),
            'harga_default' => $harga
        ];

        if ($this->Produk_model->update($id_produk, $data))
        {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function delete()
    {
        $id_produk = $this->input->post('id_produk');

        $check_produk = $this->Produk_model->get_by_id($id_produk);

        if (!$check_produk) {
            $response = array(
                'status'    => 'gagal',
                'pesan'        => 'Tidak ditemukan',
            );
            $this->set_output($response);
        };

        $check_digunakanoutlet = $this->Produk_model->get_produk_by_idoutlet($id_produk);
        if($check_digunakanoutlet)
        {
            $response = array(
                'status'    => 'gagal',
                'pesan'     => 'Data produk ini masih digunakan, tidak bisa dihapus',
            );
            $this->set_output($response);
        }

        $check_digunakantoko = $this->Produk_model->get_produk_by_idtoko($id_produk);
        if($check_digunakantoko)
        {
            $response = array(
                'status'    => 'gagal',
                'pesan'     => 'Data produk ini masih digunakan, tidak bisa dihapus',
            );
            $this->set_output($response);
        }

        $this->Produk_model->delete($id_produk);

        $response = array(
            'status'    => 'sukses',
            'pesan'        => 'Berhasil dihapus',
        );
        $this->set_output($response);
    }

    public function delete_logsetor()
    {
        $id_log_setor_toko = $this->input->post('id_log_setor_toko');
        $this->Produk_model->delete_logsetor($id_log_setor_toko);

        $response = array(
            'status'    => 'sukses',
            'pesan'        => 'Berhasil dihapus',
        );
        $this->set_output($response);
    }

    public function create_pemilik()
    {
        $data = [
            'nama_pemilik' => $this->input->post('nama_pemilik'),
            'jenis_pemilik' => $this->input->post('jenis_pemilik'),
        ];
        $this->Produk_model->insert_pemilik($data);
        echo json_encode(['status' => 'success']);
    }

    public function create_toko()
    {
        $data = [
            'nama_toko' => $this->input->post('nama_toko'),
            'status' => $this->input->post('status_toko'),
        ];
        $this->Produk_model->insert_toko($data);
        echo json_encode(['status' => 'success']);
    }

    public function create_outlet()
    {
        $data = [
            'nama_outlet' => $this->input->post('nama_outlet'),
        ];
        $this->Produk_model->insert_outlet($data);
        echo json_encode(['status' => 'success']);
    }

    public function update_pemilik($id_pemilik)
    {
        $data = [
            'nama_pemilik' => $this->input->post('nama_pemilik'),
            'jenis_pemilik' => $this->input->post('jenis_pemilik'),
        ];

        if ($this->Produk_model->update_pemilik($id_pemilik, $data))
        {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function update_toko()
    {
        $id_toko = $this->input->post('id_toko');

        $data = [
            'nama_toko' => $this->input->post('nama_toko'),
            'status' => $this->input->post('status_toko'),
        ];

        if ($this->Produk_model->update_toko($id_toko, $data))
        {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function update_outlet()
    {
        $id_outlet = $this->input->post('id_outlet');
        $data = [
            'nama_outlet' => $this->input->post('nama_outlet'),
        ];

        if ($this->Produk_model->update_outlet($id_outlet, $data))
        {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function delete_pemilik()
    {
        $id_pemilik = $this->input->post('id_pemilik');

        $check_pemilik = $this->Produk_model->get_pemilik_by_id($id_pemilik);
        if(!$check_pemilik)
        {
            $response = array(
                'status'    => 'gagal',
                'pesan'     => 'Tidak ditemukan',
            );
            $this->set_output($response);
        }

        $check_digunakanproduk = $this->Produk_model->get_pemilik_by_produk($id_pemilik);
        if($check_digunakanproduk)
        {
            $response = array(
                'status'    => 'gagal',
                'pesan'     => 'Data pemilik ini masih digunakan, tidak bisa dihapus',
            );
            $this->set_output($response);
        }

        $this->Produk_model->delete_pemilik($id_pemilik);

        $response = array(
            'status'    => 'sukses',
            'pesan'        => 'Berhasil dihapus',
        );
        $this->set_output($response);
    }

    public function delete_toko()
    {
        $id_toko = $this->input->post('id_toko');

        $check_toko = $this->Produk_model->get_toko_by_id($id_toko);
        if(!$check_toko)
        {
            $response = array(
                'status'    => 'gagal',
                'pesan'     => 'Tidak ditemukan',
            );
            $this->set_output($response);
        }

        $this->Produk_model->delete_toko($id_toko);

        $response = array(
            'status'    => 'sukses',
            'pesan'        => 'Berhasil dihapus',
        );
        $this->set_output($response);
    }

    public function delete_outlet()
    {
        $id_outlet = $this->input->post('id_outlet');

        $check_outlet = $this->Produk_model->get_outlet_by_id($id_outlet);
        if(!$check_outlet)
        {
            $response = array(
                'status'    => 'gagal',
                'pesan'     => 'Tidak ditemukan',
            );
            $this->set_output($response);
        }

        $this->Produk_model->delete_outlet($id_outlet);

        $response = array(
            'status'    => 'sukses',
            'pesan'        => 'Berhasil dihapus',
        );
        $this->set_output($response);
    }

    public function delete_produkoutlet()
    {
        $id_produk = $this->input->post('id_produk');
        $hapus = $this->Produk_model->delete_produkoutlet($id_produk);

        if ($hapus) {
            $response = array(
                'status' => 'sukses',
                'pesan'  => 'Produk berhasil dihapus',
            );
        } else {
            $response = array(
                'status' => 'gagal',
                'pesan'  => 'Produk gagal dihapus',
            );
        }

        $this->set_output($response);
    }

    public function delete_produktoko()
    {
        $id_produk = $this->input->post('id_produk_toko');
        $hapus = $this->Produk_model->delete_produktoko($id_produk);

        if ($hapus) {
            $response = array(
                'status' => 'sukses',
                'pesan'  => 'Produk berhasil dihapus',
            );
        } else {
            $response = array(
                'status' => 'gagal',
                'pesan'  => 'Produk gagal dihapus',
            );
        }

        $this->set_output($response);
    }

    public function add_stok()
    {
        $jumlah = (int)$this->input->post('stok');
        $id_produk_outlet = (int)$this->input->post('id_produk_outlet');
        $tanggal = $this->input->post('tanggal');
        $keterangan = $this->input->post('keterangan');

        if (!$id_produk_outlet || !$jumlah || !$tanggal) {
            echo json_encode(['status' => 'error', 'pesan' => 'Data tidak lengkap']);
            return;
        }

                // Tambah stok ke tabel produk
        $this->Produk_model->add_stok($id_produk_outlet, $jumlah);

                // Catat log stok
        $log_data = [
            'id_produk_outlet' => $id_produk_outlet,
            'tanggal' => $tanggal . ' ' . date('H:i:s'),
            'jumlah' => $jumlah,
            'keterangan' => $keterangan
        ];
        $this->Produk_model->insert_log_stok($log_data);

        echo json_encode(['status' => 'success']);
    }

    public function delete_stok()
    {
        $id_logstok = $this->input->post('id_logstok');

        $log_stok_outlet = $this->Produk_model->get_logstok_by_id($id_logstok);

        if ($log_stok_outlet) {
            $this->Produk_model->kembalikan_stok_produk_outlet($log_stok_outlet->id_produk_outlet, $log_stok_outlet->jumlah);

            $this->Produk_model->delete_logstok($id_logstok);

            $response = array(
                'status' => 'sukses',
                'pesan'  => 'Berhasil dihapus & stok dikembalikan',
            );
        } else {
            $response = array(
                'status' => 'gagal',
                'pesan'  => 'Data log stok tidak ditemukan',
            );
        }

        $this->set_output($response);
    }

    public function modal_add_produk()
    {
        $data['pemilik'] = $this->Produk_model->get_pemilik();
        $this->load->view('produk/produk_add_modal', $data);
    }

    public function modal_add_produkoutlet()
    {
        $id_outlet = $this->input->get('id_outlet');
        
        $data['produk'] = $this->Produk_model->get_produk_yang_belum_terdaftar($id_outlet);
        $this->load->view('produk/produkoutlet_add_modal', $data);
    }

    public function modal_add_produktoko()
    {
        $id_toko = $this->input->get('id_toko');
        $data['id_toko'] = $id_toko;
        $data['produk'] = $this->Produk_model->get_produk();
        $this->load->view('produk/produktoko_add_modal', $data);
    }

    public function modal_edit_produktoko()
    {
        $id_produk_toko = $this->input->post('id_produk_toko');
        $data['id_produk_toko'] = $id_produk_toko;
        $data['all_produk'] = $this->Produk_model->get_produk();
        $data['produk'] = $this->Produk_model->get_produk_by_idproduktoko($id_produk_toko);
        $this->load->view('produk/produktoko_edit_modal', $data);
    }

    public function modal_add_produksetor()
    {
        $id_toko = $this->input->post('id_toko');
        $data['id_toko'] = $id_toko;
        $data['produk'] = $this->Produk_model->get_produk_by_toko($id_toko);
        $this->load->view('produk/produksetor_add_modal', $data);
    }

    public function modal_add_produksetorsave()
    {
        $id_toko = $this->input->post('id_toko');
        $produk = $this->input->post('produk'); // array of id_produk
        $jumlah = $this->input->post('jumlah'); // array of jumlah_produk

        // 1. Simpan ke log_setor_toko
        $data_setor = [
            'id_toko' => $id_toko,
            'tanggal_setor' => date('Y-m-d H:i:s'),
            'status_bayar' => 'belum' // default status
        ];
        $id_log_setor = $this->Produk_model->insert_log_setor($data_setor); // return inserted ID

        foreach ($produk as $i => $id_produk) {
            $jumlah_produk = (int) $jumlah[$i];

            // 1. Ambil stok awal dari produk_toko
            $stok_awal = $this->Produk_model->get_stok_produk_toko($id_toko, $id_produk);

            // 2. Hitung sisa stok
            $sisa_stok = $stok_awal + $jumlah_produk;

            // 3. Simpan ke detail_setor_toko
            $this->Produk_model->insert_detail_setor([
                'id_log_setor_toko' => $id_log_setor,
                'id_produk' => $id_produk,
                'jumlah_produk' => $jumlah_produk
            ]);

            // 4. Simpan ke log_stok_toko
            $this->Produk_model->insert_log_stok_toko([
                'id_produk' => $id_produk,
                'id_toko' => $id_toko,
                'tanggal' => date('Y-m-d H:i:s'),
                'jenis_perubahan' => 'setor',
                'jumlah' => $jumlah_produk,
                'keterangan' => null,
                'sisa_stok' => $sisa_stok
            ]);

            // 5. Update stok di produk_toko
            $this->Produk_model->update_stok_produk_toko($id_toko, $id_produk, $jumlah_produk);
        }

        echo json_encode(['status' => 'success']);
    }

    public function modal_bayar_toko()
    {
        $id_toko = $this->input->post('id_toko');
        $data['id_toko'] = $id_toko;
        $data['toko'] = $this->Produk_model->get_toko_by_id($id_toko);
        $this->load->view('produk/toko_bayar_modal', $data);
    }

    public function modal_detail_pembayaran_toko($id_pembayaran_toko)
    {
        if ($id_pembayaran_toko == "") {
            redirect('produk/index_pembayaran_toko');
        }

        $pembayaran = $this->Produk_model->get_pembayarantoko_by_id($id_pembayaran_toko);
        $detail_pembayaran = $this->Produk_model->get_pembayarantokodetail_by_id($id_pembayaran_toko);
        $log_setor = $this->Produk_model->get_logsetor_by_id($pembayaran->id_log_setor_toko);
        $toko = $this->Produk_model->get_toko_by_id($log_setor->id_toko);

        $data = [
            'pembayaran_toko' => $pembayaran,
            'detail_pembayaran_toko' => $detail_pembayaran,
            'log_setor_toko' => $log_setor,
            'toko' => $toko
        ];

        $this->load->view('produk/toko_pembayaran_detail_modal', $data);
    }

    public function modal_edit_pembayaran_toko($id_pembayaran_toko)
    {
        if ($id_pembayaran_toko == "") {
            redirect('produk/index_pembayaran_toko');
        }

        $data['pembayaran'] = $this->Produk_model->get_pembayarantoko_by_id($id_pembayaran_toko);
        $id_log_setor_toko = $data['pembayaran']->id_log_setor_toko;

        $id_toko = $this->Produk_model->get_id_toko_by_id_log_setor($id_log_setor_toko);

        $detail = $this->Produk_model->get_pembayarantokodetail_by_id($id_pembayaran_toko);
        foreach ($detail as &$d) {
            $d->harga_toko = $this->Produk_model->get_harga_toko_by_id_produk_toko($d->id_produk, $id_toko);
        }
        $data['detail'] = $detail;
        $this->load->view('produk/toko_pembayaran_edit_modal', $data);
    }

    public function modal_edit_pembayaran_tokosave()
    {
        $id_pembayaran_toko = $this->input->post('id_pembayaran_toko');
        $tanggal_bayar = $this->input->post('tanggal_bayar');
        $nominal = str_replace(['Rp ', '.'], '', $this->input->post('nominal'));

        $id_produk_list = $this->input->post('id_produk');
        $produk_terjual = $this->input->post('produk_terjual');
        $produk_return = $this->input->post('produk_return');

        // Update data pembayaran
        $data_update = [
            'tanggal_bayar' => $tanggal_bayar,
            'jumlah_uang'   => $nominal,
            'keterangan'    => null
        ];

        $this->Produk_model->update_pembayaran_toko($id_pembayaran_toko, $data_update);

        // Hapus detail lama
        $this->Produk_model->delete_detail_pembayaran($id_pembayaran_toko);

        // Tambahkan ulang detail baru
        foreach ($id_produk_list as $key => $id_produk) {
            $terjual = $produk_terjual[$key];
            $retur = $produk_return[$key];

            $this->Produk_model->insert_detail_pembayaran([
                'id_pembayaran_toko' => $id_pembayaran_toko,
                'id_produk'          => $id_produk,
                'jumlah_terjual'     => $terjual,
                'jumlah_return'      => $retur,
            ]);
        }

        echo json_encode(['status' => 'success']);
    }

    public function modal_bayarform_toko()
    {
        $id_log_setor_toko = $this->input->post('id_log_setor_toko');
        $data['id_log_setor_toko'] = $id_log_setor_toko;
        $data['detail'] = $this->Produk_model->get_detailsetor_by_id($id_log_setor_toko);

        $this->load->view('produk/toko_bayarform_modal', $data);
    }

    public function modal_bayarform_tokosave()
    {
        $tanggal_bayar = $this->input->post('tanggal_bayar');
        $nominal = str_replace(['Rp ', '.'], '', $this->input->post('nominal'));
        $id_produk_list = $this->input->post('id_produk');
        $produk_terjual = $this->input->post('produk_terjual');
        $produk_return = $this->input->post('produk_return');
        $id_log_setor_toko = $this->input->post('id_log_setor_toko');

        $data_pembayaran = [
            'id_log_setor_toko' => $id_log_setor_toko,
            'tanggal_bayar'     => $tanggal_bayar,
            'jumlah_uang'       => $nominal,
            'keterangan'        => null
        ];

        $id_pembayaran = $this->Produk_model->insert_pembayaran_toko($data_pembayaran);
        $this->db->update('log_setor_toko', ['status_bayar' => 'lunas'], ['id_log_setor_toko' => $id_log_setor_toko]);

        foreach ($id_produk_list as $key => $id_produk) {
            $terjual = $produk_terjual[$key];
            $retur = $produk_return[$key];

            $this->Produk_model->insert_detail_pembayaran([
                'id_pembayaran_toko' => $id_pembayaran,
                'id_produk'          => $id_produk,
                'jumlah_terjual'     => $terjual,
                'jumlah_return'      => $retur,
            ]);

            $produk = $this->Produk_model->get_produk_by_idtoko($id_produk);
            $stok_awal = $produk->stok;
            $id_toko = $produk->id_toko;
            $sisa_stok = $stok_awal - $terjual - $retur;

            $this->Produk_model->update_stok_produk_tokopembayaran($id_produk, $sisa_stok);

            $this->Produk_model->insert_log_stok_toko([
                'id_produk'        => $id_produk,
                'id_toko'          => $id_toko,
                'tanggal'          => $tanggal_bayar,
                'jenis_perubahan'  => 'terjual',
                'jumlah'           => $terjual,
                'keterangan'       => null,
                'sisa_stok'        => $sisa_stok
            ]);

            $this->Produk_model->insert_log_stok_toko([
                'id_produk'        => $id_produk,
                'id_toko'          => $id_toko,
                'tanggal'          => $tanggal_bayar,
                'jenis_perubahan'  => 'return',
                'jumlah'           => $retur,
                'keterangan'       => null,
                'sisa_stok'        => $sisa_stok
            ]);
        }

        echo json_encode(['status' => 'success']);
    }

    public function modal_detailbayar_toko()
    {
        $id_log_setor_toko = $this->input->post('id_log_setor_toko');
        $data['id_log_setor_toko'] = $id_log_setor_toko;
        $data['detail'] = $this->Produk_model->get_detailsetor_by_id($id_log_setor_toko);
        $data['logsetor'] = $this->Produk_model->get_logsetor_by_id($id_log_setor_toko);
        $this->load->view('produk/toko_detail_bayar_modal', $data);
    }

    public function modal_add_produkoutletsave()
    {
        $harga = $this->input->post('harga_produk');
        $harga = preg_replace("/[^0-9]/", "", $harga);

        $id_produk = $this->input->post('id_produk');

        $data = [
            'id_outlet' => $this->input->post('id_outlet'),
            'id_produk' => $id_produk,
            'harga_outlet' => $harga
        ];
        $this->Produk_model->insert_produkoutlet($data);
        echo json_encode(['status' => 'success']);
    }

    public function modal_add_produktokosave()
    {
        $harga = $this->input->post('harga_produk');
        $harga = preg_replace("/[^0-9]/", "", $harga);

        $id_produk = $this->input->post('id_produk');

        $data = [
            'id_toko' => $this->input->post('id_toko'),
            'id_produk' => $id_produk,
            'harga_toko' => $harga
        ];
        $this->Produk_model->insert_produktoko($data);
        echo json_encode(['status' => 'success']);
    }

    public function modal_edit_produktokosave()
    {
        $harga = $this->input->post('harga_produk');
        $harga = preg_replace("/[^0-9]/", "", $harga);

        $id_produk = $this->input->post('id_produk');
        $id_produk_toko = $this->input->post('id_produk_toko');

        $data = [
            'id_toko' => $this->input->post('id_toko'),
            'id_produk' => $id_produk,
            'harga_toko' => $harga
        ];
        $this->Produk_model->update_produktoko($id_produk_toko, $data);
        echo json_encode(['status' => 'success']);
    }

    public function modal_edit($id_produk)
    {
        if ($id_produk == "") {
            redirect('produk');
        }

        $data['produk'] = $this->Produk_model->get_by_id($id_produk);
        $data['pemilik'] = $this->Produk_model->get_pemilik();

        $this->load->view('produk/produk_edit_modal', $data);
    }

    public function modal_pemilik()
    {
        $data['pemilik'] = $this->Produk_model->get_pemilik();
        $this->load->view('produk/pemilik_modal', $data);
    }

    public function modal_toko()
    {
        $data['toko'] = $this->Produk_model->get_toko();
        $this->load->view('produk/toko_modal', $data);
    }

    public function modal_outlet()
    {
        $data['outlet'] = $this->Produk_model->get_outlet();
        $this->load->view('produk/outlet_modal', $data);
    }

    public function modal_produk()
    {
        $data['produk'] = $this->Produk_model->get_produk();
        $this->load->view('produk/produk_modal', $data);
    }

    public function modal_add_pemilik()
    {
        $this->load->view('produk/pemilik_add_modal');
    }

    public function modal_add_outlet()
    {
        $this->load->view('produk/outlet_add_modal');
    }

    public function modal_add_toko()
    {
        $this->load->view('produk/toko_add_modal');
    }

    public function modal_edit_pemilik($id_pemilik)
    {        
        if ($id_pemilik == "") {
            redirect('produk');
        }

        $data['pemilik'] = $this->Produk_model->get_pemilik_by_id($id_pemilik);

        $this->load->view('produk/pemilik_edit_modal', $data);
    }

    public function modal_edit_toko($id_toko)
    {        
        if ($id_toko == "") {
            redirect('produk/index_toko');
        }

        $data['toko'] = $this->Produk_model->get_toko_by_id($id_toko);

        $this->load->view('produk/toko_edit_modal', $data);
    }

    public function modal_edit_outlet($id_outlet)
    {        
        if ($id_outlet == "") {
            redirect('produk');
        }

        $data['outlet'] = $this->Produk_model->get_outlet_by_id($id_outlet);

        $this->load->view('produk/outlet_edit_modal', $data);
    }

    public function modal_add_stok($id_produk_outlet)
    {
        if ($id_produk_outlet == "") {
            redirect('produk');
        }

        $data['produk_outlet'] = $this->Produk_model->get_produk_outlet_by_id($id_produk_outlet);

        $this->load->view('produk/stok_add_modal', $data);
    }


}
