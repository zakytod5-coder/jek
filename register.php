<?php
session_start();
require_once 'config/database.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validasi input
    if (empty($nama) || empty($email) || empty($password)) {
        $response['message'] = 'Semua field harus diisi!';
    } elseif ($password !== $confirm_password) {
        $response['message'] = 'Password dan konfirmasi password tidak sama!';
    } elseif (strlen($password) < 6) {
        $response['message'] = 'Password minimal 6 karakter!';
    } else {
        try {
            // Cek apakah email sudah terdaftar
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $response['message'] = 'Email sudah terdaftar!';
            } else {
                // Hash password dan simpan user baru
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$nama, $email, $hashed_password]);
                
                $response['success'] = true;
                $response['message'] = 'Registrasi berhasil! Silakan login.';
            }
        } catch (PDOException $e) {
            $response['message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>