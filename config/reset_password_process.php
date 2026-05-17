<?php

session_start();

include 'koneksi.php';

if(!isset($_SESSION['otp_verified'])){
    header('Location: ../forgot_password.php');
    exit;
}

$email = $_SESSION['reset_email'];

$password = $_POST['password'];
$password_hashed = password_hash($password, PASSWORD_DEFAULT);
mysqli_query($conn,"
    UPDATE user
    SET password='$password_hashed'
    WHERE email='$email'
");

unset($_SESSION['otp']);
unset($_SESSION['otp_verified']);
unset($_SESSION['reset_email']);
unset($_SESSION['otp_expired']);

session_destroy();

?>

<script>

alert('Password berhasil diubah');

window.location='../login.php';

</script>