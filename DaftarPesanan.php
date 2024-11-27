<?php
include('database.php');


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
                        <th>Nama Pelanggan</th>
                        <th>Email</th>
                        <th>No. Telpon</th>
                        <th>Tipe Tiket</th>
                        <th>Nama Konser</th>
                    </tr>
                </thead>
                <tbody>
                <?php
             
                $query = "
                    SELECT 
                        pembayaran.name AS nama_pelanggan, 
                        pembayaran.email, 
                        pembayaran.phone, 
                        pembayaran.ticket_type, 
                        konser.nama_artis AS nama_konser
                    FROM pembayaran
                    LEFT JOIN konser ON pembayaran.concert_id = konser.id
                    WHERE pembayaran.payment_status = 'Validated'
                ";

                $result = $conn->query($query);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['nama_pelanggan']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['phone']}</td>
                                <td>{$row['ticket_type']}</td>
                                <td>{$row['nama_konser']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Belum ada pesanan yang divalidasi.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
