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
        $data['questions'] = $this->M_survey->get_questions();
        $data['chart_data'] = $this->M_survey->get_chart_summary();
        $data['respondents'] = $this->M_survey->get_raw_responses();
        
        $this->load->view('admin/report_survey', $data);
    }

    public function export_excel() {
        $questions = $this->M_survey->get_questions();
        $respondents = $this->M_survey->get_raw_responses();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Hasil Survey Dinamis');

        // Hitung total kolom yang dibutuhkan (No + Jumlah Pertanyaan + Waktu Mengisi)
        $total_kolom_angka = 1 + count($questions) + 1;
        $kolom_terakhir = Coordinate::stringFromColumnIndex($total_kolom_angka);

       // 1. Header Judul Atas
$total_kolom_angka = 1 + count($questions) + 1;
$kolom_terakhir = Coordinate::stringFromColumnIndex($total_kolom_angka);

// PERBAIKAN: Tambahkan angka 1 setelah titik "." agar formatnya menjadi "A1:F1" bukan "A1:F"
$sheet->mergeCells("A1:" . $kolom_terakhir . "1"); 

$sheet->setCellValue('A1', 'LAPORAN HASIL RESPONDEN SURVEY (FORMAT DINAMIS)');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1CC88A'));
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // 2. Set Header Tabel Dinamis
        $sheet->setCellValue('A3', 'No');
        
        $current_col = 2; // Kolom ke-2 adalah B
        foreach ($questions as $q) {
            $huruf = Coordinate::stringFromColumnIndex($current_col);
            $sheet->setCellValue($huruf . '3', $q['question_text']);
            $current_col++;
        }
        // Kolom waktu submit diletakkan paling akhir
        $huruf_akhir = Coordinate::stringFromColumnIndex($current_col);
        $sheet->setCellValue($huruf_akhir . '3', 'Waktu Pengisian');

        // Styling Header Tabel (Warna Hijau Sukses SB Admin)
        $styleHeader = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1CC88A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle("A3:".$kolom_terakhir."3")->applyFromArray($styleHeader);
        $sheet->getRowDimension('3')->setRowHeight(26);

        // 3. Mengisi Baris Data Dinamis
        $baris = 4;
        $no = 1;
        foreach ($respondents as $r) {
            $sheet->setCellValue('A' . $baris, $no++);
            
            $current_col = 2;
            foreach ($questions as $q) {
                $huruf = Coordinate::stringFromColumnIndex($current_col);
                $jawaban = isset($r['answers'][$q['id']]) ? $r['answers'][$q['id']] : '-';
                
                // Jika jawaban berupa NIK (angka panjang), set sebagai text agar angka 0 di depan aman
                if ($q['id'] == 14 || stripos($q['question_text'], 'NIK') !== false) {
                    $sheet->setCellValueExplicit($huruf . $baris, $jawaban, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->getStyle($huruf . $baris)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                } else {
                    $sheet->setCellValue($huruf . $baris, $jawaban);
                }
                $current_col++;
            }
            
            // Tulis waktu submit di kolom paling belakang
            $huruf_akhir = Coordinate::stringFromColumnIndex($current_col);
            $sheet->setCellValue($huruf_akhir . $baris, $r['submitted_at']);

            // Set styling border per baris data
            $sheet->getStyle("A$baris:".$kolom_terakhir."$baris")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("A$baris")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $baris++;
        }

        // 4. Auto Size Lebar Kolom secara Otomatis
        for ($i = 1; $i <= $total_kolom_angka; $i++) {
            $k = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($k)->setAutoSize(true);
        }

        // 5. Transfer ke Browser untuk download (.xlsx)
        $nama_file = "Laporan_Survey_Dinamis_" . date('Ymd_His') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nama_file . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    public function get_question_json($id) {
    // Ambil data berdasarkan ID
    $question = $this->db->get_where('survey_questions', ['id' => $id])->row();
    
    // Set header agar dibaca sebagai JSON murni oleh jQuery
    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($question));
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
}