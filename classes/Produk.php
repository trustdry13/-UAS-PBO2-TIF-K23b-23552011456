<?php
abstract class Produk {
    protected $id;
    protected $nama;
    protected $harga;
    protected $stok;
    protected $kategori;

    public function __construct($id, $nama, $harga, $stok, $kategori) {
        $this->id = $id;
        $this->nama = $nama;
        $this->harga = $harga;
        $this->stok = $stok;
        $this->kategori = $kategori;
    }

    // Getter methods
    public function getId() { return $this->id; }
    public function getNama() { return $this->nama; }
    public function getHarga() { return $this->harga; }
    public function getStok() { return $this->stok; }
    public function getKategori() { return $this->kategori; }

    // Abstract method untuk menghitung diskon
    abstract public function hitungDiskon();

    // Method untuk mengurangi stok
    public function kurangiStok($jumlah) {
        if ($jumlah <= $this->stok) {
            $this->stok -= $jumlah;
            return true;
        }
        return false;
    }

    public static function getAll(Database $db) {
        $produks = [];
        $result = $db->query("SELECT * FROM produk");
        
        while ($row = $result->fetch_assoc()) {
            if ($row['kategori'] === 'Elektronik') {
                $spesifik = json_decode($row['spesifik'], true);
                $produks[] = new Elektronik(
                    $row['id'],
                    $row['nama'],
                    $row['harga'],
                    $row['stok'],
                    $spesifik['merek'],
                    $spesifik['garansi']
                );
            } else if ($row['kategori'] === 'Pakaian') {
                $spesifik = json_decode($row['spesifik'], true);
                $produks[] = new Pakaian(
                    $row['id'],
                    $row['nama'],
                    $row['harga'],
                    $row['stok'],
                    $spesifik['ukuran'],
                    $spesifik['warna']
                );
            }
        }
        
        return $produks;
    }

    public static function getById(Database $db, $id) {
        $stmt = $db->prepare("SELECT * FROM produk WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            if ($row['kategori'] === 'Elektronik') {
                $spesifik = json_decode($row['spesifik'], true);
                return new Elektronik(
                    $row['id'],
                    $row['nama'],
                    $row['harga'],
                    $row['stok'],
                    $spesifik['merek'],
                    $spesifik['garansi']
                );
            } else if ($row['kategori'] === 'Pakaian') {
                $spesifik = json_decode($row['spesifik'], true);
                return new Pakaian(
                    $row['id'],
                    $row['nama'],
                    $row['harga'],
                    $row['stok'],
                    $spesifik['ukuran'],
                    $spesifik['warna']
                );
            }
        }
        
        return null;
    }
}