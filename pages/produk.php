<?php
require_once '../config/config.php';
if (!is_logged_in()) {
    redirect('pages/login.php');
}

$db = new Database();
$produks = Produk::getAll($db);

// Handle add to cart
if (isset($_GET['action']) && $_GET['action'] === 'add_to_cart' && isset($_GET['id'])) {
    $produkId = (int)$_GET['id'];
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
        redirect('pages/keranjang.php');
    }
}

require_once '../includes/header.php';
?>

<h2>Daftar Produk</h2>

<?php if (isset($_SESSION['success_message'])): ?>
<div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
<?php endif; ?>

<div class="row">
    <?php foreach ($produks as $produk): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($produk->getNama()); ?></h5>
                <p class="card-text">
                    <strong>Harga:</strong> Rp<?php echo number_format($produk->getHarga(), 0, ',', '.'); ?><br>
                    <strong>Diskon:</strong> Rp<?php echo number_format($produk->hitungDiskon(), 0, ',', '.'); ?><br>
                    <strong>Stok:</strong> <?php echo $produk->getStok(); ?>
                </p>
                <?php if ($produk instanceof Elektronik): ?>
                <p class="card-text">
                    <strong>Merek:</strong> <?php echo htmlspecialchars($produk->getMerek()); ?><br>
                    <strong>Garansi:</strong> <?php echo htmlspecialchars($produk->getGaransi()); ?>
                </p>
                <?php elseif ($produk instanceof Pakaian): ?>
                <p class="card-text">
                    <strong>Ukuran:</strong> <?php echo htmlspecialchars($produk->getUkuran()); ?><br>
                    <strong>Warna:</strong> <?php echo htmlspecialchars($produk->getWarna()); ?>
                </p>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <a href="<?php echo base_url('pages/produk.php?action=add_to_cart&id=' . $produk->getId()); ?>" class="btn btn-primary">Tambah ke Keranjang</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once '../includes/footer.php'; ?>