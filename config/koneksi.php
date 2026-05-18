<?php
$host = "localhost";
$user = "root";
$pass = ""; 
$db   = "warung_kelontong";

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn){
    die("Koneksi gagal");
}
?>