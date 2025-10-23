<?php
// /gudang/tambah.php

// =========================================================================
//  LANGKAH 1: PINDAHKAN SEMUA LOGIKA PEMROSESAN FORM KE ATAS
// =========================================================================

// Kita butuh session dan koneksi database SEBELUM logika apapun
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Panggil koneksi.php secara manual di sini
include_once(__DIR__ . "/../koneksi.php"); 

$error = "";
// Blok ini (yang aslinya ada di baris 136) dipindahkan ke sini
if (isset($_POST['simpan'])) {
    $kodegudang = trim($_POST['kodegudang']);
    $namagudang = trim($_POST['namagudang']);
    // PERBAIKAN: Mengambil data yang sesuai dengan kolom DB
    $alamat     = trim($_POST['alamat']);
    $kontak     = trim($_POST['kontak']);
    $kapasitas  = (double)$_POST['kapasitas'];
    

    // Validasi
    if (empty($kodegudang) || empty($namagudang)) {
        $error = "Kode Gudang dan Nama Gudang wajib diisi!";
    } elseif (strlen($kodegudang) > 20) { // Max 20 di skema DB
        $error = "Kode Gudang terlalu panjang! Maksimal 20 karakter.";
    } elseif (strlen($namagudang) > 100) { // Max 100 di skema DB
        $error = "Nama Gudang terlalu panjang! Maksimal 100 karakter.";
    } elseif (strlen($kontak) > 50) { // Max 50 di skema DB
        $error = "Kontak terlalu panjang! Maksimal 50 karakter.";
    } elseif (strlen($alamat) > 200) { // Max 200 di skema DB
        $error = "Alamat terlalu panjang! Maksimal 200 karakter.";
    }


    // Cek kode unik
    if ($error == "") {
        // Pastikan $koneksi ada (dari include_once di atas)
        $cekKode = mysqli_query($koneksi, "SELECT kodegudang FROM gudang WHERE kodegudang='$kodegudang'");
        if (mysqli_num_rows($cekKode) > 0) {
            $error = "Kode Gudang sudah ada, gunakan kode lain!";
        }
    }

    // Kalau tidak ada error → simpan
    if ($error == "") {
        // PERBAIKAN: Menggunakan kolom yang benar dan memasukkan semua data yang diinput
        $alamat_sql = mysqli_real_escape_string($koneksi, $alamat);
        $kontak_sql = mysqli_real_escape_string($koneksi, $kontak);
        
        mysqli_query($koneksi, "INSERT INTO gudang (kodegudang, namagudang, alamat, kontak, kapasitas)
                                VALUES ('$kodegudang', '$namagudang', '$alamat_sql', '$kontak_sql', $kapasitas)");
        
        $_SESSION['msg'] = 'success';
        
        // Panggilan header() SEKARANG AMAN karena belum ada HTML yang dikirim
        header("Location: index.php");
        exit; // Selalu 'exit' setelah redirect
    }
}

// =========================================================================
//  LANGKAH 2: SETELAH SEMUA LOGIKA SELESAI, BARU MULAI TAMPILAN (HTML)
// =========================================================================
$page_title = "Tambah Gudang";
// File header.php sekarang aman untuk dipanggil
include "../template/header.php";
?>

<style>
/* Clean Modern Style - Matching Your Design System */
.page-wrapper {
    background: #f5f7fa;
    min-height: 100vh;
    padding: 2rem 0;
}

/* Clean Card */
.clean-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

/* Header Style */
.page-header {
    background: white;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e8ecf4;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.page-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
}

/* Back Button - Cyan Style */
.btn-back-clean {
    background: white;
    color: #667eea;
    border: 1px solid #e8ecf4;
    border-radius: 8px;
    padding: 0.625rem 1.25rem;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-back-clean:hover {
    background: #f8f9fa;
    border-color: #cbd5e0;
    color: #667eea;
}

/* Form Section */
.form-section {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.form-section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f4f8;
}

/* Form Controls */
.form-label-clean {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-control-clean {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    background: #ffffff;
}

.form-control-clean:focus {
    border-color: #17c1e8;
    box-shadow: 0 0 0 3px rgba(23, 193, 232, 0.1);
    outline: none;
}

.form-control-clean::placeholder {
    color: #a0aec0;
}

/* Required Mark */
.required-mark {
    color: #ef4444;
    margin-left: 2px;
}

/* Form Text Helper */
.form-text-clean {
    color: #718096;
    font-size: 0.85rem;
    margin-top: 0.375rem;
    display: block;
}

/* Alert Clean */
.alert-clean {
    border: none;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-clean.alert-danger {
    background: #fef2f2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

.alert-clean .btn-close {
    margin-left: auto;
}

/* Submit Button - Cyan Primary */
.btn-submit-clean {
    background: #17c1e8;
    border: none;
    border-radius: 8px;
    padding: 0.875rem 2rem;
    font-weight: 600;
    color: white;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-submit-clean:hover {
    background: #0ea5c9;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(23, 193, 232, 0.3);
    color: white;
}

.btn-submit-clean:active {
    transform: translateY(0);
}

/* Input Focus Effect */
.input-group-clean {
    position: relative;
}

.input-group-clean input:focus,
.input-group-clean textarea:focus {
    border-color: #17c1e8;
}

/* Spacing */
.form-group-spacing {
    margin-bottom: 1.5rem;
}

/* Badge Style for Hints */
.hint-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    color: #6b7280;
    font-size: 0.85rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-wrapper {
        padding: 1rem 0;
    }
    
    .form-section {
        padding: 1.5rem;
    }
    
    .page-header {
        padding: 1rem 1.5rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}
</style>

<?php
// BLOK PHP YANG SEBELUMNYA ADA DI SINI (BARIS 136) SUDAH DIHAPUS 
// KARENA SUDAH DIPINDAH KE ATAS
?>

<div class="page-wrapper">
    <div class="container-fluid px-4">
        <div class="page-header">
            <h2><i class="bi bi-box-seam me-2" style="color: #17c1e8;"></i>Manajemen Gudang</h2>
            <a href="index.php" class="btn btn-back-clean">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-plus-circle me-2" style="color: #17c1e8;"></i>Tambah Gudang Baru
            </div>

            <?php if ($error != ""): ?>
                <div class="alert alert-clean alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span><?= $error ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group-spacing">
                            <label for="kodegudang" class="form-label-clean">
                                Kode Gudang <span class="required-mark">*</span>
                            </label>
                            <div class="input-group-clean">
                                <input type="text" 
                                       name="kodegudang" 
                                       id="kodegudang" 
                                       class="form-control form-control-clean" 
                                       value="<?= isset($_POST['kodegudang']) ? htmlspecialchars($_POST['kodegudang']) : '' ?>" 
                                       maxlength="20" 
                                       required 
                                       placeholder="Maksimal 20 karakter">
                            </div>
                            <small class="form-text-clean">
                                <i class="bi bi-info-circle me-1"></i>Maksimal 20 karakter, harus unik
                            </small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-spacing">
                            <label for="namagudang" class="form-label-clean">
                                Nama Gudang <span class="required-mark">*</span>
                            </label>
                            <div class="input-group-clean">
                                <input type="text" 
                                       name="namagudang" 
                                       id="namagudang" 
                                       class="form-control form-control-clean" 
                                       value="<?= isset($_POST['namagudang']) ? htmlspecialchars($_POST['namagudang']) : '' ?>" 
                                       maxlength="100" 
                                       required 
                                       placeholder="Maksimal 100 karakter">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group-spacing">
                            <label for="kontak" class="form-label-clean">
                                Kontak
                            </label>
                            <div class="input-group-clean">
                                <input type="text" 
                                       name="kontak" 
                                       id="kontak" 
                                       class="form-control form-control-clean" 
                                       value="<?= isset($_POST['kontak']) ? htmlspecialchars($_POST['kontak']) : '' ?>" 
                                       maxlength="50" 
                                       placeholder="Contoh: 0812xxxxxx (Maks. 50 karakter)">
                            </div>
                            <small class="form-text-clean">
                                <i class="bi bi-telephone me-1"></i>Nama atau Nomor Telepon yang dapat dihubungi.
                            </small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-spacing">
                            <label for="kapasitas" class="form-label-clean">
                                Kapasitas (Kg/Pcs)
                            </label>
                            <div class="input-group-clean">
                                <input type="number" 
                                       name="kapasitas" 
                                       id="kapasitas" 
                                       class="form-control form-control-clean" 
                                       value="<?= isset($_POST['kapasitas']) ? htmlspecialchars($_POST['kapasitas']) : '0' ?>" 
                                       min="0"
                                       required 
                                       placeholder="Contoh: 1000">
                            </div>
                            <small class="form-text-clean">
                                <i class="bi bi-rulers me-1"></i>Total kapasitas penyimpanan gudang (diisi angka).
                            </small>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group-spacing">
                            <label for="alamat" class="form-label-clean">
                                Alamat
                            </label>
                            <div class="input-group-clean">
                                <textarea name="alamat" 
                                          id="alamat" 
                                          class="form-control form-control-clean" 
                                          rows="4" 
                                          maxlength="200"
                                          placeholder="Alamat lengkap gudang (Maks. 200 karakter)"><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : '' ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="index.php" class="btn btn-light px-4">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </a>
                            <button type="submit" name="simpan" class="btn btn-submit-clean">
                                <i class="bi bi-check-circle-fill"></i> Simpan Gudang
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include "../template/footer.php";
?>