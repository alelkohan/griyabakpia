<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bahan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Bahan_model');
        $this->load->model('Keuangan_model');
    }

    public function index()
    {
        $data['bahan'] = $this->Bahan_model->get_bahan();
        $this->load->view('bahan/bahan', $data);
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

    public function get_bahan()
    {
        $daftar_bahan = $this->Bahan_model->get_bahan();
        $daftar_input = array();
        $no = 1;

        foreach ($daftar_bahan as $key) {
            $stok = ($key->stok == 0)
            ? '<span class="badge rounded text-danger bg-danger-subtle">0</span>'
            : '<span class="badge rounded text-success bg-success-subtle">' . $key->stok . '</span>';

            // Format data untuk dimasukkan ke dalam DataTable
            $bahan_input = array(
                $no++,
                $key->nama_bahan,
                $key->satuan,
                $stok,
                '<div class="dropdown d-inline-block">
                <a class="dropdown-toggle arrow-none" id="dLabel' . $key->id_bahan . '" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                </a>
                <div class="text-end">
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dLabel' . $key->id_bahan . '">
                <button class="dropdown-item btn-bahan-edit" data-id="' . $key->id_bahan .'">Edit</button>
                <button class="dropdown-item btn-delete-bahan" data-id="' . $key->id_bahan .'">Hapus</button>
                </div>
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

    public function get_all_logbahan()
    {
        $daftar_logbahan = $this->Bahan_model->get_logbahan();
        $daftar_input = array();
        $no = 1;

        foreach ($daftar_logbahan as $key) {
            if ($key->jenis_aktivitas == 'pemakaian') {
                $harga_satuan = '-';
                $harga_total = '-';
            } else {
                $harga_satuan = "Rp " . number_format($key->harga_satuan, 0, ',', '.');
                $harga_total = "Rp " . number_format($key->harga_total, 0, ',', '.');
            }

            $jenis_aktivitas_badge = ($key->jenis_aktivitas == "pembelian")
            ? '<span class="badge rounded text-danger bg-danger-subtle">' . $key->jenis_aktivitas .'</span>'
            : '<span class="badge rounded text-success bg-success-subtle">' . $key->jenis_aktivitas . '</span>';

            $bahan_input = array(
                $no++,
                $key->nama_bahan,
                $key->tanggal,
                $jenis_aktivitas_badge, // << ini udah badge
                $key->jumlah . ' ' . $key->satuan,
                $harga_satuan,
                $harga_total,
                $key->keterangan,
                '<button class="btn btn-logbahan-edit" data-id="' . $key->id_log_bahan .'"><i class="fas fa-pencil-alt text-warning"></i></button>',
            );
            array_push($daftar_input, $bahan_input);
        };

        // Kirim data dalam bentuk JSON
        $response = array(
            'data' => $daftar_input
        );
        $this->set_output($response);
    }

    public function create_logbahan()
    {
        $harga_satuan = $this->input->post('harga_satuan');
        $harga_total = $this->input->post('harga_total');
        $jumlah = (int) $this->input->post('jumlah');
        $id_bahan = $this->input->post('id_bahan');
        $jenis_aktivitas = $this->input->post('jenis_aktivitas');
        $stok = $this->Bahan_model->get_stok($id_bahan);

        $harga_satuan = str_replace(['Rp', '.', ' '], '', $harga_satuan);
        $harga_total = str_replace(['Rp', '.', ' '], '', $harga_total);

        $harga_satuan = $harga_satuan ? intval($harga_satuan) : 0;
        $harga_total = $harga_total ? intval($harga_total) : 0;

        if ($jenis_aktivitas == 'pembelian') {

            $saldo = $this->Keuangan_model->get_saldo();

            if ($saldo < $harga_total) {
                $this->set_output(['status' => 'error', 'message' => 'Saldo tidak cukup untuk pembelian']);
                return;
            }

            $this->Keuangan_model->kurangi_saldo($harga_total, 'Beli bahan: ' . $this->Bahan_model->get_nama_bahan($id_bahan), 'Bahan');

            $this->Bahan_model->add_stok($id_bahan, $jumlah);

        } else { 
            if ($stok < $jumlah) {
                $this->set_output(['status' => 'error', 'message' => 'Stok tidak cukup untuk pemakaian']);
                return;
            }
            $this->Bahan_model->kurangi_stok($id_bahan, $jumlah);
        }

        $data = [
            'id_bahan' => $id_bahan,
            'tanggal' => $this->input->post('tanggal')  . ' ' . date('H:i:s'),
            'jenis_aktivitas' => $jenis_aktivitas,
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan,
            'harga_total' => $harga_total,
            'keterangan' => $this->input->post('keterangan'),
            'updated_by' => null, // belum ada session user
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->Bahan_model->insert_logbahan($data);
        echo json_encode(['status' => 'success']);
    }

    public function update_logbahan()
    {
        $id_log_bahan = $this->input->post('id_log_bahan');

        $log_lama = $this->Bahan_model->get_logbahan_by_id($id_log_bahan);

        if (!$log_lama) {
            $this->set_output(['status' => 'error', 'message' => 'Log tidak ditemukan']);
            return;
        }

        $bahan = $this->Bahan_model->get_bahan_by_id($log_lama->id_bahan);

        if (!$bahan) {
            $this->set_output(['status' => 'error','message' => 'Bahan tidak ditemukan']);
            return;
        }

        $jumlah_baru = (int) $this->input->post('jumlah');
        $harga_total_baru = intval(str_replace(['Rp', '.', ' '], '', $this->input->post('harga_total')));
        $harga_satuan_baru = intval(str_replace(['Rp', '.', ' '], '', $this->input->post('harga_satuan')));

        $selisih_jumlah = $jumlah_baru - $log_lama->jumlah;
        $selisih_harga = $harga_total_baru - $log_lama->harga_total;

        // update stok (karena jenis aktivitas tidak bisa diubah)
        if ($log_lama->jenis_aktivitas == 'pembelian') {
            $stok_terbaru = $bahan->stok + $selisih_jumlah;
        } else { // pemakaian
            $stok_terbaru = $bahan->stok - $selisih_jumlah;

            // cek kalau pemakaian melebihi stok
            if ($stok_terbaru < 0) {
                $this->set_output(['status' => 'error', 'message' => 'Stok tidak cukup untuk pemakaian']);
                return;
            }
        }

        // update stok di DB
        $this->Bahan_model->update_stok_bahan($log_lama->id_bahan, $stok_terbaru);

        // Update logbahan
        $data = [
            'tanggal' => $this->input->post('tanggal'),
            'jenis_aktivitas' => $this->input->post('jenis_aktivitas'),
            'jumlah' => $jumlah_baru,
            'harga_satuan' => intval(str_replace(['Rp', '.', ' '], '', $this->input->post('harga_satuan'))),
            'harga_total' => intval(str_replace(['Rp', '.', ' '], '', $this->input->post('harga_total'))),
            'keterangan' => $this->input->post('keterangan'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->Bahan_model->update_logbahan($id_log_bahan, $data);

        if ($log_lama->jenis_aktivitas == 'pembelian' && $selisih_harga !== 0) {
            if ($selisih_harga > 0) {
                $this->Keuangan_model->kurangi_saldo($selisih_harga, 'Koreksi pembelian bahan: ' . $this->Bahan_model->get_nama_bahan($log_lama->id_bahan), 'Bahan');
            } else {
                $this->Keuangan_model->tambah_saldo(abs($selisih_harga), 'Pengembalian koreksi pembelian bahan', 'Bahan');
            }
        }

        echo json_encode(['status' => 'success']);
    }

    public function create_bahan()
    {
        $data = [
            'nama_bahan' => $this->input->post('nama_bahan'),
            'satuan' => $this->input->post('satuan'),
        ];
        $this->Bahan_model->insert_bahan($data);
        echo json_encode(['status' => 'success']);
    }

    public function update_bahan()
    {
        $id_bahan = $this->input->post('id_bahan');
        
        $data = [
            'nama_bahan' => $this->input->post('nama_bahan'),
            'satuan' => $this->input->post('satuan'),
        ];

        if ($this->Bahan_model->update_bahan($id_bahan, $data))
        {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function delete_bahan()
    {
        $id_bahan = $this->input->post('id_bahan');

        $this->Bahan_model->delete_bahan($id_bahan);

        $response = array(
            'status'    => 'sukses',
            'pesan'        => 'Berhasil dihapus',
        );
        $this->set_output($response);
    }

    public function modal_add_logbahan()
    {
        $data['bahan'] = $this->Bahan_model->get_bahan();
        $this->load->view('bahan/logbahan_add_modal', $data);
    }

    public function modal_edit_logbahan($id_logbahan)
    {
        $data['log'] = $this->Bahan_model->get_logbahan_by_id($id_logbahan);
        $data['bahan'] = $this->Bahan_model->get_bahan();
        
        $this->load->view('bahan/logbahan_edit_modal', $data);
    }

    public function modal_bahan()
    {
        $data['bahan'] = $this->Bahan_model->get_bahan();
        $this->load->view('bahan/bahan_modal', $data);
    }

    public function modal_add_bahan()
    {
        $data['bahan'] = $this->Bahan_model->get_bahan();
        $this->load->view('bahan/bahan_add_modal', $data);
    }

    public function modal_edit_bahan($id_bahan)
    {
        $data['bahan'] = $this->Bahan_model->get_bahan_by_id($id_bahan);
        $this->load->view('bahan/bahan_edit_modal', $data);
    }

}