<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        getBooking();
        break;
    case 'POST':
        addBooking();
        break;
    case 'PUT':
        updateBooking();
        break;
    case 'DELETE':
        deleteBooking();
        break;
}

function getBooking() {
    global $pdo;
    
    $stmt = $pdo->query("
        SELECT b.*, g.nama as nama_gedung, p.nama as nama_penyewa 
        FROM booking b 
        JOIN gedung g ON b.id_gedung = g.id 
        JOIN penyewa p ON b.id_penyewa = p.id 
        ORDER BY b.id
    ");
    $booking = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($booking);
}

function addBooking() {
    global $pdo;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $pdo->prepare("INSERT INTO booking (id_gedung, id_penyewa, tanggal_mulai, tanggal_selesai, total_harga, status) VALUES (?, ?, ?, ?, ?, ?)");
    $result = $stmt->execute([$data['id_gedung'], $data['id_penyewa'], $data['tanggal_mulai'], $data['tanggal_selesai'], $data['total_harga'], $data['status']]);
    
    if($result) {
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } else {
        echo json_encode(['success' => false]);
    }
}

function updateBooking() {
    global $pdo;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $pdo->prepare("UPDATE booking SET id_gedung=?, id_penyewa=?, tanggal_mulai=?, tanggal_selesai=?, total_harga=?, status=? WHERE id=?");
    $result = $stmt->execute([$data['id_gedung'], $data['id_penyewa'], $data['tanggal_mulai'], $data['tanggal_selesai'], $data['total_harga'], $data['status'], $data['id']]);
    
    echo json_encode(['success' => $result]);
}

function deleteBooking() {
    global $pdo;
    
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("DELETE FROM booking WHERE id=?");
    $result = $stmt->execute([$id]);
    
    echo json_encode(['success' => $result]);
}
?>