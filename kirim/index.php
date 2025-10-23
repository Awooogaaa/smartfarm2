<?php
// /kirim/index.php

// === START LOGIKA PEMROSESAN DATA & FILTER ===

// 1. Pastikan session dimulai dan koneksi dibuat (diasumsikan ada di luar blok ini atau di header)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "../koneksi.php";

// Logika Hapus Master Kirim (diletakkan di atas)
if (isset($_GET['delete'])) {
    $kode_kirim_hapus = mysqli_real_escape_string($koneksi, $_GET['delete']);
    
    // Hapus master, detail akan ikut terhapus karena ON DELETE CASCADE
    $deleteQuery = "DELETE FROM masterkirim WHERE kodekirim='$kode_kirim_hapus'";
    if (mysqli_query($koneksi, $deleteQuery)) {
        $_SESSION['msg'] = 'deleted'; // Tambahkan notifikasi
        header("Location: index.php");
        exit;
    } 
    // Jika delete gagal, proses dilanjutkan ke bawah untuk menampilkan error.
}

// 2. Ambil data untuk Dropdown
$kendaraan_res = mysqli_query($koneksi, "SELECT nopol, namakendaraan FROM kendaraan ORDER BY nopol ASC");
$gudang_res = mysqli_query($koneksi, "SELECT kodegudang, namagudang FROM gudang ORDER BY namagudang ASC");

// 3. Inisialisasi dan Ambil Filter dari URL
$filter_awal = isset($_GET['awal']) ? mysqli_real_escape_string($koneksi, $_GET['awal']) : '';
$filter_akhir = isset($_GET['akhir']) ? mysqli_real_escape_string($koneksi, $_GET['akhir']) : '';
$filter_jenis = isset($_GET['jenis']) ? mysqli_real_escape_string($koneksi, $_GET['jenis']) : 'rekap';
$filter_nopol = isset($_GET['nopol']) ? mysqli_real_escape_string($koneksi, $_GET['nopol']) : 'all';
$filter_gudang = isset($_GET['gudang']) ? mysqli_real_escape_string($koneksi, $_GET['gudang']) : 'all';

$where = "WHERE 1=1 ";
$join = "";
$group_by = "";
$select_fields = "m.*, k.namakendaraan";

// Filter Berdasarkan Tanggal
if (!empty($filter_awal)) {
    $where .= " AND m.tglkirim >= '$filter_awal'";
}
if (!empty($filter_akhir)) {
    $where .= " AND m.tglkirim <= '$filter_akhir'";
}

// Filter Berdasarkan Nopol
if ($filter_nopol !== 'all') {
    $where .= " AND m.nopol = '$filter_nopol'";
}

// Filter Berdasarkan Gudang (Membutuhkan JOIN ke detailkirim dan produk)
if ($filter_gudang !== 'all') {
    // Tambahkan join untuk filter gudang
    $join .= " JOIN detailkirim d ON m.kodekirim = d.kodekirim 
              JOIN produk p ON d.kodeproduk = p.kodeproduk";
    $where .= " AND p.kodegudang = '$filter_gudang'";
    // Group by harus ditambahkan agar master kirim tidak duplikat jika ada 
    // banyak detail produk yang memenuhi kriteria gudang
    $group_by = " GROUP BY m.kodekirim";
}


// Penyesuaian Query berdasarkan Jenis Laporan
if ($filter_jenis === 'rekap') {
    // REKAP: Master Kirim
    if ($filter_gudang !== 'all') {
         // Jika ada filter gudang, total qty harus disum ulang dari detail
        $select_fields = "m.kodekirim, m.tglkirim, m.nopol, SUM(d.qty) as totalqty, k.namakendaraan";
    } else {
        // Jika tidak ada filter gudang, pakai totalqty dari masterkirim (Lebih cepat)
        $select_fields = "m.*, k.namakendaraan";
    }
    
    $query = "SELECT $select_fields
              FROM masterkirim m 
              LEFT JOIN kendaraan k ON m.nopol = k.nopol 
              $join 
              $where 
              $group_by 
              ORDER BY m.tglkirim DESC, m.kodekirim DESC";

} elseif ($filter_jenis === 'detail') {
    // DETAIL: Menampilkan per produk
    // PERBAIKAN: Tambahkan m.nopol ke select fields
    $select_fields = "m.kodekirim, m.tglkirim, m.nopol, k.namakendaraan, p.nama AS namaproduk, g.namagudang, d.qty";
    
    // Pastikan semua join untuk detail ada
    $join = " JOIN detailkirim d ON m.kodekirim = d.kodekirim 
              LEFT JOIN produk p ON d.kodeproduk = p.kodeproduk 
              LEFT JOIN gudang g ON p.kodegudang = g.kodegudang";

    // Hapus Group By jika ada dari filter Gudang (karena detail harus menampilkan setiap baris)
    $group_by = "";
    
    $query = "SELECT $select_fields
              FROM masterkirim m 
              LEFT JOIN kendaraan k ON m.nopol = k.nopol 
              $join 
              $where 
              ORDER BY m.tglkirim DESC, m.kodekirim DESC, p.nama ASC";
}

$res = mysqli_query($koneksi, $query);

// === END LOGIKA PHP ===

// --- START OUTPUT HTML ---
$page_title="Pengiriman"; 
include "../template/header.php";

// Tampilkan error jika ada error delete yang gagal redirect
if (isset($deleteQuery) && !empty($deleteQuery) && $res === false) {
    echo "<div class='alert alert-danger'>Error menghapus data: " . mysqli_error($koneksi) . "</div>";
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Master Pengiriman</h3>
        <a class="btn btn-primary" href="tambah.php">Buat Pengiriman</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">Filter Laporan</div>
        <div class="card-body">
            <form method="GET" action="index.php" id="filterForm" class="row g-3">
                
                <div class="col-md-3">
                    <label for="awal" class="form-label">Tgl. Awal</label>
                    <input type="date" class="form-control" name="awal" id="awal" value="<?= htmlspecialchars($filter_awal) ?>">
                    <div class="invalid-feedback" id="awalError">Tanggal awal tidak boleh lebih dari tanggal akhir.</div>
                </div>
                
                <div class="col-md-3">
                    <label for="akhir" class="form-label">Tgl. Akhir</label>
                    <input type="date" class="form-control" name="akhir" id="akhir" value="<?= htmlspecialchars($filter_akhir) ?>">
                    <div class="invalid-feedback" id="akhirError">Tanggal akhir tidak boleh kurang dari tanggal awal.</div>
                </div>

                <div class="col-md-2">
                    <label for="jenis" class="form-label">Jenis Laporan</label>
                    <select class="form-select" name="jenis" id="jenis">
                        <option value="rekap" <?= ($filter_jenis === 'rekap') ? 'selected' : '' ?>>Rekap (Master)</option>
                        <option value="detail" <?= ($filter_jenis === 'detail') ? 'selected' : '' ?>>Detail (Per Produk)</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="nopol" class="form-label">Nopol Pickup</label>
                    <select class="form-select" name="nopol" id="nopol">
                        <option value="all">Semua Nopol</option>
                        <?php 
                        mysqli_data_seek($kendaraan_res, 0); // Reset pointer
                        while($k = mysqli_fetch_assoc($kendaraan_res)) { ?>
                            <option value="<?= htmlspecialchars($k['nopol']) ?>" <?= ($filter_nopol === $k['nopol']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['namakendaraan']) . " (" . htmlspecialchars($k['nopol']) . ")"; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="gudang" class="form-label">Filter Gudang</label>
                    <select class="form-select" name="gudang" id="gudang">
                        <option value="all">Semua Gudang</option>
                        <?php 
                        mysqli_data_seek($gudang_res, 0); // Reset pointer
                        while($g = mysqli_fetch_assoc($gudang_res)) { ?>
                            <option value="<?= htmlspecialchars($g['kodegudang']) ?>" <?= ($filter_gudang === $g['kodegudang']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($g['namagudang']) . " (" . htmlspecialchars($g['kodegudang']) . ")"; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-info">Tampilkan</button>
                    <a href="index.php" class="btn btn-secondary">Reset Filter</a>
                </div>
            </form>
        </div>
    </div>
    <table class="table table-striped table-hover">
        <thead>
            <?php if ($filter_jenis === 'rekap'): ?>
                <tr>
                    <th>Kode Kirim</th>
                    <th>Tgl. Kirim</th>
                    <th>Nopol / Kendaraan</th>
                    <th>Total Qty</th>
                    <th>Aksi</th>
                </tr>
            <?php else: // Detail View ?>
                <tr>
                    <th>Kode Kirim</th>
                    <th>Tgl. Kirim</th>
                    <th>Nopol / Kendaraan</th>
                    <th>Produk</th>
                    <th>Gudang</th>
                    <th>Qty Kirim</th>
                </tr>
            <?php endif; ?>
        </thead>
        <tbody>
        <?php 
        if ($res && mysqli_num_rows($res) > 0) {
            while($r = mysqli_fetch_assoc($res)): 
                // Format Nopol - Nama Kendaraan
                $kendaraan_display = htmlspecialchars($r['nopol']) . " - " . htmlspecialchars($r['namakendaraan']);

                if ($filter_jenis === 'rekap'):
        ?>
            <tr>
                <td><?= htmlspecialchars($r['kodekirim']) ?></td>
                <td><?= htmlspecialchars($r['tglkirim']) ?></td>
                <td><?= $kendaraan_display ?></td> <td><?= htmlspecialchars($r['totalqty']) ?></td>
                <td>
                    <a class="btn btn-sm btn-info" href="edit.php?kode=<?= urlencode($r['kodekirim']) ?>">
                        Detail / Edit
                    </a>
                    <a class="btn btn-sm btn-danger" href="index.php?delete=<?= urlencode($r['kodekirim']) ?>" onclick="return confirm('Hapus pengiriman ini? Tindakan ini tidak dapat dibatalkan.')">
                        Hapus
                    </a>
                </td>
            </tr>
            <?php else: // Detail View ?>
            <tr>
                <td><?= htmlspecialchars($r['kodekirim']) ?></td>
                <td><?= htmlspecialchars($r['tglkirim']) ?></td>
                <td><?= $kendaraan_display ?></td> <td><?= htmlspecialchars($r['namaproduk']) ?></td>
                <td><?= htmlspecialchars($r['namagudang']) ?></td>
                <td><?= htmlspecialchars($r['qty']) ?></td>
            </tr>
            <?php 
                endif;
            endwhile; 
        } else {
            // Tampilkan pesan jika tidak ada data
            $colspan = ($filter_jenis === 'rekap') ? 5 : 6;
            echo "<tr><td colspan='$colspan' class='text-center text-muted'><i>Tidak ada data pengiriman ditemukan.</i></td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const awalInput = document.getElementById('awal');
    const akhirInput = document.getElementById('akhir');
    const form = document.getElementById('filterForm');
    
    function validateDates() {
        const awalDate = new Date(awalInput.value);
        const akhirDate = new Date(akhirInput.value);
        let isValid = true;

        // Reset status
        awalInput.classList.remove('is-invalid');
        akhirInput.classList.remove('is-invalid');

        // Logic 1: Tanggal Awal > Tanggal Akhir
        if (awalInput.value && akhirInput.value && awalDate > akhirDate) {
            awalInput.classList.add('is-invalid');
            document.getElementById('awalError').textContent = 'Tanggal awal tidak boleh lebih dari tanggal akhir.';
            isValid = false;
        }

        // Logic 2: Tanggal Akhir < Tanggal Awal
        // Ini sebenarnya sama dengan Logic 1, tapi untuk feedback user yang lebih spesifik
        if (awalInput.value && akhirInput.value && akhirDate < awalDate) {
            akhirInput.classList.add('is-invalid');
            document.getElementById('akhirError').textContent = 'Tanggal akhir tidak boleh kurang dari tanggal awal.';
            isValid = false;
        }

        return isValid;
    }

    awalInput.addEventListener('change', validateDates);
    akhirInput.addEventListener('change', validateDates);

    // Mencegah submit jika validasi gagal
    form.addEventListener('submit', function(event) {
        if (!validateDates()) {
            event.preventDefault(); // Mencegah form terkirim
        }
    });

    // Panggil sekali saat load untuk memastikan status awal (jika sudah ada nilai)
    validateDates();
});
</script>
<?php
include "../template/footer.php"; 
?>