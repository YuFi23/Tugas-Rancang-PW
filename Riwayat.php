<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

include('connection.php');
$username = $_SESSION['username'];  // Mendapatkan username dari session

// Debugging: Periksa email yang ada di session
echo "<script>console.log('Email Session: " . $_SESSION['email'] . "');</script>";

// Query yang disesuaikan untuk mengambil data riwayat berdasarkan email pengguna
$query = "SELECT p.id, k.concert_name, p.ticket_type, p.created_at, p.payment_status 
          FROM pembayaran p 
          JOIN konser k ON p.concert_id = k.id 
          WHERE p.email = ?";  // Gunakan email untuk menyaring berdasarkan pengguna

$stmt = $conn->prepare($query);  // Siapkan statement query
$stmt->bind_param("s", $_SESSION['email']);  // Bind parameter email yang ada di session
$stmt->execute();  // Eksekusi query

$result = $stmt->get_result();  // Ambil hasil query
$riwayat = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Debugging: Menampilkan data yang diambil
        echo "<script>console.log('Row: " . json_encode($row) . "');</script>";
        $riwayat[] = $row;  // Simpan setiap baris data ke dalam array
    }
} else {
    echo "<script>alert('Tidak ada riwayat pembayaran untuk pengguna ini.');</script>";
}

$stmt->close();  // Tutup statement
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Pembelian</title>
    <link rel="stylesheet" href="Riwayat.css">
</head>
<body>
    <nav class="navbar">
        <div class="merk">EncS</div>
        <ul class="nav-links">
            <li><a href="home.php">HOME</a></li>
            <li><a href="about.php">ABOUT US</a></li>
            <li><a href="contact.php">CONTACT US</a></li>
            <li><a href="Riwayat.php" class="active">Riwayat</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>History Pembelian</h1>
        <table>
            <thead>
                <tr>
                    <th>ID Pembelian</th>
                    <th>Nama Artis</th> <!-- Ganti "Concert" menjadi "Nama Artis" -->
                    <th>Ticket Type</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="riwayat-table">
                <?php if (count($riwayat) > 0): ?>
                    <?php foreach ($riwayat as $item): ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td><?= $item['concert_name'] ?></td> <!-- Menampilkan nama artis -->
                            <td><?= $item['ticket_type'] ?></td>
                            <td><?= $item['created_at'] ?></td>
                            <td><?= $item['payment_status'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Tidak ada riwayat pembelian.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
