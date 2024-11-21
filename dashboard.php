<?php
session_start();

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header('Location: login.php');
    exit();
}

// Menampilkan data pengguna
echo "Selamat datang, " . $_SESSION['username'] . "!";
?>

<a href="logout.php">Logout</a>
