<?php 
session_start(); // Pastikan session dimulai
include "../koneksi.php"; 
include "../template/header.php"; 
?>

<div class="container mt-4">
    <h3>Tambah Kendaraan</h3>

    <form action="" method="POST">
        <div class="mb-3">
            <label>No. Polisi (Nopol)</label>
            <input type="text" name="nopol" class="form-control" required maxlength="10">
        </div>
        
        <div class="mb-3">
            <label>Nama Kendaraan</label>
            <input type="text" name="namakendaraan" class="form-control" required maxlength="100">
        </div>

        <div class="mb-3">
            <label>Jenis Kendaraan</label>
            <select name="jeniskendaraan" class="form-select" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="Truk">Truk</option>
                <option value="Pickup">Pickup</option>
                <option value="Mini Van">Mini Van</option>
                <option value="Blindvan">Blindvan</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label>Nama Driver</label>
            <input type="text" name="namadriver" class="form-control" required maxlength="40" placeholder="Diisi '-' jika belum ada">
        </div>
        
        <div class="mb-3">
            <label>Kontak Driver</label>
            <input type="text" name="kontakdriver" class="form-control" required maxlength="15" placeholder="Diisi '-' jika belum ada">
        </div>
        
        <div class="mb-3">
            <label>Tahun Pembelian/Pembuatan</label>
            <input type="date" name="tahun" class="form-control" required value="<?= date('Y-m-d') ?>">
        </div>

        <div class="mb-3">
            <label>Kapasitas (Kg)</label>
            <input type="number" name="kapasitas" class="form-control" required min="0">
        </div>

        <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php
if(isset($_POST['simpan'])) {
    $nopol         = mysqli_real_escape_string($koneksi, $_POST['nopol']);
    $nama          = mysqli_real_escape_string($koneksi, $_POST['namakendaraan']);
    $jenis         = mysqli_real_escape_string($koneksi, $_POST['jeniskendaraan']);
    $kap           = mysqli_real_escape_string($koneksi, $_POST['kapasitas']);
    $driver        = mysqli_real_escape_string($koneksi, $_POST['namadriver']);
    $kontak        = mysqli_real_escape_string($koneksi, $_POST['kontakdriver']);
    $tahun         = mysqli_real_escape_string($koneksi, $_POST['tahun']);
    $foto          = ""; // Diatur kosong karena tidak ada input file di form

    // Cek duplikasi Nopol
    $cekNopol = mysqli_query($koneksi, "SELECT nopol FROM kendaraan WHERE nopol='$nopol'");
    if (mysqli_num_rows($cekNopol) > 0) {
        echo "<script>alert('Gagal menyimpan! No. Polisi sudah ada.');</script>";
        exit;
    }

    // PERBAIKAN: Menggunakan semua kolom NOT NULL
    $query = "INSERT INTO kendaraan (nopol, namakendaraan, jeniskendaraan, namadriver, kontakdriver, tahun, kapasitas, foto) 
              VALUES ('$nopol', '$nama', '$jenis', '$driver', '$kontak', '$tahun', '$kap', '$foto')";

    if(mysqli_query($koneksi, $query)) {
        echo "<script>alert('Berhasil disimpan!'); location='index.php';</script>";
    } else {
         echo "<script>alert('Gagal menyimpan: ".mysqli_error($koneksi)."');</script>";
    }
}
?>

<?php include "../template/footer.php"; ?>