<?php
$page_title="Tambah Kendaraan"; include "../template/header.php";
$jenis_list = ['Pickup','Truk Box','Mini Van','Blindvan'];
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $nopol = mysqli_real_escape_string($koneksi,$_POST['nopol']);
  $nama = mysqli_real_escape_string($koneksi,$_POST['namakendaraan']);
  $jenis = (int)array_search($_POST['jeniskendaraan'],$jenis_list); // store index or use string
  // we'll store string for simplicity:
  $jenis_txt = mysqli_real_escape_string($koneksi, $_POST['jeniskendaraan']);
  $driver = mysqli_real_escape_string($koneksi,$_POST['namadriver']);
  $kontak = mysqli_real_escape_string($koneksi,$_POST['kontakdriver']);
  $tahun = mysqli_real_escape_string($koneksi,$_POST['tahun']);
  $kap = mysqli_real_escape_string($koneksi,$_POST['kapasitas']);

  $foto = "";
  if (!empty($_FILES['foto']['name'])) {
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto = $nopol . "_" . time() . "." . $ext;
    move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . "/../uploads/".$foto);
  }

  mysqli_query($koneksi,"INSERT INTO kendaraan(nopol,namakendaraan,jeniskendaraan,namadriver,kontakdriver,tahun,kapasitas,foto) VALUES('$nopol','$nama','$jenis_txt','$driver','$kontak','$tahun','$kap','$foto')");
  header("Location:index.php"); exit;
}
?>
<h3>Tambah Kendaraan</h3>
<form method="post" enctype="multipart/form-data">
  <div class="mb-2"><label>No. Pol</label><input name="nopol" class="form-control" required></div>
  <div class="mb-2"><label>Nama Kendaraan</label><input name="namakendaraan" class="form-control" required></div>
  <div class="mb-2"><label>Jenis Kendaraan</label>
    <select name="jeniskendaraan" class="form-control" required>
      <?php foreach($jenis_list as $j): ?><option><?=$j?></option><?php endforeach; ?>
    </select>
  </div>
  <div class="mb-2"><label>Nama Driver</label><input name="namadriver" class="form-control" required></div>
  <div class="mb-2"><label>Kontak Driver</label><input name="kontakdriver" class="form-control" required></div>
  <div class="mb-2"><label>Tahun</label><input name="tahun" type="date" class="form-control" required></div>
  <div class="mb-2"><label>Kapasitas</label><input name="kapasitas" class="form-control" required></div>
  <div class="mb-2"><label>Foto</label><input name="foto" type="file" accept="image/*" class="form-control"></div>
  <button class="btn btn-primary">Simpan</button>
  <a href="index.php" class="btn btn-secondary">Batal</a>
</form>
<?php include "../template/footer.php"; ?>
