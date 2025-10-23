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
if (isset($_POST['simpan'])) {
    $kode   = trim($_POST['kode']);
    $nama   = trim($_POST['nama']);
    $satuan = trim($_POST['satuan']);
    $harga  = trim($_POST['harga']);
    // TAMBAHAN 2: Mengambil kodegudang dari form
    $kodegudang = trim($_POST['kodegudang']);
    if (empty($kodegudang)) {
        $kodegudang = NULL; // Set jadi NULL jika tidak dipilih
    }

    // --- VALIDASI BARU: Cek panjang Kode dan Nama ---
    if (strlen($kode) > 20) {
        $error = "Kode produk terlalu panjang! Maksimal 20 karakter.";
    } elseif (strlen($nama) > 100) {
        $error = "Nama produk terlalu panjang! Maksimal 100 karakter.";
    }
    // --- AKHIR VALIDASI BARU ---

    // Validasi harga
    if ($error == "" && $harga < 1) {
        $error = "Harga tidak boleh kurang dari 1!";
    } elseif ($error == "" && strlen($harga) > 10) {
        $error = "Harga terlalu besar!";
    }

    // Cek kode unik
    if ($error == "") {
        // PERBAIKAN UTAMA: Mengganti 'id' dan 'kode' dengan 'kodeproduk'
        $cekKode = mysqli_query($koneksi, "SELECT kodeproduk FROM produk WHERE kodeproduk='$kode'");
        if (mysqli_num_rows($cekKode) > 0) {
            $error = "Kode produk sudah ada, gunakan kode lain!";
        }
    }

    // Validasi gambar
    $gambar = $_FILES['gambar']['name'];
    if ($error == "" && $gambar != "") {
        $tmp    = $_FILES['gambar']['tmp_name'];
        $ukuran = $_FILES['gambar']['size'];
        $ext    = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            $error = "Hanya file gambar (JPG, JPEG, PNG) yang diperbolehkan!";
        } elseif ($ukuran > 2 * 1024 * 1024) { // 2MB
            $error = "Ukuran gambar maksimal 2MB!";
        }
    }

    // Kalau tidak ada error → simpan
    if ($error == "") {
        if ($gambar != "") {
            // Membuat nama file unik untuk menghindari duplikasi
            $gambar_baru = uniqid() . '-' . $gambar;
            // PERBAIKAN 2: Path untuk upload file (harus naik satu level)
            move_uploaded_file($tmp, "../uploads/" . $gambar_baru);
        } else {
            $gambar_baru = ""; // Jika tidak ada gambar
        }
        
        // TAMBAHAN 3: Menyiapkan kodegudang untuk query SQL
        if ($kodegudang === NULL) {
            $kodegudang_sql = "NULL";
        } else {
            // Keamanan dasar untuk string
            $kodegudang_sql = "'" . mysqli_real_escape_string($koneksi, $kodegudang) . "'";
        }

        // PERBAIKAN 3: Query INSERT menggunakan kolom kodeproduk
        mysqli_query($koneksi, "INSERT INTO produk (kodeproduk, nama, satuan, harga, gambar, kodegudang)
                                VALUES ('$kode', '$nama', '$satuan', '$harga', '$gambar_baru', $kodegudang_sql)");
        
        // --- UBAH INI ---
        $_SESSION['msg'] = 'success'; // Simpan pesan di session
        header("Location: index.php"); // Redirect tanpa parameter (sudah benar)
        // --- AKHIR PERUBAHAN ---
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link href="../modul/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* Hide all scrollbars */
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

        /* Main Card */
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

        /* Form Styling */
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

        /* Button Styling */
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

        .btn-secondary {
            background-color: #677175ff;
            border-color: #90a4ae;
        }

        .btn-secondary:hover {
            background-color: #426e85ff;
            border-color: #78909c;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Upload Area */
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

        /* Alert */
        .alert {
            border-radius: 8px;
            border: none;
        }

        .icon {
            margin-right: 6px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .main-card {
                margin-left: 0;
                margin-right: 0;
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
                                <i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Produk Baru
                            </h4>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
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
                            <input type="text"
                                name="kode"
                                class="form-control"
                                placeholder="Maksimal 20 karakter"
                                value="<?= isset($_POST['kode']) ? htmlspecialchars($_POST['kode']) : '' ?>"
                                required>
                            <div class="form-text">Kode harus unik untuk setiap produk</div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-tag icon"></i>Nama Produk
                            </label>
                            <input type="text"
                                name="nama"
                                class="form-control"
                                placeholder="Maksimal 100 karakter"
                                value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>"
                                required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-rulers icon"></i>Satuan
                            </label>
                            <select name="satuan" class="form-select" required>
                                <option value="">-- Pilih Satuan --</option>
                                <option value="pcs" <?= (isset($_POST['satuan']) && $_POST['satuan'] == "pcs") ? "selected" : "" ?>>Pcs</option>
                                <option value="kg" <?= (isset($_POST['satuan']) && $_POST['satuan'] == "kg") ? "selected" : "" ?>>Kilogram (Kg)</option>
                                <option value="liter" <?= (isset($_POST['satuan']) && $_POST['satuan'] == "liter") ? "selected" : "" ?>>Liter</option>
                                <option value="box" <?= (isset($_POST['satuan']) && $_POST['satuan'] == "box") ? "selected" : "" ?>>Box</option>
                            </select>
                            <div class="form-text">Pilih satuan produk</div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-currency-dollar icon"></i>Harga
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number"
                                    name="harga"
                                    class="form-control"
                                    min="1"
                                    placeholder="0"
                                    value="<?= isset($_POST['harga']) ? $_POST['harga'] : '' ?>"
                                    required>
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
                                        <?= (isset($_POST['kodegudang']) && $_POST['kodegudang'] == $gudang['kodegudang']) ? "selected" : "" ?>>
                                        <?= htmlspecialchars($gudang['namagudang']) ?> (<?= htmlspecialchars($gudang['kodegudang']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Pilih gudang tempat produk disimpan</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-cloud-upload icon"></i>Gambar Produk (Opsional)
                        </label>
                        <div class="upload-area" onclick="$('#gambar').click()">
                            <i class="bi bi-cloud-upload" style="font-size: 2.5rem; color: #2196f3;"></i>
                            <p class="mt-2 mb-1">Klik untuk memilih gambar produk</p>
                            <small class="text-muted">JPG, JPEG, PNG • Maksimal 2MB</small>
                        </div>
                        <input type="file"
                            name="gambar"
                            id="gambar"
                            class="d-none"
                            accept="image/*">

                        <div id="previewContainer" class="preview-container mt-3" style="display:none;">
                            <div class="text-center">
                                <p class="text-success small mb-2">
                                    <i class="bi bi-check-circle icon"></i>Preview gambar yang akan diupload:
                                </p>
                                <img id="preview"
                                    class="img-preview"
                                    width="200"
                                    height="200"
                                    style="object-fit: cover;">
                                <div class="mt-2">
                                    <button type="button"
                                        id="removePreviewBtn"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x-circle"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="simpan" class="btn btn-success me-2">
                            <i class="bi bi-check-lg icon"></i>Simpan Produk
                        </button>
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
                <div class="modal-body" id="errorModalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../modul/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../modul/js/jquery.min.js"></script>
    <script src="../modul/js/tambah.js"></script>
</body>

</html>