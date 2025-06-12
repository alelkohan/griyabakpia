<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk_model extends CI_Model
{
    public function get_by_id($id_produk)
    {
        $this->db->where('id_produk', $id_produk);
        $query = $this->db->get('produk');
        return $query->row();
    }

    public function get_produk_by_idoutlet($id_produk)
    {
        $this->db->where('id_produk', $id_produk);
        $query = $this->db->get('produk_outlet');
        return $query->row();
    }

    public function get_produk_by_idtoko($id_produk)
    {
        $this->db->where('id_produk', $id_produk);
        $query = $this->db->get('produk_toko');
        return $query->row();
    }

    public function get_pemilik_by_produk($id_pemilik)
    {
        $this->db->where('id_pemilik', $id_pemilik);
        $query = $this->db->get('produk');
        return $query->row();
    }

    public function cek_nama_produk($nama_produk, $outlet)
    {
        return $this->db->get_where('produk', [
            'nama_produk' => $nama_produk,
            'outlet' => $outlet
        ])->row();
    }

    public function get_pemilik()
    {
        $this->db->order_by('id_pemilik', 'desc');
        $query = $this->db->get('pemilik');
        return $query->result();
    }

    public function get_pembayaran_toko()
    {
        $this->db->select('pembayaran_toko.*, toko.nama_toko');
        $this->db->from('pembayaran_toko');
        $this->db->join('log_setor_toko', 'pembayaran_toko.id_log_setor_toko = log_setor_toko.id_log_setor_toko');
        $this->db->join('toko', 'log_setor_toko.id_toko = toko.id_toko');
        $this->db->order_by('pembayaran_toko.id_pembayaran_toko', 'desc');
        return $this->db->get()->result();
    }

    public function get_pembayaran_toko_filter($tanggal_from, $tanggal_to, $id_toko)
    {
        $this->db->select('pembayaran_toko.*, toko.nama_toko');
        $this->db->from('pembayaran_toko');
        $this->db->join('log_setor_toko', 'pembayaran_toko.id_log_setor_toko = log_setor_toko.id_log_setor_toko');
        $this->db->join('toko', 'log_setor_toko.id_toko = toko.id_toko');

        if (!empty($tanggal_from)) {
            $this->db->where('pembayaran_toko.tanggal_bayar >=', $tanggal_from . ' 00:00:00');
        }

        if (!empty($tanggal_to)) {
            $this->db->where('pembayaran_toko.tanggal_bayar <=', $tanggal_to . ' 23:59:59');
        }

        if (!empty($id_toko)) {
            $this->db->where('toko.id_toko', $id_toko);
        }

        $this->db->order_by('pembayaran_toko.tanggal_bayar', 'DESC');
        return $this->db->get()->result();
    }

    public function get_outlet()
    {
        $this->db->order_by('id_outlet', 'desc');
        $query = $this->db->get('outlet');
        return $query->result();
    }

    public function get_toko()
    {
        $this->db->order_by('id_toko', 'desc');
        $query = $this->db->get('toko');
        return $query->result();
    }

    public function get_produk()
    {
        $this->db->select('produk.*, pemilik.nama_pemilik, pemilik.jenis_pemilik');
        $this->db->from('produk');
        $this->db->join('pemilik', 'produk.id_pemilik = pemilik.id_pemilik', 'left');
        $this->db->order_by('produk.id_produk', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_pemilik_by_id($id_pemilik)
    {
        $this->db->where('id_pemilik', $id_pemilik);
        $query = $this->db->get('pemilik');
        return $query->row();
    }

    public function get_pembayarantoko_by_id($id_pembayaran_toko)
    {
        $this->db->where('id_pembayaran_toko', $id_pembayaran_toko);
        $query = $this->db->get('pembayaran_toko');
        return $query->row();
    }

    public function get_pembayarantokodetail_by_id($id_pembayaran_toko)
    {
        $this->db->where('id_pembayaran_toko', $id_pembayaran_toko);
        $query = $this->db->get('detail_pembayaran_toko');
        return $query->result();
    }

    public function get_toko_by_id($id_toko)
    {
        $this->db->where('id_toko', $id_toko);
        $query = $this->db->get('toko');
        return $query->row();
    }

    public function get_detailsetor_by_id($id_log_setor_toko)
    {
        $this->db->select('detail.*, produk_toko.harga_toko, log_setor_toko.id_toko');
        $this->db->from('detail_setor_toko detail');
        $this->db->join('log_setor_toko', 'detail.id_log_setor_toko = log_setor_toko.id_log_setor_toko');
        $this->db->join('produk_toko', 'produk_toko.id_produk = detail.id_produk AND produk_toko.id_toko = log_setor_toko.id_toko');
        $this->db->where('detail.id_log_setor_toko', $id_log_setor_toko);
        return $this->db->get()->result();
    }

    public function get_nama_produk($id_produk)
    {
        $this->db->select('nama_produk');
        $this->db->from('produk');
        $this->db->where('id_produk', $id_produk);
        $query = $this->db->get()->row();
        return $query ? $query->nama_produk : 'Produk tidak ditemukan';
    }

    public function get_id_toko_by_id_log_setor($id_log_setor_toko)
    {
        $this->db->select('id_toko');
        $this->db->from('log_setor_toko');
        $this->db->where('id_log_setor_toko', $id_log_setor_toko);
        $query = $this->db->get();
        return $query->row() ? $query->row()->id_toko : null;
    }

    public function get_logsetor_by_id($id_log_setor_toko)
    {
        $this->db->where('id_log_setor_toko', $id_log_setor_toko);
        $query = $this->db->get('log_setor_toko');
        return $query->row();
    }

    public function get_outlet_by_id($id_outlet)
    {
        $this->db->where('id_outlet', $id_outlet);
        $query = $this->db->get('outlet');
        return $query->row();
    }

    public function get_produk_yang_belum_terdaftar($id_outlet)
    {
        $this->db->select('produk.*, pemilik.nama_pemilik, pemilik.jenis_pemilik');
        $this->db->from('produk');
        $this->db->join('pemilik', 'produk.id_pemilik = pemilik.id_pemilik', 'left');
        $this->db->order_by('produk.id_produk', 'desc');
        $this->db->where("produk.id_produk NOT IN (
            SELECT id_produk FROM produk_outlet WHERE id_outlet = ".$this->db->escape($id_outlet)."
        )");
        return $this->db->get()->result();
    }

    public function get_produk_outlet_by_id($id_produk_outlet)
    {
        $this->db->where('id_produk_outlet', $id_produk_outlet);
        $query = $this->db->get('produk_outlet');
        return $query->row();
    }

    public function get_produk_by_outlet($id_outlet)
    {
        $this->db->select('
            produk_outlet.id_produk_outlet,
            produk_outlet.harga_outlet,
            produk_outlet.stok,
            produk.nama_produk,
            pemilik.nama_pemilik,
            pemilik.jenis_pemilik
            ');
        $this->db->from('produk_outlet');
        $this->db->join('produk', 'produk.id_produk = produk_outlet.id_produk');
        $this->db->join('pemilik', 'pemilik.id_pemilik = produk.id_pemilik', 'left');
        $this->db->where('produk_outlet.id_outlet', $id_outlet);
        return $this->db->get()->result();
    }

    public function get_produk_by_toko($id_toko)
    {
        $this->db->select('
            produk_toko.id_produk_toko,
            produk_toko.harga_toko,
            produk_toko.stok,
            produk_toko.jumlah_terjual,
            produk.nama_produk,
            produk.id_produk,
            pemilik.nama_pemilik
            ');
        $this->db->from('produk_toko');
        $this->db->join('produk', 'produk.id_produk = produk_toko.id_produk');
        $this->db->join('pemilik', 'pemilik.id_pemilik = produk.id_pemilik', 'left');
        $this->db->where('produk_toko.id_toko', $id_toko);
        return $this->db->get()->result();
    }

    public function get_produk_by_idproduktoko($id_produk_toko)
    {
        $this->db->select('
            produk_toko.id_produk_toko,
            produk_toko.id_toko,
            produk_toko.harga_toko,
            produk_toko.stok,
            produk_toko.jumlah_terjual,
            produk.nama_produk,
            produk.id_produk,
            pemilik.nama_pemilik
            ');
        $this->db->from('produk_toko');
        $this->db->join('produk', 'produk.id_produk = produk_toko.id_produk');
        $this->db->join('pemilik', 'pemilik.id_pemilik = produk.id_pemilik', 'left');
        $this->db->where('produk_toko.id_produk_toko', $id_produk_toko);
        return $this->db->get()->row(); // Menggunakan row() bukan result()
    }

    public function get_harga_toko_by_id_produk_toko($id_produk, $id_toko)
    {
        $this->db->select('harga_toko');
        $this->db->from('produk_toko');
        $this->db->where('id_produk', $id_produk);
        $this->db->where('id_toko', $id_toko);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->harga_toko;
        } else {
            return 0; // bisa juga return null tergantung kebutuhan
        }
    }

    public function get_log_setor_toko_by_toko($id_toko)
    {
        $this->db->where('id_toko', $id_toko);
        $query = $this->db->get('log_setor_toko');
        return $query->result();
    }

    public function insert($data)
    {
        return $this->db->insert('produk', $data);
        $this->db->reset_query();
    }

    public function insert_produkoutlet($data)
    {
        return $this->db->insert('produk_outlet', $data);
        $this->db->reset_query();
    }

    public function insert_produktoko($data)
    {
        return $this->db->insert('produk_toko', $data);
        $this->db->reset_query();
    }

    public function insert_log_setor($data)
    {
        $this->db->insert('log_setor_toko', $data);
        return $this->db->insert_id();
    }

    public function insert_detail_setor($data)
    {
        $this->db->insert('detail_setor_toko', $data);
    }

    public function insert_pembayaran_toko($data)
    {
        $this->db->insert('pembayaran_toko', $data);
        return $this->db->insert_id();
    }

    public function insert_detail_pembayaran($data)
    {
        $this->db->insert('detail_pembayaran_toko', $data);
    }

    public function insert_log_stok_toko($data)
    {
        $this->db->insert('log_stok_toko', $data);
    }

    public function update($id_produk, $data)
    {
        $this->db->where('id_produk', $id_produk);
        return $this->db->update('produk', $data);
        $this->db->reset_query();
    }

    public function delete($id_produk)
    {
        $this->db->where('id_produk', $id_produk);
        $this->db->delete('produk');
        $this->db->reset_query();
    }

    public function delete_produkoutlet($id_produk)
    {
        return $this->db->delete('produk_outlet', array('id_produk_outlet' => $id_produk));
    }

    public function delete_logsetor($id_log_setor_toko)
    {
        // Ambil data detail setor terkait log_setor_toko
        $this->db->where('id_log_setor_toko', $id_log_setor_toko);
        $details = $this->db->get('detail_setor_toko')->result();

        foreach ($details as $detail) {
            $id_produk = $detail->id_produk;
            $jumlah_produk = $detail->jumlah_produk;

            // Kurangi stok pada tabel produk_toko
            $this->db->where('id_produk', $id_produk);
            $this->db->set('stok', 'stok - ' . (int)$jumlah_produk, false);
            $this->db->update('produk_toko');
        }

        // Hapus detail setor toko
        $this->db->delete('detail_setor_toko', ['id_log_setor_toko' => $id_log_setor_toko]);

        // Hapus log setor toko utama
        $this->db->delete('log_setor_toko', ['id_log_setor_toko' => $id_log_setor_toko]);
    }

    public function delete_produktoko($id_produk)
    {
        return $this->db->delete('produk_toko', array('id_produk_toko' => $id_produk));
    }

    public function insert_pemilik($data)
    {
        return $this->db->insert('pemilik', $data);
        $this->db->reset_query();

    }

    public function insert_toko($data)
    {
        return $this->db->insert('toko', $data);
        $this->db->reset_query();

    }

    public function insert_outlet($data)
    {
        return $this->db->insert('outlet', $data);
        $this->db->reset_query();

    }

    public function update_pemilik($id_pemilik, $data)
    {
        $this->db->where('id_pemilik', $id_pemilik);
        return $this->db->update('pemilik', $data);
        $this->db->reset_query();
    }

    public function update_toko($id_toko, $data)
    {
        $this->db->where('id_toko', $id_toko);
        return $this->db->update('toko', $data);
        $this->db->reset_query();
    }

    public function update_stok_produk_toko($id_toko, $id_produk, $jumlah_tambah)
    {
        // Periksa apakah record sudah ada
        $this->db->where('id_toko', $id_toko);
        $this->db->where('id_produk', $id_produk);
        $query = $this->db->get('produk_toko');

        if ($query->num_rows() > 0) {
            // Jika sudah ada, update stok
            $this->db->set('stok', 'stok + ' . (int)$jumlah_tambah, false);
            $this->db->where('id_toko', $id_toko);
            $this->db->where('id_produk', $id_produk);
            $this->db->update('produk_toko');
        } else {
            // Jika belum ada, insert baru
            $this->db->insert('produk_toko', [
                'id_toko' => $id_toko,
                'id_produk' => $id_produk,
                'stok' => $jumlah_tambah,
                'harga_toko' => 0,
                'jumlah_terjual' => 0
            ]);
        }
    }

    public function update_stok_produk_tokopembayaran($id_produk, $sisa_stok)
    {
        $this->db->update('produk_toko', ['stok' => $sisa_stok], ['id_produk' => $id_produk]);
    }

    public function update_outlet($id_outlet, $data)
    {
        $this->db->where('id_outlet', $id_outlet);
        return $this->db->update('outlet', $data);
        $this->db->reset_query();
    }

    public function update_produktoko($id_produk_toko, $data)
    {
        $this->db->where('id_produk_toko', $id_produk_toko);
        return $this->db->update('produk_toko', $data);
        $this->db->reset_query();
    }

    public function update_pembayaran_toko($id, $data)
    {
        return $this->db->update('pembayaran_toko', $data, ['id_pembayaran_toko' => $id]);
    }

    public function delete_detail_pembayaran($id_pembayaran_toko)
    {
        return $this->db->delete('detail_pembayaran_toko', ['id_pembayaran_toko' => $id_pembayaran_toko]);
    }
    
    public function delete_pemilik($id_pemilik)
    {
        $this->db->where('id_pemilik', $id_pemilik);
        $this->db->delete('pemilik');
        $this->db->reset_query();
    }

    public function delete_toko($id_toko)
    {
        $this->db->where('id_toko', $id_toko);
        $this->db->delete('toko');
        $this->db->reset_query();
    }

    public function delete_outlet($id_outlet)
    {
        $this->db->where('id_outlet', $id_outlet);
        $this->db->delete('outlet');
        $this->db->reset_query();
    }

    public function get_logstok()
    {
        $this->db->select('log_stok_outlet.*, produk.nama_produk, outlet.nama_outlet as outlet');
        $this->db->from('log_stok_outlet');
        $this->db->join('produk_outlet', 'log_stok_outlet.id_produk_outlet = produk_outlet.id_produk_outlet');
        $this->db->join('produk', 'produk_outlet.id_produk = produk.id_produk');
        $this->db->join('outlet', 'produk_outlet.id_outlet = outlet.id_outlet');
        $this->db->order_by('log_stok_outlet.tanggal', 'DESC');

        return $this->db->get()->result();
    }

    public function get_logstok_by_id($id_logstok)
    {
        $this->db->where('id_log_stok_outlet', $id_logstok);
        $query = $this->db->get('log_stok_outlet');
        return $query->row();
    }

    public function get_logstok_filter($tanggal_from, $tanggal_to, $outlet)
    {
        $this->db->select('log_stok_outlet.*, produk.nama_produk, outlet.nama_outlet as outlet');
        $this->db->from('log_stok_outlet');
        $this->db->join('produk_outlet', 'log_stok_outlet.id_produk_outlet = produk_outlet.id_produk_outlet');
        $this->db->join('produk', 'produk_outlet.id_produk = produk.id_produk');
        $this->db->join('outlet', 'produk_outlet.id_outlet = outlet.id_outlet');

        if (!empty($tanggal_from)) {
            $this->db->where('log_stok_outlet.tanggal >=', $tanggal_from . ' 00:00:00');
        }

        if (!empty($tanggal_to)) {
            $this->db->where('log_stok_outlet.tanggal <=', $tanggal_to . ' 23:59:59');
        }

        if (!empty($outlet)) {
            $this->db->where('outlet.id_outlet', $outlet);
        }

        $this->db->order_by('log_stok_outlet.tanggal', 'DESC');
        return $this->db->get()->result();
    }

    public function get_stok_produk_toko($id_toko, $id_produk)
    {
        $this->db->select('stok');
        $this->db->from('produk_toko');
        $this->db->where('id_toko', $id_toko);
        $this->db->where('id_produk', $id_produk);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return (int) $query->row()->stok;
        }
        return 0; // default kalau belum ada
    }

    public function add_stok($id_produk, $jumlah)
    {
        $this->db->set('stok', 'stok + ' . (int)$jumlah, false);
        $this->db->where('id_produk_outlet', $id_produk);
        return $this->db->update('produk_outlet');
    }

    public function insert_log_stok($data)
    {
        return $this->db->insert('log_stok_outlet', $data);
    }

    public function delete_logstok($id_logstok)
    {
        $this->db->where('id_log_stok_outlet', $id_logstok);
        $this->db->delete('log_stok_outlet');
        $this->db->reset_query();
    }

    public function kembalikan_stok_produk_outlet($id_produk_outlet, $jumlah)
    {
        $this->db->set('stok', 'stok - ' . (int) $jumlah, false);
        $this->db->where('id_produk_outlet', $id_produk_outlet);
        $this->db->update('produk_outlet');
    }

}
