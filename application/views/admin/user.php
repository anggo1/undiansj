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
			<div class="col-sm-3"><h6 class="m-0 font-weight-bold text-primary">Data Tamu</h6></div>
			<div class="col-sm-9" align="right">
				<a href="#" class="btn btn-success" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus-circle"></i> Tambah User</a>
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
						<th>Username</th>
						<th>Password</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php $no=1; foreach ($user as $t) { ?>
					<tr>
						<td><?= $no++?></td>
						<td><?= $t->username?></td>
						<td><?= $t->password?></td>
						<td><a href="#" class="btn btn-primary" data-toggle="modal" data-target="#edit<?= $t->id?>"><i class="fa fa-pen"></i></a> <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#hapus<?= $t->id?>"><i class="fa fa-trash"></i></a></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

</div>
<!-- /.container-fluid -->

<!-- Modal Tambah -->
<div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?= form_open('auth/tambah');?>
			<div class="form-group">
				<label for="exampleInputFile">Username</label>
				<input type="text" class="form-control" name="uname">
			</div>
			<div class="form-group">
				<label for="exampleInputFile">Password</label>
				<input type="password" class="form-control" name="password">
			</div>
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" >Simpan</button>
					</form>
      </div>
    </div>
  </div>
</div>
<?php foreach ($user as $t) { ?>
<!-- Modal Edit -->
<div class="modal fade" id="edit<?= $t->id?>" tabindex="-1" aria-labelledby="edit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="edit">Edit User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url()?>auth/update" method="post">
			<div class="form-group">
				<label for="exampleInputFile">Username</label>
				<input type="text" class="form-control" name="username" value="<?= $t->username?>">
				<input type="hidden" class="form-control" name="id" value="<?= $t->id?>">
			</div>
			<div class="form-group">
				<label for="exampleInputFile">Password</label>
				<input type="password" class="form-control" name="pwd" value="<?= $t->password?>">
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" >Update</button>
		</form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="hapus<?= $t->id?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Hapus User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
			<h3>Apakah ingin Menghapus user <?= $t->username?> ?</h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
       <a href="<?= base_url()?>auth/hapus/<?= $t->id?>" class="btn btn-danger">Ya</a>
		
      </div>
    </div>
  </div>
</div>
<?php } ?>
<?php $this->load->view('admin/komponen/footer'); ?>
