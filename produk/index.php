<?php
// Lokasi file: /produk/index.php

// 1. Ganti bagian atas HTML dengan include header
$page_title = "Manajemen Produk"; 
include "../template/header.php"; // Pastikan path ini benar! (../template/header.php)
?>

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

     /* Kosongkan jika semua style sudah ada di file CSS terpisah */
     /* Atau pindahkan style spesifik dari file lama ke sini */
     body {
         /* Sesuaikan background jika berbeda dengan template */
         background-color: #ecececff; 
     }
     .main-card { /* Style dari file lama */
       background: #ffffff;
       border-radius: 12px;
       box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
       overflow: hidden;
     }
     .card-header { /* Style dari file lama */
       background: #ffffff !important;
       border-bottom: 1px solid #e9ecef !important;
       padding: 1.5rem;
     }
     .search-input { /* Style dari file lama */
       background: #ffffffff;
       color: #495057;
       border: 2px solid #e4e4e4ff;
       border-radius: 50px;
       padding: 10px 56px 10px 16px;
       font-size: 15px;
       height: 46px;
     }
     .search-input:focus { /* Style dari file lama */
       outline: none;
       border-color: #2AF598;
     }
     .btn-search { /* Style dari file lama */
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
      .btn-search:hover { /* Style dari file lama */
        transform: translateY(-50%) scale(1.05);
      }
      .btn-reset { /* Style dari file lama */
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
      .btn-reset:hover { /* Style dari file lama */
        background: #ff0019;
        color: #fff;
        transform: scale(1.08);
        box-shadow: 0 2px 8px rgba(255, 0, 25, 0.4);
      }
     .stats-badge { /* Style dari file lama */
       background: #e7f3ff;
       color: #0066cc;
       font-weight: 600;
       padding: 8px 16px;
       border-radius: 20px;
       font-size: 0.9rem;
     }
     .btn-custom { /* Style dari file lama - pastikan hover state bekerja */
          position: relative;
          overflow: hidden;
     }
     .btn-custom .hover-state {
        background: #009EFD;
        transform: translateY(100%);
        transition: transform 0.3s ease;
        position: absolute; /* Pastikan ini ada */
        top: 0; left: 0; width: 100%; height: 100%;
        display: flex; /* Untuk ikon center */
        align-items: center;
        justify-content: center;
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
     .btn-custom-mobile { /* Style dari file lama */
        font-size: 0.8rem;
        padding: 8px 12px;
        border-radius: 8px;
        background-color: #009BEB !important;
        color: white !important; /* Tambahkan warna putih */
        text-decoration: none; /* Hapus garis bawah */
     }
      .limit-container { /* Style dari file lama */
        background: #ffffff;
        border-radius: 10px;
        padding: 1rem 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      }
      .limit-label { /* Style dari file lama */
        font-weight: 600;
        color: #495057;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }
      .limit-label i { /* Style dari file lama */
        color: #009BEB;
      }
      .limit-select { /* Style dari file lama */
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
      .limit-select:hover { /* Style dari file lama */
        border-color: #009BEB;
        box-shadow: 0 2px 8px rgba(0, 155, 235, 0.15);
      }
      .limit-select:focus { /* Style dari file lama */
        outline: none;
        border-color: #2AF598;
        box-shadow: 0 0 0 3px rgba(42, 245, 152, 0.1);
      }
     .table-clean { /* Style dari file lama */
       word-wrap: break-word;
     }
     .table-clean thead th { /* Style dari file lama */
       white-space: nowrap;
       background-color: #f8f9fa; /* Tambahkan background agar lebih jelas */
       border-bottom-width: 2px;
     }
     .product-image { /* Style dari file lama */
       width: 80px;
       height: 80px;
       border-radius: 8px;
       object-fit: cover;
     }
     .product-code { /* Style dari file lama */
       background: #fff3cd;
       color: #856404;
       padding: 6px 10px;
       border-radius: 6px;
       font-family: 'Courier New', monospace;
       font-size: 0.8rem;
     }
     .unit-badge { /* Style dari file lama */
       background: #d1ecf1;
       color: #0c5460;
       padding: 6px 10px;
       border-radius: 6px;
       font-size: 0.8rem;
     }
     .price-badge { /* Style dari file lama */
       background: #d4edda;
       color: #155724;
       padding: 8px 12px;
       border-radius: 6px;
       font-weight: 600;
     }
     .gudang-badge { /* Style BARU untuk Gudang */
        background-color: #cfe2ff;
        color: #0a3675;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
     }
     .golongan-badge { /* Style BARU untuk Golongan */
        background-color: #e2e3e5;
        color: #495057;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
     }
     .btn-edit { /* Style dari file lama */
       background: #0dcaf0; /* Biru muda */
       color: #fff;
       border-radius: 5px;
       font-size: 14px; /* Sedikit lebih kecil */
       padding: 4px 8px; /* Lebih kecil */
       border: none;
       cursor: pointer;
       transition: all 0.3s ease-in-out;
     }
     .btn-edit:hover { /* Style dari file lama */
       background: #0aa2c0;
       color: #fff;
       box-shadow: 0 2px 8px rgba(13, 202, 240, 0.4);
     }
      /* Responsive Styles dari file lama (jika ada yang perlu disesuaikan) */
      @media (max-width: 768px) {
           .search-form {
             width: 100%;
             margin-top: 1rem;
           }
           .stats-badge {
             display: none !important;
           }
           .mobile-stats-badge {
              background: #e7f3ff;
              color: #0066cc;
              font-weight: 600;
              padding: 4px 8px;
              border-radius: 12px;
              font-size: 0.75rem;
              display: inline-block !important; /* Pastikan terlihat */
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
           .table-clean th,
           .table-clean td {
             font-size: 0.85rem; /* Ukuran font lebih kecil di mobile */
             padding: 0.75rem 0.5rem; /* Padding lebih kecil */
           }
           .product-code, .unit-badge, .price-badge, .gudang-badge, .golongan-badge {
             font-size: 0.75rem; /* Ukuran badge lebih kecil */
             padding: 4px 6px;
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
               display: none !important; /* Sembunyikan di desktop */
           }
       }

</style>

<?php
// Logika notifikasi toast (Sudah ada di header.php, tapi script pemicu di bawah)
$showAlert = false;
$alertType = "";
$alertHeading = "";
$alertMessage = "";

if (isset($_SESSION['msg'])) {
    $showAlert = true;
    switch ($_SESSION['msg']) {
        case 'success': $alertType = "success"; $alertHeading = "Berhasil!"; $alertMessage = "Produk baru telah berhasil ditambahkan."; break;
        case 'updated': $alertType = "info"; $alertHeading = "Update Sukses!"; $alertMessage = "Data produk telah berhasil diperbarui."; break;
        case 'deleted': $alertType = "danger"; $alertHeading = "Data Dihapus!"; $alertMessage = "Produk telah berhasil dihapus."; break;
    }
    unset($_SESSION['msg']);
}

// Logika Pagination dan Pencarian (Sama seperti sebelumnya, $koneksi didapat dari header)
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$where = "";
$searchTerm = "";
if (isset($_GET['cari']) && trim($_GET['cari']) != "") {
    $searchTerm = trim($_GET['cari']);
    $cari = mysqli_real_escape_string($koneksi, $searchTerm);
    // PERBAIKAN: Menambahkan g.golongan ke pencarian
    $where = "WHERE p.kode LIKE '%$cari%' OR p.nama LIKE '%$cari%' OR p.satuan LIKE '%$cari%' OR g.namagudang LIKE '%$cari%' OR g.golongan LIKE '%$cari%'";
}

// PERBAIKAN: JOIN dengan tabel gudang dan SELECT g.golongan
$countQuery = mysqli_query($koneksi, "SELECT COUNT(p.id) as total 
                                      FROM produk p 
                                      LEFT JOIN gudang g ON p.kodegudang = g.kodegudang 
                                      $where");
$totalData = mysqli_fetch_assoc($countQuery)['total'];
$totalPages = ($limit > 0) ? ceil($totalData / $limit) : 1; // Hindari division by zero jika limit 0
$totalPages = max(1, $totalPages); // Pastikan minimal 1 halaman

// PERBAIKAN: JOIN dengan tabel gudang dan SELECT g.golongan
$result = mysqli_query($koneksi, "SELECT p.*, g.namagudang, g.golongan 
                                  FROM produk p 
                                  LEFT JOIN gudang g ON p.kodegudang = g.kodegudang 
                                  $where 
                                  ORDER BY p.id DESC 
                                  LIMIT $limit OFFSET $offset");
                                  
$countAll = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk");
$totalProduk = mysqli_fetch_assoc($countAll)['total'];
?>

<?php if ($showAlert) : ?>
    <script>
        $(document).ready(function() {
            var toastHTML = `
            <div class="toast align-items-center text-white bg-<?php echo $alertType; ?> border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong><?php echo $alertHeading; ?></strong> <?php echo $alertMessage; ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;
            $('.toast-container').append(toastHTML);
            var newToastEl = $('.toast-container .toast').last()[0];
            var newToast = new bootstrap.Toast(newToastEl);
            newToast.show();
        });
    </script>
<?php endif; ?>

<div class="container-fluid px-0"> <div class="main-card">
      <div class="card-header">
        <div class="row align-items-center gy-3 mb-4">
          <div class="col-lg-7 col-md-12">
            <a class="navbar-brand fw-bold fs-4 text-black" href="index.php">Manajemen Produk</a> 
          </div>
          <div class="col-lg-5 col-md-12">
             <form method="GET" action="index.php" class="d-flex align-items-center gap-2 search-form" role="search">
              <div class="position-relative flex-grow-1">
                <input type="text" class="form-control search-input" name="cari" placeholder="Cari produk, gudang..." value="<?= htmlspecialchars($searchTerm) ?>">
                <button type="submit" class="btn-search">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                  </svg>
                </button>
              </div>
               <a href="index.php" class="btn btn-reset d-flex align-items-center d-none d-md-inline">Reset</a>
            </form>
          </div>
        </div>
        <div class="row align-items-center gy-3">
          <div class="col-md-8">
            <div class="d-flex align-items-center flex-wrap gap-3">
              <div class="mobile-product-header d-md-none w-100">
                <div class="mobile-title-section">
                  <div class="mobile-title-row">
                    <div>
                      <h4 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Daftar Produk
                      </h4>
                      <?php if ($searchTerm) : ?>
                        <span class="badge bg-secondary mt-1"><i class="bi bi-search me-1"></i>"<?= htmlspecialchars($searchTerm) ?>"</span>
                      <?php endif; ?>
                    </div>
                    <span class="mobile-stats-badge"> <i class="bi bi-box me-1"></i><?= $totalData ?><?= $searchTerm ? " dari $totalProduk" : "" ?> Produk
                    </span>
                  </div>
                </div>
              </div>

              <h4 class="mb-0 fw-bold text-dark d-none d-md-block"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Daftar Produk</h4>
              <span class="stats-badge d-none d-md-inline"><i class="bi bi-box me-1"></i> <?= $totalData ?><?= $searchTerm ? " dari $totalProduk" : "" ?> Produk
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
              <span class="hover-state d-flex align-items-center gap-2">
                <span class="text-warning">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                  </svg>
                </span>
                 <span>Tambah Cepat</span> </span>
            </a>

            <div class="d-md-none d-flex justify-content-end w-100">
              <a href="tambah.php" class="btn btn-custom-mobile" style="text-decoration:none;">
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
            <form method="GET" action="index.php" id="limit-form" class="d-flex align-items-center justify-content-between flex-wrap gap-3">
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
          <table class="table table-clean table-hover align-middle"> <thead>
              <tr>
                <th scope="col" class="text-center">Aksi</th>
                <th scope="col">Gambar</th>
                <th scope="col">Kode</th>
                <th scope="col">Nama Produk</th>
                <th scope="col">Gudang</th> 
                <th scope="col">Golongan</th> <th scope="col">Satuan</th>
                <th scope="col">Harga</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Periksa apakah $result valid sebelum loop
              if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                  echo "<tr>
                        <td class='text-center'>
                            <a href='edit.php?id=" . $row['id'] . "' class='btn btn-edit btn-sm' title='Edit Produk'> 
                                <i class='bi bi-pencil-square'></i>
                            </a>
                        </td>
                        <td>";

                  // PERBAIKAN Path Gambar: ../uploads/
                  $gambar_path = "../uploads/" . htmlspecialchars($row['gambar']);
                  if (!empty($row['gambar']) && file_exists($gambar_path)) {
                    echo "<img src='" . $gambar_path . "' 
                           class='product-image zoomable' 
                           alt='" . htmlspecialchars($row['nama']) . "' 
                           data-bs-toggle='modal' 
                           data-bs-target='#imageModal'
                           data-image-src='" . $gambar_path . "'>"; // Perbaikan path data-src
                  } else {
                    echo "<div class='product-image d-flex align-items-center justify-content-center bg-light text-muted' style='font-size: 0.8rem; border: 1px solid #dee2e6;'>No Img</div>";
                  }

                  echo "</td>
                        <td><span class='product-code'>" . htmlspecialchars($row['kode']) . "</span></td>
                        <td class='fw-bold'>" . htmlspecialchars($row['nama']) . "</td>";
                  
                  // Menampilkan Nama Gudang
                  echo "<td>";
                  if (!empty($row['namagudang'])) {
                      echo "<span class='gudang-badge'>" . htmlspecialchars($row['namagudang']) . "</span>";
                  } else {
                      echo "<span class='badge bg-secondary'>N/A</span>";
                  }
                  echo "</td>";

                  // Menampilkan Golongan Gudang (BARU)
                   echo "<td>";
                   if (!empty($row['golongan'])) {
                       echo "<span class='golongan-badge'>" . htmlspecialchars($row['golongan']) . "</span>";
                   } else {
                        // Jika gudang tdk diatur, golongan jg tdk ada
                       echo "<span class='badge bg-secondary'>-</span>"; 
                   }
                   echo "</td>";
                  
                  echo "<td><span class='unit-badge'>" . htmlspecialchars($row['satuan']) . "</span></td>
                        <td><span class='price-badge'>Rp " . number_format($row['harga'], 0, ',', '.') . "</span></td>
                      </tr>";
                }
              } else {
                // Perbaikan colspan menjadi 8
                echo "<tr><td colspan='8' class='text-center p-5 text-muted'><i>Tidak ada produk yang ditemukan.</i></td></tr>"; 
              }
              ?>
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
          <span class="text-muted small">
            Menampilkan <?= ($result) ? mysqli_num_rows($result) : 0 ?> dari <?= $totalData ?> data
          </span>
          <nav>
            <ul class="pagination mb-0">
              <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                 <a class="page-link" href="index.php?page=<?= $page - 1 ?>&limit=<?= $limit ?>&cari=<?= htmlspecialchars($searchTerm) ?>">Previous</a>
              </li>

              <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                  <a class="page-link" href="index.php?page=<?= $i ?>&limit=<?= $limit ?>&cari=<?= htmlspecialchars($searchTerm) ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>

              <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link" href="index.php?page=<?= $page + 1 ?>&limit=<?= $limit ?>&cari=<?= htmlspecialchars($searchTerm) ?>">Next</a>
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
  
<script>
$(document).ready(function() {
    // Script untuk modal zoom gambar (dari file lama)
    $('.zoomable').on('click', function() {
        var imgSrc = $(this).data('image-src');
        $('#modalImage').attr('src', imgSrc);
    });

    // Script toast lama (dihapus/dinonaktifkan karena sudah ada di atas)
    /* var toastEl = document.querySelector('.custom-toast');
    if (toastEl) {
        var toast = new bootstrap.Toast(toastEl, { delay: 5000 });
        toastEl.classList.add('show');
        setTimeout(function() {
            toastEl.classList.add('hide-up');
            setTimeout(function() { toast.hide(); }, 400); 
        }, 4600); 
    }
    */
});
</script>

<?php
// 7. Ganti bagian bawah HTML dengan include footer
include "../template/footer.php"; // Pastikan path ini benar! (../template/footer.php)
?>