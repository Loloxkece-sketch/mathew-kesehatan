<?php
// models/PemeriksaanModel.php
require_once 'config/database.php';

class PemeriksaanModel {
    private $conn;
    private $table_pasien = "pasien";
    private $table_hasil = "hasil_pemeriksaan";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Simpan data pasien & hasil
    public function simpanPemeriksaan($data) {
        try {
            // Insert ke tabel pasien
            $query = "INSERT INTO " . $this->table_pasien . "
                      (nama, gender, usia, tinggi_cm, berat_kg, sistol, diastol, 
                       gula_puasa, gula_pp, asam_urat, kolesterol, bmi)
                      VALUES (:nama, :gender, :usia, :tinggi, :berat, :sistol, :diastol,
                              :gula_puasa, :gula_pp, :asam_urat, :kolesterol, :bmi)";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':nama', $data['nama']);
            $stmt->bindParam(':gender', $data['gender']);
            $stmt->bindParam(':usia', $data['usia']);
            $stmt->bindParam(':tinggi', $data['tinggi']);
            $stmt->bindParam(':berat', $data['berat']);
            $stmt->bindParam(':sistol', $data['sistol']);
            $stmt->bindParam(':diastol', $data['diastol']);
            $stmt->bindParam(':gula_puasa', $data['gula_puasa']);
            $stmt->bindParam(':gula_pp', $data['gula_pp']);
            $stmt->bindParam(':asam_urat', $data['asam_urat']);
            $stmt->bindParam(':kolesterol', $data['kolesterol']);
            $stmt->bindParam(':bmi', $data['bmi']);
            
            if($stmt->execute()) {
                $pasien_id = $this->conn->lastInsertId();
                
                // Insert ke tabel hasil_pemeriksaan
                $query2 = "INSERT INTO " . $this->table_hasil . "
                          (pasien_id, tensi_status, gula_puasa_status, gula_pp_status, 
                           asam_urat_status, kolesterol_status, bmi_status, risiko_faktor, rekomendasi)
                          VALUES (:pid, :tensi, :gpuasa, :gpp, :asam, :kolesterol, :bmi, :risiko, :rekom)";
                $stmt2 = $this->conn->prepare($query2);
                $stmt2->bindParam(':pid', $pasien_id);
                $stmt2->bindParam(':tensi', $data['tensi_status']);
                $stmt2->bindParam(':gpuasa', $data['gula_puasa_status']);
                $stmt2->bindParam(':gpp', $data['gula_pp_status']);
                $stmt2->bindParam(':asam', $data['asam_urat_status']);
                $stmt2->bindParam(':kolesterol', $data['kolesterol_status']);
                $stmt2->bindParam(':bmi', $data['bmi_status']);
                $stmt2->bindParam(':risiko', $data['risiko_faktor']);
                $stmt2->bindParam(':rekom', $data['rekomendasi']);
                $stmt2->execute();
                
                return $pasien_id;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Ambil riwayat terbaru (untuk ditampilkan)
    public function getRiwayatTerbaru($limit = 10) {
        $query = "SELECT p.*, h.* FROM " . $this->table_pasien . " p 
                  LEFT JOIN " . $this->table_hasil . " h ON p.id = h.pasien_id
                  ORDER BY p.created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>