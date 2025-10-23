<?php
include "../koneksi.php";
include "../template/header.php";

$id = $_GET['id'];
$q = mysqli_query($koneksi, "SELECT * FROM kirim WHERE kodekirim='$id'");
$d = mysqli_fetch_assoc($q);

$produk = mysqli_query($koneksi, "SELECT * FROM produk");
$kendaraan = mysqli_query($koneksi, "SELECT * FROM kendaraan");
?>

<div class="container mt-4">
    <h3>Edit Pengiriman</h3>

    <form action="" method="POST">
        <div class="mb-3">
            <label>Kode Kirim</label>
            <input type="text" value="<?= $d['kodekirim']; ?>" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="<?= $d['tanggal']; ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kendaraan</label>
            <select name="nokendaraan" class="form-select" required>
                <?php while($k = mysqli_fetch_assoc($kendaraan)) { ?>
                <option value="<?= $k['nokendaraan']; ?>" <?= ($k['nokendaraan']==$d['nokendaraan'])?'selected':'' ?>>
                    <?= $k['jeniskendaraan']; ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Produk</label>
            <select name="kodeproduk" class="form-select" required>
                <?php while($p = mysqli_fetch_assoc($produk)) { ?>
                <option value="<?= $p['kodeproduk']; ?>" <?= ($p['kodeproduk']==$d['kodeproduk'])?'selected':'' ?>>
                    <?= $p['nama']; ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Qty</label>
            <input type="number" name="qty" class="form-control" value="<?= $d['qty']; ?>" required>
        </div>

        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php
if (isset($_POST['update'])) {
    $tgl  = $_POST['tanggal'];
    $kend = $_POST['nokendaraan'];
    $prod = $_POST['kodeproduk'];
    $qty  = $_POST['qty'];

    mysqli_query($koneksi,
        "UPDATE kirim SET 
        tanggal='$tgl',
        nokendaraan='$kend',
        kodeproduk='$prod',
        qty='$qty'
        WHERE kodekirim='$id'"
    );

    echo "<script>alert('Berhasil diupdate!'); location='index.php';</script>";
}
?>

<?php include "../template/footer.php"; ?>
