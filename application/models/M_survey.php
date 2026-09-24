<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_survey extends CI_Model {

    protected $table = 'surveys';

    public function get_all() {
        return $this->db->order_by('id', 'DESC')->get($this->table)->result();
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }
//report survey
    /**
     * 1. Mengambil daftar semua pertanyaan survey untuk dijadikan Header Tabel secara dinamis
     */
    public function get_questions($survey_id = 1) {
        $this->db->where('survey_id', $survey_id);
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('survey_questions')->result_array();
    }

    /**
     * 2. Mengambil data ringkasan untuk grafik (misal menghitung total pengisi per Gelombang)
     */
    public function get_chart_summary() {
        // Query ini menghitung jawaban dari pertanyaan tipe Pilihan/Radio secara otomatis
        // Asumsi: Kita mencari persentase pilihan dari pertanyaan yang bertuliskan 'Gelombang'
        $this->db->select('a.answer_text as label, COUNT(a.id) as count');
        $this->db->from('survey_answers a');
        $this->db->join('survey_questions q', 'q.id = a.question_id');
        $this->db->where('q.question_type', 'radio'); // Mengambil tipe radio/pilihan untuk grafik
        $this->db->group_by('a.answer_text');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * 3. Mengambil Data Mentah Responden & Jawaban (Dinamis & Fleksibel)
     */
    public function get_raw_responses($survey_id = 1) {
        // Ambil semua response survey
        $this->db->where('survey_id', $survey_id);
        $this->db->order_by('id', 'DESC');
        $responses = $this->db->get('survey_responses')->result_array();

        $result = [];
        foreach ($responses as $res) {
            // Ambil semua jawaban untuk response_id saat ini
            $this->db->where('response_id', $res['id']);
            $answers = $this->db->get('survey_answers')->result_array();

            // Petakan jawaban ke dalam array key-value [question_id => answer_text]
            $mapped_answers = [];
            foreach ($answers as $ans) {
                $mapped_answers[$ans['question_id']] = $ans['answer_text'];
            }

            $result[] = [
                'response_id'  => $res['id'],
                'submitted_at' => $res['submitted_at'],
                'answers'      => $mapped_answers // Berisi kumpulan jawaban dinamis
            ];
        }
        return $result;
    }
}
