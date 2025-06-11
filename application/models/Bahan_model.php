<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bahan_model extends CI_Model
{

    public function get_stok($id_bahan)
    {
        $this->db->select('stok');
        $this->db->where('id_bahan', $id_bahan);
        $query = $this->db->get('bahan');
        
        if ($query->num_rows() > 0) {
            return (int) $query->row()->stok; // pastikan return-nya angka, bukan object
        }
        return 0;
    }

    public function get_bahan()
    {
        $this->db->order_by('id_bahan', 'DESC');
        $query = $this->db->get('bahan');
        return $query->result();
    }

    public function get_bahan_by_id($id_bahan)
    {
        $this->db->where('id_bahan', $id_bahan);
        $query = $this->db->get('bahan');
        return $query->row();
    }

    public function insert_bahan($data)
    {
        return $this->db->insert('bahan', $data);
        $this->db->reset_query();

    }

    public function update_bahan($id_bahan, $data)
    {
        $this->db->where('id_bahan', $id_bahan);
        return $this->db->update('bahan', $data);
        $this->db->reset_query();
    }

    public function delete_bahan($id_bahan)
    {
        $this->db->where('id_bahan', $id_bahan);
        $this->db->delete('bahan');
        $this->db->reset_query();
    }

    public function get_logbahan()
    {
        $this->db->select('log_bahan.*, bahan.*');
        $this->db->from('log_bahan');
        $this->db->join('bahan', 'log_bahan.id_bahan = bahan.id_bahan');
        $this->db->order_by('log_bahan.tanggal', 'DESC');

        return $this->db->get()->result();
    }

    public function get_logbahan_by_id($id_logbahan)
    {
        $this->db->where('id_log_bahan', $id_logbahan);
        $query = $this->db->get('log_bahan');
        return $query->row();
    }

    public function get_logbahan_filter($tanggal_from, $tanggal_to)
    {
        $this->db->select('log_bahan.*, bahan.*');
        $this->db->from('log_bahan');
        $this->db->join('bahan', 'log_bahan.id_bahan = bahan.id_bahan');

        if ($tanggal_from && $tanggal_to) {
            $this->db->where('DATE(log_bahan.tanggal) >=', $tanggal_from);
            $this->db->where('DATE(log_bahan.tanggal) <=', $tanggal_to);
        }

        $this->db->order_by('log_bahan.tanggal', 'DESC');
        return $this->db->get()->result();
    }

    public function insert_logbahan($data)
    {
        return $this->db->insert('log_bahan', $data);
    }

    public function add_stok($id_bahan, $jumlah)
    {
        $this->db->set('stok', 'stok + ' . (int)$jumlah, false);
        $this->db->where('id_bahan', $id_bahan);
        return $this->db->update('bahan');
    }

    public function kurangi_stok($id_bahan, $jumlah)
    {
        $this->db->set('stok', 'stok - ' . (int)$jumlah, false);
        $this->db->where('id_bahan', $id_bahan);
        return $this->db->update('bahan');
    }

    public function update_stok_bahan($id_bahan, $stok_baru)
    {
        $this->db->where('id_bahan', $id_bahan);
        return $this->db->update('bahan', ['stok' => $stok_baru]);
    }

    public function update_logbahan($id_log_bahan, $data)
    {
        $this->db->where('id_log_bahan', $id_log_bahan);
        return $this->db->update('log_bahan', $data);
        $this->db->reset_query();
    }

    public function delete_logstok($id_logstok)
    {
        $this->db->where('id_log_stok', $id_logstok);
        $this->db->delete('log_stok');
        $this->db->reset_query();
    }

    public function get_nama_bahan($id_bahan)
    {
        $this->db->select('nama_bahan');
        $this->db->where('id_bahan', $id_bahan);
        $query = $this->db->get('bahan');
        return $query->row()->nama_bahan;
    }

}
