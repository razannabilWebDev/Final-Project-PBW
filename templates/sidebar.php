<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="dashboard/admin.php" class="sidebar-brand">
            <i class="fas fa-store"></i>
            <span class="menu-text d-none d-md-inline">WK</span>
        </a>
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <ul class="sidebar-menu">
        <li class="nav-item">
            <a href="dashboard/admin.php" class="<?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>
        
        <li class="nav-item has-submenu <?php echo in_array($current_page, ['barang']) ? 'active' : ''; ?>">
            <a href="#barangSubmenu" class="submenu-toggle">
                <i class="fas fa-box"></i>
                <span class="menu-text">Barang</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="submenu">
                <li><a href="barang/index.php">Daftar Barang</a></li>
                <li><a href="barang/tambah.php">Tambah Barang</a></li>
            </ul>
        </li>
        
        <li class="nav-item has-submenu <?php echo in_array($current_page, ['supplier']) ? 'active' : ''; ?>">
            <a href="#supplierSubmenu" class="submenu-toggle">
                <i class="fas fa-truck"></i>
                <span class="menu-text">Supplier</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="submenu">
                <li><a href="supplier/index.php">Daftar Supplier</a></li>
                <li><a href="supplier/tambah.php">Tambah Supplier</a></li>
            </ul>
        </li>
        
        <li class="nav-item has-submenu <?php echo in_array($current_page, ['pelanggan']) ? 'active' : ''; ?>">
            <a href="#pelangganSubmenu" class="submenu-toggle">
                <i class="fas fa-users"></i>
                <span class="menu-text">Pelanggan</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="submenu">
                <li><a href="pelanggan/index.php">Daftar Pelanggan</a></li>
                <li><a href="pelanggan/tambah.php">Tambah Pelanggan</a></li>
            </ul>
        </li>
        
        <li class="nav-item <?php echo $current_page == 'transaksi' ? 'active' : ''; ?>">
            <a href="transaksi/index.php">
                <i class="fas fa-shopping-cart"></i>
                <span class="menu-text">Transaksi</span>
            </a>
        </li>
        
        <li class="nav-item has-submenu <?php echo in_array($current_page, ['laporan']) ? 'active' : ''; ?>">
            <a href="#laporanSubmenu" class="submenu-toggle">
                <i class="fas fa-chart-bar"></i>
                <span class="menu-text">Laporan</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="submenu">
                <li><a href="laporan/penjualan.php">Penjualan</a></li>
                <li><a href="laporan/stok.php">Stok Barang</a></li>
                <li><a href="laporan/pelanggan.php">Pelanggan</a></li>
            </ul>
        </li>
        
        <?php if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'owner'): ?>
        <li class="nav-item has-submenu <?php echo in_array($current_page, ['user']) ? 'active' : ''; ?>">
            <a href="#userSubmenu" class="submenu-toggle">
                <i class="fas fa-user-shield"></i>
                <span class="menu-text">Pengguna</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="submenu">
                <li><a href="user/index.php">Daftar User</a></li>
                <li><a href="user/tambah.php">Tambah User</a></li>
            </ul>
        </li>
        <?php endif; ?>
    </ul>
</nav>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>