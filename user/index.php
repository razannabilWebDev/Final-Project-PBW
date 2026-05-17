<?php

session_start();

require '../config/koneksi.php';
require '../config/session.php';

cek_login_admin();


$page_title = "Data User";
$current_page = "user";

$queryUser = mysqli_query($conn,"
    SELECT *
    FROM user
    ORDER BY id_user DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Data User</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          rel="stylesheet">

    <link href="../assets/css/style.css"
          rel="stylesheet">
</head>

<body>

<div class="wrapper">

    <?php include '../templates/sidebar.php'; ?>

    <div class="main-content">


        <div class="content-body">

            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h2 class="fw-bold mb-1">
                            Data User
                        </h2>

                        <p class="text-muted mb-0">
                            Kelola akun pengguna sistem
                        </p>
                    </div>

                    <a href="../register.php"
                       class="btn btn-primary rounded-4">

                        <i class="fas fa-plus me-2"></i>
                        Tambah User

                    </a>

                </div>

                <div class="card border-0 shadow rounded-4">

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>

                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>

                                </thead>

                                <tbody>

                                <?php
                                $no = 1;

                                while($user = mysqli_fetch_assoc($queryUser)):
                                ?>

                                    <tr>

                                        <td><?= $no++; ?></td>

                                        <td>
                                            <?= $user['username']; ?>
                                        </td>

                                        <td>
                                            <?= $user['email']; ?>
                                        </td>

                                        <td>

                                            <?php if($user['role'] == 'admin'): ?>

                                                <span class="badge bg-primary">
                                                    Admin
                                                </span>

                                            <?php else: ?>

                                                <span class="badge bg-success">
                                                    Kasir
                                                </span>

                                            <?php endif; ?>

                                        </td>

                                        <td>

                                            <?php if($user['id_user'] != $_SESSION['id_user']): ?>

                                            <a href="delete.php?id=<?= $user['id_user']; ?>"
                                               class="btn btn-danger btn-sm rounded-3"
                                               onclick="return confirm('Yakin ingin menghapus user ini?')">

                                                <i class="fas fa-trash"></i>

                                            </a>

                                            <?php else: ?>

                                                <span class="text-muted">
                                                    Akun Anda
                                                </span>

                                            <?php endif; ?>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <?php include '../templates/footer.php'; ?>

    </div>

</div>

</body>
</html>