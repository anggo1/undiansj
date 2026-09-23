<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Survey_model');
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

        foreach ($questions as $question) {
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
        $answers = $this->input->post('answer', TRUE) ?: array();
        foreach ($questions as $question) {
            $answer = isset($answers[$question->id]) ? $answers[$question->id] : '';
            if (is_array($answer)) $answer = implode(' | ', $answer);
            $this->Survey_model->save_answer($response_id, $question->id, trim($answer));
        }
        $this->load->view('survey/success', array('survey' => $survey));
    }

    public function admin()
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
