<?php
$koneksi = mysqli_connect("192.168.0.10", "hashiracode", "hashiracode", "hashiracode", 3306);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
