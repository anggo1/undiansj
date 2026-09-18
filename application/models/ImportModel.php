<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImportModel extends CI_Model {

	public function import($data_pegawai){
		
		$jumlah = count($data_pegawai);
		if ($jumlah > 0) {
			$this->db->replace('employees', $data_pegawai);
		}
	}

}
