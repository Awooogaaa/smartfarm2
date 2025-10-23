<?php
// /gudang/edit.php

// === START LOGIKA PEMROSESAN DATA (HARUS DI ATAS) ===

// Pastikan session dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "../koneksi.php";

$error = "";

// 1. Ambil KODE dari URL
if (!isset($_GET['kode'])) {
    header("Location: index.php");
    exit;
}
$kode_gudang_url = mysqli_real_escape_string($koneksi, $_GET['kode']); // Kode lama (Original PK)

// 2. Update logic (Harus diletakkan sebelum fetch data jika post gagal)
if (isset($_POST['update'])) {
    // Kode gudang tidak bisa diubah (Primary Key), jadi kita ambil dari data yang ada
    $kodegudang = $kode_gudang_url; // Tetap menggunakan kode dari GET
    
    $namagudang = trim($_POST['namagudang']);
    $kontak 	= trim($_POST['kontak']);
    $alamat 	= trim($_POST['alamat']);
    $kapasitas 	= (double)$_POST['kapasitas'];
    
    // Validasi
    if (empty($namagudang)) {
        $error = "Nama Gudang wajib diisi!";
    } elseif (strlen($namagudang) > 100) { 
        $error = "Nama Gudang terlalu panjang! Maksimal 100 karakter.";
    } elseif (strlen($kontak) > 50) { 
        $error = "Kontak terlalu panjang! Maksimal 50 karakter.";
    } elseif (strlen($alamat) > 200) { 
        $error = "Alamat terlalu panjang! Maksimal 200 karakter.";
    }

    if ($error == "") {
        $namagudang_sql = mysqli_real_escape_string($koneksi, $namagudang);
        $kontak_sql = mysqli_real_escape_string($koneksi, $kontak);
        $alamat_sql = mysqli_real_escape_string($koneksi, $alamat);

        // Query UPDATE tanpa mengubah kodegudang
        $query = "UPDATE gudang SET 
                    namagudang='$namagudang_sql', 
                    kontak='$kontak_sql', 
                    alamat='$alamat_sql',
                    kapasitas=$kapasitas 
                  WHERE kodegudang='$kodegudang'";
        mysqli_query($koneksi, $query);

        $_SESSION['msg'] = 'updated';
        
        // REDIRECT BERHASIL (di atas output HTML)
        header("Location: index.php"); 
        exit;
    }
}

// 3. Delete logic (Juga harus di atas output HTML)
if (isset($_POST['delete'])) {
    // Cek dulu apakah gudang dipakai oleh produk
    $cekProduk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk WHERE kodegudang='$kode_gudang_url'");
    $totalProduk = mysqli_fetch_assoc($cekProduk)['total'];

    if ($totalProduk > 0) {
        $error = "Tidak bisa menghapus gudang! Masih ada $totalProduk produk yang terdaftar di gudang ini.";
    } else {
        mysqli_query($koneksi, "DELETE FROM gudang WHERE kodegudang='$kode_gudang_url'");
        $_SESSION['msg'] = 'deleted';
        // REDIRECT BERHASIL (di atas output HTML)
        header("Location: index.php");
        exit;
    }
}

// 4. Ambil data gudang yang akan diedit (setelah update/delete, sebelum HTML)
$result = mysqli_query($koneksi, "SELECT * FROM gudang WHERE kodegudang='$kode_gudang_url'");
$data = mysqli_fetch_assoc($result);
if (!$data) {
    header("Location: index.php");
    exit;
}

// Jika ada error dari POST, data di-override agar input user tidak hilang
if (isset($_POST['update']) && $error != "") {
    $data['namagudang'] = htmlspecialchars($_POST['namagudang']);
    $data['kontak'] = htmlspecialchars($_POST['kontak']);
    $data['alamat'] = htmlspecialchars($_POST['alamat']);
    $data['kapasitas'] = htmlspecialchars($_POST['kapasitas']);
}

// === END LOGIKA PEMROSESAN DATA ===

// === START OUTPUT HTML ===
$page_title = "Edit Gudang";
include "../template/header.php"; // Output HTML dimulai di sini (aman)
?>

<style>
/* Modern Clean Blue Theme - Edit Page */
.page-wrapper {
    background: #f5f7fa;
    min-height: 100vh;
    padding: 2rem 0;
}

/* Page Header */
.page-header {
    background: white;
    padding: 1.5rem 2rem;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
}

.page-subtitle {
    color: #718096;
    font-size: 0.9rem;
    margin: 0.5rem 0 0 0;
}

/* Back Button */
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
    text-decoration: none;
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

.form-control-clean:disabled {
    background: #f8fafc;
    color: #a0aec0;
    border-color: #e2e8f0;
    cursor: not-allowed;
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

/* Action Buttons */
.btn-update {
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

.btn-update:hover {
    background: #0ea5c9;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(23, 193, 232, 0.3);
    color: white;
}

.btn-delete {
    background: #ef4444;
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

.btn-delete:hover {
    background: #dc2626;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    color: white;
}

.btn-cancel {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.875rem 2rem;
    font-weight: 500;
    color: #4a5568;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-cancel:hover {
    background: #f8fafc;
    border-color: #cbd5e0;
    color: #2d3748;
}

/* Form Group Spacing */
.form-group-spacing {
    margin-bottom: 1.5rem;
}

/* Locked Field Indicator */
.locked-field {
    position: relative;
}

.locked-field::after {
    content: '\F4C0';
    font-family: 'bootstrap-icons';
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0; /* Ubah warna agar terlihat seperti terkunci */
    pointer-events: none;
}

/* Modal Styles */
.modal-modern .modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-modern .modal-header {
    background: #fef2f2;
    border-radius: 12px 12px 0 0;
    border-bottom: 1px solid #fee2e2;
    padding: 1.5rem;
}

.modal-modern .modal-title {
    font-weight: 700;
    color: #991b1b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-modern .modal-body {
    padding: 1.5rem;
}

.modal-modern .modal-footer {
    border-top: 1px solid #f0f4f8;
    padding: 1.25rem 1.5rem;
    background: #fafbfc;
    border-radius: 0 0 12px 12px;
}

/* Warning Box */
.warning-box {
    background: #fef2f2;
    border-left: 4px solid #ef4444;
    padding: 1rem;
    border-radius: 8px;
    margin: 1rem 0;
}

.warning-box-title {
    color: #991b1b;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.info-box {
    background: #fffbeb;
    border-left: 4px solid #f59e0b;
    padding: 0.875rem;
    border-radius: 8px;
    margin-top: 1rem;
}

.info-box small {
    color: #92400e;
}

/* Badge in Modal */
.badge-code-modal {
    background: #dc2626;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.85rem;
    margin-left: 0.5rem;
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
        padding: 1.25rem 1.5rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .form-section-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .form-section-actions .btn-delete,
    .form-section-actions .btn-group {
        width: 100%;
    }
    
    .form-section-actions .btn-group {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .btn-cancel,
    .btn-update {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="page-wrapper">
    <div class="container-fluid px-4">
        <div class="page-header">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-pencil-square" style="color: #17c1e8;"></i>
                    Edit Gudang
                </h1>
                <p class="page-subtitle">Perbarui informasi gudang <strong><?= htmlspecialchars($data['namagudang']) ?></strong></p>
            </div>
            <a href="index.php" class="btn-back-clean">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="form-section">
            <?php if ($error != ""): ?>
                <div class="alert alert-clean alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span><strong>Error!</strong> <?= $error ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group-spacing">
                            <label for="kodegudang" class="form-label-clean">
                                Kode Gudang
                            </label>
                            <div class="locked-field">
                                <input type="text" 
                                       name="kodegudang" 
                                       id="kodegudang" 
                                       class="form-control form-control-clean" 
                                       value="<?= htmlspecialchars($data['kodegudang']) ?>" 
                                       readonly 
                                       disabled>
                            </div>
                            <small class="form-text-clean">
                                <i class="bi bi-lock-fill me-1"></i>Kode tidak dapat diubah (Primary Key)
                            </small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-spacing">
                            <label for="namagudang" class="form-label-clean">
                                Nama Gudang <span class="required-mark">*</span>
                            </label>
                            <input type="text" 
                                   name="namagudang" 
                                   id="namagudang" 
                                   class="form-control form-control-clean" 
                                   value="<?= htmlspecialchars($data['namagudang']) ?>" 
                                   maxlength="100" 
                                   required 
                                   placeholder="Masukkan nama gudang">
                            <small class="form-text-clean">
                                <i class="bi bi-info-circle me-1"></i>Maksimal 100 karakter
                            </small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-spacing">
                            <label for="kontak" class="form-label-clean">
                                Kontak
                            </label>
                            <input type="text" 
                                   name="kontak" 
                                   id="kontak" 
                                   class="form-control form-control-clean" 
                                   value="<?= htmlspecialchars($data['kontak']) ?>" 
                                   maxlength="50" 
                                   placeholder="Nomor telepon/kontak gudang">
                            <small class="form-text-clean">
                                <i class="bi bi-telephone me-1"></i>Nomor kontak gudang (Maks. 50 karakter).
                            </small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group-spacing">
                            <label for="kapasitas" class="form-label-clean">
                                Kapasitas (Kg/Pcs)
                            </label>
                            <input type="number" 
                                   name="kapasitas" 
                                   id="kapasitas" 
                                   class="form-control form-control-clean" 
                                   value="<?= $data['kapasitas'] ?>" 
                                   min="0"
                                   required 
                                   placeholder="Total kapasitas gudang">
                            <small class="form-text-clean">
                                <i class="bi bi-rulers me-1"></i>Total kapasitas penyimpanan gudang.
                            </small>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group-spacing">
                            <label for="alamat" class="form-label-clean">
                                Alamat
                            </label>
                            <textarea name="alamat" 
                                      id="alamat" 
                                      class="form-control form-control-clean" 
                                      rows="4" 
                                      maxlength="200"
                                      placeholder="Alamat lengkap gudang (Maks. 200 karakter)"><?= htmlspecialchars($data['alamat']) ?></textarea>
                            <small class="form-text-clean">
                                <i class="bi bi-geo-alt me-1"></i>Alamat lengkap gudang.
                            </small>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top form-section-actions" style="border-color: #f0f4f8 !important;">
                            <button type="button" 
                                    class="btn-delete" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal">
                                <i class="bi bi-trash-fill"></i> Hapus Gudang
                            </button>
                            <div class="d-flex gap-2 btn-group">
                                <a href="index.php" class="btn-cancel">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <button type="submit" name="update" class="btn-update">
                                    <i class="bi bi-check-circle-fill"></i> Update Gudang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-modern" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="color: #2d3748; font-size: 1rem; margin-bottom: 1rem;">
                    Apakah Anda yakin ingin menghapus gudang ini?
                </p>
                
                <div class="warning-box">
                    <div class="warning-box-title">Gudang yang akan dihapus:</div>
                    <div style="color: #991b1b;">
                        <?= htmlspecialchars($data['namagudang']) ?>
                        <span class="badge-code-modal"><?= htmlspecialchars($data['kodegudang']) ?></span>
                    </div>
                </div>

                <div class="info-box">
                    <small>
                        <i class="bi bi-info-circle-fill me-1"></i>
                        <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <form action="" method="POST" class="d-flex gap-2 w-100">
                    <button type="button" class="btn-cancel flex-fill" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                    <button type="submit" name="delete" class="btn-delete flex-fill">
                        <i class="bi bi-trash-fill"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include "../template/footer.php";
?>