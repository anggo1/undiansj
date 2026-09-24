<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_survey');
    }

    public function index() {
        $data['surveys'] = $this->M_survey->get_all();
        $this->load->view('survey_view', $data);
    }

    public function add() {
        $title = $this->input->post('title', TRUE);
        $desc  = $this->input->post('description', TRUE);
        $active = $this->input->post('is_active', TRUE);

        if (!$title) {
            echo json_encode(['status' => 'error', 'message' => 'Judul Survey wajib diisi.']);
            return;
        }

        $data_insert = ['title' => $title, 'description' => $desc, 'is_active' => (int)$active];
        $insert_id = $this->M_survey->insert($data_insert);

        if ($insert_id) {
            echo json_encode(['status' => 'success', 'id' => $insert_id, 'title' => $title, 'description' => $desc, 'is_active' => $active]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke database.']);
        }
    }

    public function edit() {
        $id     = $this->input->post('id', TRUE);
        $title  = $this->input->post('title', TRUE);
        $desc   = $this->input->post('description', TRUE);
        $active = $this->input->post('is_active', TRUE);

        $data_update = ['title' => $title, 'description' => $desc, 'is_active' => (int)$active];
        
        if ($this->M_survey->update($id, $data_update)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data.']);
        }
    }

    public function delete() {
        $id = $this->input->post('id', TRUE);
        if ($this->M_survey->delete($id)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data.']);
        }
    }
}