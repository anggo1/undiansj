<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Undian extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();

        $this->load->helper(array(
            'url',
            'security'
        ));

        /*
         * Session digunakan untuk menyimpan
         * calon pemenang sementara.
         *
         * Calon pemenang BELUM dianggap menang
         * sampai tombol "Simpan Pemenang" ditekan.
         */
        $this->load->library('session');
    }


    /**
     * =========================================================
     * HALAMAN UTAMA
     * =========================================================
     */
    public function index()
    {
        /*
         * Ambil karyawan yang belum menang.
         */
        $data['employees'] = $this->db
            ->where('is_won', 0)
            ->order_by('id', 'ASC')
            ->get('employees')
            ->result_array();


        /*
         * Ambil daftar hadiah + jumlah pemenang.
         */
        $this->db->select('
            i.*,
            COUNT(w.id) AS total_terundi,
            GROUP_CONCAT(
                DISTINCT e.name
                ORDER BY e.name ASC
                SEPARATOR ", "
            ) AS nama_pemenang
        ');

        $this->db->from('items i');

        $this->db->join(
            'winners w',
            'w.item_id = i.id',
            'left'
        );

        $this->db->join(
            'employees e',
            'e.id = w.employee_id',
            'left'
        );

        $this->db->group_by('i.id');

        $this->db->order_by(
            'i.id',
            'ASC'
        );

        $data['items'] =
            $this->db
                ->get()
                ->result_array();


        /*
         * Kalau ada calon pemenang yang masih pending,
         * kirim ke view.
         *
         * Ini berguna kalau halaman tidak sengaja
         * mengalami refresh sebelum tombol simpan/skip.
         */
        $data['pending_winner'] =
            $this->session->userdata(
                'pending_winner'
            );


        $this->load->view(
            'undian_view',
            $data
        );
    }


    /**
     * =========================================================
     * DRAW
     *
     * HANYA MENENTUKAN CALON PEMENANG.
     *
     * BELUM MENYIMPAN KE DATABASE.
     * =========================================================
     */
    public function draw()
    {
        /*
         * Hanya POST.
         */
        if (
            $this->input->method(TRUE)
            !== 'POST'
        ) {
            return $this->_json_response(
                'error',
                'Metode request tidak diperbolehkan.',
                405
            );
        }


        /*
         * Kalau masih ada calon pemenang
         * yang belum disimpan / di-skip,
         * jangan izinkan spin baru.
         */
        $pending =
            $this->session->userdata(
                'pending_winner'
            );

        if (!empty($pending)) {

            return $this->_json_response(
                'error',
                'Masih ada calon pemenang yang belum diproses. Silakan Simpan atau Skip terlebih dahulu.',
                409
            );
        }


        /*
         * Ambil item.
         */
        $item_id =
            (int) $this->input->post(
                'item_id'
            );


        if ($item_id <= 0) {

            return $this->_json_response(
                'error',
                'Hadiah belum dipilih.',
                400
            );
        }


        /*
         * Ambil hadiah.
         */
        $item =
            $this->db
                ->where(
                    'id',
                    $item_id
                )
                ->get('items')
                ->row_array();


        if (!$item) {

            return $this->_json_response(
                'error',
                'Hadiah tidak ditemukan.',
                404
            );
        }


        /*
         * Cek stok.
         */
        if (
            (int) $item['stock']
            <= 0
        ) {

            return $this->_json_response(
                'error',
                'Stok hadiah ini sudah habis.',
                400
            );
        }


        /*
         * Ambil kandidat yang belum menang.
         */
        $employees =
            $this->db
                ->where(
                    'is_won',
                    0
                )
                ->order_by(
                    'id',
                    'ASC'
                )
                ->get('employees')
                ->result_array();


        if (empty($employees)) {

            return $this->_json_response(
                'error',
                'Semua karyawan sudah mendapatkan hadiah.',
                400
            );
        }


        /*
         * Pilih secara random.
         */
        $winner_index =
            array_rand(
                $employees
            );


        $winner =
            $employees[
                $winner_index
            ];


        /*
         * =====================================================
         * SIMPAN SEBAGAI PENDING
         *
         * BELUM MASUK winners.
         * BELUM mengubah is_won.
         * BELUM mengurangi stock.
         * =====================================================
         */
        $pending_winner = array(
            'employee_id' =>
                (int) $winner['id'],

            'employee_name' =>
                $winner['name'],

            'employee_department' =>
                isset(
                    $winner['department']
                )
                    ? $winner['department']
                    : '',

            'item_id' =>
                (int) $item['id'],

            'item_name' =>
                $item['item_name'],

            'winner_index' =>
                (int) $winner_index
        );


        $this->session->set_userdata(
            'pending_winner',
            $pending_winner
        );


        /*
         * Kirim ke frontend.
         */
        return $this->_json_response(
            'success',
            'Calon pemenang berhasil ditentukan.',
            200,
            array(
                'winner_index' =>
                    (int) $winner_index,

                'winner_id' =>
                    (int) $winner['id'],

                'winner_name' =>
                    $winner['name'],

                'winner_dept' =>
                    isset(
                        $winner['department']
                    )
                        ? $winner['department']
                        : '',

                'item_id' =>
                    (int) $item['id'],

                'item_name' =>
                    $item['item_name']
            )
        );
    }


    /**
     * =========================================================
     * SAVE WINNER
     *
     * BARU DI SINI PEMENANG BENAR-BENAR SAH.
     * =========================================================
     */
    public function save_winner()
    {
        /*
         * Hanya POST.
         */
        if (
            $this->input->method(TRUE)
            !== 'POST'
        ) {
            return $this->_json_response(
                'error',
                'Metode request tidak diperbolehkan.',
                405
            );
        }


        /*
         * Ambil calon pemenang dari session.
         */
        $pending =
            $this->session->userdata(
                'pending_winner'
            );


        if (empty($pending)) {

            return $this->_json_response(
                'error',
                'Tidak ada calon pemenang yang menunggu untuk disimpan.',
                400
            );
        }


        $employee_id =
            (int) $pending['employee_id'];

        $item_id =
            (int) $pending['item_id'];


        /*
         * =====================================================
         * TRANSACTION
         * =====================================================
         */
        $this->db->trans_begin();


        /*
         * Lock employee.
         */
        $employee =
            $this->db
                ->query(
                    'SELECT *
                     FROM employees
                     WHERE id = ?
                     LIMIT 1
                     FOR UPDATE',
                    array(
                        $employee_id
                    )
                )
                ->row_array();


        if (!$employee) {

            $this->db->trans_rollback();

            return $this->_json_response(
                'error',
                'Data karyawan tidak ditemukan.',
                404
            );
        }


        /*
         * Pastikan belum menang.
         */
        if (
            (int) $employee['is_won']
            === 1
        ) {

            $this->db->trans_rollback();

            $this->session->unset_userdata(
                'pending_winner'
            );

            return $this->_json_response(
                'error',
                'Karyawan tersebut sudah memiliki hadiah.',
                409
            );
        }


        /*
         * Lock hadiah.
         */
        $item =
            $this->db
                ->query(
                    'SELECT *
                     FROM items
                     WHERE id = ?
                     LIMIT 1
                     FOR UPDATE',
                    array(
                        $item_id
                    )
                )
                ->row_array();


        if (!$item) {

            $this->db->trans_rollback();

            return $this->_json_response(
                'error',
                'Hadiah tidak ditemukan.',
                404
            );
        }


        /*
         * Pastikan stok masih ada.
         */
        if (
            (int) $item['stock']
            <= 0
        ) {

            $this->db->trans_rollback();

            return $this->_json_response(
                'error',
                'Stok hadiah sudah habis.',
                400
            );
        }


        /*
         * =====================================================
         * 1. Tandai karyawan sudah menang
         * =====================================================
         */
        $this->db
            ->where(
                'id',
                $employee_id
            )
            ->where(
                'is_won',
                0
            )
            ->update(
                'employees',
                array(
                    'is_won' => 1
                )
            );


        if (
            $this->db->affected_rows()
            !== 1
        ) {

            $this->db->trans_rollback();

            return $this->_json_response(
                'error',
                'Status karyawan gagal diperbarui.',
                500
            );
        }


        /*
         * =====================================================
         * 2. Kurangi stok
         * =====================================================
         */
        $this->db
            ->where(
                'id',
                $item_id
            )
            ->where(
                'stock >',
                0
            )
            ->set(
                'stock',
                'stock - 1',
                FALSE
            )
            ->update('items');


        if (
            $this->db->affected_rows()
            !== 1
        ) {

            $this->db->trans_rollback();

            return $this->_json_response(
                'error',
                'Stok hadiah gagal diperbarui.',
                500
            );
        }


        /*
         * =====================================================
         * 3. Simpan winners
         * =====================================================
         */
        $insert =
            $this->db->insert(
                'winners',
                array(
                    'employee_id' =>
                        $employee_id,

                    'item_id' =>
                        $item_id
                )
            );


        if (!$insert) {

            $this->db->trans_rollback();

            return $this->_json_response(
                'error',
                'Riwayat pemenang gagal disimpan.',
                500
            );
        }


        /*
         * =====================================================
         * 4. Cek transaction
         * =====================================================
         */
        if (
            !$this->db->trans_status()
        ) {

            $this->db->trans_rollback();

            return $this->_json_response(
                'error',
                'Gagal menyimpan pemenang.',
                500
            );
        }


        /*
         * Commit.
         */
        $this->db->trans_commit();


        /*
         * Hapus calon pemenang dari session.
         */
        $this->session->unset_userdata(
            'pending_winner'
        );


        /*
         * Ambil stok terbaru.
         */
        $remaining_stock =
            max(
                0,
                (int) $item['stock'] - 1
            );


        /*
         * Response.
         */
        return $this->_json_response(
            'success',
            'Pemenang berhasil disimpan.',
            200,
            array(
                'winner_id' =>
                    $employee_id,

                'winner_name' =>
                    $employee['name'],

                'winner_dept' =>
                    isset(
                        $employee['department']
                    )
                        ? $employee['department']
                        : '',

                'item_id' =>
                    $item_id,

                'item_name' =>
                    $item['item_name'],

                'remaining_stock' =>
                    $remaining_stock
            )
        );
    }


    /**
     * =========================================================
     * SKIP WINNER
     *
     * Calon pemenang dibatalkan.
     * Tidak masuk database.
     * =========================================================
     */
    public function skip_winner()
    {
        /*
         * Hanya POST.
         */
        if (
            $this->input->method(TRUE)
            !== 'POST'
        ) {
            return $this->_json_response(
                'error',
                'Metode request tidak diperbolehkan.',
                405
            );
        }


        /*
         * Ambil pending.
         */
        $pending =
            $this->session->userdata(
                'pending_winner'
            );


        if (empty($pending)) {

            return $this->_json_response(
                'error',
                'Tidak ada calon pemenang untuk di-skip.',
                400
            );
        }


        /*
         * Simpan nama untuk response.
         */
        $skipped_name =
            $pending['employee_name'];


        /*
         * Hapus pending.
         */
        $this->session->unset_userdata(
            'pending_winner'
        );


        return $this->_json_response(
            'success',
            'Calon pemenang berhasil di-skip.',
            200,
            array(
                'skipped_name' =>
                    $skipped_name
            )
        );
    }


    /**
     * =========================================================
     * JSON RESPONSE
     * =========================================================
     */
    private function _json_response(
        $status,
        $message,
        $http_code = 200,
        $data = array()
    ) {

        $response = array(
            'status' =>
                $status,

            'message' =>
                $message
        );


        if (!empty($data)) {

            $response =
                array_merge(
                    $response,
                    $data
                );

        }


        return $this->output
            ->set_status_header(
                $http_code
            )
            ->set_content_type(
                'application/json'
            )
            ->set_output(
                json_encode(
                    $response,
                    JSON_UNESCAPED_UNICODE |
                    JSON_UNESCAPED_SLASHES
                )
            );
    }
// untuk hadiah
public function items()
{
    $data['items'] = $this->db
        ->order_by('id', 'ASC')
        ->get('items')
        ->result_array();

    $this->load->view('items_view', $data);
}


public function add_item()
{
    if ($this->input->method(TRUE) !== 'POST') {
        return $this->_json_response(
            'error',
            'Metode request tidak diperbolehkan.',
            405
        );
    }

    $item_name = trim($this->input->post('item_name', TRUE));
    $color     = trim($this->input->post('color', TRUE));
    $stock     = (int)$this->input->post('stock');

    if ($item_name === '') {
        return $this->_json_response(
            'error',
            'Nama hadiah wajib diisi.',
            400
        );
    }

    if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
        return $this->_json_response(
            'error',
            'Warna tidak valid.',
            400
        );
    }

    if ($stock < 0) {
        return $this->_json_response(
            'error',
            'Stok tidak boleh kurang dari 0.',
            400
        );
    }

    $insert = $this->db->insert('items', array(
        'item_name' => $item_name,
        'color'     => $color,
        'stock'     => $stock
    ));

    if (!$insert) {
        return $this->_json_response(
            'error',
            'Hadiah gagal ditambahkan.',
            500
        );
    }

    return $this->_json_response(
        'success',
        'Hadiah berhasil ditambahkan.',
        200,
        array(
            'item_id'   => $this->db->insert_id(),
            'item_name' => $item_name,
            'color'     => $color,
            'stock'     => $stock
        )
    );
}

public function update_item()
{
    $id = (int) $this->input->post('item_id');
    $name = trim((string) $this->input->post('item_name'));
    $color = trim((string) $this->input->post('color'));
    $stock = (int) $this->input->post('stock');
    $send = function ($data) { return $this->output->set_content_type('application/json')->set_output(json_encode($data)); };

    if ($id < 1 || $name === '' || $stock < 0 || !preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
        return $send(array('status' => 'error', 'message' => 'Data hadiah tidak valid.'));
    }
    $item = $this->db->get_where('items', array('id' => $id))->row();
    if (!$item) return $send(array('status' => 'error', 'message' => 'Hadiah tidak ditemukan.'));

    $used = isset($item->total_terundi) ? (int) $item->total_terundi : 0;
    if ($stock < $used) return $send(array('status' => 'error', 'message' => 'Stok tidak boleh kurang dari jumlah yang sudah terundi: ' . $used . '.'));

    $this->db->where('id', $id)->update('items', array('item_name' => $name, 'color' => $color, 'stock' => $stock));
    return $send(array('status' => 'success', 'item_id' => $id, 'item_name' => $name, 'color' => $color, 'stock' => $stock));
}

public function delete_item()
{
    if ($this->input->method(TRUE) !== 'POST') {
        return $this->_json_response(
            'error',
            'Metode request tidak diperbolehkan.',
            405
        );
    }

    $item_id = (int)$this->input->post('item_id');

    if ($item_id <= 0) {
        return $this->_json_response(
            'error',
            'ID hadiah tidak valid.',
            400
        );
    }

    /*
     * Cek apakah hadiah sudah pernah digunakan
     * dalam tabel winners.
     */
    $used = $this->db
        ->where('item_id', $item_id)
        ->count_all_results('winners');

    if ($used > 0) {
        return $this->_json_response(
            'error',
            'Hadiah ini sudah memiliki riwayat pemenang sehingga tidak dapat dihapus.',
            400
        );
    }

    $item = $this->db
        ->where('id', $item_id)
        ->get('items')
        ->row_array();

    if (!$item) {
        return $this->_json_response(
            'error',
            'Hadiah tidak ditemukan.',
            404
        );
    }

    $this->db
        ->where('id', $item_id)
        ->delete('items');

    return $this->_json_response(
        'success',
        'Hadiah berhasil dihapus.'
    );
}}
