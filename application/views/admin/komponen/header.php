<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard Doorprize</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url()?>assets/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url()?>assets/admin/css/sb-admin-2.min.css" rel="stylesheet">
	<link href="<?= base_url()?>assets/admin/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
			
<?php 
    $status = $this->session->userdata('status');
    if ($status == 'login') {
	 
    } else {
		$this->session->set_flashdata('alert','<div class="alert alert-danger">Silahkan login terlebih dahulu untuk masuk ke halaman admin !</div>');
        redirect (base_url('login'));
	}

?>
