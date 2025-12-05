<?php
require_once 'config/database.php';

try {
    // Cek apakah tabel users sudah ada
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    
    if ($stmt->rowCount() == 0) {
        // Buat tabel users
        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'user') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        echo "Tabel users berhasil dibuat.<br>";
        
        // Tambahkan admin default
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Administrator', 'admin@sewagedung.com', $admin_password, 'admin']);
        
        echo "Admin default berhasil ditambahkan.<br>";
        echo "Email: admin@sewagedung.com<br>";
        echo "Password: admin123<br>";
    } else {
        echo "Tabel users sudah ada.<br>";
    }
    
    echo "<br><a href='index.php'>Kembali ke Login</a>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>