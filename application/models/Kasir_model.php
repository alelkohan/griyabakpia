<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kasir_model extends CI_Model
{
	public function get_all_produk()
	{
		return $this->db->get('produk')->result();
	}

	public function get_produk_by_outlet($id_outlet)
	{
		$this->db->select('*');
		$this->db->from('produk_outlet');
		$this->db->join('produk','produk_outlet.id_produk = produk.id_produk','left');
		$this->db->join('pemilik','produk.id_pemilik = pemilik.id_pemilik','left');
		$this->db->where('produk_outlet.id_outlet',$id_outlet);
		return $this->db->get()->result();
	}

	public function get_produk_by_id_produk($id_produk)
	{
		$this->db->where('id_produk',$id_produk);
		return $this->db->get('produk')->row();
	}

	public function search_produk_by_nama_produk($keyword, $id_outlet)
	{
		$this->db->select('*');
		$this->db->from('produk_outlet');
		$this->db->join('produk','produk_outlet.id_produk = produk.id_produk','left');
		$this->db->join('pemilik','produk.id_pemilik = pemilik.id_pemilik','left');
		$this->db->like('produk.nama_produk', $keyword);
		$this->db->where('produk_outlet.id_outlet',$id_outlet);
		return $this->db->get()->result();
	}

	public function insert_transaksi_kasir($data)
	{
		return $this->db->insert('transaksi_kasir',$data);
	}

	public function insert_detail_transaksi_kasir($data)
	{
		return $this->db->insert('detail_transaksi_kasir',$data);
	}

	public function kurangi_stok_produk_outlet($id_produk, $qty, $id_outlet)
	{
		$this->db->set('stok', 'stok - ' . (int)$qty, FALSE);
		$this->db->where('id_produk', $id_produk);
		$this->db->update('produk_outlet');
		
	    // Cek apakah query berhasil
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function tambah_stok_produk_outlet($id_produk, $qty, $id_outlet)
	{
		$this->db->set('stok', 'stok + ' . (int)$qty, FALSE);
		$this->db->where('id_produk', $id_produk);
		$this->db->update('produk_outlet');
		
	    // Cek apakah query berhasil
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function kosongkan_stok_produk_outlet($id_produk, $id_outlet)
	{
		$this->db->set('stok',0);
		$this->db->where('id_produk',$id_produk);
		$this->db->where('id_outlet',$id_outlet);
		return $this->db->update('produk_outlet');
	}

	public function insert_log_stok_outlet($data)
	{
		return $this->db->insert('log_stok_outlet',$data);
	}

	public function get_outlet_by_id_outlet($id_outlet)
	{
		$this->db->where('id_outlet',$id_outlet);
		return $this->db->get('outlet')->row();
	}

	public function get_transaksi_by_id_transaksi($id_transaksi)
	{
		$this->db->select('*');
		$this->db->from('transaksi_kasir');
		$this->db->join('outlet','transaksi_kasir.id_outlet = outlet.id_outlet','left');
		$this->db->join('karyawan','transaksi_kasir.id_kasir = karyawan.id_karyawan','left');
		$this->db->where('id_transaksi_kasir',$id_transaksi);
		return $this->db->get()->row();
	}

	public function get_detail_transaksi_by_id_transaksi($id_transaksi)
	{
		$this->db->select('*');
		$this->db->from('detail_transaksi_kasir');
		$this->db->join('produk','produk.id_produk = detail_transaksi_kasir.id_produk','left');
		$this->db->where('id_transaksi',$id_transaksi);
		return $this->db->get()->result();
	}

	public function get_all_penitip()
	{
		$this->db->where('jenis_pemilik','penitip');
		$this->db->order_by('id_pemilik','DESC');
		return $this->db->get('pemilik')->result();
	}

	public function get_produk_penitip($id_penitip, $id_outlet)
	{
		$this->db->select('*');
		$this->db->from('produk_outlet');
		$this->db->join('produk','produk_outlet.id_produk = produk.id_produk','left');
		$this->db->join('pemilik','produk.id_pemilik = pemilik.id_pemilik','left');
		$this->db->where('pemilik.id_pemilik',$id_penitip);
		$this->db->where('produk_outlet.id_outlet',$id_outlet);
		return $this->db->get()->result();
	}

	public function insert_titipan_toko($data)
	{
		$this->db->insert('titipan_toko',$data);
		return $this->db->insert_id(); 
	}

	public function insert_detail_titipan_toko($data)
	{
		return $this->db->insert('detail_titipan_toko',$data);
	}

	public function get_detail_titipan_toko_by_id_produk_dan_id_titipan_toko($id_produk,$id_titipan_toko)
	{
		$this->db->select('*');
		$this->db->from('detail_titipan_toko');
		$this->db->join('produk','produk.id_produk = detail_titipan_toko.id_produk','inner');
		$this->db->where('detail_titipan_toko.id_produk',$id_produk);
		$this->db->where('detail_titipan_toko.id_titipan_toko',$id_titipan_toko);
		return $this->db->get()->row();
	}

	public function update_detail_titipan_toko($id_detail_titipan_toko, $data)
	{
		$this->db->where('id_detail_titipan_toko',$id_detail_titipan_toko);
		return $this->db->update('detail_titipan_toko',$data);
	}

	public function get_titipan_toko_by_id_pemilik($id_pemilik)
	{
		$this->db->where('id_pemilik',$id_pemilik);
		return $this->db->get('titipan_toko')->result();
	}

	public function get_titipan_toko_belum_lunas_by_id_pemilik($id_pemilik)
	{
		$this->db->where('id_pemilik',$id_pemilik);
		$this->db->where('lunas','false');
		return $this->db->get('titipan_toko')->result();
	}

	public function get_detail_titipan_toko_by_id_titipan_toko($id_titipan_toko)
	{
		$this->db->select('*');
		$this->db->from('detail_titipan_toko');
		$this->db->join('produk','produk.id_produk = detail_titipan_toko.id_produk','inner');
		$this->db->where('id_titipan_toko',$id_titipan_toko);
		return $this->db->get()->result();
	}

	public function insert_bayar_titipan_toko($data)
	{
		return $this->db->insert('bayar_titipan_toko',$data);
	}

	public function update_titipan_toko($id_titipan_toko, $data)
	{
		$this->db->where('id_titipan_toko',$id_titipan_toko);
		return $this->db->update('titipan_toko',$data);
	}

	public function get_bayar_titipan_toko_by_id_pemilik($id_pemilik)
	{
		$this->db->where('id_pemilik',$id_pemilik);
		return $this->db->get('bayar_titipan_toko')->result();
	}

	public function get_titipan_toko_terakhir_by_id_pemilik($id_pemilik)
	{
		$this->db->order_by('waktu','DESC');
		$this->db->limit(1);
		$this->db->where('id_pemilik',$id_pemilik);
		return $this->db->get('titipan_toko')->row();
	}
}