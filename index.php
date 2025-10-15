<?php
session_start(); // <-- Pastikan ini ada di baris paling atas
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Produk</title>
  <link href="modul/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    /* [START] KODE BARU UNTUK ZOOM GAMBAR */
    .product-image.zoomable {
      cursor: pointer;
      transition: transform 0.2s ease-in-out;
    }

    .product-image.zoomable:hover {
      transform: scale(1.1);
    }

    #imageModal .modal-body {
      background-color: #f8f9fa;
    }

    /* [END] KODE BARU */

    .toast-container {
      position: fixed;
      top: 1.5rem;
      right: 1.5rem;
      z-index: 1090;
      width: 350px;
    }

    .custom-toast {
      opacity: 0;
      transform: translateY(-20px);
      transition: all 0.4s ease-in-out;
    }

    .custom-toast.show {
      opacity: 1;
      transform: translateY(0);
    }

    .custom-toast.hide-up {
      opacity: 0;
      transform: translateY(-50px);
    }

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

    .search-input {
      background: #ffffffff;
      color: #495057;
      border: 2px solid #e4e4e4ff;
      border-radius: 50px;
      padding: 10px 56px 10px 16px;
      font-size: 15px;
      height: 46px;
    }

    .search-input:focus {
      outline: none;
      border-color: #2AF598;
    }

    .btn-search {
      position: absolute;
      top: 50%;
      right: 4px;
      transform: translateY(-50%);
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, #2AF598 0%, #009EFD 100%);
      border: none;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }

    .btn-search:hover {
      transform: translateY(-50%) scale(1.05);
    }

    .btn-reset {
      background: #dc3545;
      color: #fff;
      border-radius: 25px;
      font-size: 15px;
      height: 40px;
      line-height: 40px;
      padding: 0 20px;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
    }


    .btn-reset:hover {
      background: #ff0019;
      color: #fff;
      transform: scale(1.08);
      box-shadow: 0 2px 8px rgba(255, 0, 25, 0.4);
    }

    .main-card {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      will-change: transform, opacity;
    }

    .card-header {
      background: #ffffff !important;
      border-bottom: 1px solid #e9ecef !important;
      padding: 1.5rem;
    }

    .stats-badge {
      background: #e7f3ff;
      color: #0066cc;
      font-weight: 600;
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 0.9rem;
    }

    .mobile-stats-badge {
      background: #e7f3ff;
      color: #0066cc;
      font-weight: 600;
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 0.75rem;
    }

    .btn-custom-mobile {
      font-size: 0.8rem;
      padding: 8px 12px;
      border-radius: 8px;
      background-color: #009BEB !important;
      transition: none;
    }

    .btn-custom-mobile:hover {
      background-color: #009BEB !important;
      transform: none;
    }

    .btn-custom-mobile .hover-state {
      display: none !important;
    }

    .limit-container {
      background: #ffffff;
      border-radius: 10px;
      padding: 1rem 1.5rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .limit-label {
      font-weight: 600;
      color: #495057;
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .limit-label i {
      color: #009BEB;
    }

    .limit-select {
      border: 2px solid #dee2e6;
      border-radius: 8px;
      padding: 0.5rem 2.5rem 0.5rem 1rem;
      font-weight: 500;
      color: #495057;
      background-color: #ffffff;
      transition: all 0.3s ease;
      cursor: pointer;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23009BEB' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 0.75rem center;
      background-size: 16px 12px;
    }

    .limit-select:hover {
      border-color: #009BEB;
      box-shadow: 0 2px 8px rgba(0, 155, 235, 0.15);
    }

    .limit-select:focus {
      outline: none;
      border-color: #2AF598;
      box-shadow: 0 0 0 3px rgba(42, 245, 152, 0.1);
    }

    .btn-custom .hover-state {
      background: #009EFD;
      transform: translateY(100%);
      transition: transform 0.3s ease;
    }

    .btn-custom .default-state {
      transition: transform 0.3s ease;
    }

    .btn-custom:hover .default-state {
      transform: translateY(-100%);
    }

    .btn-custom:hover .hover-state {
      transform: translateY(0%);
    }

    .btn-edit {
      background: #0dcaf0;
      color: #fff;
      border-radius: 5px;
      font-size: 15px;
      padding: 6px 12px;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
    }

    .btn-edit:hover {
      background: #0aa2c0;
      color: #fff;
      box-shadow: 0 3px 10px rgba(13, 202, 240, 0.4);
    }

    .table-clean {
      word-wrap: break-word;
    }

    .table-clean thead th {
      white-space: nowrap;
    }

    .product-image {
      width: 80px;
      height: 80px;
      border-radius: 8px;
      object-fit: cover;
    }

    .product-code {
      background: #fff3cd;
      color: #856404;
      padding: 6px 10px;
      border-radius: 6px;
      font-family: 'Courier New', monospace;
      font-size: 0.8rem;
    }

    .unit-badge {
      background: #d1ecf1;
      color: #0c5460;
      padding: 6px 10px;
      border-radius: 6px;
      font-size: 0.8rem;
    }

    .price-badge {
      background: #d4edda;
      color: #155724;
      padding: 8px 12px;
      border-radius: 6px;
      font-weight: 600;
    }

    @media (max-width: 768px) {
      .navbar {
        flex-direction: column;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        max-width: 88% !important;
      }

      .search-form {
        width: 100%;
        margin-top: 1rem;
      }

      .stats-badge {
        display: none !important;
      }

      .mobile-stats-badge {
        display: inline-block !important;
      }

      .mobile-product-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        margin-bottom: 1rem;
      }

      .mobile-title-section {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
      }

      .mobile-title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
      }

      .mobile-button-section {
        display: flex;
        justify-content: flex-end;
      }

      .table-clean th,
      .table-clean td {
        font-size: 0.9rem;
        padding: 0.85rem 0.6rem;
      }

      .product-code,
      .unit-badge,
      .price-badge {
        font-size: 0.85rem;
      }

      .product-image {
        width: 50px;
        height: 50px;
      }

      .limit-container {
        padding: 0.75rem 1rem;
      }

      .limit-label {
        font-size: 0.85rem;
      }

      .limit-select {
        padding: 0.4rem 2rem 0.4rem 0.8rem;
        font-size: 0.9rem;
      }
    }

    @media (min-width: 769px) {
      .mobile-stats-badge {
        display: none !important;
      }
    }
  </style>
</head>

<body>
  <div class="container text-center my-4">
    <h1 class="display-5 fw-bold text-primary">SmartFarm</h1>
    <p class="text-muted">Sistem Manajemen Produk Pertanian</p>
  </div>


  <?php
  $showAlert = false;
  $alertType = "";
  $alertHeading = "";
  $alertMessage = "";

  if (isset($_SESSION['msg'])) {
    $showAlert = true;
    switch ($_SESSION['msg']) {
      case 'success':
        $alertType = "success";
        $alertHeading = "Berhasil!";
        $alertMessage = "Produk baru telah berhasil ditambahkan.";
        break;
      case 'updated':
        $alertType = "info";
        $alertHeading = "Update Sukses!";
        $alertMessage = "Data produk telah berhasil diperbarui.";
        break;
      case 'deleted':
        $alertType = "danger";
        $alertHeading = "Data Dihapus!";
        $alertMessage = "Produk telah berhasil dihapus.";
        break;
    }
    unset($_SESSION['msg']);
  }

  $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $offset = ($page - 1) * $limit;

  $where = "";
  $searchTerm = "";
  if (isset($_GET['cari']) && trim($_GET['cari']) != "") {
    $searchTerm = trim($_GET['cari']);
    $cari = mysqli_real_escape_string($koneksi, $searchTerm);
    $where = "WHERE kode LIKE '%$cari%' OR nama LIKE '%$cari%' OR satuan LIKE '%$cari%'";
  }

  $countQuery = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk $where");
  $totalData = mysqli_fetch_assoc($countQuery)['total'];
  $totalPages = ceil($totalData / $limit);
  $result = mysqli_query($koneksi, "SELECT * FROM produk $where ORDER BY id DESC LIMIT $limit OFFSET $offset");
  $countAll = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk");
  $totalProduk = mysqli_fetch_assoc($countAll)['total'];
  ?>

  <?php if ($showAlert) : ?>
    <div class="toast-container">
      <div class="alert alert-<?= $alertType ?> custom-toast" role="alert">
        <h5 class="alert-heading fw-bold"><?= $alertHeading ?></h5>
        <p class="mb-0"><?= $alertMessage ?></p>
      </div>
    </div>
  <?php endif; ?>

  <div class="container px-4" style="max-width: 1300px;">
    <div class="main-card">
      <div class="card-header">
        <div class="row align-items-center gy-3 mb-4">
          <div class="col-lg-7 col-md-12">
            <a class="navbar-brand fw-bold fs-4 text-black" href="index.php">Manajemen Produk</a>
          </div>
          <div class="col-lg-5 col-md-12">
            <form method="GET" action="" class="d-flex align-items-center gap-2 search-form" role="search">
              <div class="position-relative flex-grow-1">
                <input type="text" class="form-control search-input" name="cari" placeholder="Cari produk..." value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>">
                <button type="submit" class="btn-search">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                  </svg>
                </button>
              </div>
              <a href="index.php" class="btn btn-reset d-flex align-items-center d-none d-md-inline">Reset</a>
              <a class="d-inline d-md-none"></a>
            </form>
          </div>
        </div>
        <div class="row align-items-center gy-3">
          <div class="col-md-8">
            <div class="d-flex align-items-center flex-wrap gap-3">
              <div class="mobile-product-header d-md-none w-100">
                <div class="mobile-title-section">
                    <div class="mobile-title-row">
                        <div class="w-100">
                        <h4 class="mb-2 fw-bold text-dark">
                        <i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Daftar Produk
                        </h4>
                        <span class="mobile-stats-badge">
                        <i class="bi bi-box me-1"></i><?= $totalData ?><?= $searchTerm ? " dari $totalProduk" : "" ?> Produk
              </span>
        <?php if ($searchTerm) : ?>
          <span class="badge bg-secondary ms-2"><i class="bi bi-search me-1"></i>"<?= htmlspecialchars($searchTerm) ?>"</span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

              <h4 class="mb-0 fw-bold text-dark d-none d-md-block"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Daftar Produk</h4>
              <span class="stats-badge d-none d-md-inline"><i class="bi bi-box me-1"></i>
                <?= $totalData ?><?= $searchTerm ? " dari $totalProduk" : "" ?> Produk
              </span>

              <?php if ($searchTerm) : ?>
                <span class="badge bg-secondary d-none d-md-inline"><i class="bi bi-search me-1"></i>"<?= htmlspecialchars($searchTerm) ?>"</span>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-md-4 text-md-end">
            <a href="tambah.php" class="btn btn-custom text-white position-relative overflow-hidden px-3 d-none d-md-inline-flex align-items-center gap-2" style="background-color:#009BEB; text-decoration:none;">
              <span class="default-state d-flex align-items-center gap-2">
                <span>Tambah Produk</span>
              </span>
              <span class="hover-state d-flex align-items-center gap-2 position-absolute top-0 start-0 w-100 h-100 justify-content-center">
                <span class="text-warning">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                  </svg>
                </span>
              </span>
            </a>

            <div class="d-md-none d-flex justify-content-end w-100">
              <a href="tambah.php" class="btn btn-custom-mobile text-white d-inline-flex align-items-center gap-1" style="text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" viewBox="0 0 16 16">
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
                <span>Tambah</span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="p-3">
          <div class="limit-container">
            <form method="GET" action="" id="limit-form" class="d-flex align-items-center justify-content-between flex-wrap gap-3">
              <div class="d-flex align-items-center gap-3">
                <label for="limit" class="limit-label mb-0">
                  <i class="bi bi-list-ul"></i>
                  <span>Tampilkan:</span>
                </label>
                <select name="limit" id="limit" class="limit-select" onchange="document.getElementById('limit-form').submit()">
                  <option value="5" <?= ($limit == 5) ? 'selected' : '' ?>>5 data</option>
                  <option value="10" <?= ($limit == 10) ? 'selected' : '' ?>>10 data</option>
                  <option value="25" <?= ($limit == 25) ? 'selected' : '' ?>>25 data</option>
                  <option value="50" <?= ($limit == 50) ? 'selected' : '' ?>>50 data</option>
                </select>
              </div>
              <input type="hidden" name="cari" value="<?= htmlspecialchars($searchTerm) ?>">
            </form>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-clean align-middle">
            <thead>
              <tr>
                <th scope="col" class="text-center">Aksi</th>
                <th scope="col">Gambar</th>
                <th scope="col">Kode</th>
                <th scope="col">Nama Produk</th>
                <th scope="col">Satuan</th>
                <th scope="col">Harga</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                  echo "<tr>
                                              <td class='text-center'>
                                                  <a href='edit.php?id=" . $row['id'] . "' class='btn btn-edit btn-sm'>
                                                      Edit
                                                  </a>
                                              </td>
                                              <td>";

                  if (!empty($row['gambar']) && file_exists("uploads/" . $row['gambar'])) {
                    // --- [START] PERUBAHAN DI SINI ---
                    echo "<img src='uploads/" . htmlspecialchars($row['gambar']) . "' 
                                 class='product-image zoomable' 
                                 alt='" . htmlspecialchars($row['nama']) . "' 
                                 data-bs-toggle='modal' 
                                 data-bs-target='#imageModal'>";
                    // --- [END] PERUBAHAN ---
                  } else {
                    echo "<div class='product-image d-flex align-items-center justify-content-center bg-light text-muted' style='font-size: 0.8rem; border: 1px solid #dee2e6;'>No Img</div>";
                  }

                  echo "</td>
                                              <td><span class='product-code'>" . htmlspecialchars($row['kode']) . "</span></td>
                                              <td class='fw-bold'>" . htmlspecialchars($row['nama']) . "</td>
                                              <td><span class='unit-badge'>" . htmlspecialchars($row['satuan']) . "</span></td>
                                              <td><span class='price-badge'>Rp " . number_format($row['harga'], 0, ',', '.') . "</span></td>
                                            </tr>";
                }
              } else {
                echo "<tr><td colspan='6' class='text-center p-5'>Belum ada produk.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
          <span class="text-muted small">
            Menampilkan <?= mysqli_num_rows($result) ?> dari <?= $totalData ?> data
          </span>
          <nav>
            <ul class="pagination mb-0">
              <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&cari=<?= htmlspecialchars($searchTerm) ?>">Previous</a>
              </li>

              <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                  <a class="page-link" href="?page=<?= $i ?>&limit=<?= $limit ?>&cari=<?= htmlspecialchars($searchTerm) ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>

              <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&cari=<?= htmlspecialchars($searchTerm) ?>">Next</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Detail Gambar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <img src="" id="modalImage" class="img-fluid rounded" alt="Gambar Produk">
        </div>
      </div>
    </div>
  </div>
  <script src="modul/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="modul/js/jquery.min.js"></script>
  <script src="modul/js/index.js"></script>
</body>

</html>