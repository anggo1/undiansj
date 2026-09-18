<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Undian extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load database otomatis di constructor
        $this->load->database();
        $this->load->helper('url');
    }

    public function index() {
        // 1. Ambil karyawan yang belum pernah menang undian
        $data['employees'] = $this->db->get_where('employees', array('is_won' => 0))->result_array();
                                
        // 2. Ambil semua hadiah lengkap dengan rekapitulasi nama pemenangnya
        $this->db->select('i.*, 
                           (SELECT COUNT(*) FROM winners w WHERE w.item_id = i.id) as total_terundi,
                           GROUP_CONCAT(e.name SEPARATOR ", ") as nama_pemenang');
        $this->db->from('items i');
        $this->db->join('winners w', 'w.item_id = i.id', 'left');
        $this->db->join('employees e', 'e.id = w.employee_id', 'left');
        $this->db->group_by('i.id');
        $data['items'] = $this->db->get()->result_array();

        $this->load->view('undian_view', $data);
    }

    public function draw() {
        // Ambil semua karyawan aktif yang belum menang
        $employees = $this->db->get_where('employees', array('is_won' => 0))->result_array();
        
        // Ambil ID hadiah dari request POST frontend (Sintaks CI3)
        $item_id = $this->input->post('item_id'); 
        $item = $this->db->get_where('items', array('id' => $item_id))->row_array();

        if (empty($employees)) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(array('status' => 'error', 'message' => 'Semua karyawan sudah mendapatkan undian!')));
        }
        
        if (!$item || $item['stock'] <= 0) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(array('status' => 'error', 'message' => 'Stok hadiah ini sudah habis / sudah ada pemenangnya!')));
        }

        // Jalankan Database Transaction CI3 agar proses tulis data aman
        $this->db->trans_start();

        // Mengacak index pemenang dari array karyawan
        $winner_index = array_rand($employees);
        $winner = $employees[$winner_index];

        // A. Kunci status karyawan agar tidak menang lagi
        $this->db->where('id', $winner['id']);
        $this->db->update('employees', array('is_won' => 1));
        
        // B. Potong stok barang hadiah
        $this->db->where('id', $item['id']);
        $this->db->set('stock', 'stock-1', FALSE);
        $this->db->update('items');

        // C. Simpan log riwayat pemenang ke tabel transaksi
        $this->db->insert('winners', array(
            'employee_id' => $winner['id'],
            'item_id'     => $item['id']
        ));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(array('status' => 'error', 'message' => 'Gagal memproses undian sistem.')));
        }

        // Return respon JSON gaya CI3
        return $this->output->set_content_type('application/json')
                            ->set_output(json_encode(array(
                                'status'       => 'success',
                                'winner_index' => $winner_index,
                                'winner_name'  => $winner['name'],
                                'winner_dept'  => $winner['department'],
                                'item_name'    => $item['item_name']
                            )));
    }
}
