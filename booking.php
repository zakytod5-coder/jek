<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit;
}
require_once 'config/database.php';

// Ambil data booking dengan join
$stmt = $pdo->query("
    SELECT b.*, g.nama as nama_gedung, p.nama as nama_penyewa 
    FROM booking b 
    JOIN gedung g ON b.id_gedung = g.id 
    JOIN penyewa p ON b.id_penyewa = p.id 
    ORDER BY b.id DESC
");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil data gedung dan penyewa untuk form
$gedung_list = $pdo->query("SELECT * FROM gedung WHERE status = 'tersedia'")->fetchAll(PDO::FETCH_ASSOC);
$penyewa_list = $pdo->query("SELECT * FROM penyewa")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Booking - SEWA GEDUNG</title>
     <style>
        /* Reset dan Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
    background-image: url(assets/gedung.png);
}

/* Login Styles */
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #bbcdd8, #03a3e8);
}

.login-form {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
}

.logo-section {
    text-align: center;
    margin-bottom: 2rem;
}

.logo {
    max-width: 150px;
    margin-bottom: 1rem;
}

.logo-section h1 {
    color: rgb(173, 206, 224);
    margin-bottom: 0.5rem;
}

.logo-section p {
    color: #666;
    font-size: 0.9rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}

.form-group input:focus {
    outline: none;
    border-color: #03a3e8;
}

.form-options {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 1.5rem;
}

.link-btn {
    background: none;
    border: none;
    color: #03a3e8;
    cursor: pointer;
    text-decoration: underline;
}

.link-btn:hover {
    color: #2a73c1;
}

.btn-primary {
    width: 100%;
    padding: 0.75rem;
    background-color: #03a3e8;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: #2a73c1;
}

.btn-secondary {
    padding: 0.5rem 1rem;
    background-color: #6c757d;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-secondary:hover {
    background-color: #545b62;
}

.register-section {
    text-align: center;
    margin-top: 1.5rem;
}

.register-section p {
    color: #666;
}

/* Dashboard Styles */
.dashboard-container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
    background-image: url("gedung.png");
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.header-left {
    display: flex;
    align-items: center;
}

.header-left h1 {
    margin-left: 1rem;
    color: rgb(173, 206, 224);
}

.header-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.greeting {
    font-weight: 500;
}

.dashboard-nav {
    background-color: #03a3e8;
}

.dashboard-nav ul {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-link {
    display: block;
    padding: 1rem 1.5rem;
    color: white;
    text-decoration: none;
    transition: background-color 0.3s;
}

.nav-link:hover, .nav-link.active {
    background-color: #2a73c1;
}

.dropdown {
    position: relative;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 200px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    z-index: 1;
}

.dropdown-content a {
    color: #333;
    padding: 0.75rem 1rem;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dashboard-main {
    flex: 1;
    padding: 2rem;
}

.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.card h3 {
    margin-bottom: 0.5rem;
    color: #0056b3;
}

.card p {
    margin-bottom: 1rem;
    color: #666;
}

/* Tracking Styles */
.tracking-main, .stok-main {
    background: transparent;
    border: solid rgba(225, 225, 225, 0.2);
    backdrop-filter: blur(5px);
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    color: #03a3e8;
}

.tracking-form {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    align-items: flex-end;
}

.tracking-form .form-group {
    flex: 1;
    margin-bottom: 0;
}

.tracking-result {
    margin-top: 2rem;
}

.tracking-details {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.progress-bar {
    height: 10px;
    background-color: #e9ecef;
    border-radius: 5px;
    margin: 1rem 0;
    overflow: hidden;
}

.progress {
    height: 100%;
    background-color: #28a745;
    border-radius: 5px;
    transition: width 0.3s;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-dikirim {
    background-color: #d4edda;
    color: #155724;
}

.status-diproses {
    background-color: #fff3cd;
    color: #856404;
}

.status-selesai {
    background-color: #d1ecf1;
    color: #0c5460;
}

/* Stok Styles */
.stok-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.stock-table {
    width: 100%;
    border-collapse: collapse;
}

.stock-table th, .stock-table td {
    padding: 0.75rem;
    text-align: left;
    color: whitesmoke;
    border-bottom: 1px solid #ddd;
}

.stock-table th {
    background-color: #f8f9fa4a;
    font-weight: 600;
}

.stock-table tr:hover {
    background-color: #f8f9fa29;
}

.action-btn {
    padding: 0.25rem 0.5rem;
    margin-right: 0.5rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.8rem;
}

.edit-btn {
    background-color: #ffc107;
    color: #212529;
}

.delete-btn {
    background-color: #dc3545;
    color: white;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: 10% auto;
    padding: 2rem;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    position: relative;
}

.close {
    position: absolute;
    top: 1rem;
    right: 1.5rem;
    font-size: 1.5rem;
    cursor: pointer;
}

/* Alert Styles */
.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 5px;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
/* Dummy Section Styles */
.dummy-section {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 2px solid #e9ecef;
    color: whitesmoke;
}

.dummy-section h3 {
    color: #03a3e8;
    margin-bottom: 0.5rem;
}

.dummy-table {
    margin-top: 1rem;
}

.use-do-btn {
    padding: 0.4rem 0.8rem;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.8rem;
    transition: background-color 0.3s;
}

.use-do-btn:hover {
    background-color: #218838;
}

.copy-do-btn {
    padding: 0.4rem 0.8rem;
    background-color: #17a2b8;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.8rem;
    margin-right: 0.5rem;
    transition: background-color 0.3s;
}

.copy-do-btn:hover {
    background-color: #138496;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="header-left">
                <img src="assets/logo.png" alt="Logo UT" class="logo">
                <h1>SEWA GEDUNG</h1>
            </div>
            <div class="header-right">
                <span id="greeting" class="greeting"></span>
                <a href="logout.php" class="btn-secondary">Keluar</a>
            </div>
        </header>

        <nav class="dashboard-nav">
            <ul>
                <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li><a href="penyewa.php" class="nav-link">Informasi Penyewa</a></li>
                <li><a href="booking.php" class="nav-link active">Informasi Booking</a></li>
                <li><a href="gedung.php" class="nav-link">Data Gedung</a></li>
                <li><a href="laporan.php" class="nav-link">Laporan</a></li>
            </ul>
        </nav>

        <main class="stok-main">
            <div class="stok-header">
                <h2>Informasi Booking</h2>
                <button id="addBookingBtn" class="btn-primary">Tambah Booking</button>
            </div>
            
            <table class="stock-table">
                <thead>
                    <tr>
                        <th>ID Booking</th>
                        <th>Nama Gedung</th>
                        <th>Nama Penyewa</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bookings as $booking): ?>
                    <tr>
                        <td><?= $booking['id'] ?></td>
                        <td><?= $booking['nama_gedung'] ?></td>
                        <td><?= $booking['nama_penyewa'] ?></td>
                        <td><?= date('d/m/Y', strtotime($booking['tanggal_mulai'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($booking['tanggal_selesai'])) ?></td>
                        <td>Rp <?= number_format($booking['total_harga'], 0, ',', '.') ?></td>
                        <td><span class="status-<?= $booking['status'] ?>"><?= ucfirst($booking['status']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>

    <!-- Modal Tambah Booking -->
    <div id="addBookingModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Tambah Booking</h2>
            <form id="addBookingForm" method="POST" action="process_booking.php">
                <div class="form-group">
                    <label for="id_gedung">Pilih Gedung</label>
                    <select id="id_gedung" name="id_gedung" required>
                        <option value="">-- Pilih Gedung --</option>
                        <?php foreach($gedung_list as $gedung): ?>
                        <option value="<?= $gedung['id'] ?>" data-harga="<?= $gedung['harga'] ?>">
                            <?= $gedung['nama'] ?> - <?= $gedung['lokasi'] ?> (Rp <?= number_format($gedung['harga'], 0, ',', '.') ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_penyewa">Pilih Penyewa</label>
                    <select id="id_penyewa" name="id_penyewa" required>
                        <option value="">-- Pilih Penyewa --</option>
                        <?php foreach($penyewa_list as $penyewa): ?>
                        <option value="<?= $penyewa['id'] ?>"><?= $penyewa['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" required>
                </div>
                <div class="form-group">
                    <label for="total_harga">Total Harga</label>
                    <input type="number" id="total_harga" name="total_harga" readonly>
                </div>
                <button type="submit" class="btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <script src="js/data.js"></script>
    <script src="js/script.js"></script>
    <script>
        // Setup modal booking
        document.getElementById('addBookingBtn').addEventListener('click', function() {
            document.getElementById('addBookingModal').style.display = 'block';
        });

        // Hitung total harga otomatis
        document.getElementById('id_gedung').addEventListener('change', calculateTotal);
        document.getElementById('tanggal_mulai').addEventListener('change', calculateTotal);
        document.getElementById('tanggal_selesai').addEventListener('change', calculateTotal);

        function calculateTotal() {
            const gedungSelect = document.getElementById('id_gedung');
            const tanggalMulai = document.getElementById('tanggal_mulai').value;
            const tanggalSelesai = document.getElementById('tanggal_selesai').value;
            
            if (gedungSelect.value && tanggalMulai && tanggalSelesai) {
                const harga = gedungSelect.options[gedungSelect.selectedIndex].dataset.harga;
                const mulai = new Date(tanggalMulai);
                const selesai = new Date(tanggalSelesai);
                const hari = Math.ceil((selesai - mulai) / (1000 * 60 * 60 * 24)) + 1;
                
                if (hari > 0) {
                    document.getElementById('total_harga').value = harga * hari;
                }
            }
        }
    </script>
</body>
</html>