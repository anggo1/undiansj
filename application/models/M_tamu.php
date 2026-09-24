<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_tamu extends CI_Model {

    // Definisikan nama tabel di database agar mudah dikelola
    protected $table = 'employees';

    public function __construct() {
        parent::__construct();
        // Memastikan library database sudah ter-load
        $this->load->database(); 
    }

    /**
     * Mengambil semua data tamu dari database
     * Digunakan untuk menampilkan data di tabel VIEW dan untuk EXPORT ke Excel
     */
    public function get_all_tamu() {
        $query = $this->db->get($this->table);
        return $query->result(); // Mengembalikan data dalam bentuk array of objects ($tamu->name)
    }

    /**
     * Memasukkan data baru (Bisa digunakan untuk proses IMPORT data kelak)
     */
    public function insert_tamu($data) {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Menghapus seluruh data dari tabel tamu
     * Terhubung dengan tombol "Reset Semua Data" di view Anda (excel/reset)
     */
    public function reset_data() {
        return $this->db->empty_table($this->table); 
    }
}
