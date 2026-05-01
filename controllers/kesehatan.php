<?php
// controllers/kesehatan.php
require_once 'models/PemeriksaanModel.php';

class KesehatanController {
    private $model;
    
    public function __construct() {
        $this->model = new PemeriksaanModel();
    }
    
    // Fungsi interpretasi medis (sama seperti versi JavaScript)
    public function interpretasiTD($sistol, $diastol) {
        if ($sistol < 90 || $diastol < 60) 
            return ["status" => "Hipotensi", "warna" => "#f97316", "saran" => "Perbanyak minum, konsultasi jika pusing."];
        if ($sistol < 120 && $diastol < 80) 
            return ["status" => "Normal Optimal", "warna" => "#10b981", "saran" => "Pertahankan gaya hidup sehat."];
        if ($sistol >= 120 && $sistol <= 129 && $diastol < 80) 
            return ["status" => "Elevated", "warna" => "#f59e0b", "saran" => "Kurangi garam, olahraga teratur."];
        if (($sistol >= 130 && $sistol <= 139) || ($diastol >= 80 && $diastol <= 89)) 
            return ["status" => "Hipertensi Stage 1", "warna" => "#f97316", "saran" => "Konsultasi ke dokter."];
        if ($sistol >= 140 || $diastol >= 90) 
            return ["status" => "Hipertensi Stage 2", "warna" => "#ef4444", "saran" => "Segera periksa ke dokter."];
        if ($sistol >= 180 || $diastol >= 120) 
            return ["status" => "Krisis Hipertensi", "warna" => "#b91c1c", "saran" => "SEGERA KE UGD!"];
        return ["status" => "Tidak valid", "warna" => "#aaa", "saran" => "Cek ulang tensi."];
    }
    
    public function interpretasiGulaPuasa($gd) {
        if ($gd < 70) return ["status" => "Hipoglikemia", "warna" => "#f97316", "saran" => "Segera konsumsi gula."];
        if ($gd >= 70 && $gd < 100) return ["status" => "Normal", "warna" => "#10b981", "saran" => "Bagus, pertahankan."];
        if ($gd >= 100 && $gd <= 125) return ["status" => "Pra-Diabetes", "warna" => "#f59e0b", "saran" => "Kontrol gula & olahraga."];
        if ($gd >= 126) return ["status" => "Diabetes", "warna" => "#ef4444", "saran" => "Segera ke dokter."];
        return ["status" => "Invalid", "warna" => "#aaa", "saran" => ""];
    }
    
    public function interpretasiGulaPP($gpp) {
        if ($gpp < 140) return ["status" => "Normal", "warna" => "#10b981", "saran" => "Kontrol bagus."];
        if ($gpp >= 140 && $gpp <= 199) return ["status" => "Pra-Diabetes", "warna" => "#f59e0b", "saran" => "Perbaiki pola makan."];
        if ($gpp >= 200) return ["status" => "Diabetes", "warna" => "#ef4444", "saran" => "Butuh penanganan medis."];
        return ["status" => "-", "warna" => "#aaa", "saran" => ""];
    }
    
    public function interpretasiAsamUrat($value, $gender) {
        $batas = ($gender == 'pria') ? 7.0 : 6.0;
        if ($value <= $batas) return ["status" => "Normal", "warna" => "#10b981", "saran" => "Pertahankan, hindari jeroan."];
        if ($value > $batas && $value <= 9.0) return ["status" => "Hiperurisemia Ringan", "warna" => "#f59e0b", "saran" => "Kurangi purin, perbanyak air."];
        return ["status" => "Hiperurisemia Tinggi", "warna" => "#ef4444", "saran" => "Kontrol ke dokter, risiko gout."];
    }
    
    public function interpretasiKolesterol($kol) {
        if ($kol < 200) return ["status" => "Normal", "warna" => "#10b981", "saran" => "Pertahankan diet sehat."];
        if ($kol >= 200 && $kol <= 239) return ["status" => "Batas Tinggi", "warna" => "#f59e0b", "saran" => "Kurangi lemak jenuh."];
        if ($kol >= 240) return ["status" => "Tinggi", "warna" => "#ef4444", "saran" => "Segera konsultasi."];
        return ["status" => "-", "warna" => "#aaa", "saran" => ""];
    }
    
    public function hitungBMI($tinggi, $berat) {
        if ($tinggi <= 0) return 0;
        $tinggiM = $tinggi / 100;
        return round($berat / ($tinggiM * $tinggiM), 1);
    }
    
    public function interpretasiBMI($bmi) {
        if ($bmi < 18.5) return ["status" => "Kurus", "warna" => "#f59e0b", "saran" => "Tingkatkan asupan kalori."];
        if ($bmi >= 18.5 && $bmi <= 24.9) return ["status" => "Ideal", "warna" => "#10b981", "saran" => "Jaga pola makan."];
        if ($bmi >= 25 && $bmi <= 29.9) return ["status" => "Overweight", "warna" => "#f97316", "saran" => "Turunkan berat badan."];
        if ($bmi >= 30) return ["status" => "Obesitas", "warna" => "#ef4444", "saran" => "Konsultasi ke ahli gizi."];
        return ["status" => "-", "warna" => "#aaa", "saran" => ""];
    }
    
    public function prosesDanSimpan($post) {
        $gender = $post['gender'];
        $usia = (int)$post['usia'];
        $tinggi = (float)$post['tinggi'];
        $berat = (float)$post['berat'];
        $sistol = (int)$post['sistol'];
        $diastol = (int)$post['diastol'];
        $gpuasa = (float)$post['gpuasa'];
        $gpp = (float)$post['gpp'];
        $asam = (float)$post['asam_urat'];
        $kolesterol = (int)$post['kolesterol'];
        
        $tensi = $this->interpretasiTD($sistol, $diastol);
        $gulaP = $this->interpretasiGulaPuasa($gpuasa);
        $gulaPP = $this->interpretasiGulaPP($gpp);
        $asamU = $this->interpretasiAsamUrat($asam, $gender);
        $kolesterolRes = $this->interpretasiKolesterol($kolesterol);
        $bmiVal = $this->hitungBMI($tinggi, $berat);
        $bmiRes = $this->interpretasiBMI($bmiVal);
        
        // Hitung risiko sederhana
        $risiko = $this->hitungRisiko($tensi['status'], $gulaP['status'], $gulaPP['status'], $asamU['status'], $kolesterolRes['status'], $bmiRes['status']);
        
        $data = [
            'nama' => $post['nama'] ?? 'Pasien',
            'gender' => $gender,
            'usia' => $usia,
            'tinggi' => $tinggi,
            'berat' => $berat,
            'sistol' => $sistol,
            'diastol' => $diastol,
            'gula_puasa' => $gpuasa,
            'gula_pp' => $gpp,
            'asam_urat' => $asam,
            'kolesterol' => $kolesterol,
            'bmi' => $bmiVal,
            'tensi_status' => $tensi['status'],
            'gula_puasa_status' => $gulaP['status'],
            'gula_pp_status' => $gulaPP['status'],
            'asam_urat_status' => $asamU['status'],
            'kolesterol_status' => $kolesterolRes['status'],
            'bmi_status' => $bmiRes['status'],
            'risiko_faktor' => $risiko['faktor'],
            'rekomendasi' => $risiko['rekomendasi']
        ];
        
        $id = $this->model->simpanPemeriksaan($data);
        
        return [
            'id' => $id,
            'hasil' => [
                'tensi' => $tensi, 'gulaPuasa' => $gulaP, 'gulaPP' => $gulaPP,
                'asamUrat' => $asamU, 'kolesterol' => $kolesterolRes, 'bmi' => ['value' => $bmiVal, 'status' => $bmiRes['status'], 'warna' => $bmiRes['warna'], 'saran' => $bmiRes['saran']],
                'risiko' => $risiko
            ]
        ];
    }
    
    private function hitungRisiko($tensi, $gulaP, $gulaPP, $asam, $kolesterol, $bmi) {
        $faktor = [];
        if (strpos($tensi, 'Hipertensi') !== false || $tensi == 'Elevated') $faktor[] = "Tekanan darah tinggi";
        if (strpos($gulaP, 'Diabetes') !== false || strpos($gulaP, 'Pra-Diabetes') !== false) $faktor[] = "Gula darah puasa bermasalah";
        if (strpos($gulaPP, 'Diabetes') !== false || strpos($gulaPP, 'Pra-Diabetes') !== false) $faktor[] = "Gula postprandial bermasalah";
        if (strpos($asam, 'Hiperurisemia') !== false) $faktor[] = "Asam urat tinggi";
        if (strpos($kolesterol, 'Tinggi') !== false || strpos($kolesterol, 'Batas Tinggi') !== false) $faktor[] = "Kolesterol tidak ideal";
        if (strpos($bmi, 'Overweight') !== false || strpos($bmi, 'Obesitas') !== false) $faktor[] = "Berat badan berlebih";
        
        if (count($faktor) == 0) $rekom = "✅ Risiko rendah. Pertahankan gaya hidup sehat!";
        elseif (count($faktor) <= 2) $rekom = "⚠️ Risiko sedang. Perbaiki pola makan & olahraga teratur.";
        else $rekom = "❗ Risiko tinggi. Segera evaluasi ke dokter.";
        
        return ['faktor' => implode(", ", $faktor), 'rekomendasi' => $rekom];
    }
}
?>