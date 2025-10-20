<?php
// /gudang/tambah.php
$page_title = "Tambah Gudang";
include "../template/header.php"; // Menggunakan ../
?>
<?php
$error = "";
if (isset($_POST['simpan'])) {
    $kodegudang = trim($_POST['kodegudang']);
    $namagudang = trim($_POST['namagudang']);
    $golongan   = trim($_POST['golongan']);
    $keterangan = trim($_POST['keterangan']);

    // Validasi
    if (empty($kodegudang) || empty($namagudang)) {
        $error = "Kode Gudang dan Nama Gudang wajib diisi!";
    } elseif (strlen($kodegudang) > 10) {
        $error = "Kode Gudang terlalu panjang! Maksimal 10 karakter.";
    }

    // Cek kode unik
    if ($error == "") {
        $cekKode = mysqli_query($koneksi, "SELECT kodegudang FROM gudang WHERE kodegudang='$kodegudang'");
        if (mysqli_num_rows($cekKode) > 0) {
            $error = "Kode Gudang sudah ada, gunakan kode lain!";
        }
    }

    // Kalau tidak ada error → simpan
    if ($error == "") {
        mysqli_query($koneksi, "INSERT INTO gudang (kodegudang, namagudang, golongan, keterangan)
                                VALUES ('$kodegudang', '$namagudang', '$golongan', '$keterangan')");
        
        $_SESSION['msg'] = 'success';
        header("Location: index.php");
        exit;
    }
}
?>

<div class="container-fluid p-0">
    <a href="index.php" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Gudang
    </a>

    <div class="card shadow-sm border-0">
        <div class="card-header" style="background-color: #ffffff !important;">
            <h2 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-success"></i>Tambah Gudang Baru</h2>
        </div>
        <div class="card-body p-4">
            <?php if ($error != ""): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="kodegudang" class="form-label fw-bold">Kode Gudang <span class="text-danger">*</span></label>
                        <input type="text" name="kodegudang" id="kodegudang" class="form-control" 
                               value="<?= isset($_POST['kodegudang']) ? htmlspecialchars($_POST['kodegudang']) : '' ?>" 
                               maxlength="10" required>
                        <div class="form-text">Contoh: G-001, UTAMA, DLL (Maks 10 karakter)</div>
                    </div>
                    <div class="col-md-6">
                        <label for="namagudang" class="form-label fw-bold">Nama Gudang <span class="text-danger">*</span></label>
                        <input type="text" name="namagudang" id="namagudang" class="form-control" 
                               value="<?= isset($_POST['namagudang']) ? htmlspecialchars($_POST['namagudang']) : '' ?>" 
                               maxlength="100" required>
                    </div>
                    <div class="col-md-12">
                        <label for="golongan" class="form-label fw-bold">Golongan / Kategori</label>
                        <input type="text" name="golongan" id="golongan" class="form-control" 
                               value="<?= isset($_POST['golongan']) ? htmlspecialchars($_POST['golongan']) : '' ?>" 
                               maxlength="50">
                        <div class="form-text">Contoh: Bahan Baku, Produk Jadi, Alat</div>
                    </div>
                    <div class="col-md-12">
                        <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3"><?= isset($_POST['keterangan']) ? htmlspecialchars($_POST['keterangan']) : '' ?></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" name="simpan" class="btn btn-success btn-lg">
                            <i class="bi bi-save-fill me-1"></i> Simpan Gudang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include "../template/footer.php"; // Menggunakan ../
?>