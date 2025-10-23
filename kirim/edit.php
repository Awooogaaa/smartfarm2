<?php 
include "../template/header.php"; 
include "../koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$data = mysqli_query($koneksi, "SELECT * FROM kirim WHERE id = $id");
$kirim = mysqli_fetch_assoc($data);

if (!$kirim) {
    echo "<script>alert('Data tidak ditemukan!');window.location='index.php';</script>";
    exit;
}

// Dropdown Gudang
$gudang = mysqli_query($koneksi, "SELECT * FROM gudang");

// Dropdown Kendaraan
$kendaraan = mysqli_query($koneksi, "SELECT * FROM kendaraan");

// UPDATE DATA
if (isset($_POST['update'])) {
    $tgl = $_POST['tgl'];
    $gudang_asal = $_POST['gudang_asal'];
    $kendaraan_id = $_POST['kendaraan'];
    $qty = $_POST['qty'];

    // Cek kapasitas!
    $cek = mysqli_query($koneksi, "SELECT kapasitas FROM kendaraan WHERE id=$kendaraan_id");
    $cek_kap = mysqli_fetch_assoc($cek)['kapasitas'];

    if ($qty > $cek_kap) {
        echo "<script>alert('Total Qty melebihi kapasitas kendaraan!');</script>";
    } else {
        $query = "UPDATE kirim 
                  SET tgl='$tgl', gudang_asal='$gudang_asal', kendaraan=$kendaraan_id, qty=$qty 
                  WHERE id=$id";

        if (mysqli_query($koneksi, $query)) {
            $_SESSION['msg'] = 'updated';
            header("Location: index.php");
            exit;
        } else {
            echo mysqli_error($koneksi);
        }
    }
}
?>

<div class="container mt-4">
    <h3>Edit Pengiriman</h3>

    <form method="POST">
        <div class="mb-3">
            <label>Tanggal Kirim</label>
            <input type="date" name="tgl" class="form-control" 
                   value="<?= $kirim['tgl'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Gudang Asal</label>
            <select name="gudang_asal" class="form-control" required>
                <?php while ($g = mysqli_fetch_assoc($gudang)) { ?>
                    <option value="<?= $g['id'] ?>" 
                        <?= ($g['id'] == $kirim['gudang_asal']) ? 'selected' : '' ?>>
                        <?= $g['namagudang'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Kendaraan</label>
            <select name="kendaraan" class="form-control" required>
                <?php while ($k = mysqli_fetch_assoc($kendaraan)) { ?>
                    <option value="<?= $k['id'] ?>" 
                        <?= ($k['id'] == $kirim['kendaraan']) ? 'selected' : '' ?>>
                        <?= $k['nomor'] ?> - <?= $k['jenis'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Total Qty (Kg)</label>
            <input type="number" name="qty" 
                   value="<?= $kirim['qty'] ?>" class="form-control" required>
        </div>

        <a href="index.php" class="btn btn-secondary">Kembali</a>
        <button type="submit" name="update" class="btn btn-primary">Simpan</button>
    </form>
</div>

<?php include "../template/footer.php"; ?>
