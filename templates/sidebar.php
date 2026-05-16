<?php
$role = $_SESSION['role'] ?? '';
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="sidebar" id="sidebar">

    <div class="sidebar-header">
        <a href="#" class="sidebar-brand text-decoration-none">
            <div class="brand-logo">
                <i class="fas fa-store"></i>
            </div>

            <div class="brand-text">
                <h5>WK Store</h5>
                <span>Management System</span>
            </div>
        </a>
    </div>

    <ul class="sidebar-menu">

        <!-- ADMIN MENU -->
        <?php if($role == 'admin'): ?>

            <li>
                <a href="../dashboard/admin.php" class="<?= $current_page == 'admin.php' ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="../barang/index.php">
                    <i class="fas fa-box"></i>
                    <span>Barang</span>
                </a>
            </li>

            <li>
                <a href="../supplier/index.php">
                    <i class="fas fa-truck"></i>
                    <span>Supplier</span>
                </a>
            </li>

            <li>
                <a href="../pelanggan/index.php">
                    <i class="fas fa-users"></i>
                    <span>Pelanggan</span>
                </a>
            </li>

            <li>
                <a href="../transaksi/index.php">
                    <i class="fas fa-cart-shopping"></i>
                    <span>Transaksi</span>
                </a>
            </li>

            <li>
                <a href="../laporan/index.php">
                    <i class="fas fa-chart-line"></i>
                    <span>Laporan</span>
                </a>
            </li>

            <li>
                <a href="../user/index.php">
                    <i class="fas fa-user-shield"></i>
                    <span>User</span>
                </a>
            </li>

            <li>
                <a href="../register.php">
                    <i class="fas fa-user-plus"></i>
                    <span>Register</span>
                </a>
            </li>

        <?php endif; ?>

        <!-- KASIR MENU -->
        <?php if($role == 'kasir'): ?>

            <li>
                <a href="../dashboard/kasir.php" class="<?= $current_page == 'kasir.php' ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="../transaksi/index.php">
                    <i class="fas fa-cash-register"></i>
                    <span>Transaksi</span>
                </a>
            </li>

            <li>
                <a href="../pelanggan/index.php">
                    <i class="fas fa-users"></i>
                    <span>Pelanggan</span>
                </a>
            </li>

        <?php endif; ?>

    </ul>

    <div class="sidebar-footer">
        <a href="../logout.php" class="logout-btn text-decoration-none d-flex align-items-center justify-content-center gap-2">
            <i class="fas fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>

</nav>