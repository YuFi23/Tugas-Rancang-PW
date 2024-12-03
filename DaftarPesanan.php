<?php
include('connection.php');

// Proses logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Fungsi untuk generate kode tiket acak
function generateTicketCode($length = 10) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

// Menangani form validasi pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $concert_id = $_POST['concert_id'];
    $ticket_type = $_POST['ticket_type'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Generate kode tiket baru
    $ticket_code = generateTicketCode();

    // Menyiapkan query SQL untuk memasukkan data ke database
    $stmt = $conn->prepare("INSERT INTO pembayaran (concert_id, ticket_type, name, email, phone, payment_status, kode_tiket) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $status = 'Validated'; // Status default setelah validasi
    $stmt->bind_param("issssss", $concert_id, $ticket_type, $name, $email, $phone, $status, $ticket_code);

    // Eksekusi query
    if ($stmt->execute()) {
        echo "<script>alert('Pembayaran berhasil divalidasi!');</script>";
        header("Location: succes.php"); // Redirect ke halaman sukses
        exit();
    } else {
        echo "<script>alert('Gagal memvalidasi pembayaran.');</script>";
    }
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
            <a href="?logout=true" class="logout-btn">Logout</a>
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
                        <th>Code Tiket</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Query untuk mengambil data pesanan yang sudah divalidasi
                $query = "
                    SELECT 
                        pembayaran.name AS nama_pelanggan, 
                        pembayaran.email, 
                        pembayaran.phone, 
                        pembayaran.ticket_type, 
                        konser.nama_artis AS nama_konser,
                        pembayaran.kode_tiket
                    FROM pembayaran
                    LEFT JOIN konser ON pembayaran.concert_id = konser.id
                    WHERE pembayaran.payment_status = 'Validated'
                ";

                // Eksekusi query
                $result = $conn->query($query);

                // Menampilkan data jika ada
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['nama_pelanggan']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['phone']}</td>
                                <td>{$row['ticket_type']}</td>
                                <td>{$row['nama_konser']}</td>
                                <td>{$row['kode_tiket']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Belum ada pesanan yang divalidasi.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
