<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
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


	public function user()
	{
		$data['user'] = $this->db->get('tb_pengguna')->result();
		$this->load->view('admin/user',$data);
	}

}
