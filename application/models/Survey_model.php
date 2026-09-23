<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Survey_model extends CI_Model
{
    public function get_active_survey()
    {
        return $this->db->where('is_active', 1)->order_by('id', 'ASC')->get('surveys')->row();
    }
    public function get_questions($survey_id)
    {
        return $this->db->where('survey_id', $survey_id)->order_by('sort_order', 'ASC')->get('survey_questions')->result();
    }
    public function next_sort_order($survey_id)
    {
        $row = $this->db->select_max('sort_order')->where('survey_id', $survey_id)->get('survey_questions')->row();
        return ((int) $row->sort_order) + 1;
    }
    public function add_question($data) { return $this->db->insert('survey_questions', $data); }
    public function delete_question($id) { return $this->db->delete('survey_questions', array('id' => $id)); }
    public function create_response($survey_id)
    {
        $this->db->insert('survey_responses', array('survey_id' => $survey_id, 'submitted_at' => date('Y-m-d H:i:s')));
        return $this->db->insert_id();
    }
    public function save_answer($response_id, $question_id, $answer)
    {
        return $this->db->insert('survey_answers', array('response_id' => $response_id, 'question_id' => $question_id, 'answer_text' => $answer));
    }
    public function response_count($survey_id)
    {
        return $this->db->where('survey_id', $survey_id)->count_all_results('survey_responses');
    }
}
