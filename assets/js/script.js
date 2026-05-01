// assets/js/script.js
// Fungsi untuk validasi dan preview dinamis (opsional, tidak menggantikan proses PHP)

(function() {
    // Ambil elemen-elemen form
    const form = document.getElementById('formKesehatan');
    const tinggiInput = document.querySelector('input[name="tinggi"]');
    const beratInput = document.querySelector('input[name="berat"]');
    const sistolInput = document.querySelector('input[name="sistol"]');
    const diastolInput = document.querySelector('input[name="diastol"]');
    const gulaPuasaInput = document.querySelector('input[name="gpuasa"]');
    const gulaPPInput = document.querySelector('input[name="gpp"]');
    const asamUratInput = document.querySelector('input[name="asam_urat"]');
    const kolesterolInput = document.querySelector('input[name="kolesterol"]');
    const usiaInput = document.querySelector('input[name="usia"]');
    const genderRadios = document.querySelectorAll('input[name="gender"]');
    
    // Fungsi validasi angka positif
    function validatePositiveNumber(value, fieldName) {
        const num = parseFloat(value);
        if (isNaN(num) || num <= 0) {
            return false;
        }
        return true;
    }
    
    // Validasi sebelum submit
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessages = [];
            
            // Validasi Tinggi
            if (!validatePositiveNumber(tinggiInput.value, 'Tinggi')) {
                errorMessages.push('❌ Tinggi badan harus diisi angka positif (cm)');
                tinggiInput.style.borderColor = '#ef4444';
                isValid = false;
            } else {
                tinggiInput.style.borderColor = '#cbd5e1';
            }
            
            // Validasi Berat
            if (!validatePositiveNumber(beratInput.value, 'Berat')) {
                errorMessages.push('❌ Berat badan harus diisi angka positif (kg)');
                beratInput.style.borderColor = '#ef4444';
                isValid = false;
            } else {
                beratInput.style.borderColor = '#cbd5e1';
            }
            
            // Validasi Tekanan Darah
            if (!validatePositiveNumber(sistolInput.value, 'Sistol')) {
                errorMessages.push('❌ Tekanan darah sistol harus diisi angka positif');
                sistolInput.style.borderColor = '#ef4444';
                isValid = false;
            } else {
                sistolInput.style.borderColor = '#cbd5e1';
            }
            
            if (!validatePositiveNumber(diastolInput.value, 'Diastol')) {
                errorMessages.push('❌ Tekanan darah diastol harus diisi angka positif');
                diastolInput.style.borderColor = '#ef4444';
                isValid = false;
            } else {
                diastolInput.style.borderColor = '#cbd5e1';
            }
            
            // Validasi Gula Darah
            if (!validatePositiveNumber(gulaPuasaInput.value, 'Gula Puasa')) {
                errorMessages.push('❌ Gula darah puasa harus diisi angka positif');
                gulaPuasaInput.style.borderColor = '#ef4444';
                isValid = false;
            } else {
                gulaPuasaInput.style.borderColor = '#cbd5e1';
            }
            
            if (!validatePositiveNumber(gulaPPInput.value, 'Gula PP')) {
                errorMessages.push('❌ Gula darah 2 jam PP harus diisi angka positif');
                gulaPPInput.style.borderColor = '#ef4444';
                isValid = false;
            } else {
                gulaPPInput.style.borderColor = '#cbd5e1';
            }
            
            // Validasi Asam Urat
            if (!validatePositiveNumber(asamUratInput.value, 'Asam Urat')) {
                errorMessages.push('❌ Asam urat harus diisi angka positif');
                asamUratInput.style.borderColor = '#ef4444';
                isValid = false;
            } else {
                asamUratInput.style.borderColor = '#cbd5e1';
            }
            
            // Validasi Kolesterol
            if (!validatePositiveNumber(kolesterolInput.value, 'Kolesterol')) {
                errorMessages.push('❌ Kolesterol harus diisi angka positif');
                kolesterolInput.style.borderColor = '#ef4444';
                isValid = false;
            } else {
                kolesterolInput.style.borderColor = '#cbd5e1';
            }
            
            // Validasi Usia
            if (usiaInput.value && (parseInt(usiaInput.value) < 0 || parseInt(usiaInput.value) > 130)) {
                errorMessages.push('❌ Usia harus antara 0-130 tahun');
                usiaInput.style.borderColor = '#ef4444';
                isValid = false;
            } else if (usiaInput.value && !validatePositiveNumber(usiaInput.value, 'Usia')) {
                errorMessages.push('❌ Usia harus diisi angka positif');
                usiaInput.style.borderColor = '#ef4444';
                isValid = false;
            } else {
                usiaInput.style.borderColor = '#cbd5e1';
            }
            
            // Tampilkan error jika ada
            if (!isValid) {
                e.preventDefault();
                alert(errorMessages.join('\n'));
            }
        });
    }
    
    // Fungsi untuk menghitung BMI secara real-time (preview)
    function hitungBMIRealTime() {
        const tinggi = parseFloat(tinggiInput?.value);
        const berat = parseFloat(beratInput?.value);
        
        if (tinggi && berat && tinggi > 0 && berat > 0) {
            const tinggiM = tinggi / 100;
            const bmi = (berat / (tinggiM * tinggiM)).toFixed(1);
            
            // Tampilkan preview BMI jika ada elemen khusus
            let previewElement = document.getElementById('bmiPreview');
            if (!previewElement) {
                // Buat elemen preview jika belum ada
                const container = document.querySelector('.form-group:has(input[name="berat"])');
                if (container && !document.getElementById('bmiPreview')) {
                    const preview = document.createElement('div');
                    preview.id = 'bmiPreview';
                    preview.style.marginTop = '8px';
                    preview.style.padding = '8px';
                    preview.style.borderRadius = '12px';
                    preview.style.fontSize = '0.85rem';
                    container.appendChild(preview);
                    previewElement = preview;
                }
            }
            
            if (previewElement) {
                let status = '';
                let warna = '';
                if (bmi < 18.5) { status = 'Kurus'; warna = '#f59e0b'; }
                else if (bmi >= 18.5 && bmi <= 24.9) { status = 'Ideal'; warna = '#10b981'; }
                else if (bmi >= 25 && bmi <= 29.9) { status = 'Overweight'; warna = '#f97316'; }
                else { status = 'Obesitas'; warna = '#ef4444'; }
                
                previewElement.innerHTML = `📊 <strong>Preview BMI:</strong> ${bmi} (${status})`;
                previewElement.style.backgroundColor = `${warna}20`;
                previewElement.style.color = warna;
            }
        }
    }
    
    // Event listener untuk preview BMI
    if (tinggiInput && beratInput) {
        tinggiInput.addEventListener('input', hitungBMIRealTime);
        beratInput.addEventListener('input', hitungBMIRealTime);
    }
    
    // Fungsi untuk menampilkan saran berdasarkan input real-time (opsional)
    function previewSaranRealTime() {
        // Optional: tampilkan peringatan dini jika nilai di luar batas normal
        const gulaPuasa = parseFloat(gulaPuasaInput?.value);
        if (gulaPuasa && gulaPuasa >= 126) {
            showTooltip(gulaPuasaInput, '⚠️ Gula puasa ≥126 mg/dL: indikasi diabetes, segera konsultasi');
        } else if (gulaPuasa && gulaPuasa >= 100) {
            showTooltip(gulaPuasaInput, '⚠️ Gula puasa 100-125 mg/dL: pra-diabetes');
        } else if (gulaPuasa && gulaPuasa > 0) {
            clearTooltip(gulaPuasaInput);
        }
        
        const kolesterol = parseFloat(kolesterolInput?.value);
        if (kolesterol && kolesterol >= 240) {
            showTooltip(kolesterolInput, '⚠️ Kolesterol ≥240 mg/dL: tinggi, perlu intervensi');
        } else if (kolesterol && kolesterol >= 200) {
            showTooltip(kolesterolInput, '⚠️ Kolesterol 200-239 mg/dL: batas tinggi');
        } else if (kolesterol && kolesterol > 0) {
            clearTooltip(kolesterolInput);
        }
        
        const sistol = parseFloat(sistolInput?.value);
        const diastol = parseFloat(diastolInput?.value);
        if (sistol && diastol) {
            if (sistol >= 180 || diastol >= 120) {
                showTooltip(sistolInput, '⚠️ KRISIS HIPERTENSI! Segera ke UGD!');
                showTooltip(diastolInput, '⚠️ KRISIS HIPERTENSI!');
            } else if (sistol >= 140 || diastol >= 90) {
                showTooltip(sistolInput, '⚠️ Hipertensi stage 2, konsultasi dokter');
            } else if (sistol >= 130 || diastol >= 80) {
                showTooltip(sistolInput, '⚠️ Hipertensi stage 1 / elevated');
            } else {
                clearTooltip(sistolInput);
                clearTooltip(diastolInput);
            }
        }
    }
    
    // Helper untuk menampilkan tooltip sementara
    let activeTimeouts = {};
    function showTooltip(element, message) {
        if (!element) return;
        const existingId = `tooltip-${element.name}`;
        let tooltip = document.getElementById(existingId);
        
        if (!tooltip) {
            tooltip = document.createElement('div');
            tooltip.id = existingId;
            tooltip.style.position = 'absolute';
            tooltip.style.backgroundColor = '#fef3c7';
            tooltip.style.color = '#92400e';
            tooltip.style.padding = '4px 10px';
            tooltip.style.borderRadius = '20px';
            tooltip.style.fontSize = '0.7rem';
            tooltip.style.marginTop = '4px';
            tooltip.style.zIndex = '100';
            element.style.position = 'relative';
            element.parentNode?.appendChild(tooltip);
        }
        
        tooltip.innerHTML = message;
        tooltip.style.display = 'block';
        
        // Auto hide setelah 5 detik
        if (activeTimeouts[existingId]) clearTimeout(activeTimeouts[existingId]);
        activeTimeouts[existingId] = setTimeout(() => {
            if (tooltip) tooltip.style.display = 'none';
        }, 5000);
    }
    
    function clearTooltip(element) {
        if (!element) return;
        const existingId = `tooltip-${element.name}`;
        const tooltip = document.getElementById(existingId);
        if (tooltip) tooltip.style.display = 'none';
    }
    
    // Event listener untuk preview saran
    const medicalInputs = [gulaPuasaInput, kolesterolInput, sistolInput, diastolInput, asamUratInput, gulaPPInput];
    medicalInputs.forEach(input => {
        if (input) {
            input.addEventListener('input', previewSaranRealTime);
        }
    });
    
    // Set gender info tambahan
    function updateGenderInfo() {
        const selectedGender = document.querySelector('input[name="gender"]:checked')?.value;
        const asamUratLabel = document.querySelector('label[for="asam_urat"]') || 
                              document.querySelector('.form-group:has(input[name="asam_urat"]) label');
        if (asamUratLabel && selectedGender) {
            const batas = selectedGender === 'pria' ? '≤7.0 mg/dL' : '≤6.0 mg/dL';
            // Optional: update label tanpa mengganggu terlalu banyak
        }
    }
    
    genderRadios.forEach(radio => {
        radio.addEventListener('change', updateGenderInfo);
    });
    
    // Jalankan sekali saat load
    updateGenderInfo();
    hitungBMIRealTime();
    
    console.log('✅ Script.js berjalan - validasi & preview siap');
})();