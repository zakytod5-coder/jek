CREATE DATABASE IF NOT EXISTS sewa_gedung;
USE sewa_gedung;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE gedung (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    lokasi VARCHAR(100) NOT NULL,
    kapasitas INT NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    status ENUM('tersedia', 'tidak tersedia') DEFAULT 'tersedia'
);

CREATE TABLE penyewa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    telepon VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL
);

CREATE TABLE booking (
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
);

INSERT INTO users (nama, email, password, role) VALUES
('Administrator', 'admin@sewagedung.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

INSERT INTO gedung (nama, lokasi, kapasitas, harga, status) VALUES
('Aula Merdeka', 'Bandung', 500, 3500000, 'tersedia'),
('Graha Utama', 'Cimahi', 300, 2500000, 'tersedia'),
('Bale Wiyata', 'Bandung', 150, 1500000, 'tersedia'),
('Gedung Serbaguna', 'Jakarta', 800, 5000000, 'tersedia'),
('Aula Pancasila', 'Bogor', 200, 2000000, 'tersedia'),
('Balai Kartini', 'Depok', 400, 3000000, 'tidak tersedia'),
('Gedung Proklamasi', 'Bekasi', 600, 4000000, 'tersedia'),
('Aula Garuda', 'Tangerang', 350, 2800000, 'tersedia');

INSERT INTO penyewa (nama, telepon, email, alamat) VALUES
('Rizal Pratama', '081234567890', 'rizal@mail.com', 'Jl. Merdeka No. 123, Bandung'),
('Siti Lestari', '082345678901', 'siti@mail.com', 'Jl. Sudirman No. 456, Cimahi'),
('Adi Nugraha', '083456789012', 'adi@mail.com', 'Jl. Gatot Subroto No. 789, Bandung'),
('Budi Santoso', '084567890123', 'budi@mail.com', 'Jl. Thamrin No. 321, Jakarta'),
('Maya Sari', '085678901234', 'maya@mail.com', 'Jl. Diponegoro No. 654, Bogor'),
('Dedi Kurniawan', '086789012345', 'dedi@mail.com', 'Jl. Ahmad Yani No. 987, Depok'),
('Rina Wati', '087890123456', 'rina@mail.com', 'Jl. Veteran No. 147, Bekasi'),
('Agus Setiawan', '088901234567', 'agus@mail.com', 'Jl. Pahlawan No. 258, Tangerang');

INSERT INTO booking (id_gedung, id_penyewa, tanggal_mulai, tanggal_selesai, total_harga, status) VALUES
(1, 1, '2025-01-10', '2025-01-12', 10500000, 'aktif'),
(2, 2, '2025-01-15', '2025-01-16', 5000000, 'selesai'),
(3, 3, '2025-01-20', '2025-01-21', 3000000, 'aktif'),
(4, 4, '2025-02-01', '2025-02-03', 15000000, 'aktif'),
(5, 5, '2025-02-05', '2025-02-06', 4000000, 'selesai'),
(7, 6, '2025-02-10', '2025-02-12', 12000000, 'aktif'),
(8, 7, '2025-02-15', '2025-02-16', 5600000, 'selesai'),
(1, 8, '2025-03-01', '2025-03-02', 7000000, 'aktif'),
(2, 1, '2025-03-05', '2025-03-07', 7500000, 'aktif'),
(4, 3, '2025-03-10', '2025-03-11', 10000000, 'selesai');