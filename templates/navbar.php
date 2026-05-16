<!-- Navbar -->
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

            <button class="notification-btn position-relative">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </button>

            <div class="dropdown">

                <button class="user-profile dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>

                    <div class="user-info text-start d-none d-md-block">
                        <h6 class="mb-0">
                            <?php echo $_SESSION['nama']; ?>
                        </h6>

                        <small>
                            <?php echo ucfirst($_SESSION['role']); ?>
                        </small>
                    </div>
                </button>

                <ul class="dropdown-menu dropdown-menu-end custom-dropdown">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user-circle me-2"></i>
                            Profile
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-gear me-2"></i>
                            Pengaturan
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item text-danger" href="../logout.php">
                            <i class="fas fa-right-from-bracket me-2"></i>
                            Logout
                        </a>
                    </li>
                </ul>

            </div>

        </div>

    </div>

</nav>