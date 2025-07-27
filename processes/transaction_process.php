<?php
require_once '../config/config.php';
if (!is_logged_in()) {
    redirect('pages/login.php');
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'checkout':
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $db = new Database();
            $total = 0;
            
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['produk']->getHarga() * $item['jumlah'];
            }
            
            $metodePembayaran = $_POST['metode_pembayaran'] ?? 'Transfer Bank';
            $transaksi = new Transaksi($_SESSION['user_id'], $total, $metodePembayaran);
            
            foreach ($_SESSION['cart'] as $produkId => $item) {
                $transaksi->tambahDetail($produkId, $item['jumlah'], $item['produk']->getHarga());
            }
            
            if ($transaksi->simpan($db)) {
                unset($_SESSION['cart']);
                $_SESSION['success_message'] = 'Transaksi berhasil diproses!';
            } else {
                $_SESSION['error_message'] = 'Gagal memproses transaksi';
            }
        }
        redirect('pages/transaksi.php');
        break;
        
    default:
        redirect('pages/transaksi.php');
}