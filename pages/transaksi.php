<?php
require_once '../config/config.php';
if (!is_logged_in()) {
    redirect('pages/login.php');
}

$db = new Database();
$user = User::getById($db, $_SESSION['user_id']);

// Handle checkout
if (isset($_GET['action']) && $_GET['action'] === 'checkout' && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['produk']->getHarga() * $item['jumlah'];
    }
    
    // Simpan transaksi
    $transaksi = new Transaksi($_SESSION['user_id'], $total, 'Transfer Bank'); // Default metode pembayaran
    
    foreach ($_SESSION['cart'] as $produkId => $item) {
        $transaksi->tambahDetail($produkId, $item['jumlah'], $item['produk']->getHarga());
    }
    
    if ($transaksi->simpan($db)) {
        unset($_SESSION['cart']);
        $_SESSION['success_message'] = 'Transaksi berhasil diproses!';
        redirect('pages/transaksi.php');
    } else {
        $_SESSION['error_message'] = 'Gagal memproses transaksi';
    }
}

// Get user transactions
$transaksis = Transaksi::getByUserId($db, $_SESSION['user_id']);

require_once '../includes/header.php';
?>

<h2>Riwayat Transaksi</h2>

<?php if (isset($_SESSION['success_message'])): ?>
<div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
<div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
<?php endif; ?>

<?php if (empty($transaksis)): ?>
<div class="alert alert-info">Anda belum memiliki transaksi.</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Metode Pembayaran</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transaksis as $transaksi): ?>
            <tr>
                <td><?php echo $transaksi->getId(); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($transaksi->getTanggal())); ?></td>
                <td>Rp<?php echo number_format($transaksi->getTotal(), 0, ',', '.'); ?></td>
                <td><?php echo htmlspecialchars($transaksi->getMetodePembayaran()); ?></td>
                <td>
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo $transaksi->getId(); ?>">
                        Lihat Detail
                    </button>
                </td>
            </tr>
            
            <!-- Modal for transaction details -->
            <div class="modal fade" id="detailModal<?php echo $transaksi->getId(); ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detail Transaksi #<?php echo $transaksi->getId(); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transaksi->getDetails() as $detail): 
                                        $produk = Produk::getById($db, $detail['produk_id']);
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($produk->getNama()); ?></td>
                                        <td>Rp<?php echo number_format($detail['harga_satuan'], 0, ',', '.'); ?></td>
                                        <td><?php echo $detail['jumlah']; ?></td>
                                        <td>Rp<?php echo number_format($detail['harga_satuan'] * $detail['jumlah'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td><strong>Rp<?php echo number_format($transaksi->getTotal(), 0, ',', '.'); ?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>