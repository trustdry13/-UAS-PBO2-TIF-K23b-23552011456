<?php
interface MetodePembayaran {
    public function prosesPembayaran($jumlah);
}

class TransferBank implements MetodePembayaran {
    public function prosesPembayaran($jumlah) {
        // Logika untuk transfer bank
        return "Pembayaran sebesar Rp" . number_format($jumlah, 0, ',', '.') . " via Transfer Bank berhasil diproses.";
    }
}

class EWallet implements MetodePembayaran {
    public function prosesPembayaran($jumlah) {
        // Logika untuk e-wallet
        return "Pembayaran sebesar Rp" . number_format($jumlah, 0, ',', '.') . " via E-Wallet berhasil diproses.";
    }
}

class COD implements MetodePembayaran {
    public function prosesPembayaran($jumlah) {
        // Logika untuk COD
        return "Pesanan akan diproses dengan pembayaran COD sebesar Rp" . number_format($jumlah, 0, ',', '.') . " saat barang diterima.";
    }
}