<?php
// Konfigurasi database
$servername = "localhost"; // Ganti dengan nama server Anda
$username = "root";        // Ganti dengan username database Anda
$password = "";            // Ganti dengan password database Anda
$dbname = "cupcake_db";    // Ganti dengan nama database Anda

// Aktifkan mode pelaporan error untuk mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Membuat koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Set charset untuk mendukung karakter spesial
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    // Tangani error jika koneksi gagal
    die("Database connection failed: " . $e->getMessage());
}
?>
