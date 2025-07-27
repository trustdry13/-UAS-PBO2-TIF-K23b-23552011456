<?php
require_once '../config/config.php';
if (!is_logged_in()) {
    redirect('pages/login.php');
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        $produkId = (int)($_GET['id'] ?? 0);
        $db = new Database();
        $produk = Produk::getById($db, $produkId);
        
        if ($produk) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            if (isset($_SESSION['cart'][$produkId])) {
                $_SESSION['cart'][$produkId]['jumlah']++;
            } else {
                $_SESSION['cart'][$produkId] = [
                    'produk' => $produk,
                    'jumlah' => 1
                ];
            }
            
            $_SESSION['success_message'] = 'Produk berhasil ditambahkan ke keranjang';
        }
        redirect('pages/keranjang.php');
        break;
        
    case 'remove':
        $produkId = (int)($_GET['id'] ?? 0);
        if (isset($_SESSION['cart'][$produkId])) {
            unset($_SESSION['cart'][$produkId]);
            $_SESSION['success_message'] = 'Produk berhasil dihapus dari keranjang';
        }
        redirect('pages/keranjang.php');
        break;
        
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantities'])) {
            foreach ($_POST['quantities'] as $produkId => $quantity) {
                $produkId = (int)$produkId;
                $quantity = (int)$quantity;
                
                if ($quantity > 0 && isset($_SESSION['cart'][$produkId])) {
                    $_SESSION['cart'][$produkId]['jumlah'] = $quantity;
                }
            }
            $_SESSION['success_message'] = 'Keranjang berhasil diperbarui';
        }
        redirect('pages/keranjang.php');
        break;
        
    default:
        redirect('pages/keranjang.php');
}