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
        <div class="col-lg-5 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus mr-1"></i> Tambah Pertanyaan</h6>
                </div>
                <div class="card-body">
                    <?= form_open('survey/add_question') ?>
                        
                        <div class="form-group">
                            <label class="font-weight-bold">Pertanyaan</label>
                            <input required name="question_text" class="form-control" placeholder="Contoh: Seberapa puas Anda?">
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
                                    <input type="checkbox" class="custom-control-input" id="is_required" name="is_required" value="1">
                                    <label class="custom-control-label font-weight-bold text-danger" for="is_required">Wajib Isi</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Pilihan Jawaban</label>
                            <textarea name="options" rows="4" class="form-control" placeholder="Sangat Puas&#10;Puas&#10;Cukup Puas"></textarea>
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle mr-1"></i> Isi satu pilihan per baris. Gunakan hanya untuk pilihan ganda, kotak centang, atau daftar pilihan.
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
        <div class="col-lg-7 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list mr-1"></i> Daftar Pertanyaan</h6>
                </div>
                <div class="card-body p-0">
                    <?php if(empty($questions)): ?>
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2 text-gray-400"></i>
                            <p class="mb-0">Belum ada pertanyaan. Tambahkan pertanyaan pertama di panel kiri.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" width="100%" cellspacing="0">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%" class="text-center font-weight-bold text-gray-700">#</th>
                                        <th width="65%" class="font-weight-bold text-gray-700">Pertanyaan</th>
                                        <th width="20%" class="font-weight-bold text-gray-700">Tipe</th>
                                        <th width="10%" class="text-center font-weight-bold text-gray-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($questions as $i => $q): ?>
                                        <tr>
                                            <td class="text-center align-middle"><?= $i+1 ?></td>
                                            <td class="align-middle">
                                                <span class="font-weight-bold text-gray-800"><?= html_escape($q->question_text) ?></span>
                                                <?= $q->is_required ? '<span class="badge badge-danger ml-1">Wajib</span>' : '' ?>
                                            </td>
                                            <td class="align-middle text-capitalize">
                                                <span class="badge badge-secondary"><?= html_escape($q->question_type) ?></span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <a class="btn btn-danger btn-circle btn-sm shadow-sm" 
                                                   onclick="return confirm('Hapus pertanyaan ini?')" 
                                                   href="<?= site_url('survey/delete_question/'.$q->id) ?>" 
                                                   title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php $this->load->view('admin/komponen/footer'); ?>
