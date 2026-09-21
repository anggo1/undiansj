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
	public function items()
	{
		$data['items'] = $this->db->get('items')->result();
		$this->load->view('admin/items_view',$data);
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

    $this->load->view('admin/winners_report_view', $data);
}

}
