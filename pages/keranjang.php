<?php
require_once '../config/config.php';
if (!is_logged_in()) {
    redirect('pages/login.php');
}

$db = new Database();

// Handle remove from cart
if (isset($_GET['action']) && $_GET['action'] === 'remove_from_cart' && isset($_GET['id'])) {
    $produkId = (int)$_GET['id'];
    if (isset($_SESSION['cart'][$produkId])) {
        unset($_SESSION['cart'][$produkId]);
        $_SESSION['success_message'] = 'Produk berhasil dihapus dari keranjang';
    }
}

// Handle update quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $produkId => $quantity) {
        $quantity = (int)$quantity;
        if ($quantity > 0 && isset($_SESSION['cart'][$produkId])) {
            $_SESSION['cart'][$produkId]['jumlah'] = $quantity;
        }
    }
    $_SESSION['success_message'] = 'Keranjang berhasil diperbarui';
}

// Calculate total
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['produk']->getHarga() * $item['jumlah'];
    }
}

require_once '../includes/header.php';
?>

<h2>Keranjang Belanja</h2>

<?php if (isset($_SESSION['success_message'])): ?>
<div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
<?php endif; ?>

<?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
<div class="alert alert-info">Keranjang belanja Anda kosong.</div>
<?php else: ?>
<form method="POST">
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $produkId => $item): 
                $produk = $item['produk'];
                $jumlah = $item['jumlah'];
                $subtotal = $produk->getHarga() * $jumlah;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($produk->getNama()); ?></td>
                <td>Rp<?php echo number_format($produk->getHarga(), 0, ',', '.'); ?></td>
                <td>
                    <input type="number" name="quantities[<?php echo $produkId; ?>]" value="<?php echo $jumlah; ?>" min="1" max="<?php echo $produk->getStok(); ?>" class="form-control" style="width: 70px;">
                </td>
                <td>Rp<?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                <td>
                    <a href="<?php echo base_url('pages/keranjang.php?action=remove_from_cart&id=' . $produkId); ?>" class="btn btn-danger btn-sm">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td colspan="2"><strong>Rp<?php echo number_format($total, 0, ',', '.'); ?></strong></td>
            </tr>
        </tfoot>
    </table>
    <div class="d-flex justify-content-between">
        <a href="<?php echo base_url('pages/produk.php'); ?>" class="btn btn-secondary">Lanjut Belanja</a>
        <div>
            <button type="submit" name="update_cart" class="btn btn-warning">Perbarui Keranjang</button>
            <a href="<?php echo base_url('pages/transaksi.php?action=checkout'); ?>" class="btn btn-success">Checkout</a>
        </div>
    </div>
</form>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>