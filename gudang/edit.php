<?php
require '../koneksi.php'; // Sesuaikan path

$kodegudang = $_GET['kodegudang'];

// Ambil data gudang berdasarkan kodegudang
$result = mysqli_query($conn, "SELECT * FROM gudang WHERE kodegudang = $kodegudang");
$gudang = mysqli_fetch_assoc($result);

if (isset($_POST['submit'])) {
    $namagudang = mysqli_real_escape_string($conn, $_POST['namagudang']);
    $golongan = mysqli_real_escape_string($conn, $_POST['golongan']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $query = "UPDATE gudang SET
                namagudang = '$namagudang',
                golongan = '$golongan',
                keterangan = '$keterangan'
              WHERE kodegudang = $kodegudang";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data gudang berhasil diubah!');
                document.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal mengubah data!');
              </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Gudang</title>
    <link rel="stylesheet" href="../modul/node_modules/bootstrap/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Edit Data Gudang</h1>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="namagudang" class="form-label">Nama Gudang</label>
                <input type="text" class="form-control" id="namagudang" name="namagudang" value="<?= htmlspecialchars($gudang['namagudang']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="golongan" class="form-label">Golongan</label>
                <input type="text" class="form-control" id="golongan" name="golongan" value="<?= htmlspecialchars($gudang['golongan']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= htmlspecialchars($gudang['keterangan']); ?></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script src="../modul/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>