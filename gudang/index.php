<?php
// /gudang/index.php
$page_title = "Manajemen Gudang";
include "../template/header.php"; // Menggunakan ../ untuk naik satu level
?>

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
    $where = "WHERE kodegudang LIKE '%$cari%' OR namagudang LIKE '%$cari%' OR golongan LIKE '%$cari%'";
}

$countQuery = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM gudang $where");
$totalData = mysqli_fetch_assoc($countQuery)['total'];
$totalPages = ceil($totalData / $limit);
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

<div class="container-fluid p-0">
    <div class="card shadow-sm border-0">
        <div class="card-header" style="background-color: #ffffff !important; border-bottom: 1px solid #e9ecef !important;">
            <div class="row align-items-center gy-3 mb-4">
                <div class="col-lg-7 col-md-12">
                    <h2 class="mb-0 fw-bold"><i class="bi bi-house-door-fill me-2 text-success"></i>Manajemen Gudang</h2>
                </div>
                <div class="col-lg-5 col-md-12">
                    <form method="GET" action="" class="d-flex align-items-center gap-2" role="search">
                        <div class="position-relative flex-grow-1">
                            <input type="text" class="form-control" name="cari" placeholder="Cari gudang..." value="<?= htmlspecialchars($searchTerm) ?>" style="border-radius: 50px; padding-left: 1.5rem;">
                        </div>
                        <button type="submit" class="btn btn-success" style="border-radius: 50px;">Cari</button>
                        <a href="index.php" class="btn btn-outline-danger" style="border-radius: 50px;">Reset</a>
                    </form>
                </div>
            </div>
            
            <div class="row align-items-center gy-3">
                <div class="col-md-8">
                    <span class="badge bg-light text-dark p-2">
                        <i class="bi bi-archive me-1"></i>
                        Menampilkan <?php echo $totalData; ?> <?php echo $searchTerm ? " dari $totalGudang" : ""; ?> Gudang
                    </span>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="tambah.php" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Gudang
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="p-3">
                <div class="limit-container bg-light p-3 rounded">
                    <form method="GET" action="" id="limit-form" class="d-flex align-items-center gap-2">
                        <label for="limit" class="form-label mb-0">Tampilkan:</label>
                        <select name="limit" id="limit" class="form-select form-select-sm" style="width: 80px;" onchange="document.getElementById('limit-form').submit()">
                            <option value="5" <?= ($limit == 5) ? 'selected' : '' ?>>5</option>
                            <option value="10" <?= ($limit == 10) ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= ($limit == 25) ? 'selected' : '' ?>>25</option>
                        </select>
                        <label for="limit" class="form-label mb-0">data</label>
                        <input type="hidden" name="cari" value="<?= htmlspecialchars($searchTerm) ?>">
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-center">Aksi</th>
                            <th scope="col">Kode Gudang</th>
                            <th scope="col">Nama Gudang</th>
                            <th scope="col">Golongan</th>
                            <th scope="col">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <td class='text-center'>
                                        <a href='edit.php?kode=" . htmlspecialchars($row['kodegudang']) . "' class='btn btn-warning btn-sm'>
                                            <i class='bi bi-pencil-square'></i>
                                        </a>
                                    </td>
                                    <td><span class='badge bg-primary'>" . htmlspecialchars($row['kodegudang']) . "</span></td>
                                    <td class='fw-bold'>" . htmlspecialchars($row['namagudang']) . "</td>
                                    <td>" . htmlspecialchars($row['golongan']) . "</td>
                                    <td>" . htmlspecialchars($row['keterangan']) . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center p-5'>Belum ada data gudang.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <span class="text-muted small">
                    Halaman <?php echo $page; ?> dari <?php echo $totalPages; ?>
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

<?php
include "../template/footer.php"; // Menggunakan ../ untuk naik satu level
?>