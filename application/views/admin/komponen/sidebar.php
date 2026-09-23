

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
                <a class="nav-link" href="<?= base_url()?>admin/tamu">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Data Tamu</span></a>
            </li>
             <li class="nav-item">
                <a class="nav-link" href="<?= base_url()?>admin/items">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Data Hadiah</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url()?>admin/Pertanyaan">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Data Kuesioner</span></a>
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
