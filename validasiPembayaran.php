<?php
include('database.php'); 
session_start();

// Proses validasi pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $concert_id = $_POST['concert_id'];
    $ticket_type = $_POST['ticket_type'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

   
    $stmt = $conn->prepare("INSERT INTO pembayaran (concert_id, ticket_type, name, email, phone, payment_status) VALUES (?, ?, ?, ?, ?, ?)");
    $status = 'Validated';
    $stmt->bind_param("isssss", $concert_id, $ticket_type, $name, $email, $phone, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Pembayaran berhasil divalidasi!');</script>";
        header("Location: success.php"); 
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
    <title>Validasi Pembayaran</title>
    <link rel="stylesheet" href="validasi.css">
</head>
<body>
    <div class="container">
        <h1>Validasi Pembayaran</h1>
        <form action="validasiPembayaran.php" method="POST">
            <!-- Hidden fields untuk data konser dan pengguna -->
            <input type="hidden" name="concert_id" value="<?php echo isset($_GET['concert_id']) ? $_GET['concert_id'] : ''; ?>">
            <input type="hidden" name="ticket_type" value="<?php echo isset($_GET['ticket_type']) ? $_GET['ticket_type'] : ''; ?>">
            <input type="hidden" name="name" value="<?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?>">
            <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? $_GET['email'] : ''; ?>">
            <input type="hidden" name="phone" value="<?php echo isset($_GET['phone']) ? $_GET['phone'] : ''; ?>">

            <!-- Tombol validasi -->
            <button type="submit" class="validate-button">Validasi Pembayaran</button>
        </form>
    </div>
</body>
</html>
