<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hadiah extends CI_Model {

    // Tentukan nama tabel di database Anda
    protected $table = 'items'; 

    public function __construct() {
        parent::__construct();
    }

    /**
     * Mengambil semua data hadiah beserta status total terundi
     */
    public function get_all_items() {
        // Contoh jika ada tabel relasi undian untuk menghitung hadiah yang sudah terpakai
        // Jika belum ada tabel undian, Anda bisa menghapus bagian SELECT & LEFT JOIN di bawah ini.
        $this->db->select('items.*, COUNT(undian.id) as total_terundi');
        $this->db->from($this->table);
        $this->db->join('undian', 'undian.item_id = items.id', 'left');
        $this->db->group_by('items.id');
        $this->db->order_by('items.id', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Memasukkan data hadiah baru
     */
    public function insert_item($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id(); // Mengembalikan ID yang baru dibuat
    }

    /**
     * Memperbarui data hadiah berdasarkan ID
     */
    public function update_item($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Menghapus hadiah dengan proteksi keamanan (cek status terundi)
     */
    public function delete_item($id) {
        // Keamanan Tambahan: Pastikan hadiah belum pernah diundi sebelum dihapus
        $this->db->where('item_id', $id);
        $terundi = $this->db->count_all_results('winners'); // Misal ada tabel 'winners' yang menyimpan data pemenang

        if ($terundi > 0) {
            return false; // Gagal menghapus karena hadiah sudah digunakan sistem
        }

        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}
