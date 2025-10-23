<?php 
include "../koneksi.php"; 
include "../template/header.php";

// Ambil produk & kendaraan
$produk = mysqli_query($koneksi, "SELECT * FROM produk");
$kendaraan = mysqli_query($koneksi, "SELECT * FROM kendaraan");
?>

<div class="container mt-4">
    <h3>Tambah Pengiriman</h3>

    <form action="" method="POST">
        <div class="mb-3">
            <label>Kode Kirim</label>
            <input type="text" name="kodekirim" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kendaraan</label>
            <select name="nokendaraan" class="form-select" required>
                <option value="">-- Pilih Kendaraan --</option>
                <?php while($k = mysqli_fetch_assoc($kendaraan)) { ?>
                <option value="<?= $k['nokendaraan']; ?>"><?= $k['jeniskendaraan']; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Produk</label>
            <select name="kodeproduk" class="form-select" required>
                <option value="">-- Pilih Produk --</option>
                <?php while($p = mysqli_fetch_assoc($produk)) { ?>
                <option value="<?= $p['kodeproduk']; ?>"><?= $p['nama']; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Qty</label>
            <input type="number" name="qty" class="form-control" required>
        </div>

        <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php
if (isset($_POST['simpan'])) {
    $kode  = $_POST['kodekirim'];
    $tgl   = $_POST['tanggal'];
    $kend  = $_POST['nokendaraan'];
    $prod  = $_POST['kodeproduk'];
    $qty   = $_POST['qty'];

    mysqli_query($koneksi, 
        "INSERT INTO kirim VALUES('$kode','$tgl','$kend','$prod','$qty')"
    );

    echo "<script>alert('Berhasil disimpan!'); location='index.php';</script>";
}
?>

<?php include "../template/footer.php"; ?>
