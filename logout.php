<?php 
// Mengaktifkan session
session_start();

// Menghapus semua session
session_destroy();

// Mengalihkan halaman kembali ke index.php
header("location:index.php?pesan=logout");
?>