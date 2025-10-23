<?php
$page_title="Master Kirim"; include "../template/header.php";
$res = mysqli_query($koneksi, "SELECT m.*, k.namakendaraan FROM masterkirim m LEFT JOIN kendaraan k ON m.nopol=k.nopol ORDER BY tglkirim DESC, kodekirim DESC");
?>
<div class="d-flex justify-content-between mb-3"><h3>Pengiriman</h3><a class="btn btn-primary" href="tambah.php">Buat Pengiriman</a></div>
<table class="table"><thead><tr><th>Kode</th><th>Tgl</th><th>Kendaraan</th><th>Total Qty</th><th>Aksi</th></tr></thead><tbody>
<?php while($r=mysqli_fetch_assoc($res)): ?>
<tr>
  <td><?=htmlspecialchars($r['kodekirim'])?></td>
  <td><?=htmlspecialchars($r['tglkirim'])?></td>
  <td><?=htmlspecialchars($r['namakendaraan'])?></td>
  <td><?=htmlspecialchars($r['totalqty'])?></td>
  <td><a class="btn btn-sm btn-info" href="edit.php?kode=<?=urlencode($r['kodekirim'])?>">Detail / Edit</a></td>
</tr>
<?php endwhile; ?>
</tbody></table>
<?php include "../template/footer.php"; ?>
