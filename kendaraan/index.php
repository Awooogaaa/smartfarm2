<?php
$page_title="Kendaraan"; include "../template/header.php";
$res = mysqli_query($koneksi, "SELECT * FROM kendaraan ORDER BY nopol DESC");
?>
<div class="d-flex justify-content-between mb-3"><h3>Kendaraan</h3><a class="btn btn-primary" href="tambah.php">Tambah</a></div>
<table class="table"><thead><tr><th>Aksi</th><th>No. Pol</th><th>Nama</th><th>Jenis</th><th>Driver</th><th>Kontak</th><th>Tahun</th><th>Kapasitas</th><th>Foto</th></tr></thead><tbody>
<?php while($r=mysqli_fetch_assoc($res)): ?>
<tr>
  <td><a class="btn btn-sm btn-warning" href="edit.php?nopol=<?=urlencode($r['nopol'])?>">Edit</a>
      <a class="btn btn-sm btn-danger" href="index.php?delete=<?=urlencode($r['nopol'])?>" onclick="return confirm('Hapus?')">Hapus</a>
  </td>
  <td><?=htmlspecialchars($r['nopol'])?></td>
  <td><?=htmlspecialchars($r['namakendaraan'])?></td>
  <td><?=htmlspecialchars($r['jeniskendaraan'])?></td>
  <td><?=htmlspecialchars($r['namadriver'])?></td>
  <td><?=htmlspecialchars($r['kontakdriver'])?></td>
  <td><?=htmlspecialchars($r['tahun'])?></td>
  <td><?=htmlspecialchars($r['kapasitas'])?></td>
  <td><?php if($r['foto'] && file_exists(__DIR__ . "/../uploads/".$r['foto'])) echo "<img src='../uploads/".htmlspecialchars($r['foto'])."' style='width:60px;height:60px;object-fit:cover'>"; else echo "No Img";?></td>
</tr>
<?php endwhile; ?>
</tbody></table>
<?php
if (isset($_GET['delete'])) {
  $nopol = mysqli_real_escape_string($koneksi, $_GET['delete']);
  $g = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT foto FROM kendaraan WHERE nopol='$nopol'"));
  if ($g && !empty($g['foto']) && file_exists(__DIR__ . "/../uploads/".$g['foto'])) unlink(__DIR__ . "/../uploads/".$g['foto']);
  mysqli_query($koneksi,"DELETE FROM kendaraan WHERE nopol='$nopol'");
  header("Location:index.php"); exit;
}
include "../template/footer.php";
?>
