<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hadiah extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_hadiah');
    }

    // Aksi Tambah Item
    public function add_item() {
        // Validasi input sederhana
        $name  = $this->input->post('item_name', TRUE);
        $color = $this->input->post('color', TRUE);
        $stock = $this->input->post('stock', TRUE);

        if (!$name || !$color || $stock === NULL) {
            echo json_encode(['status' => 'error', 'message' => 'Semua kolom wajib diisi!']);
            return;
        }

        $data_insert = [
            'item_name' => $name,
            'color'     => $color,
            'stock'     => (int)$stock
        ];

        // Jalankan query simpan database
        // $insert_id = $this->M_hadiah->insert($data_insert);
        $insert_id = rand(1, 100); // Simulasi ID untuk keperluan testing

        if ($insert_id) {
            echo json_encode([
                'status'    => 'success',
                'item_id'   => $insert_id,
                'item_name' => $name,
                'color'     => $color,
                'stock'     => $stock
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke database.']);
        }
    }

    // Aksi Edit Item
    public function edit_item() {
        $id    = $this->input->post('item_id', TRUE);
        $name  = $this->input->post('item_name', TRUE);
        $color = $this->input->post('color', TRUE);
        $stock = $this->input->post('stock', TRUE);

        $data_update = [
            'item_name' => $name,
            'color'     => $color,
            'stock'     => (int)$stock
        ];

        // Jalankan query update berdasarkan ID
        // $update = $this->M_hadiah->update($id, $data_update);
        $update = true; // Simulasi sukses

        if ($update) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui database.']);
        }
    }

    // Aksi Hapus Item
    public function delete_item() {
        $id = $this->input->post('item_id', TRUE);

        // Jalankan query delete berdasarkan ID
        // $delete = $this->M_hadiah->delete($id);
        $delete = true; // Simulasi sukses

        if ($delete) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus dari database.']);
        }
    }
}