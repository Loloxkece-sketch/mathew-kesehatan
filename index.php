<?php
require_once 'controllers/kesehatan.php';
$controller = new KesehatanController();
$hasil = null;

// Perbaikan: akses properti public

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cek') {
    $hasil = $controller->prosesDanSimpan($_POST);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Kesehatan Lengkap | PHP + Database</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <div class="hero">
        <h1>🫀 Cek Kesehatan Lengkap & Akurat</h1>
        <p>Tekanan Darah · Gula Darah · Asam Urat · Kolesterol · BMI</p>
        <div class="badge-medis">📋 Database tersimpan · Standar Medis WHO/PERKENI</div>
    </div>

    <div class="dashboard">
        <div class="card">
            <h2>📋 Data Diri & Pemeriksaan</h2>
            <form method="POST" id="formKesehatan">
                <input type="hidden" name="action" value="cek">
                <div class="form-group">
                    <label>🧑 Nama</label>
                    <input type="text" name="nama" placeholder="Nama / Inisial" value="Pasien Demo">
                </div>
                <div class="form-group">
                    <label>⚥ Jenis Kelamin</label>
                    <div class="radio-group">
                        <label><input type="radio" name="gender" value="pria" checked> Laki-laki</label>
                        <label><input type="radio" name="gender" value="wanita"> Perempuan</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>📅 Usia (tahun)</label>
                    <input type="number" name="usia" value="35" required>
                </div>
                <div class="form-group">
                    <label>📏 Tinggi (cm)</label>
                    <input type="number" name="tinggi" step="any" value="165" required>
                </div>
                <div class="form-group">
                    <label>⚖️ Berat (kg)</label>
                    <input type="number" name="berat" step="any" value="62" required>
                </div>
                <hr>
                <div class="form-group">
                    <label>❤️ Tekanan Darah (Sistol/Diastol)</label>
                    <div style="display:flex; gap:12px;">
                        <input type="number" name="sistol" placeholder="Sistol" value="118" required>
                        <input type="number" name="diastol" placeholder="Diastol" value="78" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>🩸 Gula Puasa (mg/dL)</label>
                    <input type="number" name="gpuasa" step="any" value="92" required>
                </div>
                <div class="form-group">
                    <label>🍽️ Gula 2 jam PP (mg/dL)</label>
                    <input type="number" name="gpp" step="any" value="125" required>
                </div>
                <div class="form-group">
                    <label>🧬 Asam Urat (mg/dL)</label>
                    <input type="number" name="asam_urat" step="any" value="5.4" required>
                </div>
                <div class="form-group">
                    <label>🫀 Kolesterol Total (mg/dL)</label>
                    <input type="number" name="kolesterol" value="185" required>
                </div>
                <button type="submit">🔍 Periksa & Simpan ke Database</button>
            </form>
        </div>

        <div class="card" id="resultPanel">
            <h2>📊 Hasil Pemeriksaan</h2>
            <div id="hasilKeseluruhan">
                <?php if ($hasil): ?>
                    <div class="result-section">
                        <div style="font-weight:bold; margin-bottom:10px;">✅ Hasil untuk: <?= htmlspecialchars($_POST['nama']) ?> (<?= $_POST['gender'] == 'pria' ? 'Laki-laki' : 'Perempuan' ?>, <?= $_POST['usia'] ?> th)</div>
                        <div class="result-item" style="border-left-color: <?= $hasil['hasil']['tensi']['warna'] ?>;">
                            <h3>❤️ TD: <?= $_POST['sistol'] ?>/<?= $_POST['diastol'] ?> mmHg <span class="status-badge" style="background:<?= $hasil['hasil']['tensi']['warna'] ?>20; color:<?= $hasil['hasil']['tensi']['warna'] ?>;"><?= $hasil['hasil']['tensi']['status'] ?></span></h3>
                            <div class="advice-text">📌 <?= $hasil['hasil']['tensi']['saran'] ?></div>
                        </div>
                        <div class="result-item" style="border-left-color: <?= $hasil['hasil']['gulaPuasa']['warna'] ?>;">
                            <h3>🩸 Gula Puasa: <?= $_POST['gpuasa'] ?> mg/dL <span class="status-badge" style="background:<?= $hasil['hasil']['gulaPuasa']['warna'] ?>20;"><?= $hasil['hasil']['gulaPuasa']['status'] ?></span></h3>
                            <div class="advice-text">📌 <?= $hasil['hasil']['gulaPuasa']['saran'] ?></div>
                        </div>
                        <div class="result-item" style="border-left-color: <?= $hasil['hasil']['gulaPP']['warna'] ?>;">
                            <h3>🍽️ Gula PP: <?= $_POST['gpp'] ?> mg/dL <span class="status-badge"><?= $hasil['hasil']['gulaPP']['status'] ?></span></h3>
                            <div class="advice-text">📌 <?= $hasil['hasil']['gulaPP']['saran'] ?></div>
                        </div>
                        <div class="result-item" style="border-left-color: <?= $hasil['hasil']['asamUrat']['warna'] ?>;">
                            <h3>🧬 Asam Urat: <?= $_POST['asam_urat'] ?> mg/dL <span><?= $hasil['hasil']['asamUrat']['status'] ?></span></h3>
                            <div class="advice-text">📌 <?= $hasil['hasil']['asamUrat']['saran'] ?></div>
                        </div>
                        <div class="result-item" style="border-left-color: <?= $hasil['hasil']['kolesterol']['warna'] ?>;">
                            <h3>🫀 Kolesterol: <?= $_POST['kolesterol'] ?> mg/dL <span><?= $hasil['hasil']['kolesterol']['status'] ?></span></h3>
                            <div class="advice-text">📌 <?= $hasil['hasil']['kolesterol']['saran'] ?></div>
                        </div>
                        <div class="result-item" style="border-left-color: <?= $hasil['hasil']['bmi']['warna'] ?>;">
                            <h3>⚖️ BMI: <?= $hasil['hasil']['bmi']['value'] ?> <span><?= $hasil['hasil']['bmi']['status'] ?></span></h3>
                            <div class="advice-text">📌 <?= $hasil['hasil']['bmi']['saran'] ?></div>
                        </div>
                    </div>
                    <div class="result-section" style="background:#eef2ff; margin-top:12px;">
                        <strong>🔍 Faktor risiko:</strong> <?= $hasil['hasil']['risiko']['faktor'] ?><br>
                        <strong>💊 Rekomendasi:</strong> <?= $hasil['hasil']['risiko']['rekomendasi'] ?>
                    </div>
                <?php else: ?>
                    <div class="result-section" style="background:#e6f0ff; text-align:center;">
                        ⚕️ Klik tombol "Periksa & Simpan ke Database" untuk melihat hasil.
                    </div>
                <?php endif; ?>
            </div>
        </div>
