<?php
require_once '../config/config.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $db = new Database();
            if (User::login($db, $username, $password)) {
                redirect('pages/beranda.php');
            } else {
                $_SESSION['error_message'] = 'Username atau password salah';
                redirect('pages/login.php');
            }
        }
        break;
        
    case 'logout':
        User::logout();
        redirect('pages/login.php');
        break;
        
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $namaLengkap = $_POST['nama_lengkap'] ?? '';
            $email = $_POST['email'] ?? '';
            
            $user = new User($username, $password, $namaLengkap, $email);
            $db = new Database();
            
            if ($user->register($db)) {
                $_SESSION['success_message'] = 'Registrasi berhasil. Silakan login.';
                redirect('pages/login.php');
            } else {
                $_SESSION['error_message'] = 'Username atau email sudah terdaftar';
                redirect('pages/register.php');
            }
        }
        break;
        
    default:
        redirect('pages/login.php');
}