

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url()?>admin">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">DRPRZ <sup>2</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="<?= base_url()?>admin">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                System
            </div>
          

           <!-- Menu  -->
            

            <li class="nav-item">
                <a class="nav-link" href="<?= site_url('Welcome') ?>" target="_blank">
            <i class="fas fa-external-link-alt fa-sm text-white-50 mr-1"></i>
                    <span>Undi Hadiah</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url()?>admin/tamu">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Data Peserta Undian</span></a>
            </li>
             <li class="nav-item">
                <a class="nav-link" href="<?= base_url()?>Admin/manage_draw">
                    <i class="fas fa-fw fa-gift"></i>
                    <span>Data Undian</span></a>
            </li>
             <li class="nav-item">
                <a class="nav-link" href="<?= base_url()?>admin/items">
                    <i class="fas fa-fw fa-gift"></i>
                    <span>Data Hadiah</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url()?>Survey/title">
                    <i class="fas fa-fw fa-laptop-code"></i>
                    <span>Data Kuesioner</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url()?>admin/Pertanyaan">
                    <i class="fas fa-fw fa-list-alt"></i>
                    <span>Pertanyaan Kuesioner</span></a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url()?>admin/winners_report">
                    <i class="fas fa-fw fa-gifts"></i>
                    <span>Report Undian</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url()?>Survey/report_survey">
                    <i class="fas fa-fw fa-list-ol"></i>
                    <span>Report Kuesioner</span></a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="<?= base_url()?>admin/user">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Data User</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            

        </ul>
        <!-- End of Sidebar -->
