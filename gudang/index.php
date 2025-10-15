<?php
require '../koneksi.php'; // Sesuaikan path ke koneksi.php

// Logika untuk MENGHAPUS data
// Cek jika ada parameter 'action' dengan nilai 'hapus' di URL
if (isset($_GET['action']) && $_GET['action'] == 'hapus') {
    $kodegudang = $_GET['kodegudang'];
    $result = mysqli_query($conn, "DELETE FROM gudang WHERE kodegudang = $kodegudang");

    if ($result) {
        echo "<script>
                alert('Data gudang berhasil dihapus!');
                document.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data. Pastikan tidak ada produk yang terkait dengan gudang ini.');
                document.location.href = 'index.php';
              </script>";
    }
    exit; // Hentikan eksekusi script setelah redirect
}

// Query untuk mengambil semua data untuk ditampilkan di tabel
$result = mysqli_query($conn, "SELECT * FROM gudang");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Gudang</title>
    <link rel="stylesheet" href="../modul/node_modules/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Manajemen Data Gudang</h1>
        <a href="tambah.php" class="btn btn-primary mb-3">Tambah Data Gudang</a>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Gudang</th>
                    <th>Golongan</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php $i = 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= htmlspecialchars($row['namagudang']); ?></td>
                            <td><?= htmlspecialchars($row['golongan']); ?></td>
                            <td><?= htmlspecialchars($row['keterangan']); ?></td>
                            <td>
                                <a href="edit.php?kodegudang=<?= $row['kodegudang']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="index.php?action=hapus&kodegudang=<?= $row['kodegudang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data gudang.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="../index.php" class="btn btn-secondary">Kembali ke Daftar Produk</a>
    </div>

    <script src="../modul/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>