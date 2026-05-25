<?php
function cek_login_admin() {
    if(!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin'){
        header("Location: ../login.php");
        exit;
    }
}

function cek_login_kasir() {
    if(!isset($_SESSION['login']) || $_SESSION['role'] !== 'kasir'){
        header("Location: ../login.php");
        exit;
    }
}

function cek_login() {
    if(!isset($_SESSION['login']) ){
        header("Location: login.php");
        exit;
    }
}

function cek_login_komplit() {
    if(!isset($_SESSION['login']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'kasir')){
        header("Location: ../login.php");
        exit;
    }
}