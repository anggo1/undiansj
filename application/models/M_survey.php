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

    /**
     * 1. Mengambil daftar semua pertanyaan berdasarkan survey_id pilihan
     */
    public function get_questions($survey_id = null) {
        if ($survey_id) {
            $this->db->where('survey_id', $survey_id);
        }
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('survey_questions')->result_array();
    }

    /**
     * 2. Mengambil ringkasan grafik terisolasi pada satu survey_id tertentu
     */
        /**
     * PERBAIKAN TOTAL: Mengambil jawaban yang unik langsung dari tabel survey_answers 
     * berdasarkan pertanyaan kuesioner utama (Pilihan/Radio)
     */
    public function get_answers_chart_dynamic($survey_id, $gelombang_q_id = null, $gelombang_val = null) {
        // 1. Cari pertanyaan berjenis pilihan (radio/select) pertama yang BUKAN gelombang
        $this->db->where_in('question_type', ['radio', 'select']);
        if ($gelombang_q_id) {
            $this->db->where('id !=', $gelombang_q_id);
        }
        $target_q = $this->db->get_where('survey_questions', ['survey_id' => $survey_id], 1)->row();

        // Fallback jika tidak ada pertanyaan lain, gunakan pertanyaan gelombang itu sendiri sebagai grafik
        if (!$target_q && $gelombang_q_id) {
            $target_q = $this->db->get_where('survey_questions', ['id' => $gelombang_q_id])->row();
        }

        if (!$target_q) {
            return ['title' => 'Grafik Jawaban Kosong', 'labels' => [], 'values' => []];
        }

        // 2. Ambil semua teks jawaban unik yang SUDAH PERNAH DIINPUT oleh responden
        $this->db->select('answer_text');
        $this->db->from('survey_answers');
        $this->db->where('question_id', $target_q->id);
        $this->db->where('answer_text IS NOT NULL');
        $this->db->where('answer_text !=', '');
        $this->db->group_by('answer_text');
        $distinct_answers = $this->db->get()->result_array();

        $chart_labels = [];
        $chart_values = [];

        // 3. Hitung jumlah responden untuk masing-masing jawaban unik tersebut (plus filter gelombang)
        foreach ($distinct_answers as $row) {
            $jawaban = $row['answer_text'];
            $chart_labels[] = $jawaban;

            $this->db->from('survey_answers a');
            $this->db->where('a.question_id', $target_q->id);
            $this->db->where('a.answer_text', $jawaban);
            
            // Terapkan join filter gelombang jika dipilih
            if (!empty($gelombang_q_id) && !empty($gelombang_val)) {
                $this->db->join('survey_responses r', 'r.id = a.response_id');
                $this->db->join('survey_answers fa', 'fa.response_id = r.id');
                $this->db->where('fa.question_id', $gelombang_q_id);
                $this->db->where('fa.answer_text', $gelombang_val);
            }

            $chart_values[] = $this->db->count_all_results();
        }

        return [
            'title'  => "Grafik Hasil Jawaban: " . $target_q->question_text,
            'labels' => $chart_labels,
            'values' => $chart_values
        ];
    }


    /**
     * 3. Mengambil Data Mentah Terfilter Akurat per ID Survey
     */
    public function get_raw_responses($survey_id = null) {
        if ($survey_id) {
            $this->db->where('survey_id', $survey_id);
        }
        $this->db->order_by('id', 'DESC');
        $responses = $this->db->get('survey_responses')->result_array();

        $result = [];
        foreach ($responses as $res) {
            $this->db->where('response_id', $res['id']);
            $answers = $this->db->get('survey_answers')->result_array();

            $mapped_answers = [];
            foreach ($answers as $ans) {
                $mapped_answers[$ans['question_id']] = $ans['answer_text'];
            }

            $result[] = [
                'response_id'  => $res['id'],
                'submitted_at' => $res['submitted_at'],
                'answers'      => $mapped_answers
            ];
        }
        return $result;
    }

    /**
     * 4. Menghitung spesifik jumlah jawaban per opsi pilihan ganda
     * Dipanggil oleh Controller untuk menyusun data chart dinamis
     */
    public function count_answer_by_option($question_id, $option_text) {
        return $this->db->where([
                            'question_id' => $question_id, 
                            'answer_text' => $option_text
                        ])
                        ->from('survey_answers')
                        ->count_all_results();
    }

    /**
     * 5. BARU: Mendeteksi ID Pertanyaan yang mengandung kata kunci 'Gelombang' secara otomatis
     */
    public function get_gelombang_question_id($survey_id) {
        if (!$survey_id) return null;
        
        $row = $this->db->like('question_text', 'Gelombang')
                        ->get_where('survey_questions', ['survey_id' => $survey_id], 1)
                        ->row();
        return $row ? $row->id : null;
    }

    /**
     * 6. BARU: Mengambil seluruh variasi opsi Gelombang unik yang pernah diinput responden
     */
    public function get_list_gelombang($question_id) {
        if (!$question_id) return [];
        
        $this->db->select('answer_text');
        $this->db->from('survey_answers');
        $this->db->where('question_id', $question_id);
        $this->db->where('answer_text IS NOT NULL');
        $this->db->where('answer_text !=', '');
        $this->db->group_by('answer_text');
        return $this->db->get()->result_array();
    }

    /**
     * 7. BARU: Mengambil Matriks Data Mentah yang telah difilter berdasarkan Gelombang
     */
    public function get_raw_responses_filtered($survey_id, $gelombang_q_id = null, $gelombang_val = null) {
        $this->db->select('r.id, r.submitted_at');
        $this->db->from('survey_responses r');
        $this->db->where('r.survey_id', $survey_id);
        
        // Gabungkan tabel jika filter gelombang aktif
        if (!empty($gelombang_q_id) && !empty($gelombang_val)) {
            $this->db->join('survey_answers filter_a', 'filter_a.response_id = r.id');
            $this->db->where('filter_a.question_id', $gelombang_q_id);
            $this->db->where('filter_a.answer_text', $gelombang_val);
        }
        
        $this->db->order_by('r.id', 'DESC');
        $responses = $this->db->get()->result_array();

        $result = [];
        foreach ($responses as $res) {
            $this->db->where('response_id', $res['id']);
            $answers = $this->db->get('survey_answers')->result_array();

            $mapped_answers = [];
            foreach ($answers as $ans) {
                $mapped_answers[$ans['question_id']] = $ans['answer_text'];
            }

            $result[] = [
                'response_id'  => $res['id'],
                'submitted_at' => $res['submitted_at'],
                'answers'      => $mapped_answers
            ];
        }
        return $result;
    }

    /**
     * 8. BARU: Menghitung total jawaban pada suatu pertanyaan, terfilter berdasarkan Gelombang tertentu
     */
    public function count_answer_with_filter($target_q_id, $option_text, $gelombang_q_id = null, $gelombang_val = null) {
        $this->db->from('survey_answers a');
        $this->db->where('a.question_id', $target_q_id);
        $this->db->where('a.answer_text', $option_text);
        
        // Kondisional Join filter gelombang apabila parameter dipilih admin
        if (!empty($gelombang_q_id) && !empty($gelombang_val)) {
            $this->db->join('survey_responses r', 'r.id = a.response_id');
            $this->db->join('survey_answers fa', 'fa.response_id = r.id');
            $this->db->where('fa.question_id', $gelombang_q_id);
            $this->db->where('fa.answer_text', $gelombang_val);
        }
        
        return $this->db->count_all_results();
    }
}
