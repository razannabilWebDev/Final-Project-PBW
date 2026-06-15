<!-- Navbar -->
<?php 
$username = strtoupper($_SESSION['username']);
$role = strtoupper($_SESSION['role']);

?>
<nav class="navbar navbar-expand-lg custom-navbar">

    <div class="container-fluid">

        <button class="mobile-toggle d-lg-none" id="mobileToggle">
            <i class="fas fa-bars"></i>
        </button>

        <div>
            <h4 class="page-title mb-0">
                Dashboard Warung
            </h4>

            <small class="page-subtitle">
                Selamat datang di sistem informasi warung kelontong
            </small>
        </div>

        <div class="ms-auto d-flex align-items-center gap-3">

            

            <div class="dropdown">

                <button class="user-profile">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>

                    <div class="user-info text-start d-none d-md-block">
                        <h6 class="mb-0">
                            <?php echo $username; ?>
                        </h6>

                        <small>
                            <?php echo $role; ?>
                        </small>
                    </div>
                </button>

                

            </div>

        </div>

    </div>

</nav>