<?php
$server = "localhost";
$user = "root";
$pass = "";
$database = "db_lms_sakinah";

$koneksi = mysqli_connect($server, $user, $pass, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>