<?php

session_start();

include 'koneksi.php';
include 'mailer.php';

$email = $_POST['email'];

$query = mysqli_query($conn,"
    SELECT * FROM user
    WHERE email='$email'
");

if(mysqli_num_rows($query) < 1){

    echo "
    <script>
        alert('Email tidak ditemukan');
        window.location='../forgot_password.php';
    </script>
    ";

    exit;
}

$otp = rand(100000,999999);

$_SESSION['reset_email'] = $email;
$_SESSION['otp'] = $otp;
$_SESSION['otp_expired'] = time() + 300;

$send = kirimOTP($email,$otp);

if($send){

    header('Location: ../verify_otp.php');

} else {

    echo "
    <script>
        alert('Gagal mengirim OTP');
        window.location='../forgot_password.php';
    </script>
    ";
}
?>