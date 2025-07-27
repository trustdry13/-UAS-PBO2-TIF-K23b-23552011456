<?php
class Transaksi {
    private $id;
    private $userId;
    private $total;
    private $metodePembayaran;
    private $tanggal;
    private $details = [];

    public function __construct($userId, $total, $metodePembayaran) {
        $this->userId = $userId;
        $this->total = $total;
        $this->metodePembayaran = $metodePembayaran;
    }

    // Getter methods
    public function getId() { return $this->id; }
    public function getUserId() { return $this->userId; }
    public function getTotal() { return $this->total; }
    public function getMetodePembayaran() { return $this->metodePembayaran; }
    public function getTanggal() { return $this->tanggal; }
    public function getDetails() { return $this->details; }

    // Method untuk menambahkan detail transaksi
    public function tambahDetail($produkId, $jumlah, $hargaSatuan) {
        $this->details[] = [
            'produk_id' => $produkId,
            'jumlah' => $jumlah,
            'harga_satuan' => $hargaSatuan
        ];
    }

    // Method untuk menyimpan transaksi ke database
    public function simpan(Database $db) {
        // Mulai transaksi
        $db->query("START TRANSACTION");

        try {
            // Simpan transaksi utama
            $stmt = $db->prepare("INSERT INTO transaksi (user_id, total, metode_pembayaran) VALUES (?, ?, ?)");
            $stmt->bind_param("ids", $this->userId, $this->total, $this->metodePembayaran);
            $stmt->execute();
            $this->id = $db->getLastInsertId();

            // Simpan detail transaksi
            foreach ($this->details as $detail) {
                $stmt = $db->prepare("INSERT INTO detail_transaksi (transaksi_id, produk_id, jumlah, harga_satuan) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiid", $this->id, $detail['produk_id'], $detail['jumlah'], $detail['harga_satuan']);
                $stmt->execute();

                // Update stok produk
                $stmt = $db->prepare("UPDATE produk SET stok = stok - ? WHERE id = ?");
                $stmt->bind_param("ii", $detail['jumlah'], $detail['produk_id']);
                $stmt->execute();
            }

            // Commit transaksi
            $db->query("COMMIT");
            return true;
        } catch (Exception $e) {
            // Rollback jika terjadi error
            $db->query("ROLLBACK");
            return false;
        }
    }

    // Method static untuk mendapatkan transaksi oleh user
    public static function getByUserId(Database $db, $userId) {
        $transaksis = [];
        $stmt = $db->prepare("SELECT * FROM transaksi WHERE user_id = ? ORDER BY tanggal DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $transaksi = new Transaksi($row['user_id'], $row['total'], $row['metode_pembayaran']);
            $transaksi->id = $row['id'];
            $transaksi->tanggal = $row['tanggal'];

            // Ambil detail transaksi
            $stmtDetail = $db->prepare("
                SELECT dt.produk_id, dt.jumlah, dt.harga_satuan, p.nama 
                FROM detail_transaksi dt
                JOIN produk p ON dt.produk_id = p.id
                WHERE dt.transaksi_id = ?
            ");
            $stmtDetail->bind_param("i", $transaksi->id);
            $stmtDetail->execute();
            $resultDetail = $stmtDetail->get_result();

            while ($detail = $resultDetail->fetch_assoc()) {
                $transaksi->tambahDetail($detail['produk_id'], $detail['jumlah'], $detail['harga_satuan']);
            }

            $transaksis[] = $transaksi;
        }

        return $transaksis;
    }
}