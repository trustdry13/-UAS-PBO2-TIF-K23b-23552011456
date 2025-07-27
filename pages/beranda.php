<?php
require_once '../config/config.php';
if (!is_logged_in()) {
    redirect('pages/login.php');
}

$db = new Database();
$produks = Produk::getAll($db);
$featuredProducts = array_slice($produks, 0, 4);

require_once '../includes/header.php';
?>

<h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!</h2>
<p class="lead">Ini adalah halaman beranda toko online kami.</p>

<h3 class="mt-4">Produk Unggulan</h3>
<div class="row">
    <?php foreach ($featuredProducts as $produk): ?>
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($produk->getNama()); ?></h5>
                <p class="card-text">
                    Harga: Rp<?php echo number_format($produk->getHarga(), 0, ',', '.'); ?><br>
                    Diskon: Rp<?php echo number_format($produk->hitungDiskon(), 0, ',', '.'); ?>
                </p>
                <?php if ($produk instanceof Elektronik): ?>
                <p class="card-text">
                    Merek: <?php echo htmlspecialchars($produk->getMerek()); ?><br>
                    Garansi: <?php echo htmlspecialchars($produk->getGaransi()); ?>
                </p>
                <?php elseif ($produk instanceof Pakaian): ?>
                <p class="card-text">
                    Ukuran: <?php echo htmlspecialchars($produk->getUkuran()); ?><br>
                    Warna: <?php echo htmlspecialchars($produk->getWarna()); ?>
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