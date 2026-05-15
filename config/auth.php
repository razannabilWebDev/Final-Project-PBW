<?php
require_once 'session.php';
require_once 'koneksi.php';

function cek_login() {
    if (!is_logged_in()) {
        header('Location: ../login.php');
        exit();
    }
    regenerate_session();
}

function cek_role($roles = []) {
    $user_role = $_SESSION['role'] ?? '';
    if (!in_array($user_role, (array)$roles)) {
        header('Location: ../login.php?error=unauthorized');
        exit();
    }
}

function login($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        set_session_user($user);
        return true;
    }
    return false;
}

function register($data) {
    global $pdo;
    
    // Cek username/email sudah ada
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$data['username'], $data['email']]);
    
    if ($stmt->fetch()) {
        return false; // Sudah ada
    }
    
    // Insert user baru
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT INTO users (nama, username, email, password, role, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    
    return $stmt->execute([
        $data['nama'],
        $data['username'],
        $data['email'],
        $hashed_password,
        $data['role'] ?? 'kasir'
    ]);
}

function logout() {
    session_destroy();
    header('Location: ../login.php');
    exit();
}
?>