<?php
session_start();

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header('Location: login.php');
    exit();
}

// Menampilkan data pengguna
echo "Selamat datang, " . $_SESSION['username'] . "!<br>";

// Memeriksa peran pengguna dan menampilkan konten berdasarkan peran
if ($_SESSION['role'] == 'admin') {
    // Tampilan untuk admin
    echo "<h2>Admin Dashboard</h2>";
    echo "<p>Selamat datang di halaman admin. Anda dapat mengelola tiket dan melihat statistik.</p>";
    echo "<a href='DataPelanggan.php.php'>Go to Ticket Management</a><br>";
} elseif ($_SESSION['role'] == 'user') {
    // Tampilan untuk user biasa
    echo "<h2>User Dashboard</h2>";
    echo "<p>Selamat datang di halaman pengguna. Anda dapat melihat tiket yang tersedia untuk dibeli.</p>";
    echo "<a href='view_tickets.php'>View Available Tickets</a><br>";
} else {
    // Tampilan jika peran tidak dikenali
    echo "<p>Peran tidak dikenali!</p>";
}

?>

<a href="logout.php">Logout</a>
