<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        getGedung();
        break;
    case 'POST':
        addGedung();
        break;
    case 'PUT':
        updateGedung();
        break;
    case 'DELETE':
        deleteGedung();
        break;
}

function getGedung() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM gedung ORDER BY id");
    $gedung = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($gedung);
}

function addGedung() {
    global $pdo;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $pdo->prepare("INSERT INTO gedung (nama, lokasi, kapasitas, harga, status) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute([$data['nama'], $data['lokasi'], $data['kapasitas'], $data['harga'], $data['status']]);
    
    if($result) {
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } else {
        echo json_encode(['success' => false]);
    }
}

function updateGedung() {
    global $pdo;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $pdo->prepare("UPDATE gedung SET nama=?, lokasi=?, kapasitas=?, harga=?, status=? WHERE id=?");
    $result = $stmt->execute([$data['nama'], $data['lokasi'], $data['kapasitas'], $data['harga'], $data['status'], $data['id']]);
    
    echo json_encode(['success' => $result]);
}

function deleteGedung() {
    global $pdo;
    
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("DELETE FROM gedung WHERE id=?");
    $result = $stmt->execute([$id]);
    
    echo json_encode(['success' => $result]);
}
?>