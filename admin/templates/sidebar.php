<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion mykosan-sidebar" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= (isset($inside_folder) ? '../index' : 'index');?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Kosan</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item<?= isset($dashboard_active) ? ' active' : '';?>">
        <a class="nav-link" href="<?= (isset($inside_folder) ? '../dashboard' : 'dashboard');?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
    Master
    </div>

    <!-- Nav Item - Charts -->
    
    <li class="nav-item<?= isset($room_active) ? ' active' : '';?>">
        <a class="nav-link" href="<?=(isset($inside_folder) ? '../master/room' : 'master/room');?>">
            <i class="fas fa-fw fa-door-open"></i>
            <span>Room</span>
        </a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item<?= isset($equipment_active) ? ' active' : '';?>">
        <a class="nav-link" href="<?=(isset($inside_folder) ? '../master/equipment' : 'master/equipment');?>">
            <i class="fas fa-fw fa-cog"></i>
            <span>Equipment</span>
        </a>
    </li>

    <li class="nav-item<?= isset($user_active) ? ' active' : '';?>">
        <a class="nav-link" href="<?=(isset($inside_folder) ? '../master/user' : 'master/user');?>">
            <i class="fas fa-fw fa-users-cog"></i>
            <span>Admin</span>
        </a>
    </li>

    <li class="nav-item<?= isset($tenant_active) ? ' active' : '';?>">
        <a class="nav-link" href="<?=(isset($inside_folder) ? '../master/tenant' : 'master/tenant');?>">
            <i class="fas fa-fw fa-users"></i>
            <span>Tenant</span>
        </a>
    </li>

    <li class="nav-item<?= isset($bank_active) ? ' active' : '';?>">
        <a class="nav-link" href="<?=(isset($inside_folder) ? '../master/bank' : 'master/bank');?>">
            <i class="fas fa-fw fa-money-check"></i>
            <span>Bank</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
    Transaction and Invoice
    </div>

    <li class="nav-item<?= isset($invoice_active) ? ' active' : '';?>">
        <a class="nav-link" href="<?=(isset($inside_folder) ? '../invoice/list' : 'invoice/list');?>">
            <i class="fas fa-fw fa-money-bill"></i>
            <span>Invoice</span>
        </a>
    </li>

    <li class="nav-item<?= isset($transaction_active) ? ' active' : '';?>">
        <a class="nav-link" href="<?=(isset($inside_folder) ? '../booking/list' : 'booking/list');?>">
            <i class="fas fa-fw fa-list"></i>
            <span>Booking</span>
        </a>
    </li>

    <li class="nav-item<?= isset($fine_active) ? ' active' : '';?>">
        <a class="nav-link" href="<?=(isset($inside_folder) ? '../fine/list' : 'fine/list');?>">
            <i class="fas fa-fw fa-money-bill-alt"></i>
            <span>Fine</span>
        </a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
    Settings
    </div>

    <li class="nav-item<?= isset($internal_active) ? ' active' : '';?>">
        <a class="nav-link" href="<?=(isset($inside_folder) ? '../settings/internal' : 'settings/internal');?>">
            <i class="fas fa-fw fa-cog"></i>
            <span>Internal Parameter</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <!-- <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div> -->

</ul>
<!-- End of Sidebar -->