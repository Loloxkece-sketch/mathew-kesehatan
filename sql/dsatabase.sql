-- Buat database
CREATE DATABASE IF NOT EXISTS cek_kesehatan;
USE cek_kesehatan;

-- Tabel pasien
CREATE TABLE IF NOT EXISTS pasien (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    gender ENUM('pria', 'wanita') NOT NULL,
    usia INT NOT NULL,
    tinggi_cm DECIMAL(5,1) NOT NULL,
    berat_kg DECIMAL(5,1) NOT NULL,
    sistol INT NOT NULL,
    diastol INT NOT NULL,
    gula_puasa DECIMAL(5,1) NOT NULL,
    gula_pp DECIMAL(5,1) NOT NULL,
    asam_urat DECIMAL(4,1) NOT NULL,
    kolesterol INT NOT NULL,
    bmi DECIMAL(4,1),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel hasil pemeriksaan (opsional, untuk riwayat interpretasi)
CREATE TABLE IF NOT EXISTS hasil_pemeriksaan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pasien_id INT,
    tensi_status VARCHAR(100),
    gula_puasa_status VARCHAR(100),
    gula_pp_status VARCHAR(100),
    asam_urat_status VARCHAR(100),
    kolesterol_status VARCHAR(100),
    bmi_status VARCHAR(100),
    risiko_faktor TEXT,
    rekomendasi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pasien_id) REFERENCES pasien(id) ON DELETE CASCADE
);