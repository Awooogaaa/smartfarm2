<?php
// /kirim/index.php (DIPERBAIKI LAGI)
$page_title="Master Kirim"; include "../template/header.php";

// PERBAIKAN: Ganti nama tabel dari 'kirim' menjadi 'masterkirim'
$res = mysqli_query($koneksi, "SELECT m.*, k.namakendaraan 
                              FROM masterkirim m 
                              LEFT JOIN kendaraan k ON m.nopol=k.nopol 
                              ORDER BY m.tglkirim DESC, m.kodekirim DESC");
?>
<div class="d-flex justify-content-between mb-3"><h3>Pengiriman</h3><a class="btn btn-primary" href="tambah.php">Buat Pengiriman</a></div>
<table class="table"><thead><tr><th>Kode</th><th>Tgl</th><th>Kendaraan</th><th>Total Qty</th><th>Aksi</th></tr></thead><tbody>
<?php 
// Pastikan query berhasil sebelum loop
if ($res) {
    while($r=mysqli_fetch_assoc($res)): 
?>
<tr>
  <td><?=htmlspecialchars($r['kodekirim'])?></td>
  <td><?=htmlspecialchars($r['tglkirim'])?></td>
  <td><?=htmlspecialchars($r['namakendaraan'])?></td>
  <td><?=htmlspecialchars($r['totalqty'])?></td>
  <td>
      <a class="btn btn-sm btn-info" href="edit.php?kode=<?=urlencode($r['kodekirim'])?>">
          Detail / Edit
      </a>
      <a class="btn btn-sm btn-danger" href="index.php?delete=<?=urlencode($r['kodekirim'])?>" onclick="return confirm('Hapus pengiriman ini? Tindakan ini tidak dapat dibatalkan.')">
          Hapus
      </a>
  </td>
</tr>
<?php 
    endwhile; 
} else {
    // Tampilkan pesan error jika query gagal
    echo "<tr><td colspan='5' class='text-danger'>Error: " . mysqli_error($koneksi) . "</td></tr>";
}
?>
</tbody></table>

<?php
// Tambahan: Logika Hapus Master Kirim (dan detailnya otomatis terhapus karena CASCADE)
if (isset($_GET['delete'])) {
  $kode_kirim_hapus = mysqli_real_escape_string($koneksi, $_GET['delete']);
  
  // PERINGATAN: Logika stok dinonaktifkan. Seharusnya stok dikembalikan di sini.
  // ... (Logika pengembalian stok detail kirim yang dihapus) ...

  // Hapus master, detail akan ikut terhapus karena ON DELETE CASCADE
  $deleteQuery = "DELETE FROM masterkirim WHERE kodekirim='$kode_kirim_hapus'";
  if (mysqli_query($koneksi, $deleteQuery)) {
      $_SESSION['msg'] = 'deleted'; // Tambahkan notifikasi
      header("Location: index.php");
      exit;
  } else {
      echo "<div class='alert alert-danger'>Error menghapus data: " . mysqli_error($koneksi) . "</div>";
  }
}

include "../template/footer.php"; 
?>