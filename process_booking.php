<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_gedung = $_POST['id_gedung'];
    $id_penyewa = $_POST['id_penyewa'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $total_harga = $_POST['total_harga'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO booking (id_gedung, id_penyewa, tanggal_mulai, tanggal_selesai, total_harga, status) VALUES (?, ?, ?, ?, ?, 'aktif')");
        $result = $stmt->execute([$id_gedung, $id_penyewa, $tanggal_mulai, $tanggal_selesai, $total_harga]);
        
        if ($result) {
            header('Location: booking.php?success=1');
        } else {
            header('Location: booking.php?error=1');
        }
    } catch (Exception $e) {
        header('Location: booking.php?error=1');
    }
} else {
    header('Location: booking.php');
}
?>