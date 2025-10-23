<?php
include "../koneksi.php";
include "../template/header.php";

$id = mysqli_real_escape_string($koneksi, $_GET['kode']); // Menggunakan 'kode' dari index.php
// PERBAIKAN: Mengambil data dari masterkirim dan detailkirim (mengasumsikan 1 detail)
$q = mysqli_query($koneksi, "SELECT m.*, d.kodeproduk, d.qty as detail_qty 
                              FROM masterkirim m 
                              JOIN detailkirim d ON m.kodekirim = d.kodekirim 
                              WHERE m.kodekirim='$id'");
$d = mysqli_fetch_assoc($q);

if (!$d) {
    echo "<script>alert('Data pengiriman tidak ditemukan!');window.location='index.php';</script>";
    exit;
}

// Ambil produk & kendaraan untuk dropdown
$produk_res = mysqli_query($koneksi, "SELECT kodeproduk, nama FROM produk");
$kendaraan_res = mysqli_query($koneksi, "SELECT nopol, jeniskendaraan FROM kendaraan");
?>

<div class="container mt-4">
    <h3>Edit Pengiriman</h3>

    <form action="" method="POST">
        <div class="mb-3">
            <label>Kode Kirim</label>
            <input type="text" value="<?= htmlspecialchars($d['kodekirim']); ?>" class="form-control" readonly>
            <small class="form-text text-muted">Kode tidak dapat diubah (Primary Key)</small>
        </div>

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="<?= htmlspecialchars($d['tglkirim']); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kendaraan</label>
            <select name="nopol" class="form-select" required>
                <?php while($k = mysqli_fetch_assoc($kendaraan_res)) { ?>
                <option value="<?= htmlspecialchars($k['nopol']); ?>" <?= ($k['nopol'] == $d['nopol'])?'selected':'' ?>>
                    <?= htmlspecialchars($k['jeniskendaraan']) . " (" . htmlspecialchars($k['nopol']) . ")"; ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Produk</label>
            <select name="kodeproduk" class="form-select" required>
                <?php 
                // Reset pointer untuk digunakan di form
                mysqli_data_seek($produk_res, 0); 
                while($p = mysqli_fetch_assoc($produk_res)) { ?>
                <option value="<?= htmlspecialchars($p['kodeproduk']); ?>" <?= ($p['kodeproduk'] == $d['kodeproduk'])?'selected':'' ?>>
                    <?= htmlspecialchars($p['nama']); ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Qty</label>
            <input type="number" name="qty" class="form-control" value="<?= $d['detail_qty']; ?>" min="1" required>
        </div>

        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php
if (isset($_POST['update'])) {
    $tgl  = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    // PERBAIKAN: Mengganti nokendaraan dengan nopol
    $nopol = mysqli_real_escape_string($koneksi, $_POST['nopol']);
    $prod = mysqli_real_escape_string($koneksi, $_POST['kodeproduk']);
    $qty  = (double)$_POST['qty'];

    // PERBAIKAN: Update ke masterkirim
    $q_master = "UPDATE masterkirim SET 
                    tglkirim='$tgl',
                    nopol='$nopol',
                    totalqty='$qty'
                  WHERE kodekirim='$id'";
    
    // PERBAIKAN: Update ke detailkirim
    $q_detail = "UPDATE detailkirim SET 
                    kodeproduk='$prod',
                    qty='$qty'
                  WHERE kodekirim='$id'";

    if (mysqli_query($koneksi, $q_master) && mysqli_query($koneksi, $q_detail)) {
        echo "<script>alert('Berhasil diupdate!'); location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate: ".mysqli_error($koneksi)."');</script>";
    }
}
?>

<?php include "../template/footer.php"; ?>