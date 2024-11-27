<?php
include('database.php'); // Include database connection

// Ambil parameter 'concert' dari URL
$concert_id = isset($_GET['concert']) ? $_GET['concert'] : null;

// Ambil data konser berdasarkan ID
if ($concert_id) {
    $stmt = $conn->prepare("SELECT * FROM konser WHERE id = ?");
    $stmt->bind_param("i", $concert_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $concert = $result->fetch_assoc();
} else {
    $concert = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Ticket</title>
    <link rel="stylesheet" href="pesen.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="buy.php">
                <button class="back-button">Back</button>
            </a>
            <img src="img/logo.png" alt="Logo" class="logo">
        </div>
        <div class="content">
            <?php if ($concert): ?>
            <div class="image-section">
                <img src="img/<?php echo $concert['gambar']; ?>" alt="Concert Image" class="event-image">
                <div class="event-info">
                    <h1><?php echo $concert['nama_artis']; ?> - <?php echo date('d F Y', strtotime($concert['tanggal'])); ?></h1>
                </div>
            </div>
            <div class="form-section">
                <form action="" method="POST">
                    <label for="name">Nama:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="phone">No Telp:</label>
                    <input type="tel" id="phone" name="phone" required>

                    <label for="payment">Metode Pembayaran:</label>
                    <select id="payment" name="payment_method" required>
                        <option value="qris">QRIS</option>
                    </select>

                    <div class="price-info">
                        <p>VIP: <?php echo number_format($concert['harga'] * 2, 0, ',', '.'); ?>,-</p>
                        <p>Regular: <?php echo number_format($concert['harga'], 0, ',', '.'); ?>,-</p>
                    </div>
                    <div class="buy-button">
                        <a href="validasiPembayaran.php?concert_id=<?php echo $concert['id']; ?>&ticket_type=VIP&name=<?php echo urlencode($name); ?>&email=<?php echo urlencode($email); ?>&phone=<?php echo urlencode($phone); ?>" href="https://saweria.co/YuFi" target="_blank">BUY VIP</a>
                    </div>
                    <div class="buy-button">
                        <a href="validasiPembayaran.php?concert_id=<?php echo $concert['id']; ?>&ticket_type=Regular&name=<?php echo urlencode($name); ?>&email=<?php echo urlencode($email); ?>&phone=<?php echo urlencode($phone); ?>" href="https://saweria.co/YuFi" target="_blank">BUY REGULAR</a>
                    </div>
                </form>
            </div>
            <?php else: ?>
            <p>Konser tidak ditemukan.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
