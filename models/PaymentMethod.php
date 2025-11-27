<?php

// PaymentMethod.php
// Dibutuhkan oleh semua model transaksi
require_once __DIR__ . '/../helper/helper.php';

abstract class PaymentMethod {
    protected $name;

    public function __construct($name = "") {
        $this->name = $name;
    }

    // --- Polymorphism: tiap metode bayar wajib implementasi cara kerjanya ---
    abstract public function processPayment(float $amount, array $meta = []): array;

    public function getName() {
        return $this->name;
    }
}

// -------------------------------
// Implementasi Pembayaran Tunai
// -------------------------------
class CashPayment extends PaymentMethod {

    public function __construct() {
        parent::__construct('Cash');
    }

    public function processPayment(float $amount, array $meta = []): array {
        return [
            'success' => true,
            'method'  => $this->name,
            'amount'  => $amount,
            'detail'  => 'Pembayaran tunai diterima'
        ];
    }
}

// -----------------------------------
// Implementasi Pembayaran Transfer
// -----------------------------------
class TransferPayment extends PaymentMethod {

    public function __construct() {
        parent::__construct('Bank Transfer');
    }

    public function processPayment(float $amount, array $meta = []): array {

        // contoh validasi bukti transfer
        $bukti = $meta['bukti'] ?? null;

        return [
            'success' => true,
            'method'  => $this->name,
            'amount'  => $amount,
            'detail'  => $bukti
                ? 'Transfer diverifikasi dengan bukti'
                : 'Transfer diproses'
        ];
    }
}
