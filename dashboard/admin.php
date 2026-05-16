<?php 
session_start();
require '../config/koneksi.php';
require '../config/session.php';
cek_login_admin();

// Set current page untuk sidebar
$current_page = 'dashboard';
$page_title = 'Dashboard Admin';
?>

<?php include '../templates/header.php'; ?>
<?php include '../templates/navbar.php'; ?>
<?php include '../templates/sidebar.php'; ?>

<div class="main-content">
    <div class="content-header">
        <div class="row align-items-center">
            <div class="col">
                <h1><i class="fas fa-tachometer-alt text-primary me-2"></i>Dashboard</h1>
                <p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p>
            </div>
        </div>
    </div>
    
    <div class="content-body">
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="text-primary mb-0">150</h3>
                                <p class="text-muted mb-0">Total Barang</p>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Stats cards lainnya... -->
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>