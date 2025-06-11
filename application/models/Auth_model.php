<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
	public function login($nama_karyawan, $password)
    {
    	$this->db->select('*');
    	$this->db->from('karyawan');
    	$this->db->join('outlet','karyawan.id_outlet = outlet.id_outlet','left');
        $this->db->where('nama_karyawan', $nama_karyawan);
        $query = $this->db->get();

        if ($query->num_rows() === 1) {
            $karyawan = $query->row();

            if (password_verify($password, $karyawan->password)) {
                return $karyawan; // Login berhasil, return data user
            } else {
                return false; // Password salah
            }
        }
        return false; // User tidak ditemukan
    }
}