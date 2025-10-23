<?php
include "../koneksi.php";

// Hapus
if (isset($_GET['delete'])) {
    // PERBAIKAN: Menggunakan nopol sebagai identifier
    $nopol_hapus = mysqli_real_escape_string($koneksi, $_GET['delete']);
    mysqli_query($koneksi, "DELETE FROM kendaraan WHERE nopol='$nopol_hapus'");
    header("Location: index.php");
    exit;
}

// PERBAIKAN: Menggunakan nopol sebagai Primary Key
$q = mysqli_query($koneksi, "SELECT * FROM kendaraan ORDER BY nopol ASC");
include "../template/header.php";
?>

<div class="container mt-4">
    <h3>Data Kendaraan</h3>
    <a href="tambah.php" class="btn btn-primary mb-3">+ Tambah Kendaraan</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No. Polisi (Nopol)</th>
                <th>Nama Kendaraan</th>
                <th>Jenis Kendaraan</th>
                <th>Kapasitas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php while($d = mysqli_fetch_assoc($q)) { ?>
            <tr>
                <td><?= htmlspecialchars($d['nopol']); ?></td>
                <td><?= htmlspecialchars($d['namakendaraan']); ?></td>
                <td><?= htmlspecialchars($d['jeniskendaraan']); ?></td>
                <td><?= htmlspecialchars($d['kapasitas']); ?></td>
                <td>
                    <a href="edit.php?nopol=<?= urlencode($d['nopol']); ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a onclick="return confirm('Hapus kendaraan dengan nopol <?= htmlspecialchars($d['nopol']); ?>?')" href="index.php?delete=<?= urlencode($d['nopol']); ?>" class="btn btn-danger btn-sm">Hapus</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php include "../template/footer.php"; ?>