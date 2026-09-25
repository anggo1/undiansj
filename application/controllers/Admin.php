<?php
defined('BASEPATH') OR exit('No direct script access allowed');


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
class Admin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
        $this->load->model('Survey_model');
        $this->load->model('M_tamu'); 
        $this->load->model('M_undian');
        $this->load->model('M_hadiah');
        $this->load->library('upload');
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
        $insert_id = $this->M_hadiah->insert_item($data_insert);
        //$insert_id = rand(1, 100); // Simulasi ID untuk keperluan testing

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
         $update = $this->M_hadiah->update_item($id, $data_update);
        //$update = true; // Simulasi sukses

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
         $delete = $this->M_hadiah->delete_item($id);
        //$delete = true; // Simulasi sukses

        if ($delete) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ada Data Pemenang untuk hadiah ini.']);
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

    $this->load->view('admin/winners_report', $data);
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

    public function export() {
        // 1. Ambil data tamu dari database via Model
        // Sesuai dengan struktur loop foreach ($tamu) di view Anda
        $tamu = $this->M_tamu->get_all_tamu(); 

        // 2. Inisialisasi PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 3. Buat Header Tabel Excel
        $sheet->setCellValue('A1', 'No Undian');
        $sheet->setCellValue('B1', 'NIK');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Departemen');

        // 4. Masukkan Data Looping (Mulai dari baris ke-2)
        $baris = 2;
        $no = 1;
        foreach ($tamu as $t) {
            $sheet->setCellValue('A' . $baris, $no++);

            // Format NIK sebagai teks eksplisit agar angka nol di depan tetap aman (misal: 000123)
            // Sesuai dengan format di view Anda: 000 + NIK
            $nik_lengkap = $t->nik;
            $sheet->setCellValueExplicit('B' . $baris, $nik_lengkap, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $baris, $t->name);
            $sheet->setCellValue('D' . $baris, $t->department);

            $baris++;
        }

        // 5. Otomatisasi Ukuran Kolom (Optional, agar rapi dan tidak terpotong)
        foreach (range('A', 'D') as $kolom) {
            $sheet->getColumnDimension($kolom)->setAutoSize(true);
        }

        // 6. Set Header Browser untuk download file .xlsx
        $nama_file = "Data_Tamu_" . date('Ymd_His') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nama_file . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1'); // Diperlukan untuk stabilitas IE9
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Tanggal lampau
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        // 7. Proses Tulis dan Download ke Output Browser
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    //hasil undian

    public function export_excel() {
        $winners = $this->M_undian->get_detail_pemenang();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Daftar Pemenang');

        // 1. Judul Laporan Atas
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'LAPORAN DAFTAR PEMENANG HADIAH UNDIAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('4E73DF'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 2. Set Header Tabel Excel
        $headers = ['No', 'No Undian', 'Nama Hadiah', 'Nama Pemenang', 'NIK', 'Departemen'];
        $kolomHuruf = ['A', 'B', 'C', 'D', 'E', 'F'];
        
        foreach ($headers as $index => $title) {
            $sheet->setCellValue($kolomHuruf[$index] . '3', $title);
        }

        // Kustomisasi Gaya Header Tabel (Tema Biru SB Admin 2)
        $styleHeader = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4E73DF']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A3:F3')->applyFromArray($styleHeader);
        $sheet->getRowDimension('4')->setRowHeight(25);

        // 3. Memasukkan Data Looping
        $baris = 4;
        $no = 1;
        foreach ($winners as $winner) {
            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $winner['employee_id']); // No Undian
            $sheet->setCellValue('C' . $baris, $winner['item_name']);
            $sheet->setCellValue('D' . $baris, $winner['nik']);
            
            // Kolom NIK dipaksa jadi STRING agar Excel tidak menghilangkan awalan angka nol
            $sheet->setCellValueExplicit('D' . $baris, $winner['nik'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            
            $sheet->setCellValue('E' . $baris, $winner['name']);
            $sheet->setCellValue('F' . $baris, $winner['department']); // Nama Hadiah

            // Beri border tipis di baris data dan set alignment tengah untuk No & NIK
            $sheet->getStyle("A$baris:F$baris")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("A$baris")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D$baris")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $baris++;
        }

        // 4. Auto Size Lebar Kolom
        foreach ($kolomHuruf as $k) {
            $sheet->getColumnDimension($k)->setAutoSize(true);
        }

        // 5. Pengaturan Dokumen & Download Output ke Browser
        $nama_file = "Laporan_Pemenang_Undian_" . date('Ymd_His') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nama_file . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    //logo pengundian
        public function manage_draw() {
        $data['surveys'] = $this->db->get('undian')->result(); // Ganti 'surveys' dengan nama tabel Anda
        $this->load->view('admin/draw_setting', $data);
    }

    // PROSES TAMBAH DATAz
    public function add_survey_process() {
        $title = $this->input->post('title');
        $desc  = $this->input->post('description');
        $logo  = null;

        if (!empty($_FILES['logo']['name'])) {
            $logo = $this->_upload_logo_handler(); 
        }

        $insert_data = [
            'title'       => $title,
            'description' => $desc,
            'logo'        => $logo
        ];

        $insert = $this->db->insert('undian', $insert_data);

        if($insert) {
            echo json_encode(['status' => 'success', 'message' => 'Data undian baru berhasil disimpan.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data ke database.']);
        }
    }
public function get_survey_json($id) {
        $data = $this->db->get_where('undian', ['id' => $id])->row();
        echo json_encode($data);
    }
    // PROSES EDIT / UPDATE DATA (Mengembalikan respon JSON)
    public function edit_survey_process() {
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $description = $this->input->post('description');

        $data = array(
            'title' => $title,
            'description' => $description
        );

        if (!empty($_FILES['logo']['name'])) {
            $config['upload_path']   = './uploads/logo/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 2048; 
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo')) {
                $upload_data = $this->upload->data();
                $data['logo'] = $upload_data['file_name'];

                $old_data = $this->db->get_where('undian', array('id' => $id))->row();
                if (!empty($old_data->logo) && file_exists('./uploads/logo/' . $old_data->logo)) {
                    unlink('./uploads/logo/' . $old_data->logo);
                }
            }
        }

        $this->db->where('id', $id);
        $update = $this->db->update('undian', $data);

        if($update) {
            echo json_encode(['status' => 'success', 'message' => 'Perubahan data undian berhasil disimpan.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data.']);
        }
    }

    // PROSES HAPUS DATA UNDIAN (Mengembalikan respon JSON)
    public function delete_survey_process($id) {
        $data = $this->db->get_where('undian', array('id' => $id))->row();
        
        if ($data) {
            if (!empty($data->logo) && file_exists('./uploads/logo/' . $data->logo)) {
                unlink('./uploads/logo/' . $data->logo);
            }

            $this->db->where('id', $id);
            $delete = $this->db->delete('undian');

            if($delete) {
                echo json_encode(['status' => 'success', 'message' => 'Data undian berhasil dihapus permanen.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data di database.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }
    }
private function _upload_logo_handler() {
        $config['upload_path']   = './uploads/logo/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        // Lakukan inisialisasi ulang jika library sudah terlanjur di-load sebelumnya
        $this->upload->initialize($config);

        if ($this->upload->do_upload('logo')) {
            $upload_data = $this->upload->data();
            return $upload_data['file_name']; // Mengembalikan nama file yang sukses diupload
        }

        return null; // Mengembalikan null jika upload gagal atau tidak ada file
    }
}