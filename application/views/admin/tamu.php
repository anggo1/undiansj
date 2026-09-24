<?php

$this->load->view('admin/komponen/header');
$this->load->view('admin/komponen/sidebar');
$this->load->view('admin/komponen/navbar');

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Tamu</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="col-sm-12 row">
                <div class="col-sm-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Tamu</h6>
                </div>
                <div class="col-sm-9" align="right"><a href="<?= base_url('Admin/export') ?>" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#exampleModal"><i
                            class="fa fa-plus-circle"></i> Import Data (Excel)</a>
                    <a href="" class="btn btn-danger" data-toggle="modal" data-target="#exampleModaldelete"><i
                            class="fa fa-trash"></i> Reset Semua Data</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?= $this->session->flashdata('success'); ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Departemen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($tamu as $t) { ?>
                        <tr>
                            <td><?= $no++?></td>
                            <td><?= $t->nik?></td>
                            <td><?= $t->name?></td>
                            <td><?= $t->department?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<!-- Modal Import -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Tamu ( Excel )</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open_multipart('excel/import');?>
                <div class="form-group">
                    <label for="exampleInputFile">File Excel</label>
                    <input type="file" class="form-control" id="exampleInputFile" name="excel">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Import</button>
                <? form_close();?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="exampleModaldelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reset Data Tamu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <h3>Apakah ingin mereset semua data tamu ?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="<?= base_url()?>excel/reset" class="btn btn-danger">Ya</a>

            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/komponen/footer'); ?>