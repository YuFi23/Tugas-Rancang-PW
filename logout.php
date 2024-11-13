<?php
session_start();

// Hapus session
session_unset();
session_destroy();

// Arahkan pengguna ke halaman login setelah logout
header("Location: login.php"); // Ganti dengan halaman login Anda
exit;
?>
