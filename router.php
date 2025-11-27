<?php
// Router bawaan PHP: kalau file ada, tampilkan file tsb
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if ($path !== "/" && file_exists(__DIR__ . $path)) {
    return false;
}

// Jika request mengarah ke folder public
if (str_starts_with($path, "/public/")) {
    $file = __DIR__ . $path;
    if (file_exists($file)) {
        return false; // langsung load file CSS, JS, image
    }
}

require 'index.php';
