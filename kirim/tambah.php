<?php
$page_title="Tambah Pengiriman"; include "../template/header.php";

// ambil data produk, gudang, kendaraan
$produkRes = mysqli_query($koneksi, "SELECT kodeproduk,nama FROM produk ORDER BY nama");
$gudangRes = mysqli_query($koneksi, "SELECT kodegudang,namagudang FROM gudang ORDER BY namagudang");
$kendRes = mysqli_query($koneksi, "SELECT nopol,namakendaraan FROM kendaraan ORDER BY namakendaraan");

if ($_SERVER['REQUEST_METHOD']==='POST') {
  // ambil master
  $kodekirim = mysqli_real_escape_string($koneksi, $_POST['kodekirim']);
  $tgl = mysqli_real_escape_string($koneksi, $_POST['tglkirim']);
  $nopol = mysqli_real_escape_string($koneksi, $_POST['nopol']);

  // detail arrays
  $d_kodeproduk = $_POST['kodeproduk'] ?? [];
  $d_kodegudang = $_POST['kodegudang'] ?? [];
  $d_qty = $_POST['qty'] ?? [];

  // validate arrays length and not empty
  $rows = [];
  for ($i=0;$i<count($d_kodeproduk);$i++) {
      $kp = mysqli_real_escape_string($koneksi, $d_kodeproduk[$i]);
      $kg = mysqli_real_escape_string($koneksi, $d_kodegudang[$i]);
      $q = (float) $d_qty[$i];
      if ($kp=="" || $kg=="" || $q<=0) continue;
      $rows[] = ['kodeproduk'=>$kp,'kodegudang'=>$kg,'qty'=>$q];
  }
  if (count($rows)===0) {
    $err = "Detail pengiriman kosong.";
  } else {
    // check stok for each row
    $stok_ok = true; $stok_errors=[];
    foreach($rows as $r) {
      $kp = $r['kodeproduk']; $kg = $r['kodegudang']; $q = $r['qty'];
      $s = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT stok FROM stok_gudang WHERE kodegudang='".mysqli_real_escape_string($koneksi,$kg)."' AND kodeproduk='".mysqli_real_escape_string($koneksi,$kp)."'"));
      $stok_now = $s ? (float)$s['stok'] : 0;
      if ($stok_now < $q) { $stok_ok=false; $stok_errors[] = "Stok kurang untuk produk $kp di gudang $kg (tersedia: $stok_now, butuh: $q)"; }
    }
    if (!$stok_ok) {
      $err = implode("<br>", $stok_errors);
    } else {
      // insert master
      // totalqty compute
      $totalqty = 0; foreach($rows as $r) $totalqty += $r['qty'];
      $insm = mysqli_query($koneksi, "INSERT INTO masterkirim(kodekirim,tglkirim,nopol,totalqty) VALUES('".mysqli_real_escape_string($koneksi,$kodekirim)."','".mysqli_real_escape_string($koneksi,$tgl)."','".mysqli_real_escape_string($koneksi,$nopol)."',$totalqty)");
      if (!$insm) { $err = mysqli_error($koneksi); }
      else {
         // insert detail and reduce stok
         foreach($rows as $r) {
            $kp = mysqli_real_escape_string($koneksi, $r['kodeproduk']);
            $kg = mysqli_real_escape_string($koneksi, $r['kodegudang']);
            $q = (float)$r['qty'];
            mysqli_query($koneksi, "INSERT INTO detailkirim(kodekirim,kodeproduk,qty,kodegudang) VALUES('".mysqli_real_escape_string($koneksi,$kodekirim)."','$kp',$q,'$kg')");
            // kurangi stok
            mysqli_query($koneksi, "UPDATE stok_gudang SET stok = stok - $q WHERE kodegudang='$kg' AND kodeproduk='$kp'");
         }
         $_SESSION['msg']='success';
         header("Location: index.php"); exit;
      }
    }
  }
}
?>

<h3>Tambah Pengiriman</h3>
<?php if(isset($err)): echo "<div class='alert alert-danger'>$err</div>"; endif; ?>
<form method="post" id="frm">
  <div class="row mb-2">
    <div class="col-md-4"><label>Kode Kirim</label><input name="kodekirim" class="form-control" required value="<?= 'KIR'.time() ?>"></div>
    <div class="col-md-4"><label>Tanggal</label><input name="tglkirim" type="date" class="form-control" required value="<?= date('Y-m-d') ?>"></div>
    <div class="col-md-4"><label>Kendaraan</label>
      <select name="nopol" class="form-control" required>
        <option value="">-- Pilih Kendaraan --</option>
        <?php while($k=mysqli_fetch_assoc($kendRes)): ?>
          <option value="<?=htmlspecialchars($k['nopol'])?>"><?=htmlspecialchars($k['nopol'].' - '.$k['namakendaraan'])?></option>
        <?php endwhile;?>
      </select>
    </div>
  </div>

  <h5>Detail Produk</h5>
  <table class="table" id="detail-table">
    <thead><tr><th>Produk</th><th>Gudang</th><th>Qty</th><th>Aksi</th></tr></thead>
    <tbody></tbody>
  </table>
  <button type="button" class="btn btn-sm btn-secondary" id="add-row">Tambah Baris</button>
  <div class="mt-3">
    <button class="btn btn-primary">Simpan Pengiriman</button>
    <a href="index.php" class="btn btn-secondary">Batal</a>
  </div>
</form>

<script>
const produkOptions = `<?php
  // render options for produk (escape)
  mysqli_data_seek($produkRes,0);
  $opts = '';
  while($p = mysqli_fetch_assoc($produkRes)) $opts .= "<option value='".addslashes($p['kodeproduk'])."'>".addslashes($p['kodeproduk']." - ".$p['nama'])."</option>";
  echo $opts;
?>`;
const gudangOptions = `<?php
  mysqli_data_seek($gudangRes,0);
  $gopts='';
  while($g=mysqli_fetch_assoc($gudangRes)) $gopts .= "<option value='".addslashes($g['kodegudang'])."'>".addslashes($g['kodegudang']." - ".$g['namagudang'])."</option>";
  echo $gopts;
?>`;

function addRow() {
  const row = `<tr>
    <td><select name="kodeproduk[]" class="form-control" required><option value=''>--Pilih--</option>${produkOptions}</select></td>
    <td><select name="kodegudang[]" class="form-control" required><option value=''>--Pilih--</option>${gudangOptions}</select></td>
    <td><input name="qty[]" type="number" step="0.01" class="form-control" required></td>
    <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
  </tr>`;
  $('#detail-table tbody').append(row);
}
$(document).on('click','.remove-row',function(){ $(this).closest('tr').remove(); });
$('#add-row').on('click', addRow);
// add initial row
addRow();
</script>

<?php include "../template/footer.php"; ?>
