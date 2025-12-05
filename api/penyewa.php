<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        getPenyewa();
        break;
    case 'POST':
        addPenyewa();
        break;
    case 'PUT':
        updatePenyewa();
        break;
    case 'DELETE':
        deletePenyewa();
        break;
}

function getPenyewa() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM penyewa ORDER BY id");
    $penyewa = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($penyewa);
}

function addPenyewa() {
    global $pdo;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $pdo->prepare("INSERT INTO penyewa (nama, telepon, email, alamat) VALUES (?, ?, ?, ?)");
    $result = $stmt->execute([$data['nama'], $data['telepon'], $data['email'], $data['alamat']]);
    
    if($result) {
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } else {
        echo json_encode(['success' => false]);
    }
}

function updatePenyewa() {
    global $pdo;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $pdo->prepare("UPDATE penyewa SET nama=?, telepon=?, email=?, alamat=? WHERE id=?");
    $result = $stmt->execute([$data['nama'], $data['telepon'], $data['email'], $data['alamat'], $data['id']]);
    
    echo json_encode(['success' => $result]);
}

function deletePenyewa() {
    global $pdo;
    
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("DELETE FROM penyewa WHERE id=?");
    $result = $stmt->execute([$id]);
    
    echo json_encode(['success' => $result]);
}
?>