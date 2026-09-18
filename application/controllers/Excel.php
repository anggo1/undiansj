<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'third_party/Spout/Autoloader/autoload.php';

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory ;

class Excel extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('ImportModel');
	}

	public function index()
	{
		$data['anggota'] = $this->db->get('employees')->result();
		$this->load->view('import_excel',$data);
	}

	public function import(){
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'xlsx|xls';
		$config['file_name'] = 'doc' . time();
		$this->load->library('upload', $config);
		if ($this->upload->do_upload('excel')){
			$file = $this->upload->data();

			$reader = ReaderEntityFactory::createXLSXReader();
			$reader->open('uploads/'.$file['file_name']);
			foreach ($reader->getSheetIterator() as $sheet) {
				$numRow =1 ;
				foreach ($sheet->getRowIterator() as $row) {
					if ($numRow > 1) {
						$data_pegawai = array(
							'nik'		=> $row->getCellAtIndex(0),
							'name'		=> $row->getCellAtIndex(1),
							'department'		=> $row->getCellAtIndex(2),
						);
						$this->ImportModel->import($data_pegawai);
					}
					$numRow++;
				}
				$reader->close();
				unlink('uploads/'.$file['file_name']);
				$this->session->set_flashdata('success', '<div class="alert alert-success">Data berhasil diimport!</div>');
				redirect('admin/tamu');
			}
		}else {
			echo "Error :" . $this->upload->display_errors();
		}
	}

	public function reset(){
		$this->db->empty_table('employees');
		$this->session->set_flashdata('success', '<div class="alert alert-success">Data berhasil direset!</div>');
		redirect('admin/tamu');
	}

}
