<?php 
include "../koneksi.php"; 
include "../template/header.php";

// Ambil produk & kendaraan
$produk_res = mysqli_query($koneksi, "SELECT kodeproduk, nama FROM produk");
$kendaraan_res = mysqli_query($koneksi, "SELECT nopol, namakendaraan, jeniskendaraan FROM kendaraan");
?>

<div class="container mt-4">
    <h3>Tambah Pengiriman</h3>

    <form action="" method="POST">
        <div class="mb-3">
            <label>Kode Kirim</label>
            <input type="text" name="kodekirim" class="form-control" required value="<?= isset($_POST['kodekirim']) ? htmlspecialchars($_POST['kodekirim']) : '' ?>">
        </div>

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required value="<?= isset($_POST['tanggal']) ? htmlspecialchars($_POST['tanggal']) : date('Y-m-d') ?>">
        </div>

        <div class="mb-3">
            <label>Kendaraan</label>
            <select name="nopol" class="form-select" required>
                <option value="">-- Pilih Kendaraan --</option>
                <?php while($k = mysqli_fetch_assoc($kendaraan_res)) { ?>
                <option value="<?= htmlspecialchars($k['nopol']); ?>" <?= (isset($_POST['nopol']) && $_POST['nopol'] == $k['nopol']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($k['jeniskendaraan']) . " (" . htmlspecialchars($k['nopol']) . ")"; ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Produk</label>
            <select name="kodeproduk" class="form-select" required>
                <option value="">-- Pilih Produk --</option>
                <?php while($p = mysqli_fetch_assoc($produk_res)) { ?>
                <option value="<?= htmlspecialchars($p['kodeproduk']); ?>" <?= (isset($_POST['kodeproduk']) && $_POST['kodeproduk'] == $p['kodeproduk']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nama']); ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Qty</label>
            <input type="number" name="qty" class="form-control" min="1" required value="<?= isset($_POST['qty']) ? htmlspecialchars($_POST['qty']) : '' ?>">
        </div>

        <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php
if (isset($_POST['simpan'])) {
    $kode  = mysqli_real_escape_string($koneksi, $_POST['kodekirim']);
    $tgl   = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    // PERBAIKAN: Mengganti nokendaraan dengan nopol
    $nopol  = mysqli_real_escape_string($koneksi, $_POST['nopol']);
    $prod  = mysqli_real_escape_string($koneksi, $_POST['kodeproduk']);
    $qty   = (double)$_POST['qty'];
    
    // 1. Cek duplikasi Kode Kirim
    $cek_master = mysqli_query($koneksi, "SELECT kodekirim FROM masterkirim WHERE kodekirim='$kode'");
    if (mysqli_num_rows($cek_master) > 0) {
        echo "<script>alert('Kode Kirim sudah ada!');</script>";
        // Hentikan proses, biarkan form terisi
    } else {
        // PERBAIKAN: Insert ke masterkirim
        $q_master = "INSERT INTO masterkirim (kodekirim, tglkirim, nopol, totalqty) VALUES ('$kode', '$tgl', '$nopol', '$qty')";
        // PERBAIKAN: Insert ke detailkirim
        $q_detail = "INSERT INTO detailkirim (kodekirim, kodeproduk, qty) VALUES ('$kode', '$prod', '$qty')";
        
        if (mysqli_query($koneksi, $q_master) && mysqli_query($koneksi, $q_detail)) {
            echo "<script>alert('Berhasil disimpan!'); location='index.php';</script>";
        } else {
             // Rollback jika salah satu gagal (logika sederhana)
             mysqli_query($koneksi, "DELETE FROM masterkirim WHERE kodekirim='$kode'");
             echo "<script>alert('Gagal menyimpan pengiriman: ".mysqli_error($koneksi)."');</script>";
        }
    }
}
?>

<?php include "../template/footer.php"; ?>