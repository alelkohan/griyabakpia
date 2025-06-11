<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kasir extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('kasir_model');
        $this->load->model('keuangan_model');

        if (!$this->session->userdata('kasir')) {
            redirect('auth');
        }
    }

    private function set_output($data)
    {
        $this->output
        ->set_status_header(200)
        ->set_content_type('application/json','utf-8')
        ->set_output(json_encode($data,JSON_PRETTY_PRINT))
        ->_display();
        exit;
    }

    //URUSAN KASIR
    //==========================================================================================================

    public function index()
    {
        $kasir = $this->session->userdata('kasir');
        $data['produk'] = $this->kasir_model->get_produk_by_outlet($kasir->id_outlet);
        // echo "<pre>";
        // var_dump($data['produk']);
        // echo "</pre>";
        // die();
        $this->load->view('kasir/kasir',$data);
    }

    public function cari_produk()
    {
        $keyword = $this->input->get('keyword');
        $kasir   = $this->session->userdata('kasir');
        $produk  = $this->kasir_model->search_produk_by_nama_produk($keyword, $kasir->id_outlet); // buat query sesuai model kamu

        echo `<div class="row">`;
        foreach ($produk as $item) {
            echo `<div class="col-lg-3 col-md-4 col-6">`;
            echo '<div class="card pilih-produk" 
            data-id="'.$item->id_produk.'" 
            data-nama="'.$item->nama_produk.'" 
            data-harga="'.$item->harga_outlet.'" 
            data-harga-default="'.$item->harga_default.'" 
            data-stok="'.$item->stok.'"
            data-jenis-pemilik="'.$item->jenis_pemilik.'
            data-id-pemilik="'.$item->id_pemilik.'">
            <div class="card-body">
            <h5>'.$item->nama_produk.'</h5>
            <p>Rp '.number_format($item->harga_outlet, 0, ',', '.').'</p>
            </div>
            </div>';
            echo `</div>`;
        }
        echo `</div>`;
    }

    public function simpan_transaksi()
    {
        // Ambil input
        $post = $this->input->post(NULL, TRUE);

        // Validasi awal: Pastikan ada produk yang dibeli
        if (empty($post['id_produk']) || !is_array($post['id_produk'])) {
            show_error('Tidak ada data produk yang valid untuk diproses.');
        }

        $this->db->trans_start();

        // Data kasir & outlet
        $id_transaksi   = 'TR' . date('ymdhis');
        $kasir          = $this->session->userdata('kasir');
        $outlet         = $this->kasir_model->get_outlet_by_id_outlet($kasir->id_outlet);

        // Simpan transaksi utama
        $data_transaksi = [
            'id_transaksi_kasir' => $id_transaksi,
            'id_kasir'           => $kasir->id_karyawan,
            'total_harga'        => $post['total'],
            'total_diskon'       => $post['total_diskon'],
            'diskon'             => $post['diskon'],
            'jumlah_bayar'       => $post['bayar'],
            'jumlah_kembalian'   => $post['kembalian'],
            'waktu_transaksi'    => date('Y-m-d H:i:s'),
            'catatan'            => $post['catatan'],
            'id_outlet'          => $kasir->id_outlet,
            'metode_bayar'       => $post['metode_bayar']
        ];
        $this->kasir_model->insert_transaksi_kasir($data_transaksi);

        // Simpan detail transaksi
        $count = count($post['id_produk']);
        for ($i = 0; $i < $count; $i++) {
            $data_detail_transaksi = [
                'id_detail_transaksi' => 'DTR' . uniqid(),
                'id_transaksi'        => $id_transaksi,
                'id_produk'           => $post['id_produk'][$i],
                'nama_produk'         => $post['nama_produk'][$i],
                'harga_satuan'        => $post['harga_satuan'][$i],
                'qty'                 => $post['qty'][$i],
                'subtotal'            => $post['subtotal'][$i],
            ];

            $success = $this->kasir_model->insert_detail_transaksi_kasir($data_detail_transaksi);
            if (!$success) {
                $this->db->trans_rollback();
                show_error('Gagal menyimpan detail transaksi.');
            }

            // Kurangi stok produk
            $this->kasir_model->kurangi_stok_produk_outlet($post['id_produk'][$i], $post['qty'][$i], $outlet->id_outlet);

            // Tambahkan log stok
            $data_log_stok_otlet = [
                'id_produk_outlet' => $post['id_produk'][$i],
                'tanggal'          => date('Y-m-d H:i:s'),
                'jumlah'           => $post['qty'][$i],
                'keterangan'       => 'Laku'
            ];
            $this->kasir_model->insert_log_stok_outlet($data_log_stok_otlet);

            // Bagi hasil jika produk penitip
            if ($post['jenis_pemilik'][$i] === 'penitip') {
                $id_pemilik = $post['id_penitip'][$i];

                $titipan_terakhir = $this->kasir_model->get_titipan_toko_terakhir_by_id_pemilik($id_pemilik);
                if ($titipan_terakhir) {
                    $detail_titipan = $this->kasir_model->get_detail_titipan_toko_by_id_produk_dan_id_titipan_toko(
                        $post['id_produk'][$i],
                        $titipan_terakhir->id_titipan_toko
                    );

                    if ($detail_titipan) {
                        $laku_baru = $detail_titipan->laku + $post['qty'][$i];
                        $bayar_baru = $detail_titipan->bayar + ($post['harga_default'][$i] * $post['qty'][$i]);

                        $this->kasir_model->update_detail_titipan_toko($detail_titipan->id_detail_titipan_toko, [
                            'laku'  => $laku_baru,
                            'bayar' => $bayar_baru
                        ]);
                    }
                }
            }
        }

        // Simpan keuangan (saldo)
        $keterangan = 'Transaksi kasir';
        $asal = 'kasir : ' . $outlet->nama_outlet;
        $this->keuangan_model->tambah_saldo($post['total'], $keterangan, $asal);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            show_error('Terjadi kesalahan saat menyimpan transaksi.');
        }

        // Cetak struk
        redirect('kasir/cetak_struk/' . $id_transaksi);
    }

    public function cetak_struk($id_transaksi)
    {
        $data['transaksi'] = $this->kasir_model->get_transaksi_by_id_transaksi($id_transaksi);
        $data['detail_transaksi'] = $this->kasir_model->get_detail_transaksi_by_id_transaksi($id_transaksi);

        $this->load->view('kasir/cetak_struk',$data);
    }

    //URUSAN PENITIP
    //==========================================================================================================

    public function setoran()
    {
        $this->load->view('setoran/penitip_view');
    }

    public function penitip_daftar()
    {
        $penitip = $this->kasir_model->get_all_penitip();
        $daftar_input = [];
        $no = 1;
        foreach ($penitip as $key) {
            $bahan_input = [
                $no,
                $key->nama_pemilik,
                '<div class="text-end">
                <button type="button" class="mx-1 btn btn-soft-primary btn-produk-penitip" data-id="'. $key->id_pemilik .'">Produk</button>
                <button type="button" class="mx-1 btn btn-soft-warning btn-setoran-penitip" data-id="'. $key->id_pemilik .'">Setoran</button>
                <button type="button" class="mx-1 btn btn-soft-success btn-pembayaran-penitip" data-id="'. $key->id_pemilik .'">Pembayaran</button>
                </div>'
            ];
            array_push($daftar_input, $bahan_input);
            $no++;
        };
        $response = array(
            'data' => $daftar_input
        );
        // var_dump($response);
        // die();
        $this->set_output($response);
    }

    public function produk_penitip($id_pemilik)
    {
        $kasir = $this->session->userdata('kasir');
        $data['produk'] = $this->kasir_model->get_produk_penitip($id_pemilik, $kasir->id_outlet);
        $this->load->view('setoran/produk_penitip',$data);
    }

    public function setoran_penitip($id_pemilik)
    {
        $kasir = $this->session->userdata('kasir');
        $data['id_pemilik'] = $id_pemilik;
        $data['id_outlet'] = $kasir->id_outlet;
        $data['produk_penitip'] = $this->kasir_model->get_produk_penitip($id_pemilik, $data['id_outlet']);

        $data['titipan_toko'] = $this->kasir_model->get_titipan_toko_by_id_pemilik($id_pemilik);
        foreach ($data['titipan_toko'] as $key) {
            $key->detail = $this->kasir_model->get_detail_titipan_toko_by_id_titipan_toko($key->id_titipan_toko);
        }
        // echo "<pre>";
        // var_dump($data['produk_penitip']);
        // echo "</pre>";
        // die();
        $this->load->view('setoran/setoran_penitip',$data);
    }

    public function detail_setoran_penitip($id_titipan_toko)
    {
        $data['detail_titipan_toko'] = $this->kasir_model->get_detail_titipan_toko_by_id_titipan_toko($id_titipan_toko);
        $data['id_pemilik'] = $data['detail_titipan_toko'][0]->id_pemilik;
        $this->load->view('setoran/detail_titipan_toko',$data);
    }

    public function simpan_setoran_penitip()
    {
        $id_outlet   = $this->input->post('id_outlet', TRUE);
        $id_pemilik  = $this->input->post('id_pemilik', TRUE);
        $id_produk   = $this->input->post('id_produk', TRUE);
        $jumlah      = $this->input->post('jumlah', TRUE);
        $keterangan  = $this->input->post('keterangan', TRUE);

        // VALIDASI INPUT DASAR
        if (empty($id_outlet) || empty($id_pemilik)) {
            return $this->set_output([
                'status' => 'error',
                'message' => 'Data outlet atau pemilik tidak lengkap.'
            ]);
        }

        if (!is_array($id_produk) || !is_array($jumlah) || count($id_produk) === 0) {
            return $this->set_output([
                'status' => 'error',
                'message' => 'Tidak ada produk yang disetorkan.'
            ]);
        }

        // VALIDASI PER PRODUK
        foreach ($jumlah as $i => $jml) {
            if (!isset($id_produk[$i])) {
                return $this->set_output([
                    'status' => 'error',
                    'message' => 'Produk tidak sesuai dengan jumlah.'
                ]);
            }

            if (!is_numeric($jml) || $jml <= 0) {
                return $this->set_output([
                    'status' => 'error',
                    'message' => 'Jumlah produk harus berupa angka lebih dari 0.'
                ]);
            }
        }

        // MEMULAI TRANSAKSI DATABASE
        $this->db->trans_begin();

        // UPDATE SETORAN SEBELUMNYA MENJADI RETUR
        $titipan_sebelumnya = $this->kasir_model->get_titipan_toko_terakhir_by_id_pemilik($id_pemilik);

        if ($titipan_sebelumnya) {
            $detail_sebelumnya = $this->kasir_model->get_detail_titipan_toko_by_id_titipan_toko($titipan_sebelumnya->id_titipan_toko);

            foreach ($detail_sebelumnya as $detail) {

                //ANGGAP STOK_PRODUK_OUTLET PADA TITIPAN SEBELUMNYA MENJADI 0
                $this->kasir_model->kosongkan_stok_produk_outlet($detail->id_produk, $id_outlet);

                $retur = $detail->jumlah - $detail->laku;
                $this->kasir_model->update_detail_titipan_toko($detail->id_detail_titipan_toko, [
                    'retur' => $retur
                ]);
            }
        }

        // SIMPAN DATA TITIPAN TOKO BARU
        $titipan_toko = [
            'waktu'         => date('Y-m-d H:i:s'),
            'lunas'         => 'false',
            'keterangan'    => $keterangan,
            'id_pemilik'    => $id_pemilik
        ];

        $id_titipan_toko = $this->kasir_model->insert_titipan_toko($titipan_toko);

        // SIMPAN DETAIL & LOG
        foreach ($id_produk as $i => $id) {
            $jml = (int) $jumlah[$i];

            $data_detail = [
                'id_titipan_toko'   => $id_titipan_toko,
                'id_outlet'         => $id_outlet,
                'id_produk'         => $id,
                'jumlah'            => $jml,
                'laku'              => 0,
                'retur'             => 0,
                'bayar'             => 0
            ];
            $this->kasir_model->insert_detail_titipan_toko($data_detail);

            $this->kasir_model->tambah_stok_produk_outlet($id, $jml, $id_outlet);

            $this->kasir_model->insert_log_stok_outlet([
                'id_produk_outlet'  => $id,
                'tanggal'           => date('Y-m-d H:i:s'),
                'jumlah'            => $jml,
                'keterangan'        => 'Suplai baru'
            ]);
        }

        // SELESAIKAN TRANSAKSI
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->set_output([
                'status' => 'error',
                'message' => 'Gagal menyimpan data setoran.'
            ]);
        } else {
            $this->db->trans_commit();
            return $this->set_output([
                'status'  => 'success',
                'message' => 'Berhasil menyimpan setoran penitip.'
            ]);
        }
    }

    public function pembayaran_penitip($id_pemilik)
    {
        // Ambil semua titipan dari pemilik tertentu
        $titipan_toko = $this->kasir_model->get_titipan_toko_belum_lunas_by_id_pemilik($id_pemilik);

        foreach ($titipan_toko as $titipan) {
            // Ambil semua detail titipan untuk tiap titipan toko
            $detail_list = $this->kasir_model->get_detail_titipan_toko_by_id_titipan_toko($titipan->id_titipan_toko);

            $total_bayar = 0;
            foreach ($detail_list as $detail) {
                $total_bayar += $detail->bayar; // Asumsi field 'bayar' ada di tiap detail
            }

            // Tambahkan properti baru untuk ditampilkan di view
            $titipan->total_bayar = $total_bayar;
        }

        // Kirim ke view
        $data['titipan_toko'] = $titipan_toko;
        $data['id_pemilik'] = $id_pemilik;
        $data['bayar_titipan_toko'] = $this->kasir_model->get_bayar_titipan_toko_by_id_pemilik($id_pemilik);
        $this->load->view('setoran/pembayaran_penitip', $data);
    }

    public function simpan_pembayaran_penitip()
    {
        $kasir = $this->session->userdata('kasir');
        $outlet = $this->kasir_model->get_outlet_by_id_outlet($kasir->id_outlet);

        $id_titipan_toko = $this->input->post('id_titipan_toko');
        $nominal = $this->input->post('bayar');

        $data_bayar_titipan_toko = [
            'waktu'             => date('Y-m-d'),
            'nominal'           => $nominal,
            'keterangan'        => $this->input->post('keterangan'),
            'id_titipan_toko'   => $id_titipan_toko,
            'id_pemilik'        => $this->input->post('id_pemilik')
        ];

        if ($this->kasir_model->insert_bayar_titipan_toko($data_bayar_titipan_toko)) {

            $detail_titipan_toko = $this->kasir_model->get_detail_titipan_toko_by_id_titipan_toko($id_titipan_toko);
            foreach ($detail_titipan_toko as $key) {

                //ANGGAP STOK_PRODUK_OUTLET PADA TITIPAN SEBELUMNYA MENJADI 0
                $this->kasir_model->kosongkan_stok_produk_outlet($key->id_produk, $outlet->id_outlet);

                $data_detail_titipan_toko = [
                    'retur' => $key->jumlah - $key->laku
                ];
                $this->kasir_model->update_detail_titipan_toko($key->id_detail_titipan_toko, $data_detail_titipan_toko);
            }

            $data_titipan_toko = [
                'lunas' => 'true'
            ];

            if ($this->kasir_model->update_titipan_toko($id_titipan_toko, $data_titipan_toko)) {

                //SIMPAN KEUANGAN
                $keterangan = 'Bagi hasil penjualan ke penitip';
                $asal = 'kasir : '.$outlet->nama_outlet;
                $this->keuangan_model->kurangi_saldo($nominal, $keterangan, $asal);

                $response = array(
                    'status' => 'success', 
                    'message' => 'Berhasil melakukan pembayaran.'
                );
                $this->set_output($response);
            } else {
                $response = array(
                    'status' => 'error', 
                    'message' => 'Gagal update titipan toko.'
                );
            }
        } else {
            $response = array(
                'status' => 'error', 
                'message' => 'Gagal melakukan pembayaran.'
            );
            $this->set_output($response);
        }
    }
}