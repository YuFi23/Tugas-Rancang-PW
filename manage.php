<?php
include 'database.php'; // Menghubungkan ke database
include 'functions.php'; // Memasukkan file fungsi

// Memulai session
session_start();

// Memeriksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit(); // Menghentikan eksekusi jika tidak ada session yang sesuai
}

// Menangani aksi penambahan tiket
if (isset($_POST['create'])) {
    $namakonser = trim($_POST['namakonser']);
    $price = (int) $_POST['price'];

    // Menyaring input untuk mencegah XSS dan SQL Injection
    $namakonser = htmlspecialchars($namakonser);
    
    if ($price <= 0) {
        $errorMessage = "Harga tiket harus lebih besar dari nol!";
    } else {
        // Memanggil fungsi untuk menambah tiket
        createTicket($conn, $namakonser, $price);
        $successMessage = "Tiket berhasil ditambahkan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encore Shield Management</title>
    <link rel="stylesheet" href="styles.css"> <!-- Pastikan sudah ada file styles.css -->
    <link rel="stylesheet" href="table.css"> <!-- Pastikan sudah ada file table.css -->
</head>

<body>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Encore Shield Management</h2>
            </div>
            <div class="card-body">
                <!-- Formulir untuk menambah tiket -->
                <h3>Add New Ticket</h3>

                <!-- Menampilkan pesan sukses atau error -->
                <?php
                if (isset($successMessage)) {
                    echo "<div class='alert alert-success'>$successMessage</div>";
                }
                if (isset($errorMessage)) {
                    echo "<div class='alert alert-danger'>$errorMessage</div>";
                }
                ?>

                <form action="manage.php" method="POST">
                    <div class="form-group">
                        <label for="namakonser">Nama Konser - Tanggal</label>
                        <input type="text" name="namakonser" placeholder="Nama Konser - Tanggal" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="number" name="price" placeholder="Harga" required min="1">
                    </div>
                    <button type="submit" name="create">Add Ticket</button>
                </form>

                <!-- Menampilkan tiket yang ada -->
                <h3>All Tickets</h3>

                <?php displayTickets($conn); ?>

            </div>
        </div>
    </div>

</body>

</html>
