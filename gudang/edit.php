<?php
// /gudang/edit.php
$page_title = "Edit Gudang";
include "../template/header.php"; // Menggunakan ../
?>
<?php
$error = "";

// Ambil KODE dari URL
if (!isset($_GET['kode'])) {
    header("Location: index.php");
    exit;
}
$kode_gudang_url = $_GET['kode'];

// Hapus gudang
if (isset($_POST['delete'])) {
    // Cek dulu apakah gudang dipakai oleh produk
    $cekProduk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk WHERE kodegudang='$kode_gudang_url'");
    $totalProduk = mysqli_fetch_assoc($cekProduk)['total'];

    if ($totalProduk > 0) {
        $error = "Tidak bisa menghapus gudang! Masih ada $totalProduk produk yang terdaftar di gudang ini.";
    } else {
        mysqli_query($koneksi, "DELETE FROM gudang WHERE kodegudang='$kode_gudang_url'");
        $_SESSION['msg'] = 'deleted';
        header("Location: index.php");
        exit;
    }
}

// Ambil data gudang yang akan diedit
$result = mysqli_query($koneksi, "SELECT * FROM gudang WHERE kodegudang='$kode_gudang_url'");
$data = mysqli_fetch_assoc($result);
if (!$data) {
    header("Location: index.php");
    exit;
}

// Update gudang
if (isset($_POST['update'])) {
    // Kode gudang tidak bisa diubah (Primary Key), jadi kita ambil dari data yang ada
    $kodegudang = $data['kodegudang'];
    $namagudang = trim($_POST['namagudang']);
    $golongan   = trim($_POST['golongan']);
    $keterangan = trim($_POST['keterangan']);

    if (empty($namagudang)) {
        $error = "Nama Gudang wajib diisi!";
    }

    if ($error == "") {
        $query = "UPDATE gudang SET namagudang='$namagudang', golongan='$golongan', keterangan='$keterangan' 
                  WHERE kodegudang='$kodegudang'";
        mysqli_query($koneksi, $query);

        $_SESSION['msg'] = 'updated';
        header("Location: index.php");
        exit;
    }

    // Jika ada error, data yang diinput tetap ditampilkan di form
    $data['namagudang'] = htmlspecialchars($_POST['namagudang']);
    $data['golongan'] = htmlspecialchars($_POST['golongan']);
    $data['keterangan'] = htmlspecialchars($_POST['keterangan']);
}
?>

<div class="container-fluid p-0">
    <a href="index.php" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Gudang
    </a>

    <div class="card shadow-sm border-0">
        <div class="card-header" style="background-color: #ffffff !important;">
            <h2 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Gudang</h2>
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
                        <label for="kodegudang" class="form-label fw-bold">Kode Gudang</label>
                        <input type="text" name="kodegudang" id="kodegudang" class="form-control" 
                               value="<?= htmlspecialchars($data['kodegudang']) ?>" 
                               readonly disabled>
                        <div class="form-text">Kode Gudang tidak dapat diubah.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="namagudang" class="form-label fw-bold">Nama Gudang <span class="text-danger">*</span></label>
                        <input type="text" name="namagudang" id="namagudang" class="form-control" 
                               value="<?= htmlspecialchars($data['namagudang']) ?>" 
                               maxlength="100" required>
                    </div>
                    <div class="col-md-12">
                        <label for="golongan" class="form-label fw-bold">Golongan / Kategori</label>
                        <input type="text" name="golongan" id="golongan" class="form-control" 
                               value="<?= htmlspecialchars($data['golongan']) ?>" 
                               maxlength="50">
                        <div class="form-text">Contoh: Bahan Baku, Produk Jadi, Alat</div>
                    </div>
                    <div class="col-md-12">
                        <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3"><?= htmlspecialchars($data['keterangan']) ?></textarea>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash-fill me-1"></i> Hapus
                        </button>
                        <button type="submit" name="update" class="btn btn-warning btn-lg">
                            <i class="bi bi-save-fill me-1"></i> Update Gudang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill"></i> Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Yakin ingin menghapus gudang <strong><?= htmlspecialchars($data['namagudang']) ?> (<?= htmlspecialchars($data['kodegudang']) ?>)</strong>?
                <br><br>
                <small class="text-danger">Tindakan ini tidak dapat dibatalkan.</small>
            </div>
            <div class="modal-footer">
                <form action="" method="POST">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="delete" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include "../template/footer.php"; // Menggunakan ../
?>