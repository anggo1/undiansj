<?php
$this->load->view('admin/komponen/header');
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= html_escape($survey->title) ?></title>

    <link href="<?= base_url()?>assets/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url()?>assets/admin/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/admin/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f0ebf8;
        color: #202124;
        font-family: Arial, sans-serif;
    }

    /* Aksen garis atas ungu khas kuesioner premium */
    .card-hero {
        border-top: 10px solid #4e73df !important;
    }

    /* Animasi fokus garis bawah ala Google Forms untuk input teks */
    .form-underline {
        border: 0;
        border-bottom: 1px solid #d1d3e2;
        border-radius: 0;
        padding-left: 2px;
        padding-right: 2px;
        background-color: transparent !important;
        transition: border-color 0.2s ease-in-out;
    }

    .form-underline:focus {
        border-bottom: 2px solid #4e73df;
        box-shadow: none;
    }

    .is-invalid.form-underline:focus {
        border-bottom: 2px solid #e74a3b;
    }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                <!-- Card Utama: Informasi / Judul Survey -->
                <div class="card card-hero shadow-sm border-0 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="display-5 font-weight-bold text-gray-900 mb-3"><?= html_escape($survey->title) ?>
                        </h1>
                        <p class="text-gray-600 lead mb-4"><?= nl2br(html_escape($survey->description)) ?></p>
                        <hr>
                        <p class="text-danger small mb-0 font-weight-bold">* Wajib diisi</p>
                    </div>
                </div>

                <!-- Notifikasi Gagal dari Flashdata Server -->
                <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger shadow-sm border-left-danger alert-dismissible fade show mb-4"
                    role="alert">
                    <h5 class="alert-heading font-weight-bold"><i class="fas fa-id-card-alt mr-2"></i> Pengisian Gagal
                    </h5>
                    <div class="small"><?= $this->session->flashdata('error') ?></div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif; ?>

                <!-- Notifikasi Error Validasi Bawaan CodeIgniter -->
                <?php if (validation_errors()): ?>
                <div class="alert alert-danger shadow-sm border-left-danger alert-dismissible fade show mb-4"
                    role="alert">
                    <h5 class="alert-heading font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Periksa
                        Kembali Isian Anda</h5>
                    <div class="small"><?= validation_errors() ?></div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif; ?>

                <!-- Form Pengiriman Data -->
                <?= form_open('survey/submit', array('id' => 'surveyForm')) ?>

                <?php foreach ($questions as $q): 
                        $options = array_filter(array_map('trim', explode("\n", $q->options))); 
                        // Deteksi otomatis jika teks pertanyaan mengandung unsur kata 'nik'
                        $is_nik = (strpos(strtolower($q->question_text), 'nik') !== false); 
                    ?>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <!-- Label Detail Pertanyaan -->
                        <p class="h6 font-weight-bold text-gray-900 mb-4">
                            <?= html_escape($q->question_text) ?>
                            <?= $q->is_required ? '<span class="text-danger font-weight-bold ml-1">*</span>' : '' ?>
                        </p>

                        <div class="form-group mb-0">
                            <!-- Render: TEXT INPUT -->
                            <?php if ($q->question_type === 'text'): ?>
                            <input type="text" name="answer[<?= $q->id ?>]"
                                class="form-control form-underline text-gray-800 <?= $is_nik ? 'nik-input' : '' ?>"
                                data-qid="<?= $q->id ?>"
                                placeholder="<?= $is_nik ? 'Masukkan NIK Anda (Minimal 4 angka)' : 'Jawaban singkat Anda' ?>"
                                /* Diatur agar mendukung input angka dengan minimal 4 digit */
                                <?= $is_nik ? 'inputmode="numeric" pattern="[0-9]{4,16}" maxlength="16"' : '' ?>
                                value="<?= html_escape(set_value('answer['.$q->id.']')) ?>">

                            <?php if ($is_nik): ?>
                            <!-- Ruang feedback pesan peringatan NIK -->
                            <div class="nik-feedback small font-weight-bold mt-2" style="display:none;"></div>
                            <?php endif; ?>

                            <!-- Render: TEXTAREA PARAGRAF -->
                            <?php elseif ($q->question_type === 'textarea'): ?>
                            <textarea name="answer[<?= $q->id ?>]" class="form-control text-gray-800" rows="4"
                                placeholder="Jawaban panjang Anda"><?= html_escape(set_value('answer['.$q->id.']')) ?></textarea>

                            <!-- Render: SELECT OPTION DROPDOWN -->
                            <?php elseif ($q->question_type === 'select'): ?>
                            <select name="answer[<?= $q->id ?>]" class="custom-select text-gray-800">
                                <option value="">Pilih jawaban</option>
                                <?php foreach($options as $o): ?>
                                <option value="<?= html_escape($o) ?>" <?= set_select('answer['.$q->id.']', $o) ?>>
                                    <?= html_escape($o) ?></option>
                                <?php endforeach; ?>
                            </select>

                            <!-- Render: RADIO BUTTON / CHECKBOX BOX -->
                            <?php else: ?>
                            <?php foreach($options as $key => $o): 
                                            $unique_id = 'opt_' . $q->id . '_' . $key;
                                            $is_checkbox = ($q->question_type === 'checkbox');
                                        ?>
                            <div class="custom-control <?= $is_checkbox ? 'custom-checkbox' : 'custom-radio' ?> mb-3">
                                <input type="<?= $is_checkbox ? 'checkbox' : 'radio' ?>" id="<?= $unique_id ?>"
                                    name="answer[<?= $q->id ?>]<?= $is_checkbox ? '[]' : '' ?>"
                                    class="custom-control-input" value="<?= html_escape($o) ?>"
                                    <?= set_checkbox('answer['.$q->id.']', $o) ?>
                                    <?= set_radio('answer['.$q->id.']', $o) ?>>
                                <label class="custom-control-label text-gray-800" for="<?= $unique_id ?>">
                                    <?= html_escape($o) ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Tombol Aksi Submit Form -->
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <button type="submit" id="btnSubmit" class="btn btn-primary px-5 py-2 shadow font-weight-bold">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Tanggapan
                    </button>
                </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>

    <script src="<?= base_url()?>assets/admin/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url()?>assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url()?>assets/admin/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url()?>assets/admin/js/sb-admin-2.min.js"></script>

    <!-- Script Utama Validasi AJAX Real-time -->
    <script>
    // Cek NIK saat mengetik (keyup) atau saat kursor berpindah fokus (blur)
    $(document).ready(function() {
        // Fungsi utama pengecekan NIK
        $('.nik-input').on('blur keyup', function() {
            var inputField = $(this);
            var nikValue = inputField.val().trim();
            var questionId = inputField.data('qid');
            var feedbackDiv = inputField.siblings('.nik-feedback');
            var submitBtn = $('#btnSubmit');
            // BARU: Pengecekan AJAX aktif jika panjang karakter minimal 4 angka
            if (nikValue.length >= 4) {
                feedbackDiv.removeClass('text-success text-danger').addClass('text-muted').html(
                    ' Memeriksa basis data NIK...').show();
                $.ajax({
                    url: '<?= site_url("survey/check_nik_ajax") ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        question_id: questionId,
                        nik: nikValue
                    },
                    success: function(response) {
                        if (response.status === 'exists') {
                            // Jika NIK sama/sudah ada di database
                            feedbackDiv.removeClass('text-muted text-success').addClass(
                                'text-danger').html(
                                ' Maaf, NIK ini sudah pernah digunakan.');
                            inputField.addClass('is-invalid').removeClass('is-valid');
                            submitBtn.prop('disabled', true); // Blokir tombol kirim
                        } else if (response.status === 'available') {
                            // Jika NIK belum ada di database
                            feedbackDiv.removeClass('text-muted text-danger').addClass(
                                'text-success').html(
                                ' NIK valid & belum pernah digunakan.');
                            inputField.removeClass('is-invalid').addClass('is-valid');
                            submitBtn.prop('disabled', false); // Aktifkan tombol kirim
                        }
                    },
                    error: function() {
                        feedbackDiv.hide();
                        submitBtn.prop('disabled', false);
                    }
                });
            } else if (nikValue.length > 0 && nikValue.length < 4) {
                // Peringatan jika pengguna mengetik kurang dari 4 angka
                feedbackDiv.removeClass('text-success text-muted').addClass('text-danger').text(
                    'Format salah. NIK wajib berisi minimal 4 angka.').show();
                inputField.addClass('is-invalid').removeClass('is-valid');
                submitBtn.prop('disabled', true); // Blokir tombol kirim
            } else {
                feedbackDiv.hide();
                inputField.removeClass('is-invalid is-valid');
                submitBtn.prop('disabled', false);
            }
        });
        // Proteksi keyboard: Hanya mengizinkan pengetikan karakter angka (0-9)
        $('.nik-input').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
    </script>
</body>

</html>