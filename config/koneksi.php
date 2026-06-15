<?php
$host = "localhost";
$user = "root";
$pass = ""; 
$db   = "warung_kelontong";

$conn = mysqli_connect($host, $user, $pass, $db);

// Cek apakah koneksi berhasil
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>