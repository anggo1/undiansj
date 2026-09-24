<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_undian extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Contoh mengambil data hasil undian.
     * Kode ini disimulasikan memakai database dinamis.
     */
    public function get_all_pemenang() {
        // Dalam implementasi nyata, Anda bisa menggunakan query JOIN ke tabel tamu Anda
        $this->db->select('a.*, b.name, b.nik, b.department, b.is_won, c.item_name');
        $this->db->from('winners as a');
        $this->db->join('employees as b ', 'b.id = a.employee_id', 'left');
        $this->db->join('items as c', 'c.id = a.item_id', 'left');
        $this->db->group_by('b.nik');
        return $this->db->get('winners')->result();
        
    }
    /**
     * 1. Mengambil Ringkasan/Summary Hadiah berdasarkan database asli 'items' dan 'winners'
     * Menghitung jumlah pemenang dinamis per jenis hadiah beserta sisa stoknya
     */
    public function get_summary_hadiah() {
        // Menggunakan SELECT berdasarkan nama kolom tabel 'items' Anda
        $this->db->select('items.item_name, items.stock, COUNT(winners.id) as total_winners');
        $this->db->from('items');
        // Relasi JOIN ke tabel winners menggunakan item_id
        $this->db->join('winners', 'winners.item_id = items.id', 'left'); 
        $this->db->group_by('items.id');
        
        $query = $this->db->get();
        return $query->result_array(); // Mengembalikan array asosiatif untuk loop view $summary
    }

    /**
     * 2. Mengambil Detail Data Pemenang Undian Lengkap
     * Melakukan JOIN 3 tabel: winners, employees, dan items
     */
    public function get_detail_pemenang() {
        // Mengambil data pemenang lengkap sesuai kolom di database Anda
        $this->db->select('winners.id, winners.employee_id, items.item_name, employees.name, employees.nik, employees.department');
        $this->db->from('winners');
        // Hubungkan ke tabel employees berdasarkan id karyawan
        $this->db->join('employees', 'employees.id = winners.employee_id', 'inner');
        // Hubungkan ke tabel items berdasarkan id hadiah
        $this->db->join('items', 'items.id = winners.item_id', 'inner');
        $this->db->order_by('winners.id', 'DESC'); // Menampilkan yang baru menang di posisi paling atas
        
        $query = $this->db->get();
        return $query->result_array(); // Mengembalikan array asosiatif untuk loop view $winners
    }
}
