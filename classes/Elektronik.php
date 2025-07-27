<?php
class Elektronik extends Produk {
    private $merek;
    private $garansi;

    public function __construct($id, $nama, $harga, $stok, $merek, $garansi) {
        parent::__construct($id, $nama, $harga, $stok, 'Elektronik');
        $this->merek = $merek;
        $this->garansi = $garansi;
    }

    // Implementasi method hitungDiskon untuk Elektronik
    public function hitungDiskon() {
        // Diskon 10% untuk produk elektronik
        return $this->harga * 0.10;
    }

    // Getter methods khusus Elektronik
    public function getMerek() { return $this->merek; }
    public function getGaransi() { return $this->garansi; }
}