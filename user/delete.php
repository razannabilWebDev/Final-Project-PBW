<?php

session_start();

include '../config/koneksi.php';
require '../config/session.php';

cek_login_admin();

if($_SESSION['role'] != 'admin'){
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'];

if($id == $_SESSION['id_user']){

    echo "
    <script>
        alert('Anda tidak bisa menghapus akun sendiri');
        window.location='index.php';
    </script>
    ";

    exit;
}

mysqli_query($conn,"
    DELETE FROM user
    WHERE id_user='$id'
");

?>

<script>

alert('User berhasil dihapus');

window.location='index.php';

</script>