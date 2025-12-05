<?php
session_start();

// Cek dan buat tabel users jika belum ada
try {
    require_once 'config/database.php';
    
    // Cek apakah tabel users ada
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
        
        // Tambahkan admin default
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Administrator', 'admin@sewagedung.com', $admin_password, 'admin']);
    }
} catch (PDOException $e) {
    // Jika ada error database
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    try {
        $stmt = $pdo->prepare("SELECT id, nama, email, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nama'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Email atau password salah!';
        }
    } catch (PDOException $e) {
        $error = 'Terjadi kesalahan: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SEWA GEDUNG</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <div class="logo-section">
                <img src="assets/logo.png" alt="Logo UT" class="logo">
                <h1>SEWA GEDUNG</h1>
                <p>Sistem Informasi Sewa Gedung</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Masukkan email Anda">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password Anda">
                </div>
                
                <button type="submit" name="login" class="btn-primary">Masuk</button>
                
                <div class="register-section">
                    <p>Belum punya akun? <a href="register_page.php" class="link-btn">Daftar</a></p>
                </div>
                
                <div class="demo-login">
                    <p><strong>Demo Login:</strong></p>
                    <p>Email: admin@sewagedung.com</p>
                    <p>Password: admin123</p>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Registrasi -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Daftar Akun Baru</h2>
            <form id="registerForm">
                <div class="form-group">
                    <label for="regName">Nama Lengkap</label>
                    <input type="text" id="regName" name="nama" required placeholder="Masukkan nama lengkap">
                </div>
                <div class="form-group">
                    <label for="regEmail">Email</label>
                    <input type="email" id="regEmail" name="email" required placeholder="Masukkan email">
                </div>
                <div class="form-group">
                    <label for="regPassword">Password</label>
                    <input type="password" id="regPassword" name="password" required placeholder="Minimal 6 karakter">
                </div>
                <div class="form-group">
                    <label for="regConfirmPassword">Konfirmasi Password</label>
                    <input type="password" id="regConfirmPassword" name="confirm_password" required placeholder="Ulangi password">
                </div>
                <button type="submit" class="btn-primary">Daftar</button>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>