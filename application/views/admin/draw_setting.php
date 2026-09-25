<!-- Tampilan Halaman Utama SB Admin -->
<?php
$this->load->view('admin/komponen/header');
$this->load->view('admin/komponen/sidebar');
$this->load->view('admin/komponen/navbar');
?>

<!-- PANGGIL LIBRARY SWEETALERT2 & DATATABLES -->

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Undian / Survey</h1>
    </div>

    <div class="row">
        <!-- Kolom Kiri: Form TAMBAH Data -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus mr-1"></i> Tambah Undian Baru
                    </h6>
                </div>
                <div class="card-body">
<?= form_open_multipart('Admin/add_survey_process', ['id' => 'formTambahSurvey']) ?>

                    <div class="form-group">
                        <label class="font-weight-bold">Judul Undian / Survey</label>
                        <input type="text" required name="title" class="form-control"
                            placeholder="Contoh: HUT Sinar Jaya Group">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Keterangan / Deskripsi</label>
                        <textarea name="description" rows="3" class="form-control"
                            placeholder="Contoh: Sistem pengundian otomatis doorprize utama..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Upload Logo (Opsional)</label>
                        <div class="custom-file">
                            <input type="file" name="logo" class="custom-file-input" id="logoUpload" accept="image/*">
                            <label class="custom-file-label" for="logoUpload">Pilih Gambar...</label>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-block shadow-sm mt-4" type="submit">
                        <i class="fas fa-save mr-1"></i> Simpan Undian
                    </button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: DAFTAR Data (Tabel & Aksi Edit/Hapus) -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list mr-1"></i> Daftar Acara Undian
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0" id="tableSurvey" width="100%"
                            cellspacing="0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th width="15%" class="text-center">Logo</th>
                                    <th>Nama Undian / Keterangan</th>
                                    <th width="20%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($surveys)): foreach($surveys as $i => $s): ?>
                                <tr>
                                    <td class="text-center align-middle"><?= $i+1 ?></td>
                                    <td class="text-center align-middle">
                                        <img src="<?= !empty($s->logo) ? base_url('uploads/logo/'.$s->logo) : base_url('assets/img/default-logo.png') ?>"
                                            class="img-thumbnail"
                                            style="max-height: 50px; max-width: 60px; object-fit: contain;">
                                    </td>
                                    <td class="align-middle">
                                        <strong class="text-gray-800"><?= html_escape($s->title) ?></strong>
                                        <p class="text-muted small mb-0"><?= html_escape($s->description) ?></p>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-warning btn-circle btn-sm shadow-sm mr-1 btn-edit-survey"
                                            data-id="<?= $s->id ?>" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button class="btn btn-danger btn-circle btn-sm shadow-sm btn-hapus-survey"
                                            data-href="<?= site_url('admin/delete_survey_process/'.$s->id) ?>"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

<!-- MODAL EDIT DATA -->
<div class="modal fade" id="modalEditSurvey" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fas fa-edit mr-1"></i> Edit Data Undian</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <?= form_open_multipart('admin/edit_survey_process', ['id' => 'formEditSurvey']) ?>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_id">

                <div class="form-group">
                    <label class="font-weight-bold">Judul Undian / Survey</label>
                    <input type="text" required name="title" id="edit_title" class="form-control">
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Keterangan / Deskripsi</label>
                    <textarea name="description" id="edit_description" rows="3" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Ganti Logo (Kosongkan jika tidak diubah)</label>
                    <div class="custom-file">
                        <input type="file" name="logo" class="custom-file-input" id="logoEditUpload" accept="image/*">
                        <label class="custom-file-label" for="logoEditUpload">Pilih Gambar Baru...</label>
                    </div>
                    <div id="edit_logo_preview_container" class="mt-3 text-center d-none">
                        <p class="small text-muted mb-1">Logo Saat Ini:</p>
                        <img id="edit_logo_preview" src="" class="img-thumbnail" style="max-height: 70px;">
                    </div>
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
<!-- SCRIPT JQUERY DATATABLES, AJAX FETCH & SWAL DELETE -->
<script>
$(document).ready(function() {
    // 1. INPUT FILE CUSTOM LABEL
    $(document).on('change', '.custom-file-input', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // 2. PROSES AJAX TAMBAH DATA
        // 2. PROSES AJAX TAMBAH DATA (Perbaikan agar Swal Pasti Tampil)
    $('#formTambahSurvey').on('submit', function(e) {
        e.preventDefault(); // MUTLAK: Mencegah form melakukan redirect/reload halaman
        
        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: new FormData(this), // Menggunakan FormData untuk menghandle upload file logo
            processData: false,
            contentType: false,
            dataType: "JSON",
            beforeSend: function() {
                // Menampilkan animasi loading SweetAlert sebelum data terkirim
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang memproses data undian.',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
            },
            success: function(response) {
                if(response.status === 'success') {
                    // TAMPILKAN SWAL SAAT INPUT SUKSES
                    Swal.fire({
                        icon: 'success',
                        title: 'Input Sukses!',
                        text: response.message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Cara aman mereset form di jQuery tanpa memicu error crash script
                            $('#formTambahSurvey')[0].reset(); 
                            $('.custom-file-label').removeClass("selected").html("Pilih Gambar...");
                            
                            // Muat ulang halaman untuk memperbarui data tabel
                            location.reload(); 
                        }
                    });
                } else {
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Gagal Menyimpan!', 
                        text: response.message 
                    });
                }
            },
            error: function(xhr, status, error) {
                // Jika terjadi error, tampilkan detailnya di console untuk tracing
                console.error("AJAX Error: ", xhr.responseText);
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Error Sistem!', 
                    text: 'Gagal terhubung ke server atau terjadi kesalahan sistem.' 
                });
            }
        });
    });


    // 3. LOGIK TOMBOL EDIT (Tampilkan Modal Data)
    $('#tableSurvey').on('click', '.btn-edit-survey', function() {
        const id = $(this).data('id');
        $.ajax({
            url: "<?= site_url('Admin/get_survey_json/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                if(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_title').val(data.title);
                    $('#edit_description').val(data.description);
                    
                    if(data.logo) {
                        $('#edit_logo_preview').attr('src', "<?= base_url('uploads/logo/') ?>" + data.logo);
                        $('#edit_logo_preview_container').removeClass('d-none');
                    } else {
                        $('#edit_logo_preview_container').addClass('d-none');
                    }
                    
                    $('#modalEditSurvey').modal('show');
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Gagal mengambil data dari server.' });
            }
        });
    });

    // 4. PROSES AJAX SIMPAN EDIT DATA
    $('#formEditSurvey').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: "JSON",
            beforeSend: function() {
                Swal.fire({
                    title: 'Memperbarui...',
                    text: 'Sedang menyimpan perubahan.',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
            },
            success: function(response) {
                if(response.status === 'success') {
                    $('#modalEditSurvey').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Diperbarui!',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: response.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Error!', text: 'Terjadi kesalahan saat memperbarui data.' });
            }
        });
    });

    // 5. PROSES AJAX HAPUS DATA
    $('#tableSurvey').on('click', '.btn-hapus-survey', function() {
        const url = $(this).data('href');
        const row = $(this).closest('tr'); // Tangkap baris tabel yang akan dihapus
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data undian ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang menghapus data.',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });
                    },
                    success: function(response) {
                        if(response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: response.message
                            });
                            row.fadeOut(500, function() { 
                                $(this).remove(); // Hapus baris secara visual tanpa reload halaman
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal!', text: response.message });
                        }
                    },
                    error: function() {
                        Swal.fire({ icon: 'error', title: 'Error!', text: 'Gagal menghapus data dari server.' });
                    }
                });
            }
        });
    });
});
</script>
