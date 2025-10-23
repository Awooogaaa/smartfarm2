<?php 
session_start(); // Pastikan session dimulai
include "../template/header.php"; 
include "../koneksi.php";

// PERBAIKAN: Menggunakan nopol sebagai identifier
if (!isset($_GET['nopol'])) {
    header("Location: index.php");
    exit;
}

$nopol_kendaraan = mysqli_real_escape_string($koneksi, $_GET['nopol']);

// Ambil data berdasarkan nopol
$data = mysqli_query($koneksi, "SELECT * FROM kendaraan WHERE nopol = '$nopol_kendaraan'");
$kendaraan = mysqli_fetch_assoc($data);

if (!$kendaraan) {
    echo "<script>alert('Data tidak ditemukan!');window.location='index.php';</script>";
    exit;
}

// UPDATE DATA
if (isset($_POST['update'])) {
    // Mengambil data dari form
    $nama_kendaraan = mysqli_real_escape_string($koneksi, $_POST['namakendaraan']);
    $jenis          = mysqli_real_escape_string($koneksi, $_POST['jeniskendaraan']);
    $kapasitas      = mysqli_real_escape_string($koneksi, $_POST['kapasitas']);
    $driver         = mysqli_real_escape_string($koneksi, $_POST['namadriver']);
    $kontak         = mysqli_real_escape_string($koneksi, $_POST['kontakdriver']);
    $tahun          = mysqli_real_escape_string($koneksi, $_POST['tahun']);
    // Foto diabaikan

    // PERBAIKAN: Menggunakan kolom yang benar dan nopol lama sebagai WHERE
    $query = "UPDATE kendaraan SET 
                namakendaraan='$nama_kendaraan', 
                jeniskendaraan='$jenis', 
                namadriver='$driver',
                kontakdriver='$kontak',
                tahun='$tahun',
                kapasitas='$kapasitas' 
              WHERE nopol='$nopol_kendaraan'";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['msg'] = 'updated';
        header("Location: index.php");
        exit;
    } else {
        echo "<script>alert('Gagal mengupdate: ".mysqli_error($koneksi)."');</script>";
    }
}
?>

<div class="container mt-4">
    <h3>Edit Kendaraan (Nopol: <?= htmlspecialchars($kendaraan['nopol']) ?>)</h3>

    <form method="POST">
        <div class="mb-3">
            <label>No. Polisi (Nopol)</label>
            <input type="text" class="form-control" name="nopol_new"
                value="<?= htmlspecialchars($kendaraan['nopol']) ?>" readonly>
            <small class="text-muted">No. Polisi tidak bisa diubah.</small>
        </div>
        
        <div class="mb-3">
            <label>Nama Kendaraan</label>
            <input type="text" class="form-control" name="namakendaraan"
                value="<?= htmlspecialchars($kendaraan['namakendaraan']) ?>" required maxlength="100">
        </div>

        <div class="mb-3">
            <label>Jenis Kendaraan</label>
            <select name="jeniskendaraan" class="form-select" required>
                <?php
                // Opsi yang diminta + opsi default lainnya
                $opsi = ["Truk", "Pickup", "Mini Van", "Blindvan"];
                $current_jenis = htmlspecialchars($kendaraan['jeniskendaraan']);
                
                // Tambahkan nilai saat ini ke opsi jika tidak ada
                if (!in_array($current_jenis, $opsi)) {
                    $opsi[] = $current_jenis; 
                }

                foreach ($opsi as $item) {
                    $selected = ($current_jenis == $item) ? "selected" : "";
                    echo "<option value='".htmlspecialchars($item)."' $selected>".htmlspecialchars($item)."</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label>Nama Driver</label>
            <input type="text" class="form-control" name="namadriver"
                value="<?= htmlspecialchars($kendaraan['namadriver']) ?>" required maxlength="40">
        </div>
        
        <div class="mb-3">
            <label>Kontak Driver</label>
            <input type="text" class="form-control" name="kontakdriver"
                value="<?= htmlspecialchars($kendaraan['kontakdriver']) ?>" required maxlength="15">
        </div>
        
        <div class="mb-3">
            <label>Tahun Pembelian/Pembuatan</label>
            <input type="date" class="form-control" name="tahun"
                value="<?= htmlspecialchars($kendaraan['tahun']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Kapasitas (Kg)</label>
            <input type="number" class="form-control" name="kapasitas"
                value="<?= htmlspecialchars($kendaraan['kapasitas']) ?>" required min="0">
        </div>
        
        <a href="index.php" class="btn btn-secondary">Kembali</a>
        <button type="submit" name="update" class="btn btn-primary">Simpan</button>
    </form>
</div>

<?php include "../template/footer.php"; ?>