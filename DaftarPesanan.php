<?php include('database.php');
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan</title>
    <link rel="stylesheet" href="DaftarPesanan.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>ADMIN</h2>
            <ul>
                <li><a href="DataPelanggan.php">Data Akun</a></li>
                <li><a href="DaftarPesanan.php" class="active">Daftar Pesanan</a></li>
                <li><a href="DaftarKonser.php">Daftar Konser</a></li>
            </ul>
            <a href="DataPelanggan.php?logout=true" class="logout-btn">Logout</a>
        </div>
        <div class="main-content">
            <h1>Daftar Pesanan</h1>
            <table>
                <thead>
                    <tr>
                        <th>Nama Pelanggan by email</th>
                        <th>Tipe Tiket</th>
                        <th>Nama Konser</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if (isset($_POST['add'])) {
                        $nama_pelanggan = $_POST['nama_pelanggan'];
                        $tipe_kursi = $_POST['tipe_kursi'];
                        $nama_konser = $_POST['nama_konser'];
                        $conn->query("INSERT INTO pesanan (nama_pelanggan, tipe_kursi, nama_konser) VALUES ('$nama_pelanggan', '$tipe_kursi', '$nama_konser')");
                    }

                    if (isset($_GET['delete'])) {
                        $id = $_GET['delete'];
                        $conn->query("DELETE FROM pesanan WHERE id=$id");
                    }
                    $result = $conn->query("SELECT * FROM pesanan");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['nama_pelanggan']}</td>
                                <td>{$row['tipe_kursi']}</td>
                                <td>{$row['nama_konser']}</td>
                                <td>
                                    <a href='daftar_pesanan.php?delete={$row['id']}'>Hapus</a>
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