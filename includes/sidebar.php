<?php
// Prevent direct access
if (!defined('SIDEBAR_INCLUDED')) {
    define('SIDEBAR_INCLUDED', true);
}

// Get current page name
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_uri = $_SERVER['REQUEST_URI'];

// Function to check if menu item is active
function isActiveMenu($path_segment) {
    global $current_uri;
    // Adjust path segment for exact matching where necessary
    switch ($path_segment) {
        case 'dashboard':
            return (strpos($current_uri, '/dashboard.php') !== false) ? 'active' : '';
        case 'transaksi':
            return (strpos($current_uri, '/modules/transaksi/') !== false) ? 'active' : '';
        case 'customer':
            return (strpos($current_uri, '/modules/customer/') !== false) ? 'active' : '';
        case 'sales':
            return (strpos($current_uri, '/modules/sales/') !== false) ? 'active' : '';
        case 'petugas':
            return (strpos($current_uri, '/modules/petugas/') !== false) ? 'active' : '';
        case 'produk':
            return (strpos($current_uri, '/modules/produk/') !== false) ? 'active' : '';
        case 'logout':
            return (strpos($current_uri, '/auth/logout.php') !== false) ? 'active' : '';
        default:
            return '';
    }
}

// Function to get correct path based on current location
function getPath($path) {
    // Check if we're in a module subdirectory
    $current_dir = dirname($_SERVER['PHP_SELF']);
    if (strpos($current_dir, '/modules/') !== false) {
        return '../../' . $path;
    } else {
        return $path;
    }
}
?>
<style>
.main-sidebar,
.main-sidebar .sidebar,
.main-sidebar .nav-sidebar>.nav-item>.nav-link,
.main-sidebar .nav-sidebar .nav-treeview>.nav-item>.nav-link {
    background-color: rgb(55, 55, 55) !important;
    color: rgb(255, 255, 255) !important;
    font-family: 'Poppins', sans-serif;
}

.main-sidebar .nav-sidebar>.nav-item>.nav-link.active,
.main-sidebar .nav-sidebar>.nav-item>.nav-link:hover {
    background-color: rgb(55, 158, 74) !important;
    color: #fff !important;
}

/* Enhanced active state styling */
.main-sidebar .nav-sidebar>.nav-item>.nav-link.active {
    background-color: rgb(55, 158, 74) !important;
    color: #fff !important;
    border-left: 4px solid #fff !important;
    font-weight: 600 !important;
}
</style>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4"
    style="background-color:rgb(255, 255, 255); color: #e0e0e0; font-family: 'Poppins', sans-serif;">
    <!-- Brand Logo -->
    <a href="<?php echo getPath('dashboard.php'); ?>" class="brand-link"
        style="color: #e0e0e0; font-family: 'Poppins', sans-serif; text-align: center;">
        <span class="brand-text font-weight-light text-center"
            style="font-family: 'Poppins', sans-serif; text-align: center; display: block;">Koperasi
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="font-family: 'Poppins', sans-serif;">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image mr-3">
                <i class="fas fa-user-circle"
                    style="font-size: 2.5rem; color:rgb(44, 161, 65); background: rgba(255,255,255,0.1); border-radius: 50%; padding: 5px;"></i>
            </div>
            <div class="info">
                <a href="#" class="d-block"
                    style="color: #e0e0e0; font-weight: 500; text-decoration: none;"><?php echo $_SESSION['nama']; ?></a>
                <small class="text-success"
                    style="font-size: 0.85rem; font-weight: 400;"><?php echo ucfirst($_SESSION['level']); ?></small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false"
                style="color: #e0e0e0; font-family: 'Poppins', sans-serif;">
                <li class="nav-item">
                    <a href="<?php echo getPath('dashboard.php'); ?>"
                        class="nav-link <?php echo isActiveMenu('dashboard'); ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo getPath('modules/transaksi/index.php'); ?>"
                        class="nav-link <?php echo isActiveMenu('transaksi'); ?>">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Transaksi</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo getPath('modules/customer/index.php'); ?>"
                        class="nav-link <?php echo isActiveMenu('customer'); ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Customer</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo getPath('modules/sales/index.php'); ?>"
                        class="nav-link <?php echo isActiveMenu('sales'); ?>">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Sales</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo getPath('modules/petugas/index.php'); ?>"
                        class="nav-link <?php echo isActiveMenu('petugas'); ?>">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>Petugas</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo getPath('modules/produk/index.php'); ?>"
                        class="nav-link <?php echo isActiveMenu('produk'); ?>">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Produk</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo getPath('auth/logout.php'); ?>"
                        class="nav-link <?php echo isActiveMenu('logout'); ?>">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>