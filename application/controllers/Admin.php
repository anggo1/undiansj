<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
        $this->load->model('Survey_model');
	}

	public function index()
	{
		$data['anggota'] = $this->db->get('tb_anggota')->result();
		$this->load->view('admin/index',$data);
	}

	public function tamu()
	{
		$data['tamu'] = $this->db->get('employees')->result();
		$this->load->view('admin/tamu',$data);
	}
	public function items()
	{
		$data['items'] = $this->db->get('items')->result();
		$this->load->view('admin/items_view',$data);
	}
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
                'id'   => $insert_id,
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



	public function user()
	{
		$data['user'] = $this->db->get('tb_pengguna')->result();
		$this->load->view('admin/user',$data);
	}


	//report
	public function winners_report()
{
    // Ringkasan jumlah pemenang untuk setiap hadiah.
    $this->db->select('i.id, i.item_name, i.stock, COUNT(w.employee_id) AS total_winners', FALSE);
    $this->db->from('items i');
    $this->db->join('winners w', 'w.item_id = i.id', 'left');
    $this->db->group_by('i.id, i.item_name, i.stock');
    $this->db->order_by('i.item_name', 'ASC');
    $data['summary'] = $this->db->get()->result_array();

    // Detail semua pemenang.
    $this->db->select('w.item_id, e.id AS employee_id, e.nik, e.name, e.department, i.item_name');
    $this->db->from('winners w');
    $this->db->join('employees e', 'e.id = w.employee_id');
    $this->db->join('items i', 'i.id = w.item_id');
    $this->db->order_by('i.item_name', 'ASC');
    $this->db->order_by('e.name', 'ASC');
    $data['winners'] = $this->db->get()->result_array();

    $this->load->view('admin/winners_report_view', $data);
}



    public function results($survey_id) {
        $data['survey'] = $this->Survey_model->get_survey($survey_id);
        $data['summary'] = $this->Survey_model->result_summary($survey_id);
        $data['total'] = $this->Survey_model->total_responses($survey_id);
        if (!$data['survey']) show_404();
        $this->load->view('admin/results', $data);
    }
     public function Pertanyaan()
    {
        $data['survey'] = $this->Survey_model->get_active_survey();
        if (!$data['survey']) show_404();
        $data['questions'] = $this->Survey_model->get_questions($data['survey']->id);
        $data['responses'] = $this->Survey_model->response_count($data['survey']->id);
        $this->load->view('survey/admin', $data);
    }

    public function add_question()
    {
        $survey = $this->Survey_model->get_active_survey();
        if (!$survey) show_404();
        $this->form_validation->set_rules('question_text', 'Pertanyaan', 'trim|required');
        $this->form_validation->set_rules('question_type', 'Tipe jawaban', 'required|in_list[text,textarea,radio,checkbox,select]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('survey/admin');
        }
        $type = $this->input->post('question_type', TRUE);
        $options = trim($this->input->post('options', TRUE));
        if (in_array($type, array('radio', 'checkbox', 'select')) && $options === '') {
            $this->session->set_flashdata('error', 'Pilihan jawaban wajib diisi untuk tipe pertanyaan ini.');
            redirect('survey/admin');
        }
        $this->Survey_model->add_question(array(
            'survey_id' => $survey->id,
            'question_text' => $this->input->post('question_text', TRUE),
            'question_type' => $type,
            'options' => $options,
            'is_required' => $this->input->post('is_required') ? 1 : 0,
            'sort_order' => $this->Survey_model->next_sort_order($survey->id)
        ));
        $this->session->set_flashdata('success', 'Pertanyaan berhasil ditambahkan.');
        redirect('survey/admin');
    }

    public function delete_question($id)
    {
        $this->Survey_model->delete_question((int) $id);
        $this->session->set_flashdata('success', 'Pertanyaan dihapus.');
        redirect('survey/admin');
    }
}
