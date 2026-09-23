<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey_model extends CI_Model {
    public function get_survey($id) {
        return $this->db->where('id', (int)$id)->get('surveys')->row();
    }

    public function get_questions() {
        return $this->db->where('survey_id !=0')->order_by('sort_order','ASC')->order_by('id','ASC')->get('questions')->result();
    }

    public function save_response($survey_id, $questions) {
        $this->db->trans_start();
        $this->db->insert('responses', [
            'survey_id' => (int)$survey_id,
            'respondent_name' => $this->input->post('respondent_name', TRUE)
        ]);
        $response_id = $this->db->insert_id();

        foreach ($questions as $q) {
            $key = 'q_'.$q->id;
            $value = $this->input->post($key);
            if (is_array($value)) $value = implode(', ', $value);
            $this->db->insert('answers', [
                'response_id' => $response_id,
                'question_id' => $q->id,
                'answer_text' => is_null($value) ? '' : trim($value)
            ]);
        }
        $this->db->trans_complete();
        return $response_id;
    }

    public function save_question($survey_id) {
        $options = $this->input->post('options', TRUE);
        return $this->db->insert('questions', [
            'survey_id' => (int)$survey_id,
            'question_text' => $this->input->post('question_text', TRUE),
            'question_type' => $this->input->post('question_type', TRUE),
            'options' => $options,
            'is_required' => $this->input->post('is_required') ? 1 : 0,
            'sort_order' => (int)$this->input->post('sort_order')
        ]);
    }

    public function delete_question($id) {
        return $this->db->where('id', (int)$id)->delete('questions');
    }

    public function result_summary($survey_id) {
        $questions = $this->get_questions($survey_id);
        $result = [];
        foreach ($questions as $q) {
            $answers = $this->db->select('answer_text')->where('question_id', $q->id)->get('answers')->result();
            $counts = [];
            foreach ($answers as $a) {
                $values = ($q->question_type === 'checkbox') ? preg_split('/,\s*/', $a->answer_text, -1, PREG_SPLIT_NO_EMPTY) : [$a->answer_text];
                foreach ($values as $v) {
                    $v = trim($v);
                    if ($v === '') continue;
                    if (!isset($counts[$v])) $counts[$v] = 0;
                    $counts[$v]++;
                }
            }
            arsort($counts);
            $result[] = [
                'id' => $q->id,
                'question' => $q->question_text,
                'type' => $q->question_type,
                'labels' => array_keys($counts),
                'values' => array_values($counts)
            ];
        }
        return $result;
    }

    public function total_responses($survey_id) {
        return $this->db->where('survey_id', (int)$survey_id)->count_all_results('responses');
    }
}
