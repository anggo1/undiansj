<?php 
        
defined('BASEPATH') OR exit('No direct script access allowed');
        
class Auth extends CI_Controller {

    public function index()
    {
        $this->load->view('admin/login');
    }

    public function login(){
      
        $username   = $this->input->post('username');
        $password   = md5($this->input->post('password'));

        $data = $this->db->query("SELECT * FROM tb_pengguna where username='$username' and password='$password'");

        if ($data->num_rows() > 0) {
                $user = $data->row_array();
					$data_sess = array(
						'username'	=> $user['username'],
						'status'		=> 'login',
					);
					$this->session->set_userdata( $data_sess );
					redirect(base_url('admin')); 
        	}
			$this->session->set_flashdata('alert','<div class="alert alert-warning">Username atau Kata Sandi Salah !</div>');
			redirect(base_url('login'));
    }

    public function logout(){
        
        $this->session->sess_destroy();
        $this->session->set_flashdata('alert','<div class="alert alert-warning">Berhasil logout !</div>');
        redirect(base_url('login'));
        
    }

	public function tambah(){
		$data = [
			'username'	=> $this->input->post('uname'),
			'password'	=> md5($this->input->post('password')),
		];
		$this->db->insert('tb_pengguna', $data);
		$this->session->set_flashdata('success','<div class="alert alert-success">Berhasil menambahkan user !</div>');
		redirect(base_url('admin/user'));
	}

	public function update(){
		$id		= $this->input->post('id');
		$password = $this->input->post('pwd');
		$data = $this->db->query("SELECT * FROM tb_pengguna where id='$id' and password='$password'");
		
		if ($data->num_rows() > 0) {
			$data = [
				'username'	=> $this->input->post('username'),
			];
			$this->db->where('id',$id)->update('tb_pengguna', $data);
			$this->session->set_flashdata('success','<div class="alert alert-success">Berhasil mengupdate user !</div>');
			redirect(base_url('admin/user'));
		}else {
			$data = [
				'username'	=> $this->input->post('username'),
				'password'	=> md5($this->input->post('pwd')),
			];
			$this->db->where('id',$id)->update('tb_pengguna', $data);
			$this->session->set_flashdata('success','<div class="alert alert-success">Berhasil mengupdate user !</div>');
			redirect(base_url('admin/user'));
		}

	}

	public function hapus($id){
		$this->db->where('id', $id)->delete('tb_pengguna');
		$this->session->set_flashdata('success','<div class="alert alert-success">Berhasil menghapus user !</div>');
		redirect(base_url('admin/user'));
	}
        
}
        
    /* End of file  Login.php */
        
                            