CREATE DATABASE IF NOT EXISTS db_crud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_crud;

CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    jurusan VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO mahasiswa (nim, nama, email, jurusan) VALUES
('2301001', 'Budi Santoso', 'budi@example.com', 'Teknik Informatika'),
('2301002', 'Siti Aminah', 'siti@example.com', 'Sistem Informasi'),
('2301003', 'Agus Prasetyo', 'agus@example.com', 'Teknik Elektro');
