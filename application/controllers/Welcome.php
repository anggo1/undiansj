<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
    }

    private function json_response($data)
    {
        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    // Semua ID kandidat yang NIK-nya belum pernah menang.
    private function candidate_query()
    {
        $this->db->from('employees e');
        $this->db->where('e.nik IS NOT NULL', NULL, FALSE);
        $this->db->where('e.nik !=', '');
        $this->db->where('e.is_won', 0);
        $this->db->where('NOT EXISTS (SELECT 1 FROM winners w WHERE w.nik = e.nik)', NULL, FALSE);
    }

    private function candidate_count()
    {
        $this->candidate_query();
        return (int) $this->db->count_all_results();
    }

    public function index()
    {
        $data['candidate_count'] = $this->candidate_count();
        $data['employees'] = array();
        $data['pending_winner'] = $this->session->userdata('pending_winner');
        //$data['data_undian'] = $this->db->get('undian')->result_array();
        //$data['data_undian'] = $this->db->get('undian')->row_array();
        $data['data_undian'] = $this->db->get('undian')->row(); 

        $this->db->select('i.*, (SELECT COUNT(*) FROM winners w WHERE w.item_id = i.id) AS total_terundi, GROUP_CONCAT(e.name SEPARATOR ", ") AS nama_pemenang', FALSE);
        $this->db->from('items i');
        $this->db->join('winners w', 'w.item_id = i.id', 'left');
        $this->db->join('employees e', 'e.id = w.employee_id', 'left');
        $this->db->group_by('i.id');
        $data['items'] = $this->db->get()->result_array();
        $this->load->view('undian_view', $data);
    }

    // Hanya memilih dan menyimpan calon pemenang di session. Database belum berubah.
    public function draw()
    {
        if ($this->session->userdata('pending_winner')) {
            return $this->json_response(array('status' => 'error', 'message' => 'Masih ada calon pemenang yang belum diproses.'));
        }

        $itemId = (int) $this->input->post('item_id');
        $item = $this->db->get_where('items', array('id' => $itemId))->row_array();
        if (!$item || (int) $item['stock'] < 1) {
            return $this->json_response(array('status' => 'error', 'message' => 'Hadiah tidak ditemukan atau stok habis.'));
        }

        $total = $this->candidate_count();
        if ($total === 0) {
            return $this->json_response(array('status' => 'error', 'message' => 'Tidak ada kandidat yang belum menjadi pemenang.'));
        }

        $offset = random_int(0, $total - 1);
        $this->db->select('e.id, e.nik, e.name, e.department');
        $this->candidate_query();
        $winner = $this->db->order_by('e.id', 'ASC')->limit(1, $offset)->get()->row_array();
        if (!$winner) {
            return $this->json_response(array('status' => 'error', 'message' => 'Kandidat berubah. Silakan undi kembali.'));
        }

        $pending = array(
            'employee_id' => (int) $winner['id'],
            'employee_number' => (int) $winner['id'],
            'employee_nik' => (string) $winner['nik'],
            'employee_name' => $winner['name'],
            'employee_department' => $winner['department'],
            'item_id' => (int) $item['id'],
            'item_name' => $item['item_name']
        );
        $this->session->set_userdata('pending_winner', $pending);

        return $this->json_response(array('status' => 'success') + array(
            'winner_id' => $pending['employee_id'], 'winner_number' => $pending['employee_number'],
            'winner_nik' => $pending['employee_nik'], 'winner_name' => $pending['employee_name'],
            'winner_dept' => $pending['employee_department'], 'item_id' => $pending['item_id'], 'item_name' => $pending['item_name']
        ));
    }

    // Simpan calon pemenang dan kunci SEMUA ID yang memiliki NIK sama dalam satu transaksi.
    public function save_winner()
    {
        $pending = $this->session->userdata('pending_winner');
        if (!$pending) return $this->json_response(array('status' => 'error', 'message' => 'Tidak ada calon pemenang.'));

        $this->db->trans_begin();
        $exists = $this->db->query('SELECT nik FROM winners WHERE nik = ? LIMIT 1 FOR UPDATE', array($pending['employee_nik']))->row_array();
        if ($exists) {
            $this->db->trans_rollback(); $this->session->unset_userdata('pending_winner');
            return $this->json_response(array('status' => 'error', 'message' => 'NIK ini sudah menjadi pemenang.'));
        }

        $this->db->where('id', $pending['item_id'])->where('stock >', 0)->set('stock', 'stock - 1', FALSE)->update('items');
        if ($this->db->affected_rows() !== 1) {
            $this->db->trans_rollback();
            return $this->json_response(array('status' => 'error', 'message' => 'Stok hadiah baru saja habis.'));
        }

        $this->db->where('nik', $pending['employee_nik'])->where('is_won', 0)->update('employees', array('is_won' => 1));
        $lockedIdCount = (int) $this->db->affected_rows();

        // NIK disimpan sebagai string; jangan dikonversi menjadi integer.
        $this->db->insert('winners', array('employee_id' => $pending['employee_id'], 'nik' => $pending['employee_nik'], 'item_id' => $pending['item_id']));
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->json_response(array('status' => 'error', 'message' => 'Gagal menyimpan pemenang.'));
        }
        $this->db->trans_commit();
        $this->session->unset_userdata('pending_winner');

        $item = $this->db->get_where('items', array('id' => $pending['item_id']))->row_array();
        return $this->json_response(array(
            'status' => 'success', 
            'winner_id' => $pending['employee_id'], 
            'winner_nik' => $pending['employee_nik'],
            'winner_name' => $pending['employee_name'], 
            'winner_dept' => $pending['employee_department'],
            'item_id' => $pending['item_id'], 'item_name' => $pending['item_name'],
            'remaining_stock' => (int) $item['stock'], 'remaining_candidate_count' => $this->candidate_count(),
            'locked_id_count' => $lockedIdCount
        ));
    }

    public function skip_winner()
    {
        $pending = $this->session->userdata('pending_winner');
        if (!$pending) return $this->json_response(array('status' => 'error', 'message' => 'Tidak ada calon pemenang.'));
        $this->session->unset_userdata('pending_winner');
        return $this->json_response(array('status' => 'success', 'skipped_name' => $pending['employee_name']));
    }

    //logo atas
    public function get_logo()
    {
        $logo = $this->db->get_where('settings', array('key' => 'logo'))->row_array();
        return $this->json_response(array('status' => 'success', 'logo_url' => base_url('uploads/' . $logo['value'])));
    }

}
