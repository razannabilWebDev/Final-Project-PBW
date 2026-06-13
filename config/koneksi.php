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

mysqli_set_charset($conn, "utf8mb4");

function prepared_query($conn, $sql, $types = "", $params = [])
{
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Prepare query gagal: " . mysqli_error($conn));
    }

    if ($types !== "" && !empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    if (!mysqli_stmt_execute($stmt)) {
        die("Eksekusi query gagal: " . mysqli_stmt_error($stmt));
    }

    return $stmt;
}
?>
