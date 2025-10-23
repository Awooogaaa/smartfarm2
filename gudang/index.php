<?php
// /gudang/index.php
$page_title = "Manajemen Gudang";
include "../template/header.php";
?>

<style>
/* Modern Clean Blue Theme */
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
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-subtitle {
    color: #718096;
    font-size: 0.9rem;
    margin: 0.5rem 0 0 0;
}

/* Search Bar */
.search-wrapper {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.search-input-wrapper {
    position: relative;
    flex: 1;
}

.search-input {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.625rem 1rem 0.625rem 2.5rem;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    width: 100%;
}

.search-input:focus {
    border-color: #17c1e8;
    box-shadow: 0 0 0 3px rgba(23, 193, 232, 0.1);
    outline: none;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    pointer-events: none;
}

/* Buttons */
.btn-cyan {
    background: #17c1e8;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.625rem 1.25rem;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-cyan:hover {
    background: #0ea5c9;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(23, 193, 232, 0.3);
    color: white;
}

.btn-reset {
    background: white;
    color: #ef4444;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.625rem 1.25rem;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.btn-reset:hover {
    background: #fef2f2;
    border-color: #ef4444;
    color: #ef4444;
}

/* Stats Badge */
.stats-badge {
    background: #e0f7ff;
    color: #0891b2;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

/* Main Card */
.main-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.card-toolbar {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #f0f4f8;
    background: #fafbfc;
}

/* Limit Selector */
.limit-selector {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.limit-selector label {
    color: #4a5568;
    font-weight: 500;
    font-size: 0.9rem;
    margin: 0;
}

.limit-selector select {
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.4rem 0.75rem;
    font-size: 0.9rem;
    color: #2d3748;
    background: white;
    cursor: pointer;
}

.limit-selector select:focus {
    border-color: #17c1e8;
    outline: none;
    box-shadow: 0 0 0 3px rgba(23, 193, 232, 0.1);
}

/* Table Styles */
.table-wrapper {
    overflow-x: auto;
}

.table-modern {
    width: 100%;
    margin: 0;
}

.table-modern thead {
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
}

.table-modern thead th {
    padding: 1rem 1.25rem;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #4a5568;
    border: none;
}

.table-modern tbody tr {
    border-bottom: 1px solid #f0f4f8;
    transition: background 0.2s ease;
}

.table-modern tbody tr:hover {
    background: #f8fafc;
}

.table-modern tbody td {
    padding: 1rem 1.25rem;
    vertical-align: middle;
    color: #2d3748;
    font-size: 0.95rem;
}

/* Action Button */
.btn-edit {
    background: #17c1e8;
    color: white;
    border: none;
    border-radius: 6px;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.btn-edit:hover {
    background: #0ea5c9;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(23, 193, 232, 0.3);
}

/* Badge Styles */
.badge-code {
    background: #fef3c7;
    color: #92400e;
    padding: 0.4rem 0.75rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.85rem;
    font-family: 'Courier New', monospace;
}

.badge-category {
    background: #f0f4f8;
    color: #64748b;
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
    font-size: 0.85rem;
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
}

.empty-state-icon {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 1rem;
}

.empty-state-title {
    color: #4a5568;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.empty-state-text {
    color: #a0aec0;
    font-size: 0.9rem;
}

/* Pagination */
.pagination-wrapper {
    padding: 1.25rem 1.5rem;
    border-top: 1px solid #f0f4f8;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pagination-info {
    color: #718096;
    font-size: 0.9rem;
    font-weight: 500;
}

.pagination {
    display: flex;
    gap: 0.25rem;
    margin: 0;
    padding: 0;
    list-style: none;
}

.page-item {
    display: inline-block;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0.5rem 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    color: #4a5568;
    font-weight: 500;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.2s ease;
    background: white;
}

.page-link:hover {
    background: #f8fafc;
    border-color: #17c1e8;
    color: #17c1e8;
}

.page-item.active .page-link {
    background: #17c1e8;
    border-color: #17c1e8;
    color: white;
}

.page-item.disabled .page-link {
    opacity: 0.4;
    cursor: not-allowed;
    pointer-events: none;
}

/* Toast Notification */
.toast-container {
    position: fixed;
    top: 2rem;
    right: 2rem;
    z-index: 9999;
}

/* Responsive */
@media (max-width: 768px) {
    .page-wrapper {
        padding: 1rem 0;
    }
    
    .page-header {
        padding: 1.25rem 1.5rem;
    }
    
    .search-wrapper {
        flex-direction: column;
    }
    
    .btn-cyan,
    .btn-reset {
        width: 100%;
        justify-content: center;
    }
    
    .card-toolbar {
        flex-direction: column;
        gap: 1rem;
    }
    
    .pagination-wrapper {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>

<?php
// Logika notifikasi (toast)
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
            $alertMessage = "Gudang baru telah berhasil ditambahkan.";
            break;
        case 'updated':
            $alertType = "info";
            $alertHeading = "Update Sukses!";
            $alertMessage = "Data gudang telah berhasil diperbarui.";
            break;
        case 'deleted':
            $alertType = "danger";
            $alertHeading = "Data Dihapus!";
            $alertMessage = "Gudang telah berhasil dihapus.";
            break;
    }
    unset($_SESSION['msg']);
}

// Logika Pagination dan Pencarian
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$where = "";
$searchTerm = "";
if (isset($_GET['cari']) && trim($_GET['cari']) != "") {
    $searchTerm = trim($_GET['cari']);
    $cari = mysqli_real_escape_string($koneksi, $searchTerm);
    // PERBAIKAN: Menambahkan kolom 'alamat' dan 'kontak' ke pencarian. Menghapus 'golongan'
    $where = "WHERE kodegudang LIKE '%$cari%' OR namagudang LIKE '%$cari%' OR alamat LIKE '%$cari%' OR kontak LIKE '%$cari%'";
}

$countQuery = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM gudang $where");
$totalData = mysqli_fetch_assoc($countQuery)['total'];
$totalPages = ceil($totalData / $limit);
// MENGGUNAKAN SELECT * - AMAN
$result = mysqli_query($koneksi, "SELECT * FROM gudang $where ORDER BY namagudang ASC LIMIT $limit OFFSET $offset");
$countAll = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM gudang");
$totalGudang = mysqli_fetch_assoc($countAll)['total'];
?>

<?php if ($showAlert) : ?>
    <script>
        $(document).ready(function() {
            var toastHTML = `
            <div class="toast align-items-center text-white bg-<?php echo $alertType; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong><?php echo $alertHeading; ?></strong> <?php echo $alertMessage; ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;
            $('.toast-container').append(toastHTML);
            var newToast = $('.toast-container .toast').last();
            var toast = new bootstrap.Toast(newToast);
            toast.show();
        });
    </script>
<?php endif; ?>

<div class="page-wrapper">
    <div class="container-fluid px-4">
        <div class="page-header">
            <div class="row align-items-center gy-3">
                <div class="col-lg-6">
                    <h1 class="page-title">
                        <i class="bi bi-box-seam" style="color: #17c1e8;"></i>
                        Manajemen Gudang
                    </h1>
                    <p class="page-subtitle">Kelola semua data gudang penyimpanan</p>
                </div>
                <div class="col-lg-6">
                    <form method="GET" action="" class="search-wrapper">
                        <div class="search-input-wrapper">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" 
                                   class="search-input" 
                                   name="cari" 
                                   placeholder="Cari gudang, alamat, kontak..." 
                                   value="<?= htmlspecialchars($searchTerm) ?>">
                        </div>
                        <button type="submit" class="btn-cyan">
                            <i class="bi bi-search"></i>
                            Cari
                        </button>
                        <?php if ($searchTerm): ?>
                        <a href="index.php" class="btn-reset">
                            <i class="bi bi-x-circle"></i>
                        </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <div class="main-card">
            <div class="card-toolbar">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <span class="stats-badge">
                            <i class="bi bi-archive"></i>
                            <span><?= $totalData ?> Gudang<?= $searchTerm ? " (dari $totalGudang total)" : "" ?></span>
                        </span>
                        
                        <form method="GET" action="" id="limit-form" class="limit-selector">
                            <label for="limit">Tampilkan</label>
                            <select name="limit" 
                                    id="limit" 
                                    onchange="document.getElementById('limit-form').submit()">
                                <option value="5" <?= ($limit == 5) ? 'selected' : '' ?>>5</option>
                                <option value="10" <?= ($limit == 10) ? 'selected' : '' ?>>10</option>
                                <option value="25" <?= ($limit == 25) ? 'selected' : '' ?>>25</option>
                                <option value="50" <?= ($limit == 50) ? 'selected' : '' ?>>50</option>
                            </select>
                            <label>data</label>
                            <input type="hidden" name="cari" value="<?= htmlspecialchars($searchTerm) ?>">
                        </form>
                    </div>
                    
                    <a href="tambah.php" class="btn-cyan">
                        <i class="bi bi-plus-circle"></i>
                        Tambah Gudang
                    </a>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Aksi</th>
                            <th style="width: 150px;">Kode</th>
                            <th>Nama Gudang</th>
                            <th style="width: 180px;">Kontak</th>
                            <th>Alamat</th>
                            <th style="width: 100px;">Kapasitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                
                                // PERBAIKAN: Akses langsung ke kolom yang ada di DB
                                $kontak_display = htmlspecialchars($row['kontak']);
                                $alamat_display = htmlspecialchars($row['alamat']);

                                echo "<tr>
                                    <td class='text-center'>
                                        <a href='edit.php?kode=" . htmlspecialchars($row['kodegudang']) . "' 
                                           class='btn-edit' 
                                           title='Edit'>
                                            <i class='bi bi-pencil-square'></i>
                                        </a>
                                    </td>
                                    <td>
                                        <span class='badge-code'>" . htmlspecialchars($row['kodegudang']) . "</span>
                                    </td>
                                    <td style='font-weight: 600;'>" . htmlspecialchars($row['namagudang']) . "</td>
                                    
                                    <td>" . (!empty($kontak_display) ? "<span class='badge-category'>" . $kontak_display . "</span>" : "<span style='color: #cbd5e0;'>-</span>") . "</td>
                                    
                                    <td style='color: #718096;'>" . (!empty($alamat_display) ? $alamat_display : "<span style='color: #cbd5e0;'>-</span>") . "</td>
                                    
                                    <td>" . number_format($row['kapasitas']) . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr>
                                <td colspan='6'>
                                    <div class='empty-state'>
                                        <div class='empty-state-icon'>
                                            <i class='bi bi-inbox'></i>
                                        </div>
                                        <div class='empty-state-title'>Belum ada data gudang</div>
                                        <div class='empty-state-text'>Klik tombol 'Tambah Gudang' untuk menambah data baru</div>
                                    </div>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 0): ?>
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Halaman <?= $page ?> dari <?= $totalPages ?>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&cari=<?= htmlspecialchars($searchTerm) ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        
                        <?php
                        $start = max(1, $page - 2);
                        $end = min($totalPages, $page + 2);
                        
                        if ($start > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?page=1&limit=' . $limit . '&cari=' . htmlspecialchars($searchTerm) . '">1</a></li>';
                            if ($start > 2) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }
                        
                        for ($i = $start; $i <= $end; $i++):
                        ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&limit=<?= $limit ?>&cari=<?= htmlspecialchars($searchTerm) ?>"><?= $i ?></a>
                            </li>
                        <?php
                        endfor;
                        
                        if ($end < $totalPages) {
                            if ($end < $totalPages - 1) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                            echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '&limit=' . $limit . '&cari=' . htmlspecialchars($searchTerm) . '">' . $totalPages . '</a></li>';
                        }
                        ?>
                        
                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&cari=<?= htmlspecialchars($searchTerm) ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include "../template/footer.php";
?>