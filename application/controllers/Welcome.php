<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function index()
    {
        // Tidak kirim seluruh karyawan ke browser. View cukup memakai candidate_count.
        $data['employees'] = array();
        $data['candidate_count'] = (int) $this->db->count_all('employees');

        $this->db->select('i.*, (SELECT COUNT(*) FROM winners w WHERE w.item_id = i.id) AS total_terundi, GROUP_CONCAT(e.name SEPARATOR ", ") AS nama_pemenang', FALSE);
        $this->db->from('items i');
        $this->db->join('winners w', 'w.item_id = i.id', 'left');
        $this->db->join('employees e', 'e.id = w.employee_id', 'left');
        $this->db->group_by('i.id');
        $data['items'] = $this->db->get()->result_array();

        $this->load->view('undian_view', $data);
    }

    /*
     * Voucher boleh dimenangkan berulang oleh NIK yang sama.
     * Untuk aturan yang lebih rapi di masa depan, tambahkan kolom is_voucher
     * pada tabel items dan ganti isi method ini dengan (bool) $item['is_voucher'].
     */
    private function is_voucher($item)
    {
        return stripos((string) $item['item_name'], 'voucher') !== FALSE;
    }

    /* Menerapkan kandidat sesuai jenis hadiah. */
    private function apply_candidate_filter($isVoucher)
    {
        $this->db->from('employees e');
        $this->db->where('e.nik IS NOT NULL', NULL, FALSE);
        $this->db->where('e.nik !=', '');

        // Hadiah biasa: NIK yang pernah menang hadiah biasa tidak boleh ikut lagi.
        // Voucher: tetap boleh ikut meski is_won sudah bernilai 1.
        if (!$isVoucher) {
            $this->db->where('e.is_won', 0);
        }
    }

    private function json_response($payload)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($payload));
    }

    public function draw()
    {
        $itemId = (int) $this->input->post('item_id');
        $item = $this->db->get_where('items', array('id' => $itemId))->row_array();

        if (!$item || (int) $item['stock'] <= 0) {
            return $this->json_response(array('status' => 'error', 'message' => 'Hadiah tidak ditemukan atau stok sudah habis.'));
        }

        $isVoucher = $this->is_voucher($item);

        // Mengundi berdasarkan posisi ID di database, tanpa mengirim seluruh data ke frontend.
        $this->apply_candidate_filter($isVoucher);
        $candidateCount = (int) $this->db->count_all_results();
        if ($candidateCount === 0) {
            return $this->json_response(array('status' => 'error', 'message' => 'Tidak ada kandidat yang memenuhi syarat.'));
        }

        $randomOffset = random_int(0, $candidateCount - 1);
        $this->db->select('e.id, e.nik, e.name, e.department');
        $this->apply_candidate_filter($isVoucher);
        $winner = $this->db->order_by('e.id', 'ASC')->limit(1, $randomOffset)->get()->row_array();
        if (!$winner) {
            return $this->json_response(array('status' => 'error', 'message' => 'Kandidat undian tidak ditemukan. Silakan undi lagi.'));
        }

        $this->db->trans_begin();

        // Kunci berdasarkan NIK, bukan hanya ID.
        // Semua record karyawan dengan NIK yang sama dikunci untuk hadiah biasa.
        if (!$isVoucher) {
            $this->db->where('nik', $winner['nik']);
            $this->db->where('is_won', 0);
            $this->db->update('employees', array('is_won' => 1));

            if ($this->db->affected_rows() === 0) {
                $this->db->trans_rollback();
                return $this->json_response(array('status' => 'error', 'message' => 'NIK ini baru saja menang. Silakan undi kembali.'));
            }
        }

        // Potong stok secara atomik supaya tidak minus saat dua undian terjadi bersamaan.
        $this->db->where('id', $itemId);
        $this->db->where('stock >', 0);
        $this->db->set('stock', 'stock - 1', FALSE);
        $this->db->update('items');
        if ($this->db->affected_rows() === 0) {
            $this->db->trans_rollback();
            return $this->json_response(array('status' => 'error', 'message' => 'Stok hadiah baru saja habis.'));
        }

        $this->db->insert('winners', array(
            'employee_id' => $winner['id'],
            'item_id' => $itemId
        ));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->json_response(array('status' => 'error', 'message' => 'Gagal memproses undian.'));
        }

        $this->db->trans_commit();

        return $this->json_response(array(
            'status' => 'success',
            'winner_id' => (int) $winner['id'],
            'winner_number' => (int) $winner['id'],
            'winner_name' => $winner['name'],
            'winner_dept' => $winner['department'],
            'winner_nik' => $winner['nik'],
            'item_id' => (int) $item['id'],
            'item_name' => $item['item_name'],
            'is_voucher' => $isVoucher
        ));
    }
}
