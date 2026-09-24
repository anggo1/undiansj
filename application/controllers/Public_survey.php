<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Public_survey extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Survey_model');
        $this->load->model('M_survey');
        $this->load->library(array('form_validation', 'session'));
        $this->load->helper(array('url', 'form'));
        
    }

    public function index()
    {
        $data['survey'] = $this->Survey_model->get_active_survey();
        if (!$data['survey']) show_404();
        $data['questions'] = $this->Survey_model->get_questions($data['survey']->id);
        $this->load->view('survey/form', $data);
    }
public function submit()
{
    $survey = $this->Survey_model->get_active_survey();
    if (!$survey) show_404();
    $questions = $this->Survey_model->get_questions($survey->id);
    $answers = $this->input->post('answer', TRUE) ?: array();

    // --- PROTEKSI BACKEND: Validasi NIK Ganda ---
    foreach ($questions as $question) {
        if (strpos(strtolower($question->question_text), 'nik') !== false) {
            $nik_value = isset($answers[$question->id]) ? trim($answers[$question->id]) : '';
            if (!empty($nik_value)) {
                // Silakan sesuaikan nama tabel & kolom di model Anda jika berbeda
                $this->db->where('question_id', $question->id);
                $this->db->where('answer_text', $nik_value); // Asumsi kolom jawaban bernama answer_text
                $is_exist = $this->db->get('survey_answers')->num_rows(); // Ganti survey_answers dengan nama tabel Anda

                if ($is_exist > 0) {
                    $this->form_validation->set_rules('answer['.$question->id.']', $question->question_text, 'callback_check_failed',
                        array('check_failed' => 'Maaf, NIK <strong>' . html_escape($nik_value) . '</strong> sudah pernah mengisi survei ini.'));
                }
            }
        }

        // Validasi Wajib Isi bawaan Anda
        if ($question->is_required) {
            $this->form_validation->set_rules('answer['.$question->id.']', $question->question_text, 'required',
                array('required' => 'Pertanyaan <strong>{field}</strong> wajib diisi.'));
        }
    }

    if ($this->form_validation->run() === FALSE) {
        $data = array('survey' => $survey, 'questions' => $questions);
        $this->load->view('survey/form', $data);
        return;
    }

    $response_id = $this->Survey_model->create_response($survey->id);
    foreach ($questions as $question) {
        $answer = isset($answers[$question->id]) ? $answers[$question->id] : '';
        if (is_array($answer)) $answer = implode(' | ', $answer);
        $this->Survey_model->save_answer($response_id, $question->id, trim($answer));
    }
    $this->load->view('survey/success', array('survey' => $survey));
}

// --- FUNGSI ENDPOINT UNTUK AJAX REAL-TIME ---
public function check_nik_ajax()
{
    $question_id = $this->input->post('question_id', TRUE);
    $nik_value = trim($this->input->post('nik', TRUE));

    if (empty($question_id) || empty($nik_value)) {
        echo json_encode(array('status' => 'error', 'message' => 'Data tidak lengkap.'));
        return;
    }

    // Ganti 'answers_table' & 'answer_text' sesuai dengan struktur database Anda
    $this->db->where('question_id', $question_id);
    $this->db->where('answer_text', $nik_value);
    $query = $this->db->get('survey_answers');

    if ($query->num_rows() > 0) {
        echo json_encode(array('status' => 'exists', 'message' => 'NIK sudah terdaftar!'));
    } else {
        echo json_encode(array('status' => 'available', 'message' => 'NIK dapat digunakan.'));
    }
}

}