<?php
require_once __DIR__ . '/../config/database.php';
abstract class PaymentMethod {
    protected $name;

    public function __construct($name = "") {
        $this->name = $name;
    }

    abstract public function processPayment(float $amount, array $meta = []): array;
    public function getName() { return $this->name; }
}

// contoh implementasi
class CashPayment extends PaymentMethod {
    public function __construct() { parent::__construct('Cash'); }
    public function processPayment(float $amount, array $meta = []): array {
        // balikkan struktur standar hasil pembayaran
        return ['success' => true, 'method' => $this->name, 'amount' => $amount, 'detail' => 'Tunai diterima'];
    }
}

class TransferPayment extends PaymentMethod {
    public function __construct() { parent::__construct('Bank Transfer'); }
    public function processPayment(float $amount, array $meta = []): array {
        // contoh: cek bukti transfer dsb
        return ['success' => true, 'method' => $this->name, 'amount' => $amount, 'detail' => 'Transfer diproses'];
    }
}
