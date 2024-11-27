<?php
include('connection.php');
session_start();

// Proses logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php"); // Arahkan ke halaman login setelah logout
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Link ke file CSS -->
    <link rel="stylesheet" href="DataPelanggan.css">
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>ADMIN</h2>
        <ul>
            <li><a href="DataPelanggan.php">Data Pelanggan</a></li>
            <li><a href="DaftarPesanan.php">Daftar Pesanan</a></li>
            <li><a href="DaftarKonser.php">Daftar Konser</a></li>
        </ul>
        <!-- Tombol Logout di bawah kiri -->
        <a href="DataPelanggan.php?logout=true" class="logout-btn">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Data Pelanggan</h1>
        <table>
            <thead>
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data pelanggan bisa ditambahkan secara dinamis dengan PHP -->
                <?php
                // Tambah pelanggan
                if (isset($_POST['add'])) {
                    $nama = $_POST['nama'];
                    $email = $_POST['email'];
                    $conn->query("INSERT INTO pelanggan (nama, email) VALUES ('$nama', '$email')");
                }

                // Hapus pelanggan
                if (isset($_GET['delete'])) {
                    $id = $_GET['delete'];
                    $conn->query("DELETE FROM pelanggan WHERE id=$id");
                }

                // Tampilkan data
                $result = $conn->query("SELECT * FROM pelanggan");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['nama']}</td>
                            <td>{$row['email']}</td>
                            <td>
                                <a href='data_pelanggan.php?delete={$row['id']}'>Hapus</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
