<?php 
session_start();
// PERBAIKAN 1: Path ke koneksi.php (harus naik satu level)
include "../koneksi.php"; 
?>
<?php
// TAMBAHAN 1: Mengambil data gudang untuk dropdown
$gudang_result = mysqli_query($koneksi, "SELECT kodegudang, namagudang FROM gudang ORDER BY namagudang ASC");
$gudang_options = mysqli_fetch_all($gudang_result, MYSQLI_ASSOC);

$error = "";

// Ambil KODE dari URL (Menggunakan 'kode' yang merujuk ke kodeproduk)
if (!isset($_GET['kode'])) {
    header("Location: index.php"); // Path sudah benar
    exit;
}
// PERBAIKAN: Menggunakan $kode_produk sebagai identifier
$kode_produk = mysqli_real_escape_string($koneksi, $_GET['kode']);


// **[START PERBAIKAN LOGIKA DELETE DI ATAS]**

// 1. Muat Error dari Session (Jika ada error dari delete sebelumnya)
if (isset($_SESSION['delete_error'])) {
    $error = $_SESSION['delete_error'];
    unset($_SESSION['delete_error']);
}

// 2. Hapus produk (Dipicu oleh link GET: edit.php?delete=KODE)
if (isset($_GET['delete'])) {
    $delete_kode = mysqli_real_escape_string($koneksi, $_GET['delete']);
    
    // Cek Keterhubungan ke Pengiriman
    $cekKirim = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM detailkirim WHERE kodeproduk='$delete_kode'");
    $totalKirim = mysqli_fetch_assoc($cekKirim)['total'];

    if ($totalKirim > 0) {
        // GAGAL: Set pesan error dan kembalikan ke halaman edit produk ini
        $_SESSION['delete_error'] = "Gagal menghapus produk! Produk ini sudah digunakan dalam **{$totalKirim}** transaksi pengiriman dan tidak dapat dihapus.";
        header("Location: edit.php?kode=" . urlencode($delete_kode)); 
        exit;
    }
    
    // SUKSES: Lanjutkan proses penghapusan
    // Ambil info gambar dari kode produk yang akan dihapus
    $result_to_delete = mysqli_query($koneksi, "SELECT gambar FROM produk WHERE kodeproduk='$delete_kode'");
    $row_to_delete = mysqli_fetch_assoc($result_to_delete);
    
    // Hapus file gambar
    if ($row_to_delete && !empty($row_to_delete['gambar']) && file_exists("../uploads/" . $row_to_delete['gambar'])) {
        unlink("../uploads/" . $row_to_delete['gambar']);
    }
    
    // Hapus dari database
    mysqli_query($koneksi, "DELETE FROM produk WHERE kodeproduk='$delete_kode'");

    $_SESSION['msg'] = 'deleted';
    header("Location: index.php"); 
    exit;
}
// **[END PERBAIKAN LOGIKA DELETE DI ATAS]**


// Ambil data produk yang akan diedit
// PERBAIKAN: Menggunakan kodeproduk di klausa WHERE
$result = mysqli_query($koneksi, "SELECT * FROM produk WHERE kodeproduk='$kode_produk'");
$data = mysqli_fetch_assoc($result);
if (!$data) {
    header("Location: index.php");
    exit;
}

// Update produk
if (isset($_POST['update'])) {
    // Kode produk baru yang di-input user. Ini bisa berbeda dari $kode_produk lama.
    $kode_baru = trim($_POST['kode']); 
    $nama   = trim($_POST['nama']);
    $satuan = trim($_POST['satuan']);
    $harga  = trim($_POST['harga']);
    
    // TAMBAHAN 2: Mengambil kodegudang dari form
    $kodegudang = trim($_POST['kodegudang']);
    if (empty($kodegudang)) {
        $kodegudang = NULL; // Set jadi NULL jika tidak dipilih
    }

    // Validasi panjang Kode dan Nama
    if (strlen($kode_baru) > 20) {
        $error = "Kode produk terlalu panjang! Maksimal 20 karakter.";
    } elseif (strlen($nama) > 100) {
        $error = "Nama produk terlalu panjang! Maksimal 100 karakter.";
    }

    // Validasi harga
    if ($error == "" && $harga < 1) {
        $error = "Harga tidak boleh kurang dari 1!";
    } elseif ($error == "" && strlen($harga) > 10) {
        $error = "Harga terlalu besar!";
    }

    // Cek kode unik (jika kode diubah)
    if ($error == "") {
        // PERBAIKAN: Cek duplikasi menggunakan kodeproduk dan mengabaikan kodeproduk yang sedang diedit ($kode_produk lama)
        $cekKode = mysqli_query($koneksi, "SELECT kodeproduk FROM produk WHERE kodeproduk='$kode_baru' AND kodeproduk!='$kode_produk'");
        if (mysqli_num_rows($cekKode) > 0) {
            $error = "Kode produk sudah digunakan oleh produk lain!";
        }
    }
    
    // Validasi gambar baru (jika ada)
    $gambar_baru_upload = '';
    if ($error == "" && !empty($_FILES['gambar']['name'])) {
        $tmp    = $_FILES['gambar']['tmp_name'];
        $ukuran = $_FILES['gambar']['size'];
        $ext    = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            $error = "Hanya file gambar (JPG, JPEG, PNG) yang diperbolehkan!";
        } elseif ($ukuran > 2 * 1024 * 1024) {
            $error = "Ukuran gambar maksimal 2MB!";
        } else {
            $gambar_baru_upload = uniqid() . '-' . $_FILES['gambar']['name'];
        }
    }


    // Jika validasi lolos, proses update
    if ($error == "") {
        $gambar_query_part = "";

        // Prioritas 1: Cek apakah gambar ditandai untuk dihapus
        if (isset($_POST['hapus_gambar']) && $_POST['hapus_gambar'] == '1') {
             // Path untuk unlink file diubah
            if (!empty($data['gambar']) && file_exists("../uploads/" . $data['gambar'])) {
                unlink("../uploads/" . $data['gambar']);
            }
            $gambar_query_part = ", gambar=''";
        }
        // Prioritas 2: Jika ada gambar baru yang di-upload
        elseif (!empty($gambar_baru_upload)) {
            // Hapus gambar lama jika ada
            if (!empty($data['gambar']) && file_exists("../uploads/" . $data['gambar'])) {
                unlink("../uploads/" . $data['gambar']);
            }
            // Upload gambar baru
            move_uploaded_file($tmp, "../uploads/" . $gambar_baru_upload);
            $gambar_query_part = ", gambar='" . mysqli_real_escape_string($koneksi, $gambar_baru_upload) . "'";
        }
        
        // TAMBAHAN 3: Menyiapkan kodegudang untuk query
        if ($kodegudang === NULL) {
            $kodegudang_sql = "NULL";
        } else {
            // Keamanan dasar untuk string
            $kodegudang_sql = "'" . mysqli_real_escape_string($koneksi, $kodegudang) . "'";
        }

        // PERBAIKAN 6: Query UPDATE. Jika kode produk berubah, kita update juga kolom kodeproduk.
        $query = "UPDATE produk SET 
                    kodeproduk='" . mysqli_real_escape_string($koneksi, $kode_baru) . "', 
                    nama='" . mysqli_real_escape_string($koneksi, $nama) . "', 
                    satuan='" . mysqli_real_escape_string($koneksi, $satuan) . "', 
                    harga='" . mysqli_real_escape_string($koneksi, $harga) . "',
                    kodegudang=$kodegudang_sql
                    $gambar_query_part 
                  WHERE kodeproduk='$kode_produk'"; // WHERE tetap menggunakan kode lama
        mysqli_query($koneksi, $query);

        $_SESSION['msg'] = 'updated';
        
        // Redirect ke index (atau ke halaman edit dengan kode baru jika kode berubah)
        header("Location: index.php"); 
        exit;
    }

    // Jika ada error, data yang diinput tetap ditampilkan di form
    $data['kodeproduk'] = htmlspecialchars($_POST['kode']); // Menggunakan kode baru yang diinput
    $data['nama'] = htmlspecialchars($_POST['nama']);
    $data['satuan'] = htmlspecialchars($_POST['satuan']);
    $data['harga'] = htmlspecialchars($_POST['harga']);
    // TAMBAHAN 4: Simpan kodegudang jika ada error
    $data['kodegudang'] = isset($_POST['kodegudang']) ? htmlspecialchars($_POST['kodegudang']) : $data['kodegudang'];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link href="../modul/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* CSS Dihilangkan untuk keringkasan */
        .alert-danger-custom {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: .25rem;
        }
        /* ... (sisa CSS) ... */
        * {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        *::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }

        html,
        body {
            overflow-x: hidden;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }

        body {
            background-color: #ecececff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* [START] CSS BARU UNTUK FITUR HAPUS GAMBAR INTERAKTIF */
        .image-wrapper {
            position: relative;
            display: inline-block;
            border-radius: 8px;
            overflow: hidden;
        }

        .image-wrapper .img-preview {
            transition: filter 0.3s ease;
        }

        .image-wrapper.marked-for-deletion .img-preview {
            filter: blur(4px) brightness(0.7);
        }

        .delete-button {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        .delete-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(220, 53, 69, 0.6);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
            /* Penting: Overlay tidak bisa diklik */
            z-index: 5;
        }

        .image-wrapper.marked-for-deletion .delete-overlay {
            opacity: 1;
        }

        .delete-overlay i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        /* [END] CSS BARU */

        body {
            background-color: #ecececff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            background: #ffffff !important;
            border-bottom: 1px solid #e9ecef !important;
            padding: 1.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1.5px solid #e3f2fd;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #2AF598;
            box-shadow: 0 0 0 0.2rem rgba(42, 245, 152, 0.15);
        }

        .form-label {
            font-weight: 500;
            color: #1565c0;
            margin-bottom: 6px;
        }

        .input-group-text {
            background-color: #e3f2fd;
            border-color: #e3f2fd;
            color: #1565c0;
            font-weight: 500;
        }

        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-success {
            background-color: #4caf50;
            border-color: #4caf50;
        }

        .btn-success:hover {
            background-color: #388e3c;
            border-color: #388e3c;
        }

        .btn-danger {
            background-color: #f44336;
            border-color: #f44336;
        }

        .btn-danger:hover {
            background-color: #d32f2f;
            border-color: #d32f2f;
        }

        .btn-secondary {
            background-color: #677175ff;
            border-color: #90a4ae;
        }

        .btn-secondary:hover {
            background-color: #426e85ff;
            border-color: #78909c;
        }

        .upload-area {
            border: 2px dashed #2196f3;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background: #f3f9ff;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-area:hover {
            background: #e8f4fd;
            border-color: #1976d2;
        }

        .preview-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border: 1px solid #e3f2fd;
        }

        .img-preview {
            border-radius: 8px;
            border: 2px solid #e3f2fd;
            transition: all 0.3s ease;
        }

        .img-preview:hover {
            border-color: #2196f3;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .icon {
            margin-right: 6px;
        }

        .current-image-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border: 1px solid #e3f2fd;
        }

        @media (max-width: 768px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .main-card {
                margin-left: 0;
                margin-right: 0;
            }

            .action-buttons-mobile {
                display: flex;
                gap: 8px;
                width: 100%;
            }

            .action-buttons-mobile .btn {
                flex: 1;
                font-size: 0.9rem;
                padding: 10px 12px;
            }

            .btn-secondary {
                background-color: #677175ff;
                border-color: #90a4ae;
                font-size: 0.8rem;
                padding: 6px 12px;
                transform: none;
            }

            .btn-secondary:focus {
                background-color: #426e85ff;
                border-color: #78909c;
                font-size: 0.8rem;
                padding: 6px 12px;
                transform: none;
            }
        }
    </style>
</head>

<body>
    <div class="container text-center my-4">
        <h1 class="display-5 fw-bold text-primary">SmartFarm</h1>
        <p class="text-muted">Sistem Manajemen Produk Pertanian</p>
    </div>

    <div class="container px-4" style="max-width: 1300px;">
        <div class="main-card">
            <div class="card-header">
                <div class="row align-items-center gy-3">
                    <div class="col-12 col-md-8 mb-3 text-center text-md-start">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start flex-wrap gap-3">
                            <h4 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Produk
                            </h4>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a >
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <?php if ($error != ""): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle icon"></i>
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" enctype="multipart/form-data" id="productForm">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-upc-scan icon"></i>Kode Produk
                            </label>
                            <input type="text" name="kode" class="form-control" value="<?= htmlspecialchars($data['kodeproduk']) ?>" placeholder="Maksimal 20 karakter" required>
                            <div class="form-text">Kode harus unik untuk setiap produk</div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-tag icon"></i>Nama Produk
                            </label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" placeholder="Maksimal 100 karakter" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-rulers icon"></i>Satuan
                            </label>
                            <select name="satuan" class="form-select" required>
                                <option value="">-- Pilih Satuan --</option>
                                <option value="pcs" <?= ($data['satuan'] == "pcs") ? "selected" : "" ?>>Pcs</option>
                                <option value="kg" <?= ($data['satuan'] == "kg") ? "selected" : "" ?>>Kilogram (Kg)</option>
                                <option value="liter" <?= ($data['satuan'] == "liter") ? "selected" : "" ?>>Liter</option>
                                <option value="box" <?= ($data['satuan'] == "box") ? "selected" : "" ?>>Box</option>
                            </select>
                            <div class="form-text">Pilih satuan produk</div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-currency-dollar icon"></i>Harga
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga" class="form-control" value="<?= $data['harga'] ?>" min="1" placeholder="0" required>
                            </div>
                            <div class="form-text">Harga minimal Rp 1, maksimal 10 digit</div>
                        </div>
                    </div>

                    <div class="row">
                         <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-house-door icon"></i>Gudang (Opsional)
                            </label>
                            <select name="kodegudang" class="form-select">
                                <option value="">-- Pilih Gudang --</option>
                                <?php foreach ($gudang_options as $gudang): ?>
                                    <option value="<?= htmlspecialchars($gudang['kodegudang']) ?>"
                                        <?= ($data['kodegudang'] == $gudang['kodegudang']) ? "selected" : "" ?>>
                                        <?= htmlspecialchars($gudang['namagudang']) ?> (<?= htmlspecialchars($gudang['kodegudang']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Pilih gudang tempat produk disimpan</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-image icon"></i>Gambar Saat Ini
                        </label>
                        <div class="current-image-section">
                            <div class="text-center">
                                <?php if (!empty($data['gambar']) && file_exists("../uploads/" . $data['gambar'])): ?>
                                    <div class="image-wrapper" id="currentImageWrapper">
                                        <img src="../uploads/<?= htmlspecialchars($data['gambar']) ?>"
                                            class="img-preview"
                                            width="200"
                                            height="200"
                                            style="object-fit: cover;"
                                            alt="Gambar Produk">

                                        <button type="button" class="btn btn-sm btn-danger delete-button" id="deleteImageBtn" title="Hapus Gambar Ini">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                        <div class="delete-overlay" id="deleteOverlay">
                                            <i class="bi bi-x-circle"></i>
                                            <span>Gambar akan dihapus</span>

                                        </div>
                                    </div>
                                    <input type="hidden" name="hapus_gambar" id="hapusGambarInput" value="0">
                                <?php else: ?>
                                    <div class="p-4">
                                        <i class="bi bi-image" style="font-size: 3rem; color: #90a4ae;"></i>
                                        <p class="text-muted my-2">Belum ada gambar</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-cloud-upload icon"></i>Ganti atau Tambah Gambar (Opsional)
                        </label>
                        <div class="upload-area" onclick="$('#gambar').click()">
                            <i class="bi bi-cloud-upload" style="font-size: 2.5rem; color: #2196f3;"></i>
                            <p class="mt-2 mb-1">Klik untuk memilih gambar baru</p>
                            <small class="text-muted">JPG, JPEG, PNG • Maksimal 2MB</small>
                        </div>
                        <input type="file" name="gambar" id="gambar" class="d-none" accept="image/*">

                        <div id="previewContainer" class="preview-container mt-3" style="display:none;">
                            <div class="text-center">
                                <p class="text-success small mb-2">
                                    <i class="bi bi-check-circle icon"></i>Preview gambar baru yang akan diupload:
                                </p>
                                <img id="preview" class="img-preview" width="200" height="200" style="object-fit: cover;">
                                <div class="mt-2">
                                    <button type="button" id="removePreviewBtn" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x-circle"></i> Batal Pilih
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="d-none d-md-flex gap-2 justify-content-center flex-wrap">
                            <button type="submit" name="update" class="btn btn-success">
                                <i class="bi bi-check-lg icon"></i>Update Produk
                            </button>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete('<?= urlencode($data['kodeproduk']) ?>')">
                                <i class="bi bi-trash3 icon"></i>Hapus Produk
                            </button>
                        </div>

                        <div class="d-md-none action-buttons-mobile">
                            <button type="submit" name="update" class="btn btn-success">
                                <i class="bi bi-check-lg icon"></i>Update
                            </button>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete('<?= urlencode($data['kodeproduk']) ?>')">
                                <i class="bi bi-trash3 icon"></i>Hapus
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="errorModalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px; border: none;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-danger">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                    <p class="mt-3">Yakin ingin menghapus produk ini secara permanen?</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <script src="../modul/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../modul/js/jquery.min.js"></script>
    <script>
        // Memastikan fungsi confirmDelete diperbarui untuk menggunakan 'kode'
        function confirmDelete(kode) {
            var deleteUrl = "edit.php?delete=" + kode;
            $('#confirmDeleteBtn').attr('href', deleteUrl);
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        $(document).ready(function() {
            // Logika Hapus Gambar
            var isMarkedForDeletion = false;
            
            $('#deleteImageBtn').on('click', function() {
                isMarkedForDeletion = !isMarkedForDeletion;
                if (isMarkedForDeletion) {
                    $('#currentImageWrapper').addClass('marked-for-deletion');
                    $('#hapusGambarInput').val('1');
                    $(this).removeClass('btn-danger').addClass('btn-success').html('<i class="bi bi-arrow-counterclockwise"></i> Batal Hapus');
                } else {
                    $('#currentImageWrapper').removeClass('marked-for-deletion');
                    $('#hapusGambarInput').val('0');
                    $(this).removeClass('btn-success').addClass('btn-danger').html('<i class="bi bi-trash"></i>');
                }
            });

            // Logika Preview Gambar Baru
            $('#gambar').on('change', function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview').attr('src', e.target.result);
                        $('#previewContainer').show();
                        // Jika ada gambar baru, batalkan status hapus gambar lama
                        if (isMarkedForDeletion) {
                            $('#deleteImageBtn').trigger('click'); // Membatalkan status hapus
                        }
                    }
                    reader.readAsDataURL(this.files[0]);
                } else {
                    $('#previewContainer').hide();
                }
            });

            $('#removePreviewBtn').on('click', function() {
                $('#gambar').val('');
                $('#preview').attr('src', '');
                $('#previewContainer').hide();
            });
        });
    </script>
</body>

</html>