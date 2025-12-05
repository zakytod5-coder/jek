<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Koneksi tanpa database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buat database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS sewa_gedung");
    $pdo->exec("USE sewa_gedung");
    
    // Buat tabel gedung
    $pdo->exec("CREATE TABLE IF NOT EXISTS gedung (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        lokasi VARCHAR(100) NOT NULL,
        kapasitas INT NOT NULL,
        harga DECIMAL(12,2) NOT NULL,
        status ENUM('tersedia', 'tidak tersedia') DEFAULT 'tersedia'
    )");
    
    // Buat tabel penyewa
    $pdo->exec("CREATE TABLE IF NOT EXISTS penyewa (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        telepon VARCHAR(20) NOT NULL,
        email VARCHAR(100) NOT NULL,
        alamat TEXT NOT NULL
    )");
    
    // Buat tabel booking
    $pdo->exec("CREATE TABLE IF NOT EXISTS booking (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_gedung INT NOT NULL,
        id_penyewa INT NOT NULL,
        tanggal_mulai DATE NOT NULL,
        tanggal_selesai DATE NOT NULL,
        total_harga DECIMAL(12,2) NOT NULL,
        status ENUM('aktif', 'selesai', 'dibatalkan') DEFAULT 'aktif',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_gedung) REFERENCES gedung(id),
        FOREIGN KEY (id_penyewa) REFERENCES penyewa(id)
    )");
    
    // Insert data gedung
    $pdo->exec("INSERT IGNORE INTO gedung (id, nama, lokasi, kapasitas, harga, status) VALUES
    (1, 'Aula Merdeka', 'Bandung', 500, 3500000, 'tersedia'),
    (2, 'Graha Utama', 'Cimahi', 300, 2500000, 'tersedia'),
    (3, 'Bale Wiyata', 'Bandung', 150, 1500000, 'tersedia'),
    (4, 'Gedung Serbaguna', 'Jakarta', 800, 5000000, 'tersedia'),
    (5, 'Aula Pancasila', 'Bogor', 200, 2000000, 'tersedia')");
    
    // Insert data penyewa
    $pdo->exec("INSERT IGNORE INTO penyewa (id, nama, telepon, email, alamat) VALUES
    (1, 'Rizal Pratama', '081234567890', 'rizal@mail.com', 'Jl. Merdeka No. 123, Bandung'),
    (2, 'Siti Lestari', '082345678901', 'siti@mail.com', 'Jl. Sudirman No. 456, Cimahi'),
    (3, 'Adi Nugraha', '083456789012', 'adi@mail.com', 'Jl. Gatot Subroto No. 789, Bandung')");
    
    // Insert data booking
    $pdo->exec("INSERT IGNORE INTO booking (id, id_gedung, id_penyewa, tanggal_mulai, tanggal_selesai, total_harga, status) VALUES
    (1, 1, 1, '2025-01-10', '2025-01-12', 10500000, 'aktif'),
    (2, 2, 2, '2025-01-15', '2025-01-16', 5000000, 'selesai'),
    (3, 3, 3, '2025-01-20', '2025-01-21', 3000000, 'aktif')");
    
    echo "<h2>Database Setup Berhasil!</h2>";
    echo "<p>Database 'sewa_gedung' dan semua tabel telah dibuat.</p>";
    echo "<a href='index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Login Sekarang</a>";
    
} catch(PDOException $e) {
    echo "<h2>Error Setup Database:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>