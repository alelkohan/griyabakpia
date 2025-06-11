<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keuangan_model extends CI_Model
{

    public function get_saldo()
    {
        $this->db->select('saldo');
        $this->db->order_by('id_keuangan', 'DESC'); // urutkan dari terbaru
        $this->db->limit(1); // ambil satu baris saja
        $query = $this->db->get('keuangan');
        
        if ($query->num_rows() > 0) {
            return (int) $query->row()->saldo; // pastikan return-nya angka, bukan object
        }
        return 0;
    }

    public function tambah_saldo($jumlah, $keterangan, $asal)
    {
        $saldo_sebelumnya = $this->get_saldo(); // ambil saldo terakhir

        $data = [
            'tanggal' => date('Y-m-d H:i:s'),
            'jenis' => 'pemasukan',
            'asal' => $asal,
            'nilai_mutasi' => $jumlah,
            'saldo' => $saldo_sebelumnya + $jumlah, // pastiin nilainya positif
            'keterangan' => $keterangan
        ];
        
        $this->db->insert('keuangan', $data);
    }

    public function kurangi_saldo($jumlah, $keterangan, $asal)
    {
        $saldo_sebelumnya = $this->get_saldo(); // ambil saldo terakhir

        $data = [
            'tanggal' => date('Y-m-d H:i:s'),
            'jenis' => 'pengeluaran',
            'asal' => $asal,
            'nilai_mutasi' => $jumlah,
            'saldo' => $saldo_sebelumnya - $jumlah, // saldo setelah dikurangi
            'keterangan' => $keterangan,
        ];

        $this->db->insert('keuangan', $data);
    }

    public function get_keuangan()
    {
        $this->db->order_by('id_keuangan', 'DESC');
        $query = $this->db->get('keuangan');
        return $query->result();
    }

    public function get_keuangan_filter($tanggal_from, $tanggal_to, $asal)
    {
        $this->db->from('keuangan');

        if (!empty($tanggal_from)) {
            $this->db->where('keuangan.tanggal >=', $tanggal_from . ' 00:00:00');
        }

        if (!empty($tanggal_to)) {
            $this->db->where('keuangan.tanggal <=', $tanggal_to . ' 23:59:59');
        }

        if (!empty($asal)) {
            $this->db->where('keuangan.asal', $asal);
        }

        $this->db->order_by('keuangan.tanggal', 'DESC');
        return $this->db->get()->result();
    }

}
