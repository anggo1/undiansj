<?php
$this->load->view('admin/komponen/header');
$this->load->view('admin/komponen/sidebar');
$this->load->view('admin/komponen/navbar');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Laporan Hasil Undian</h1>
    <p class="mb-4">Berikut adalah daftar pemenang undian resmi yang telah ditarik oleh sistem.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-trophy"></i> Data Pemenang Undian</h6>
                <div>
                    <a href="<?= base_url('Admin/export_excel') ?>" class="btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-file-excel fa-sm text-white-50"></i> Unduh Laporan Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?= $this->session->flashdata('message'); ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="bg-light text-dark">
                            <th>No</th>
                            <th>No Undian</th>
                            <th>NIK</th>
                            <th>Nama Pemenang</th>
                            <th>Kategori Hadiah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($pemenang as $p) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><span class="badge badge-primary"><?= $p->employee_id ?></span></td>
                            <td>000<?= $p->nik ?></td>
                            <td><strong><?= $p->name ?></strong></td>
                            <td><?= $p->item_name ?></td>
                            <td>
                                <span class="badge badge-<?= $p->is_won == '1' ? 'success' : 'warning' ?>">
                                    <?= $p->is_won ?>
                                </span>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php $this->load->view('admin/komponen/footer'); ?>
