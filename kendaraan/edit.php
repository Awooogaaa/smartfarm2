<?php 
include "../template/header.php"; 
include "../koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$data = mysqli_query($koneksi, "SELECT * FROM kendaraan WHERE id = $id");
$kendaraan = mysqli_fetch_assoc($data);

if (!$kendaraan) {
    echo "<script>alert('Data tidak ditemukan!');window.location='index.php';</script>";
    exit;
}

// UPDATE DATA
if (isset($_POST['update'])) {
    $nomor = $_POST['nomor'];
    $jenis = $_POST['jenis'];
    $kapasitas = $_POST['kapasitas'];

    $query = "UPDATE kendaraan SET nomor='$nomor', jenis='$jenis', kapasitas='$kapasitas' WHERE id=$id";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['msg'] = 'updated';
        header("Location: index.php");
        exit;
    } else {
        echo mysqli_error($koneksi);
    }
}
?>

<div class="container mt-4">
    <h3>Edit Kendaraan</h3>

    <form method="POST">
        <div class="mb-3">
            <label>No. Plat</label>
            <input type="text" class="form-control" name="nomor"
                value="<?= $kendaraan['nomor'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Jenis Kendaraan</label>
            <select name="jenis" class="form-control" required>
                <?php
                $opsi = ["Pickup", "Truk Box", "Mini Van", "Blindvan"];
                foreach ($opsi as $item) {
                    $selected = ($kendaraan['jenis'] == $item) ? "selected" : "";
                    echo "<option $selected>$item</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Kapasitas (Kg)</label>
            <input type="number" class="form-control" name="kapasitas"
                value="<?= $kendaraan['kapasitas'] ?>" required>
        </div>

        <a href="index.php" class="btn btn-secondary">Kembali</a>
        <button type="submit" name="update" class="btn btn-primary">Simpan</button>
    </form>
</div>

<?php include "../template/footer.php"; ?>
