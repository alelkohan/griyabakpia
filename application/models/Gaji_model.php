<?php
defined('BASEPATH') or exit('No direct script access allowed');

class gaji_model extends CI_Model
{

    public function get_gaji()
    {
        $this->db->select('transaksi_gaji.*, karyawan.nama_karyawan, karyawan.peran_karyawan, karyawan.foto_karyawan');
        $this->db->from('transaksi_gaji');
        $this->db->join('karyawan', 'karyawan.id_karyawan = transaksi_gaji.id_karyawan');
        $this->db->order_by('transaksi_gaji.id_transaksi_gaji', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    public function get_gaji_by_id($id_gaji)
    {
        $this->db->where('id_transaksi_gaji', $id_gaji);
        $query = $this->db->get('transaksi_gaji');
        return $query->row();
    }

    public function get_absensi_bulan_ini($id_karyawan)
    {
        $tanggal_awal = date('Y-m-01 00:00:00');
        $tanggal_akhir = date('Y-m-t 23:59:59');

        $this->db->select('*');
        $this->db->from('absensi_karyawan');
        $this->db->where('id_karyawan', $id_karyawan);
        $this->db->where('waktu_masuk >=', $tanggal_awal);
        $this->db->where('waktu_masuk <=', $tanggal_akhir);

        return $this->db->get()->result();
    }

    public function get_rekap_status_absensi_bulan_ini($id_karyawan)
    {
        $tanggal_awal = date('Y-m-01 00:00:00');
        $tanggal_akhir = date('Y-m-t 23:59:59');

        $this->db->select('status, COUNT(*) as jumlah');
        $this->db->from('absensi_karyawan');
        $this->db->where('id_karyawan', $id_karyawan);
        $this->db->where('waktu_masuk >=', $tanggal_awal);
        $this->db->where('waktu_masuk <=', $tanggal_akhir);
        $this->db->group_by('status');

        $query = $this->db->get();
        $result = $query->result();

        // Biar output-nya rapih dan selalu ada semua status
        $rekap = [
            'hadir' => 0,
            'sakit' => 0,
            'izin'  => 0,
            'alpha' => 0,
        ];

        foreach ($result as $row) {
            if (isset($rekap[$row->status])) {
                $rekap[$row->status] = (int) $row->jumlah;
            }
        }

        return $rekap;
    }

    public function get_karyawan_dengan_status_gaji()
    {
        $this->db->select('karyawan.*, 
            (
            SELECT COUNT(*) 
            FROM transaksi_gaji 
            WHERE transaksi_gaji.id_karyawan = karyawan.id_karyawan 
            AND MONTH(transaksi_gaji.tanggal_pembayaran) = MONTH(CURDATE()) 
            AND YEAR(transaksi_gaji.tanggal_pembayaran) = YEAR(CURDATE())
            ) AS sudah_digaji_bulan_ini
            ');
        $this->db->from('karyawan');

        return $this->db->get()->result();
    }

    public function insert_gaji($data)
    {
        return $this->db->insert('transaksi_gaji', $data);
        // $this->db->reset_query();

    }

    public function delete_gaji($id_gaji)
    {
        $this->db->where('id_transaksi_gaji', $id_gaji);
        $this->db->delete('transaksi_gaji');
        $this->db->reset_query();
    }

}