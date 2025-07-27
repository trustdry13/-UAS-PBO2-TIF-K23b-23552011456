<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'toko_online');

function base_url($path = '') {
    return "http://localhost/toko_online/{$path}";
}

function redirect($path) {
    header("Location: " . base_url($path));
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Produk.php';
require_once __DIR__ . '/../classes/Elektronik.php';
require_once __DIR__ . '/../classes/Pakaian.php';
require_once __DIR__ . '/../classes/Transaksi.php';
require_once __DIR__ . '/../classes/MetodePembayaran.php';