<!-- Tampilan Halaman Utama SB Admin -->
<?php
$this->load->view('admin/komponen/header');
$this->load->view('admin/komponen/sidebar');
$this->load->view('admin/komponen/navbar');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Survey</h1>
        <a href="<?= site_url('survey') ?>" target="_blank" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-external-link-alt fa-sm text-white-50 mr-1"></i> Buka Form Publik
        </a>
    </div>

    <!-- Metadata Survey -->
    <div class="card bg-gradient-primary text-white shadow mb-4">
        <div class="card-body">
            <h5 class="font-weight-bold mb-1"><?= html_escape($survey->title) ?></h5>
            <p class="mb-0 text-white-50">
                <i class="fas fa-poll mr-1"></i> Total Tanggapan: <strong class="text-white"><?= $responses ?></strong>
            </p>
        </div>
    </div>

    <!-- Flash Messages (Notifikasi) -->
    <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-1"></i> <?= $this->session->flashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-1"></i> <?= $this->session->flashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- Kolom Kiri: Tambah Pertanyaan -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus mr-1"></i> Tambah Pertanyaan
                    </h6>
                </div>
                <div class="card-body">
                    <?= form_open('survey/add_question') ?>

                    <div class="form-group">
                        <label class="font-weight-bold">Pertanyaan</label>
                        <input required name="question_text" class="form-control"
                            placeholder="Contoh: Seberapa puas Anda?">
                    </div>

                    <div class="form-row align-items-center">
                        <div class="form-group col-md-8">
                            <label class="font-weight-bold">Tipe Jawaban</label>
                            <select name="question_type" class="form-control custom-select">
                                <option value="text">Jawaban Singkat</option>
                                <option value="textarea">Paragraf</option>
                                <option value="radio">Pilihan Ganda</option>
                                <option value="checkbox">Kotak Centang</option>
                                <option value="select">Daftar Pilihan</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4 pt-4 text-center">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_required" name="is_required"
                                    value="1">
                                <label class="custom-control-label font-weight-bold text-danger" for="is_required">Wajib
                                    Isi</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Pilihan Jawaban</label>
                        <textarea name="options" rows="4" class="form-control"
                            placeholder="Sangat Puas&#10;Puas&#10;Cukup Puas"></textarea>
                        <small class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle mr-1"></i> Isi satu pilihan per baris. Gunakan hanya untuk
                            pilihan ganda, kotak centang, atau daftar pilihan.
                        </small>
                    </div>

                    <button class="btn btn-primary btn-block shadow-sm" type="submit">
                        <i class="fas fa-save mr-1"></i> Tambah Pertanyaan
                    </button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Daftar Pertanyaan -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list mr-1"></i> Daftar Pertanyaan
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?php if(empty($questions)): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-folder-open fa-2x mb-2 text-gray-400"></i>
                        <p class="mb-0">Belum ada pertanyaan. Tambahkan pertanyaan pertama di panel kiri.</p>
                    </div>
                    <?php else: ?>
                        <div class="card-body p-1">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0" id="dataTablePertanyaan" width="100%" cellspacing="0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%" class="text-center font-weight-bold text-gray-700">#</th>
                                    <th width="65%" class="font-weight-bold text-gray-700">Pertanyaan</th>
                                    <th width="10%" class="font-weight-bold text-gray-700">Tipe</th>
                                    <th width="20%" class="text-center font-weight-bold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($questions as $i => $q): ?>
                                <tr>
                                    <td class="text-center align-middle"><?= $i+1 ?></td>
                                    <td class="align-middle">
                                        <span
                                            class="font-weight-bold text-gray-800"><?= html_escape($q->question_text) ?></span>
                                        <?= $q->is_required ? '<span class="badge badge-danger ml-1">Wajib</span>' : '' ?>
                                    </td>
                                    <td class="align-middle text-capitalize">
                                        <span class="badge badge-secondary"><?= html_escape($q->question_type) ?></span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group" role="group">
                                        <button class="btn btn-warning btn-circle btn-sm shadow-sm mr-1 btn-edit"
                                            data-id="<?= $q->id ?>" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <a class="btn btn-danger btn-circle btn-sm shadow-sm"
                                            onclick="return confirm('Hapus pertanyaan ini?')"
                                            href="<?= site_url('survey/delete_question/'.$q->id) ?>" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a></div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Modal Edit Pertanyaan -->
<div class="modal fade" id="modalEditPertanyaan" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalEditLabel"><i class="fas fa-edit mr-1"></i> Edit Pertanyaan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('survey/update_question', ['id' => 'form-edit-pertanyaan']) ?>
            <div class="modal-body">
                <!-- ID Hidden untuk acuan update -->
                <input type="hidden" name="id" id="edit_id">

                <div class="form-group">
                    <label class="font-weight-bold">Pertanyaan</label>
                    <input required name="question_text" id="edit_question_text" class="form-control"
                        placeholder="Contoh: Seberapa puas Anda?">
                </div>

                <div class="form-row align-items-center">
                    <div class="form-group col-md-8">
                        <label class="font-weight-bold">Tipe Jawaban</label>
                        <select name="question_type" id="edit_question_type" class="form-control custom-select">
                            <option value="text">Jawaban Singkat</option>
                            <option value="textarea">Paragraf</option>
                            <option value="radio">Pilihan Ganda</option>
                            <option value="checkbox">Kotak Centang</option>
                            <option value="select">Daftar Pilihan</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 pt-4 text-center">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="edit_is_required" name="is_required"
                                value="1">
                            <label class="custom-control-label font-weight-bold text-danger"
                                for="edit_is_required">Wajib Isi</label>
                        </div>
                    </div>
                </div>

                <!-- Kondisi Dinamis Pilihan Jawaban -->
                <div class="form-group" id="edit_options_container">
                    <label class="font-weight-bold">Pilihan Jawaban</label>
                    <textarea name="options" id="edit_options" rows="5" class="form-control"
                        placeholder="Sangat Puas&#10;Puas&#10;Cukup Puas"></textarea>
                    <small class="form-text text-muted mt-2">
                        <i class="fas fa-info-circle mr-1"></i> Isi satu pilihan per baris.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<?php $this->load->view('admin/komponen/footer'); ?>
<script>
    $(document).ready(function() {
    // Inisialisasi DataTables untuk tabel pertanyaan
    $('#dataTablePertanyaan').DataTable({
        "pageLength": 10, // Menampilkan 10 data per halaman
        "responsive": true,
        "language": {
            "search": "Cari Pertanyaan:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data tersedia",
            "paginate": {
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
    
    // ... script AJAX btn-edit Anda yang sebelumnya tetap di sini ...
});

$(document).ready(function() {
    
    function toggleEditOptions() {
        var selectedType = $('#edit_question_type').val();
        if (selectedType === 'text' || selectedType === 'textarea') {
            $('#edit_options_container').slideUp();
            $('#edit_options').prop('required', false);
        } else {
            $('#edit_options_container').slideDown();
            $('#edit_options').prop('required', true);
        }
    }

    $('#edit_question_type').on('change', function() {
        toggleEditOptions();
    });

    // Jalankan trigger AJAX saat tombol edit diklik
    $('#dataTablePertanyaan').on('click', '.btn-edit', function() {
    var id = $(this).data('id');
        
        // Mengamankan AJAX jika aplikasi Anda mengaktifkan CSRF Protection
        var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

        $.ajax({
            url: "<?= site_url('survey/get_question_json/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            data: { [csrfName]: csrfHash }, // Mengirimkan token keamanan CSRF
            success: function(data) {
                if(data) {
                    // Inject data ke form modal
                    $('#edit_id').val(data.id);
                    $('#edit_question_text').val(data.question_text);
                    $('#edit_question_type').val(data.question_type);
                    $('#edit_options').val(data.options);
                    
                    if(data.is_required == 1) {
                        $('#edit_is_required').prop('checked', true);
                    } else {
                        $('#edit_is_required').prop('checked', false);
                    }

                    toggleEditOptions();
                    $('#modalEditPertanyaan').modal('show');
                } else {
                    alert('Data kosong atau tidak ditemukan di database.');
                }
            },
            error: function(xhr, status, error) {
                // Memberikan pesan error spesifik jika terjadi kegagalan (misal: 404 Not Found / 500 Server Error)
                console.error("AJAX Error: Status " + xhr.status + " - " + error);
                alert('Gagal memuat data! (Error Code: ' + xhr.status + '). Periksa console log browser Anda.');
            }
        });
    });
});
</script>
