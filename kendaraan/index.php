<?php
include "../koneksi.php";

// Hapus
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($koneksi, "DELETE FROM kendaraan WHERE nokendaraan='$id'");
    header("Location: index.php");
    exit;
}

$q = mysqli_query($koneksi, "SELECT * FROM kendaraan ORDER BY nokendaraan ASC");
include "../template/header.php";
?>

<div class="container mt-4">
    <h3>Data Kendaraan</h3>
    <a href="tambah.php" class="btn btn-primary mb-3">+ Tambah Kendaraan</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No. Kendaraan</th>
                <th>Jenis Kendaraan</th>
                <th>Kapasitas (Kg)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php while($d = mysqli_fetch_assoc($q)) { ?>
            <tr>
                <td><?= $d['nokendaraan']; ?></td>
                <td><?= $d['jeniskendaraan']; ?></td>
                <td><?= number_format($d['kapasitas']); ?></td>
                <td>
                    <a href="edit.php?id=<?= $d['nokendaraan']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a onclick="return confirm('Hapus kendaraan?')" href="index.php?delete=<?= $d['nokendaraan']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php include "../template/footer.php"; ?>
