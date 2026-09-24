<?php

$this->load->view('admin/komponen/header');
$this->load->view('admin/komponen/sidebar');
$this->load->view('admin/komponen/navbar');

?>

<!-- Panggil SweetAlert2 Lokal agar Sistem Bisa Offline 100% -->
<script src="<?php echo base_url('assets/admin/vendor/sweetalert2/sweetalert2.all.min.js'); ?>"></script>

<div class="container-fluid">
    <!-- Header Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-poll-h text-primary mr-2"></i>Kelola Data Survey</h1>
        <p class="mb-0 text-gray-500 d-none d-sm-block">Manajemen judul survey, deskripsi, dan status keaktifan sistem.
        </p>
    </div>

    <div class="row">
        <!-- Kolom Kiri: Form Input Tambah Survey -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <h6 class="m-0 font-weight-bold text-primary text-uppercase small">Buat Survey Baru</h6>
                        <i class="fas fa-plus-circle text-gray-300 fa-2x"></i>
                    </div>

                    <form id="surveyForm">
                        <!-- Input Title Survey -->
                        <div class="form-group">
                            <label class="text-xs font-weight-bold text-gray-700 uppercase" for="surveyTitle">Judul
                                Survey</label>
                            <div class="input-group shadow-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i
                                            class="fas fa-heading text-gray-400"></i></span>
                                </div>
                                <input type="text" id="surveyTitle" name="title" class="form-control border-left-0 pl-1"
                                    maxlength="255" placeholder="Contoh: Survey Kepuasan Layanan 2026" required>
                            </div>
                        </div>

                        <!-- Input Description Survey -->
                        <div class="form-group">
                            <label class="text-xs font-weight-bold text-gray-700 uppercase" for="surveyDesc">Deskripsi
                                Singkat</label>
                            <textarea id="surveyDesc" name="description" class="form-control shadow-sm" rows="4"
                                placeholder="Tuliskan tujuan atau keterangan survey di sini..."></textarea>
                        </div>

                        <!-- Input Is Active (Status) -->
                        <div class="form-group">
                            <label class="text-xs font-weight-bold text-gray-700 uppercase" for="surveyActive">Status
                                Keaktifan</label>
                            <select id="surveyActive" name="is_active" class="form-control shadow-sm" required>
                                <option value="1" selected>Aktif (Dapat Diakses)</option>
                                <option value="0">Non-Aktif (Ditangguhkan)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-icon-split btn-block shadow-sm mt-3"
                            id="addSurveyBtn">
                            <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                            <span class="text">Simpan Master Survey</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Daftar Tabel Survey -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <h6 class="m-0 font-weight-bold text-success text-uppercase small">Master Data Registrasi Survey
                        </h6>
                        <i class="fas fa-table text-gray-300 fa-2x"></i>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="dataTableSurvey" width="100%"
                            cellspacing="0">
                            <thead class="bg-gray-100 text-gray-800 text-xs font-weight-bold uppercase">
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th width="35%">Judul Survey</th>
                                    <th width="30%">Deskripsi</th>
                                    <th width="15%" class="text-center">Status</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="surveyTable" class="text-gray-700 text-sm">
                                <?php if (!empty($surveys)): foreach ($surveys as $index => $sv): ?>
                                <tr data-id="<?= (int)$sv->id ?>"
                                    data-title="<?= htmlspecialchars($sv->title, ENT_QUOTES, 'UTF-8') ?>"
                                    data-desc="<?= htmlspecialchars($sv->description, ENT_QUOTES, 'UTF-8') ?>"
                                    data-active="<?= (int)$sv->is_active ?>">
                                    <td class="align-middle text-center font-weight-bold"><?= $index + 1 ?></td>
                                    <td class="align-middle font-weight-bold text-gray-900 title-cell">
                                        <?= htmlspecialchars($sv->title, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="align-middle desc-cell">
                                        <?= nl2br(htmlspecialchars($sv->description, ENT_QUOTES, 'UTF-8')) ?></td>
                                    <td class="align-middle text-center">
                                        <span
                                            class="badge badge-pill badge-<?= $sv->is_active == 1 ? 'success' : 'secondary' ?> px-3 py-2 status-label">
                                            <?= $sv->is_active == 1 ? 'Aktif' : 'Non-Aktif' ?>
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning btn-circle shadow-sm"
                                                data-action="edit" title="Ubah Data"><i
                                                    class="fas fa-pencil-alt"></i></button>
                                            <button type="button"
                                                class="btn btn-sm btn-danger btn-circle shadow-sm ml-1"
                                                data-action="delete" title="Hapus Permanen"><i
                                                    class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Survey Pop-up (SB Admin Style Layout) -->
<div class="modal fade" id="editSurveyModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white py-3">
                <h5 class="modal-title font-weight-bold text-sm text-uppercase" id="editModalLabel"><i
                        class="fas fa-edit mr-2"></i>Perbarui Parameter Survey</h5>
                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="editSurveyForm">
                <div class="modal-body bg-light px-4 py-4">
                    <input type="hidden" id="editSurveyId" name="id">

                    <div class="form-group">
                        <label class="text-xs font-weight-bold text-gray-700 uppercase" for="editSurveyTitle">Judul
                            Survey</label>
                        <div class="input-group shadow-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i
                                        class="fas fa-heading text-gray-400"></i></span>
                            </div>
                            <input type="text" id="editSurveyTitle" name="title" class="form-control border-left-0 pl-1"
                                maxlength="255" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="text-xs font-weight-bold text-gray-700 uppercase"
                            for="editSurveyDesc">Deskripsi</label>
                        <textarea id="editSurveyDesc" name="description" class="form-control shadow-sm" rows="4"
                            placeholder="Tuliskan deskripsi survey..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="text-xs font-weight-bold text-gray-700 uppercase" for="editSurveyActive">Status
                            Keaktifan</label>
                        <select id="editSurveyActive" name="is_active" class="form-control shadow-sm" required>
                            <option value="1">Aktif (Dapat Diakses)</option>
                            <option value="0">Non-Aktif (Ditangguhkan)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top-0 d-flex justify-content-between px-4 pb-4">
                    <button class="btn btn-light border btn-sm px-4" type="button" data-dismiss="modal"><i
                            class="fas fa-times mr-1"></i>Batal</button>
                    <!-- PASTIKAN TOMBOL INI MEMILIKI TYPE="SUBMIT" DAN ID="saveSurveyBtn" -->
                    <button class="btn btn-primary btn-sm px-4 shadow-sm" type="submit" id="saveSurveyBtn"><i
                            class="fas fa-save mr-1"></i>Simpan Perubahan</button>
                </div>
            </form>


            <?php $this->load->view('admin/komponen/footer'); ?>
            <script>
            $(document).ready(function() {
                // 1. Inisialisasi DataTables Internal Engine
                const tableSurvey = $('#dataTableSurvey').DataTable();
                const surveyForm = document.getElementById('surveyForm'),
                    addSurveyBtn = document.getElementById('addSurveyBtn'),
                    editSurveyForm = document.getElementById('editSurveyForm'),
                    saveSurveyBtn = document.getElementById('saveSurveyBtn');
                // Anti-Error Escape Sequence via DOM Text Node
                const esc = (str) => {
                    if (!str) return '';
                    const div = document.createElement('div');
                    div.appendChild(document.createTextNode(str));
                    return div.innerHTML;
                };
                // Fungsi Pengurutan Ulang Nomor Urut Baris Tabel
                function renumber() {
                    tableSurvey.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
                // 2. Event Delegation Klik Aksi (Mendukung Multi-Halaman DataTables)
                $(document).on('click', 'button[data-action]', function(e) {
                    const button = $(this);
                    const row = button.closest('tr');
                    const action = button.data('action');
                    if (action === 'edit') {
                        $('#editSurveyId').val(row.attr('data-id'));
                        $('#editSurveyTitle').val(row.attr('data-title'));
                        $('#editSurveyDesc').val(row.attr('data-desc'));
                        $('#editSurveyActive').val(row.attr('data-active'));
                        $('#editSurveyModal').modal('show');
                    } else if (action === 'delete') {
                        Swal.fire({
                            title: 'Hapus Master Survey?',
                            text: "Menghapus master survey ini dapat berdampak pada relasi kupon undian aktif!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#e74a3b', // Warna Danger SB Admin
                            cancelButtonColor: '#858796', // Warna Secondary SB Admin
                            confirmButtonText: 'Ya, hapus permanen!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const id = row.attr('data-id');
                                const formData = new FormData();
                                formData.append('id',
                                id); // Mengirim parameter ID survey yang akan dihapus

                                button.prop('disabled', true);

                                // PERBAIKAN: Menggunakan URL absolute langsung ke route survey/delete
                                fetch('<?= site_url('survey/delete') ?>', {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.status === 'success') {
                                            // Hapus baris dari view DataTables secara real-time
                                            tableSurvey.row(row).remove().draw(false);
                                            renumber
                                        (); // Susun ulang penomoran baris 1, 2, 3

                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil!',
                                                text: 'Survey telah dihapus dari sistem.',
                                                timer: 1500,
                                                showConfirmButton: false
                                            });
                                        } else {
                                            Swal.fire('Gagal!', data.message ||
                                                'Gagal menghapus data.', 'error');
                                            button.prop('disabled', false);
                                        }
                                    })
                                    .catch(() => {
                                        Swal.fire('Error!',
                                            'Terjadi gangguan komunikasi dengan server.',
                                            'error');
                                        button.prop('disabled', false);
                                    });
                            }
                        });
                    }
                });
                // 3. Submit Proses Simpan Data Survey Baru
                $(surveyForm).on('submit', function(e) {
                    e.preventDefault();
                    const form = this;
                    const btn = $(addSurveyBtn);
                    btn.prop('disabled', true).text('Menyimpan data...');

                    // PENTING: Arahkan URL fetch ke route controller CI3 yang benar
                    fetch('<?= site_url('survey/add') ?>', {
                            method: 'POST',
                            body: new FormData(form)
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                const statusClass = data.is_active == 1 ? 'success' : 'secondary';
                                const statusText = data.is_active == 1 ? 'Aktif' : 'Non-Aktif';

                                // PERBAIKAN: Seluruh elemen HTML sekarang dibungkus dalam string backtick (``) dengan benar
                                const newRow = tableSurvey.row.add([
                                    '',
                                    esc(data.title),
                                    esc(data.description).replace(/\n/g, '<br>'),
                                    `<span class="badge badge-pill badge-${statusClass} px-3 py-2 status-label">${statusText}</span>`,
                                    `<div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-warning btn-circle shadow-sm" data-action="edit" title="Ubah Data"><i class="fas fa-pencil-alt"></i></button> 
                        <button type="button" class="btn btn-sm btn-danger btn-circle shadow-sm ml-1" data-action="delete" title="Hapus Permanen"><i class="fas fa-trash"></i></button>
                    </div>`
                                ]).draw(false).node();

                                // Tanamkan metadata ke baris TR baru
                                $(newRow).attr('data-id', data.id)
                                    .attr('data-title', data.title)
                                    .attr('data-desc', data.description)
                                    .attr('data-active', data.is_active);

                                form.reset();
                                renumber();
                                tableSurvey.draw(false);

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Survey Berhasil Dibuat',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        })
                        .catch(() => Swal.fire('Error!', 'Gagal memproses data ke database.', 'error'))
                        .finally(() => btn.prop('disabled', false).html(
                            '<span class="icon text-white-50"><i class="fas fa-save"></i></span><span class="text">Simpan Master Survey</span>'
                            ));
                });
                // 4. Submit Proses Perubahan Parameter Survey
                $(editSurveyForm).on('submit', function(e) {
                    e.preventDefault();
                    const form = this;
                    const btn = $(saveSurveyBtn);
                    const formData = new FormData(form);

                    btn.prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin mr-1"></i>Memperbarui...');

                    fetch('<?= site_url('survey/edit') ?>', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                const idTarget = formData.get('id');
                                const row = $('tr[data-id="' + idTarget + '"]');

                                if (row.length) {
                                    const activeVal = formData.get('is_active');
                                    const statusClass = activeVal == 1 ? 'success' : 'secondary';
                                    const statusText = activeVal == 1 ? 'Aktif' : 'Non-Aktif';

                                    row.attr('data-title', formData.get('title'))
                                        .attr('data-desc', formData.get('description'))
                                        .attr('data-active', activeVal);

                                    tableSurvey.cell(row.find('td:eq(1)')).data(esc(formData.get(
                                        'title')));
                                    tableSurvey.cell(row.find('td:eq(2)')).data(esc(formData.get(
                                        'description')).replace(/\n/g, '<br>'));
                                    tableSurvey.cell(row.find('td:eq(3)')).data(
                                        `<span class="badge badge-pill badge-${statusClass} px-3 py-2 status-label">${statusText}</span>`
                                        );

                                    tableSurvey.draw(false);
                                }

                                $('#editSurveyModal').modal('hide');

                                // PERBAIKAN: Menambahkan trigger auto reload setelah user melihat alert sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Data Survey Diperbarui',
                                    timer: 1500, // Durasi pop-up muncul sebelum hilang (1.5 detik)
                                    showConfirmButton: false
                                }).then(() => {
                                    // Halaman akan otomatis reload/refresh setelah SweetAlert selesai
                                    //location.reload(); 
                                });

                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error!', 'Gagal memperbarui data ke server.', 'error');
                        })
                        .finally(() => {
                            btn.prop('disabled', false).html(
                                '<i class="fas fa-save mr-1"></i>Simpan Perubahan');
                        });
                });
            });
            </script>