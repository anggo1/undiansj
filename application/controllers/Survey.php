<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Survey extends CI_Controller
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

    public function submit_sebelumnya()
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

    public function title() {
        $data['surveys'] = $this->M_survey->get_all();
        $this->load->view('survey/survey_view', $data);
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

    //report survey

    public function report_survey() {
        $survey_id = $this->input->get('survey_id', TRUE);
        $gelombang_val = $this->input->get('gelombang', TRUE);

        // Fallback default jika parameter survey_id tidak ada
        if (!$survey_id) {
            $active_survey = $this->db->get_where('surveys', ['is_active' => 1], 1)->row();
            $survey_id = $active_survey ? $active_survey->id : 0;
            if (!$survey_id) {
                $latest_survey = $this->db->order_by('id', 'DESC')->get('surveys', 1)->row();
                $survey_id = $latest_survey ? $latest_survey->id : 0;
            }
        }

        $data['all_surveys'] = $this->M_survey->get_all();
        $data['selected_survey_id'] = $survey_id;
        $data['selected_gelombang'] = $gelombang_val;

        // 1. Deteksi ID pertanyaan berteks 'Gelombang'
        $gelombang_q_id = $this->M_survey->get_gelombang_question_id($survey_id);
        $data['list_gelombang'] = $this->M_survey->get_list_gelombang($gelombang_q_id);

        // 2. Ambil data list pertanyaan & tanggapan untuk data mentah tabel
        $data['questions'] = $this->M_survey->get_questions($survey_id);
        $data['respondents'] = $this->M_survey->get_raw_responses_filtered($survey_id, $gelombang_q_id, $gelombang_val);

        // 3. PANGGIL DATA GRAFIK BARU (Berdasarkan jawaban asli yang tersimpan di DB)
        $chart_data = $this->M_survey->get_answers_chart_dynamic($survey_id, $gelombang_q_id, $gelombang_val);
        
        $data['chart_title']  = $chart_data['title'];
        $data['chart_labels'] = $chart_data['labels'];
        $data['chart_values'] = $chart_data['values'];

        $this->load->view('admin/report_survey', $data);
    }



// Memproses Update Data dari Modal
public function update_question() {
    $id = $this->input->post('id');
    $type = $this->input->post('question_type');
    
    // Hilangkan opsi jika bertipe teks biasa
    $options = ($type == 'text' || $type == 'textarea') ? null : $this->input->post('options');

    $update_data = [
        'question_text' => $this->input->post('question_text'),
        'question_type' => $type,
        'is_required'   => $this->input->post('is_required') ? 1 : 0,
        'options'       => $options
    ];

    $this->db->where('id', $id);
    $this->db->update('survey_questions', $update_data); // Sesuaikan dengan nama tabel Anda

    $this->session->set_flashdata('success', 'Pertanyaan berhasil diperbarui.');
    redirect($_SERVER['HTTP_REFERER']); // Kembali ke halaman asal survey
}
    public function export_excel($survey_id = null)
    {
        // 1. Ambil Parameter ID Survey (dari segment URL atau parameter fungsi)
        if (!$survey_id) {
            $survey_id = $this->input->get('survey_id', TRUE);
        }
        
        // Fallback default jika ID tetap kosong, ambil kuesioner aktif pertama
        if (!$survey_id) {
            $active_survey = $this->db->get_where('surveys', ['is_active' => 1], 1)->row();
            $survey_id = $active_survey ? $active_survey->id : 0;
            if (!$survey_id) {
                $latest_survey = $this->db->order_by('id', 'DESC')->get('surveys', 1)->row();
                $survey_id = $latest_survey ? $latest_survey->id : 0;
            }
        }

        // 2. Ambil Parameter Filter Gelombang dari query string GET
        $gelombang_val = $this->input->get('gelombang', TRUE);

        // Ambil meta data profil kuesioner
        $survey_meta = $this->db->get_where('surveys', ['id' => $survey_id])->row();
        
        // Cari ID pertanyaan yang merupakan input penentu 'Gelombang'
        $gelombang_q_id = $this->M_survey->get_gelombang_question_id($survey_id);

        // Tarik susunan matriks pertanyaan dan data responden yang sudah TERFILTER
        $questions = $this->M_survey->get_questions($survey_id);
        $respondents = $this->M_survey->get_raw_responses_filtered($survey_id, $gelombang_q_id, $gelombang_val);

        // 3. Inisialisasi PhpSpreadsheet Core Engine
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Responden Survey');

        // Hitung batas total kolom horizontal (No + Jumlah Kuesioner + Waktu Input)
        $total_kolom_angka = 1 + count($questions) + 1;
        $kolom_terakhir = Coordinate::stringFromColumnIndex($total_kolom_angka);

        // 4. Render Judul Banner Laporan Utama (Baris ke-1)
        $sheet->mergeCells("A1:" . $kolom_terakhir . "1"); 
        $judul_text = 'LAPORAN HASIL RESPONDEN: ' . strtoupper($survey_meta ? $survey_meta->title : 'SURVEY EVALUASI');
        if (!empty($gelombang_val)) {
            $judul_text .= ' - ' . strtoupper($gelombang_val);
        }
        $sheet->setCellValue('A1', $judul_text);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1CC88A'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Sub-Header Info rekam unduhan (Baris ke-2)
        $sheet->mergeCells("A2:" . $kolom_terakhir . "2");
        $sheet->setCellValue('A2', 'Data dicetak otomatis pada: ' . date('d-M-Y H:i:s') . ' | Total Responden: ' . count($respondents) . ' Orang');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 5. Render Komponen Kepala Tabel / Header Grid Dinamis (Baris ke-4)
        $sheet->setCellValue('A4', 'No');
        
        $current_col = 2; // Index B
        foreach ($questions as $q) {
            $huruf = Coordinate::stringFromColumnIndex($current_col);
            $sheet->setCellValue($huruf . '4', $q['question_text']);
            $current_col++;
        }
        $huruf_akhir = Coordinate::stringFromColumnIndex($current_col);
        $sheet->setCellValue($huruf_akhir . '4', 'Waktu Pengisian');

        // Definisi Tampilan Header Resmi (SB Admin Success Theme)
        $styleHeader = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1CC88A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle("A4:".$kolom_terakhir."4")->applyFromArray($styleHeader);
        $sheet->getRowDimension('4')->setRowHeight(28);

        // 6. Pengisian Dataset Konten Responden per Baris secara Berurutan
        $baris = 5;
        $no = 1;
        foreach ($respondents as $r) {
            $sheet->setCellValue('A' . $baris, $no++);
            
            $current_col = 2;
            foreach ($questions as $q) {
                $huruf = Coordinate::stringFromColumnIndex($current_col);
                $jawaban = isset($r['answers'][$q['id']]) ? $r['answers'][$q['id']] : '-';
                
                // PROTEKSI DATA NIK: Cegah auto-format scientific / hilangnya angka 0 di depan
                if (stripos($q['question_text'], 'NIK') !== false || (strlen($jawaban) >= 10 && is_numeric($jawaban))) {
                    $sheet->setCellValueExplicit($huruf . $baris, $jawaban, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->getStyle($huruf . $baris)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                } else {
                    $sheet->setCellValue($huruf . $baris, $jawaban);
                }
                $current_col++;
            }
            
            // Kolom penanda waktu submits
            $huruf_akhir = Coordinate::stringFromColumnIndex($current_col);
            $sheet->setCellValue($huruf_akhir . $baris, date('d-m-Y H:i', strtotime($r['submitted_at'])));

            // Penataan garis tepi tipis dan perataan nomor
            $sheet->getStyle("A$baris:".$kolom_terakhir."$baris")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("A$baris")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($baris)->setRowHeight(20);
            
            $baris++;
        }

        // 7. Auto-Fit Lebar Kolom secara Proporsional mengikuti isi string
        for ($i = 1; $i <= $total_kolom_angka; $i++) {
            $k = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($k)->setAutoSize(true);
        }

        // 8. Stream output menuju ke berkas berkstensi .xlsx pada browser
        $nama_file = "Laporan_Survey_Terfilter_" . date('Ymd_His') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nama_file . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

}