<?php include "../koneksi.php"; include "../template/header.php"; ?>

<div class="container mt-4">
    <h3>Tambah Kendaraan</h3>

    <form action="" method="POST">
        <div class="mb-3">
            <label>No Kendaraan</label>
            <input type="text" name="nokendaraan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jenis Kendaraan</label>
            <input type="text" name="jeniskendaraan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kapasitas (Kg)</label>
            <input type="number" name="kapasitas" class="form-control" required>
        </div>

        <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php
if(isset($_POST['simpan'])) {
    $no = $_POST['nokendaraan'];
    $jenis = $_POST['jeniskendaraan'];
    $kap = $_POST['kapasitas'];

    mysqli_query($koneksi, "INSERT INTO kendaraan VALUES('$no','$jenis','$kap')");
    echo "<script>alert('Berhasil disimpan!'); location='index.php';</script>";
}
?>

<?php include "../template/footer.php"; ?>
