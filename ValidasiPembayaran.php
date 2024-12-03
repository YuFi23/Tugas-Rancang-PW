<?php
include('connection.php'); 
session_start();

// Proses validasi pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form dan sanitasi
    $concert_id = $_POST['concert_id'];
    $ticket_type = $_POST['ticket_type'];
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $phone = trim(htmlspecialchars($_POST['phone']));

    // Validasi inputan
    $errors = [];

    // Validasi nama (tidak boleh kosong)
    if (empty($name)) {
        $errors[] = "Nama tidak boleh kosong.";
    }

    // Validasi email
    if (empty($email)) {
        $errors[] = "Email tidak boleh kosong.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }

    // Validasi telepon (misalnya, harus berupa angka dan panjang 10-15 karakter)
    if (empty($phone)) {
        $errors[] = "Nomor telepon tidak boleh kosong.";
    } elseif (!preg_match("/^[0-9]{10,15}$/", $phone)) {
        $errors[] = "Nomor telepon tidak valid. Harus berupa angka dengan panjang 10-15 karakter.";
    }

    // Jika ada error, tampilkan pesan error
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
        }
    } else {
        // Cek stok tiket sebelum melakukan validasi
        $select_query = "SELECT stock FROM konser WHERE id = ?";
        $stmt_select = $conn->prepare($select_query);
        $stmt_select->bind_param("i", $concert_id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        $row = $result->fetch_assoc();

        // Debugging: Periksa nilai stok
        if ($row) {
            echo "<script>console.log('Stok tersedia: " . $row['stock'] . "');</script>";
        } else {
            echo "<script>alert('Data konser tidak ditemukan.');</script>";
        }

        // Jika stok tiket tersedia
        if ($row && $row['stock'] > 0) { // Ubah validasi stok menjadi > 0
            // Mengurangi stok tiket
            $update_query = "UPDATE konser SET stock = stock - 1 WHERE id = ?";
            $stmt_update = $conn->prepare($update_query);
            $stmt_update->bind_param("i", $concert_id);

            // Eksekusi update stok tiket
            if ($stmt_update->execute()) {
                // Menyiapkan query SQL untuk memasukkan data ke database
                $stmt = $conn->prepare("INSERT INTO pembayaran (concert_id, ticket_type, name, email, phone, payment_status) VALUES (?, ?, ?, ?, ?, ?)");
                $status = 'Validated'; // Status default setelah validasi
                $stmt->bind_param("isssss", $concert_id, $ticket_type, $name, $email, $phone, $status);

                // Eksekusi query pembayaran
                if ($stmt->execute()) {
                    echo "<script>alert('Pembayaran berhasil divalidasi!');</script>";
                    header("Location: succes.php"); // Redirect ke halaman sukses
                    exit();
                } else {
                    echo "<script>alert('Gagal memvalidasi pembayaran.');</script>";
                    echo "<script>console.log('Error: " . $stmt->error . "');</script>"; // Debugging: Lihat error
                }
            } else {
                echo "<script>alert('Gagal memperbarui stok tiket.');</script>";
            }
        } else {
            // Stok habis, tampilkan tombol kembali ke home
            $show_button = true; // Set flag untuk menampilkan tombol
            $out_of_stock_message = "Stok tiket habis."; // Pesan stok habis
        }
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
        <p>Data pembayaran Anda sedang diproses. Silakan tunggu...</p>
        
        <!-- Pesan stok habis -->
        <?php if (isset($out_of_stock_message)): ?>
            <p class="out-of-stock-message"><?php echo $out_of_stock_message; ?></p>
        <?php endif; ?>
        
        <!-- Tombol Kembali ke Home -->
        <?php if (isset($show_button) && $show_button): ?>
            <a href="home.php" class="back-home-btn">Kembali ke Home</a>
        <?php endif; ?>
    </div>

    <style>
        .back-home-btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin-top: 20px;
            text-align: center;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-home-btn:hover {
            background-color: #45a049;
        }

        .out-of-stock-message {
            color: red;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</body>
</html>
