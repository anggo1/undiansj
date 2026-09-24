<?php
$this->load->view('admin/komponen/header');
$this->load->view('admin/komponen/sidebar');
$this->load->view('admin/komponen/navbar');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <span class="text-xs font-weight-bold text-primary text-uppercase tracking-wide">Lucky Draw
                Management</span>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Laporan Pemenang Hadiah</h1>
            <p class="text-gray-600 small mb-0">Rekap seluruh pemenang berdasarkan hadiah yang diperoleh.</p>
        </div>
        <!-- Tombol Export Excel Cantik -->
        <a href="<?= base_url('Admin/export_excel') ?>"
            class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm font-weight-bold px-3">
            <i class="fas fa-file-excel fa-sm text-white-50 mr-2"></i> Export ke Excel
        </a>
    </div>

    <!-- Ringkasan Hadiah (Summary Section) -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-box-open mr-2 text-gray-500"></i> Ringkasan
                Hadiah</h5>
        </div>

        <?php if (!empty($summary)): ?>
        <?php foreach ($summary as $item): ?>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"
                                style="min-height: 32px;">
                                <?= htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= (int) $item['total_winners']; ?> <span
                                    class="text-gray-500 small font-weight-normal">Pemenang</span>
                            </div>
                        </div>
                        <div class="col-auto text-right">
                            <i class="fas fa-trophy fa-2x text-gray-300 mb-2"></i>
                            <div class="text-xs badge badge-warning p-1.5 font-weight-bold">
                                Sisa Stok: <?= (int) $item['stock']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="col-12">
            <div class="card bg-light p-4 text-center text-gray-500 shadow-sm" style="border-radius: 12px;">
                <i class="fas fa-folder-open fa-2x mb-2 text-gray-400"></i> Belum ada data ringkasan hadiah.
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Detail Pemenang (Table Section) -->
    <div class="card shadow mb-5" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header py-3 bg-white border-bottom">
            <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-users mr-2 text-gray-500"></i> Detail Pemenang
                Undian</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-gray-100 text-dark font-weight-bold">
                        <tr>
                            <th style="width: 5%">#</th>
                            <th>No Undian</th>
                            <th>Hadiah</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Departemen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($winners)): foreach ($winners as $index => $winner): ?>
                        <tr>
                            <td class="align-middle text-center font-weight-bold text-gray-600"><?= $index + 1; ?></td>
                            <td class="align-middle font-weight-bold text-gray-800">
                                <?= htmlspecialchars($winner['employee_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="align-middle">
                                <span class="badge badge-primary px-3 py-2 font-weight-bold shadow-sm"
                                    style="border-radius: 6px;">
                                    <i class="fas fa-gift mr-1"></i>
                                    <?= htmlspecialchars($winner['item_name'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td class="align-middle font-weight-bold text-gray-800">
                                <?= htmlspecialchars($winner['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="align-middle text-primary font-weight-bold"
                                style="font-family: monospace; letter-spacing: 0.5px;">
                                <?= htmlspecialchars($winner['nik'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td class="align-middle text-gray-700">
                                <?= htmlspecialchars($winner['department'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">
                                <i class="fas fa-user-slash mr-2"></i> Belum ada pemenang yang disimpan.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="mb-5">
        <a class="btn btn-sm btn-light font-weight-bold border shadow-sm text-gray-700"
            href="<?= site_url('admin/items'); ?>">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Kelola Hadiah
        </a>
    </div>

</div>
<!-- /.container-fluid -->

<?php $this->load->view('admin/komponen/footer'); ?>