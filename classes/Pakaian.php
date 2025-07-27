<?php
class Pakaian extends Produk {
    private $ukuran;
    private $warna;

    public function __construct($id, $nama, $harga, $stok, $ukuran, $warna) {
        parent::__construct($id, $nama, $harga, $stok, 'Pakaian');
        $this->ukuran = $ukuran;
        $this->warna = $warna;
    }

    // Implementasi method hitungDiskon untuk Pakaian
    public function hitungDiskon() {
        // Diskon 5% untuk produk pakaian
        return $this->harga * 0.05;
    }

    // Getter methods khusus Pakaian
    public function getUkuran() { return $this->ukuran; }
    public function getWarna() { return $this->warna; }
}