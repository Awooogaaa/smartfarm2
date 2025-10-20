<?php
$koneksi = mysqli_connect("localhost", "root", "", "smartfarm2");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
