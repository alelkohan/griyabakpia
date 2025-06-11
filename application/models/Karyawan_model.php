<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Karyawan_model extends CI_Model
{

    // public function get_stok($id_bahan)
    // {
    //     $this->db->select('stok');
    //     $this->db->where('id_bahan', $id_bahan);
    //     $query = $this->db->get('bahan');

    //     if ($query->num_rows() > 0) {
    //         return (int) $query->row()->stok;
    //     }
    //     return 0;
    // }

    public function get_karyawan()
    {
        $this->db->order_by('id_karyawan', 'DESC');
        $query = $this->db->get('karyawan');
        return $query->result();
    }

    public function get_belum_lembur()
    {
        $today = date('Y-m-d');

        $this->db->select('k.*');
        $this->db->from('karyawan   k');
        $this->db->join('absensi_karyawan a', 'a.id_karyawan = k.id_karyawan AND DATE(a.waktu_masuk) = "' . $today . '"', 'left');
        $this->db->where('a.status', 'hadir');
        $this->db->where('a.lembur IS NULL');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_belum_absen()
    {
        $today = date('Y-m-d');

        $this->db->select('k.*');
        $this->db->from('karyawan k');
        $this->db->join('absensi_karyawan a', 'a.id_karyawan = k.id_karyawan AND DATE(a.waktu_masuk) = "' . $today . '"', 'left');
        $this->db->where('a.status IS NULL');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_belum_pulang()
    {
        $today = date('Y-m-d');

        $this->db->select('karyawan.*, absensi_karyawan.id_absensi, absensi_karyawan.waktu_masuk');
        $this->db->from('karyawan');
        $this->db->join('absensi_karyawan', 'absensi_karyawan.id_karyawan = karyawan.id_karyawan', 'inner');
        $this->db->where('DATE(absensi_karyawan.waktu_masuk)', $today);
        $this->db->where('absensi_karyawan.waktu_pulang IS NULL');
        $this->db->where('absensi_karyawan.status', 'hadir'); // <--- tambahin ini

        return $this->db->get()->result();
    }

    public function update_pulang($id_karyawan, $waktu_pulang)
    {
        $this->db->where('id_karyawan', $id_karyawan);
        $this->db->where('DATE(waktu_masuk)', date('Y-m-d')); // hanya untuk hari ini
        return $this->db->update('absensi_karyawan', [
            'waktu_pulang' => $waktu_pulang
        ]);
    }

    public function update_lembur($id_karyawan, $jam_lembur, $tanggal)
    {
        $this->db->where('id_karyawan', $id_karyawan);
        $this->db->where('DATE(waktu_masuk)', $tanggal);
        $this->db->update('absensi_karyawan', ['lembur' => $jam_lembur]);
    }

    public function get_absensi_by_id($id_absensi)
    {
        $this->db->select('absensi_karyawan.*, karyawan.*');
        $this->db->from('absensi_karyawan');
        $this->db->join('karyawan', 'absensi_karyawan.id_karyawan = karyawan.id_karyawan');
        $this->db->where('absensi_karyawan.id_absensi', $id_absensi);
        $query = $this->db->get(); 

        return $query->row();
    }

    // public function get_bahan_by_id($id_bahan)
    // {
    //     $this->db->where('id_bahan', $id_bahan);
    //     $query = $this->db->get('bahan');
    //     return $query->row();
    // }

    public function insert_karyawan($data)
    {
        return $this->db->insert('karyawan', $data);
        $this->db->reset_query();

    }

    public function update_karyawan($id_karyawan, $data)
    {
        $this->db->where('id_karyawan', $id_karyawan);
        return $this->db->update('karyawan', $data);
        $this->db->reset_query();
    }

    public function delete_absensi($id_absensi)
    {
        $this->db->where('id_absensi', $id_absensi);
        $this->db->delete('absensi_karyawan');
        $this->db->reset_query();
    }

    public function get_absen()
    {
        $this->db->select('absensi_karyawan.*, karyawan.*');
        $this->db->from('absensi_karyawan');
        $this->db->join('karyawan', 'absensi_karyawan.id_karyawan = karyawan.id_karyawan');
        $this->db->where('DATE(absensi_karyawan.waktu_masuk)', date('Y-m-d')); // filter hari ini
        $this->db->order_by('absensi_karyawan.waktu_masuk', 'DESC');

        return $this->db->get()->result();
    }

    public function get_karyawan_by_id($id_karyawan)
    {
        $this->db->where('id_karyawan', $id_karyawan);
        $query = $this->db->get('karyawan');
        return $query->row();
    }

    public function insert_absensi($data)
    {
        return $this->db->insert('absensi_karyawan', $data);
        $this->db->reset_query();

    }

    public function update_absensi($id_absensi, $data)
    {
        $this->db->where('id_absensi', $id_absensi);
        return $this->db->update('absensi_karyawan', $data);
    }

    // public function get_logbahan_filter($tanggal_from, $tanggal_to)
    // {
    //     $this->db->select('log_bahan.*, bahan.*');
    //     $this->db->from('log_bahan');
    //     $this->db->join('bahan', 'log_bahan.id_bahan = bahan.id_bahan');

    //     if ($tanggal_from && $tanggal_to) {
    //         $this->db->where('DATE(log_bahan.tanggal) >=', $tanggal_from);
    //         $this->db->where('DATE(log_bahan.tanggal) <=', $tanggal_to);
    //     }

    //     $this->db->order_by('log_bahan.tanggal', 'DESC');
    //     return $this->db->get()->result();
    // }

    // public function insert_logbahan($data)
    // {
    //     return $this->db->insert('log_bahan', $data);
    // }

    // public function add_stok($id_bahan, $jumlah)
    // {
    //     $this->db->set('stok', 'stok + ' . (int)$jumlah, false);
    //     $this->db->where('id_bahan', $id_bahan);
    //     return $this->db->update('bahan');
    // }

    // public function kurangi_stok($id_bahan, $jumlah)
    // {
    //     $this->db->set('stok', 'stok - ' . (int)$jumlah, false);
    //     $this->db->where('id_bahan', $id_bahan);
    //     return $this->db->update('bahan');
    // }

    // public function update_stok_bahan($id_bahan, $stok_baru)
    // {
    //     $this->db->where('id_bahan', $id_bahan);
    //     return $this->db->update('bahan', ['stok' => $stok_baru]);
    // }

    // public function update_logbahan($id_log_bahan, $data)
    // {
    //     $this->db->where('id_log_bahan', $id_log_bahan);
    //     return $this->db->update('log_bahan', $data);
    //     $this->db->reset_query();
    // }

    public function delete_karyawan($id_karyawan)
    {
        $this->db->where('id_karyawan', $id_karyawan);
        $this->db->delete('karyawan');
        $this->db->reset_query();
    }

    // public function get_nama_bahan($id_bahan)
    // {
    //     $this->db->select('nama_bahan');
    //     $this->db->where('id_bahan', $id_bahan);
    //     $query = $this->db->get('bahan');
    //     return $query->row()->nama_bahan;
    // }
}
