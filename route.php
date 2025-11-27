<?php
// Jika file yang diminta ada di folder public, berikan langsung
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js|woff|woff2|svg|ttf|map)$/', $_SERVER["REQUEST_URI"])) {
    return false;
}

// Jika request menuju file real di public/, kasih
$path = __DIR__ . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
if (is_file($path)) {
    return false;
}

// Selain itu, lempar semua request ke index.php
include __DIR__ . '/index.php';
